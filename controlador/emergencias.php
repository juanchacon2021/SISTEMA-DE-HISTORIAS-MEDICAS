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
		$datos = $_POST;
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
				$resultado = $o->eliminar($datos);	
				echo json_encode($resultado);
				break;
				
			case 'incluir':				
				$resultado = $o->incluir($datos);
				echo json_encode($resultado);
				break;
			case 'modificar':
				$resultado = $o->modificar($datos);
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