<?php

if (!is_file("modelo/".$pagina.".php")){
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
			 bitacora::registrar('eliminar', 'Eliminó un registro de patología crónica con código: '.$_POST['cod_cronico']);
		  }
		  else{		  
			  
			  $o->set_cod_cronico($_POST['cod_cronico']);
			  $o->set_patologia_cronica($_POST['patologia_cronica']);
			  $o->set_Tratamiento($_POST['Tratamiento']);
			  $o->set_admistracion_t($_POST['admistracion_t']);
			  $o->set_cedula_h($_POST['cedula_h']);
			  if($accion=='incluir'){
				echo  json_encode($o->incluir());
				bitacora::registrar('incluir', 'Incluyó un nuevo registro de patología crónica con cédula: '.$_POST['cedula_h'].' y patología: '.$_POST['patologia_cronica']);
				
			  }
			  elseif($accion=='modificar'){
				echo  json_encode($o->modificar());
				bitacora::registrar('modificar', 'Modificó un registro de patología crónica con código: '.$_POST['cod_cronico']);
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