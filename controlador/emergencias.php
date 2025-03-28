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
		$o = new emergencias();   

		  $accion = $_POST['accion'];
		  
		  if($accion=='consultar'){
			 echo  json_encode($o->consultar());  
		  }
		  elseif($accion=='listadopersonal'){
			$respuesta = $o->listadopersonal();
			echo json_encode($respuesta);
		}
		elseif ($accion == 'listadopacientes') {

			$respuesta = $o->listadopacientes();
			echo json_encode($respuesta);
		}
		  elseif($accion=='eliminar'){
			 $o->set_cod_emergencia($_POST['cod_emergencia']);
			 echo  json_encode($o->eliminar());
		  }
		  else{		  
			  
			  $o->set_cod_emergencia($_POST['cod_emergencia']);
			  $o->set_horaingreso($_POST['horaingreso']);
			  $o->set_fechaingreso($_POST['fechaingreso']);
			  $o->set_motingreso($_POST['motingreso']);
			  $o->set_diagnostico_e($_POST['diagnostico_e']);
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
	  
	  $o = new emergencias();
	$datos = $o->consultar();
	$datosPacientes = $o->listadopacientes();
	$datosPersonal = $o->listadopersonal();
	  require_once("vista/".$pagina.".php"); 
  }
  else{
	  echo "pagina en construccion";
  }
?>