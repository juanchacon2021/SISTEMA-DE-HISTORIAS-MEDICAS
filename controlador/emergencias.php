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
		  
		switch ($accion) {
			case 'consultar':
				echo json_encode($o->consultar());
				break;
				
			case 'listadopersonal':
				$respuesta = $o->listadopersonal();
				echo json_encode($respuesta);
				break;
				
			case 'listadopacientes':
				$respuesta = $o->listadopacientes();
				echo json_encode($respuesta);
				break;
				
			case 'eliminar':
				// Asignar los valores de la llave compuesta
				$o->set_cedula_paciente($_POST['cedula_paciente']);
				$o->set_cedula_personal($_POST['cedula_personal']);
				$o->set_fechaingreso($_POST['fechaingreso']);
				$o->set_horaingreso($_POST['horaingreso']);
				// Ejecutar eliminación
				echo json_encode($o->eliminar());
				break;
				
			case 'incluir':
				// Configurar propiedades comunes
				$o->set_horaingreso($_POST['horaingreso']);
				$o->set_fechaingreso($_POST['fechaingreso']);
				$o->set_motingreso($_POST['motingreso']);
				$o->set_diagnostico_e($_POST['diagnostico_e']);
				$o->set_tratamientos($_POST['tratamientos']);
				$o->set_cedula_personal($_POST['cedula_personal']);
				$o->set_cedula_paciente($_POST['cedula_paciente']);
				$o->set_procedimiento($_POST['procedimiento']);
				// Ejecutar inclusión
				echo json_encode($o->incluir());
				break;
				
			case 'modificar':
				// Configurar propiedades comunes
				$o->set_horaingreso($_POST['horaingreso']);
				$o->set_fechaingreso($_POST['fechaingreso']);
				$o->set_motingreso($_POST['motingreso']);
				$o->set_diagnostico_e($_POST['diagnostico_e']);
				$o->set_tratamientos($_POST['tratamientos']);
				$o->set_cedula_personal($_POST['cedula_personal']);
				$o->set_cedula_paciente($_POST['cedula_paciente']);
				$o->set_procedimiento($_POST['procedimiento']);
				// Configurar propiedades antiguas para modificación
				$o->set_old_cedula_paciente($_POST['old_cedula_paciente']);
				$o->set_old_cedula_personal($_POST['old_cedula_personal']);
				$o->set_old_fechaingreso($_POST['old_fechaingreso']);
				$o->set_old_horaingreso($_POST['old_horaingreso']);
				// Ejecutar modificación
				echo json_encode($o->modificar());
				break;
				
			default:
				// Código para acciones no reconocidas (opcional)
				echo json_encode(['error' => 'Acción no reconocida']);
		}

		exit;
	  }
	
	  require_once("vista/".$pagina.".php"); 
  }
  else{
	  echo "pagina en construccion";
  }
?>