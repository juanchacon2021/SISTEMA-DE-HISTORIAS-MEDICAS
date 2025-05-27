<?php

if (!is_file("modelo/".$pagina.".php")){
	//alli pregunte que si no es archivo se niega //con !
	//si no existe envio mensaje y me salgo
	echo "Falta definir la clase ".$pagina;
	exit;
}  
require_once("modelo/".$pagina.".php");  
  if(is_file("vista/".$pagina.".php")){
	  
	  if(!empty($_POST)){
		$o = new p_cronicos();   

		  $accion = $_POST['accion'];
		  
		  if($accion=='consultar'){
			 echo  json_encode($o->consultar());  
		  }
		elseif ($accion == 'listadopacientes') {

			$respuesta = $o->listadopacientes();
			echo json_encode($respuesta);
		}
		  elseif($accion=='eliminar'){
			 $o->set_cod_cronico($_POST['cod_cronico']);
			 echo  json_encode($o->eliminar());
		  }
		  else{		  
			  
			  $o->set_cod_cronico($_POST['cod_cronico']);
			  $o->set_patologia_cronica($_POST['patologia_cronica']);
			  $o->set_Tratamiento($_POST['Tratamiento']);
			  $o->set_admistracion_t($_POST['admistracion_t']);
			  $o->set_cedula_h($_POST['cedula_h']);
			  if($accion=='incluir'){
				echo  json_encode($o->incluir());
				
			  }
			  elseif($accion=='modificar'){
				echo  json_encode($o->modificar());
			  }
		  }
		  exit;
	  }
	  
	
	  require_once("vista/".$pagina.".php"); 
  }
  else{
	  echo "pagina en construccion";
  }
?>