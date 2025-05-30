<?php

if (!is_file("modelo/".$pagina.".php")){
	
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
			 bitacora::registrar('eliminar', 'Eliminó una emergencia con código: '.$_POST['cod_emergencia']);	
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
				bitacora::registrar('incluir', 'Incluyó una nueva emergencia con código: '.$_POST['cod_emergencia']);
				
			  }
			  elseif($accion=='modificar'){
				echo  json_encode($o->modificar());
				bitacora::registrar('modificar', 'Modificó una emergencia con código: '.$_POST['cod_emergencia']);
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