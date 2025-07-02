<?php

if (!is_file("src/modelo/".$pagina.".php")){
	
	echo "Falta definir la clase ".$pagina;
	exit;
}  
use Shm\Shm\modelo\emergencias;
require_once("modelo/bitacora.php");

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
				
				$o->setDatos($_POST, $accion);		
				$resultado = $o->eliminar();
				bitacora::registrarYNotificar(
					'Eliminar',
					'Se ha eliminado la emergencia del paciente: '.$_POST['cedula_paciente'],
					$_SESSION['usuario']
				);
				echo json_encode($resultado);
				break;
				
			case 'incluir':
			
				$o->setDatos($_POST, $accion);
				$resultado = $o->incluir();
				bitacora::registrarYNotificar(
					'Registrar',
					'Se ha registrado una emergencia para el paciente: '.$_POST['cedula_paciente'],
					$_SESSION['usuario']
				);
				echo json_encode($resultado);
				break;
				
			case 'modificar':
				$o->setDatos($_POST, $accion);
				$resultado = $o->modificar();
				bitacora::registrarYNotificar(
					'Modificar',
					'Se ha modificado la emergencia del paciente: '.$_POST['cedula_paciente'],
					$_SESSION['usuario']
				);
				echo json_encode($resultado);
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