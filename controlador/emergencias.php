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
		 elseif ($accion == 'eliminar') {
			// Asignar los valores de la llave compuesta
			$o->set_cedula_paciente($_POST['cedula_paciente']);
			$o->set_cedula_personal($_POST['cedula_personal']);
			$o->set_fechaingreso($_POST['fechaingreso']);
			$o->set_horaingreso($_POST['horaingreso']);

			// Ejecutar eliminación
			echo json_encode($o->eliminar());	
		}
		  else{		    
			  $o->set_horaingreso($_POST['horaingreso']);
			  $o->set_fechaingreso($_POST['fechaingreso']);
			  $o->set_motingreso($_POST['motingreso']);
			  $o->set_diagnostico_e($_POST['diagnostico_e']);
			  $o->set_tratamientos($_POST['tratamientos']);
			  $o->set_cedula_personal($_POST['cedula_personal']);
			  $o->set_cedula_paciente($_POST['cedula_paciente']);

			  
			  if($accion=='incluir'){
				echo  json_encode($o->incluir());

				
			  }
			  elseif($accion=='modificar'){
				$o->set_old_cedula_paciente($_POST['old_cedula_paciente']);
				$o->set_old_cedula_personal($_POST['old_cedula_personal']);
				$o->set_old_fechaingreso($_POST['old_fechaingreso']);
				$o->set_old_horaingreso($_POST['old_horaingreso']);


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