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
		$o = new consultasm();   

		  $accion = $_POST['accion'];
		  
		  if($accion=='consultar'){
			 echo  json_encode($o->consultar());  
		  }
		  elseif($accion=='listadopersonal'){
			$respuesta = $o->listadopersonal();
			echo json_encode($respuesta);
		}
		elseif($accion=='listadopacientes'){
			$respuesta = $o->listadopacientes();
			echo json_encode($respuesta);
		}
		  elseif($accion=='eliminar'){
			 $o->set_cod_consulta($_POST['cod_consulta']);
			 echo  json_encode($o->eliminar());
		  }
		  else{		  
			  
			  $o->set_cod_consulta($_POST['cod_consulta']);
			  $o->set_fechaconsulta($_POST['fechaconsulta']);
			  $o->set_consulta($_POST['consulta']);
			  $o->set_diagnostico($_POST['diagnostico']);
			  $o->set_tratamientos($_POST['tratamientos']);
			  $o->set_cedula_p($_POST['cedula_p']);
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