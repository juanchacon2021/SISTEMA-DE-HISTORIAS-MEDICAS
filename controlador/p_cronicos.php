<?php

if (!is_file("src/modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina;
	exit;
}  
use Shm\Shm\modelo\p_cronicos;
use Shm\Shm\modelo\patologias;

  if(is_file("vista/".$pagina.".php")){
	  
	  if(!empty($_POST)){
		$o = new p_cronicos(); 
		$z = new patologias();  

		$accion = $_POST['accion'];
		$datos = $_POST;

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
				echo json_encode($o->eliminar($datos));
				break;
				
			case 'agregar':
				echo json_encode($z->incluir2($datos));
				break;
				
			case 'actualizar':
				echo json_encode($z->modificar2($datos));
				break;
				
			case 'descartar':
				echo json_encode($z->eliminar2($datos));
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
				$patologias = array();
				
				foreach ($_POST['cod_patologia'] as $cod_patologia) {
					$patologias[] = [
						'cod_patologia' => $cod_patologia,
						'tratamiento' => $_POST['tratamiento_' . $cod_patologia],
						'administracion_t' => $_POST['administracion_t_' . $cod_patologia]
					];
				}
				
				if ($accion == 'incluir') {
					echo json_encode($o->incluir($datos, $patologias));
				} else {
					echo json_encode($o->modificar($datos, $patologias));
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