<?php

if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina;
	exit;
}  
require_once("modelo/".$pagina.".php");  
  if(is_file("vista/".$pagina.".php")){
	  
	  if(!empty($_POST)){
		$o = new p_cronicos(); 
		$z = new patologias();  

		$accion = $_POST['accion'];

		switch ($accion) {
			case 'consultar':
				echo json_encode($o->consultar());
				break;
				
			case 'listadopacientes':
				$respuesta = $o->listadopacientes();
				echo json_encode($respuesta);
				break;
				
			case 'listado_patologias':
				$respuesta = $z->listado_patologias();
				echo json_encode($respuesta);
				break;
				
			case 'eliminar':
				$o->set_cedula_paciente($_POST['cedula_paciente']);
				echo json_encode($o->eliminar());
				break;
				
			case 'agregar':
				$z->set_cod_patologia($_POST['cod_patologia']);
				$z->set_nombre_patologia($_POST['nombre_patologia']);
				echo json_encode($z->incluir2());
				break;
				
			case 'actualizar':
				$z->set_cod_patologia($_POST['cod_patologia']);
				$z->set_nombre_patologia($_POST['nombre_patologia']);
				echo json_encode($z->modificar2());
				break;
				
			case 'descartar':
				$z->set_cod_patologia($_POST['cod_patologia']);
				echo json_encode($z->eliminar2());
				break;
				
			case 'obtener_patologias_paciente':
				$cedula_paciente = $_POST['cedula_paciente'];
				$patologias = $o->obtener_patologias_paciente($cedula_paciente);
				
				if($patologias === false) {
					echo json_encode([
						'resultado' => 'error',
						'mensaje' => 'Error al obtener patologías'
					]);
				} else {
					echo json_encode([
						'resultado' => 'obtener_patologias_paciente',
						'datos' => $patologias
					]);
				}
				break;
				
			case 'incluir':
			case 'modificar':
				$cedula_paciente = $_POST['cedula_paciente'];
				$patologias = array();
				
				foreach ($_POST['cod_patologia'] as $cod_patologia) {
					$patologias[] = [
						'cod_patologia' => $cod_patologia,
						'tratamiento' => $_POST['tratamiento_' . $cod_patologia],
						'administracion_t' => $_POST['administracion_t_' . $cod_patologia]
					];
				}
				
				if ($accion == 'incluir') {
					echo json_encode($o->incluir($cedula_paciente, $patologias));
				} else {
					$o->set_cedula_paciente($cedula_paciente);
					echo json_encode($o->modificar($patologias));
				}
				break;
				
			default:
				// Handle unknown action
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