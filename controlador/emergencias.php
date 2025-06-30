<?php

if (!is_file("src/modelo/".$pagina.".php")){
	
	echo "Falta definir la clase ".$pagina;
	exit;
}  
use Shm\Shm\modelo\emergencias;

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
				echo json_encode($o->eliminar());
				break;
				
			case 'incluir':
			
				$o->setDatos($_POST, $accion);
				echo json_encode($o->incluir());
				break;
				
			case 'modificar':
				$o->setDatos($_POST, $accion);
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