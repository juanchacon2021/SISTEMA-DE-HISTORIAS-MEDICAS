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

<<<<<<< HEAD
        $accion = $_POST['accion'];
=======
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
>>>>>>> a5aba03ad16c3745bb648bf150066e4bfaed281b

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
                $o->set_cedula_paciente($_POST['cedula_paciente']);
                $o->set_cedula_personal($_POST['cedula_personal']);
                $o->set_fechaingreso($_POST['fechaingreso']);
                $o->set_horaingreso($_POST['horaingreso']);
                $resultado = $o->eliminar();
                bitacora::registrarYNotificar(
                    'Eliminar',
                    'Se ha eliminado una emergencia para el paciente: '.$_POST['cedula_paciente'],
                    $_SESSION['usuario']
                );
                echo json_encode($resultado);
                break;

            case 'incluir':
                $o->set_horaingreso($_POST['horaingreso']);
                $o->set_fechaingreso($_POST['fechaingreso']);
                $o->set_motingreso($_POST['motingreso']);
                $o->set_diagnostico_e($_POST['diagnostico_e']);
                $o->set_tratamientos($_POST['tratamientos']);
                $o->set_cedula_personal($_POST['cedula_personal']);
                $o->set_cedula_paciente($_POST['cedula_paciente']);
                $o->set_procedimiento($_POST['procedimiento']);
                $resultado = $o->incluir();
                bitacora::registrarYNotificar(
                    'Registrar',
                    'Se ha registrado una emergencia para el paciente: '.$_POST['cedula_paciente'],
                    $_SESSION['usuario']
                );
                echo json_encode($resultado);
                break;

            case 'modificar':
                $o->set_horaingreso($_POST['horaingreso']);
                $o->set_fechaingreso($_POST['fechaingreso']);
                $o->set_motingreso($_POST['motingreso']);
                $o->set_diagnostico_e($_POST['diagnostico_e']);
                $o->set_tratamientos($_POST['tratamientos']);
                $o->set_cedula_personal($_POST['cedula_personal']);
                $o->set_cedula_paciente($_POST['cedula_paciente']);
                $o->set_procedimiento($_POST['procedimiento']);
                $o->set_old_cedula_paciente($_POST['old_cedula_paciente']);
                $o->set_old_cedula_personal($_POST['old_cedula_personal']);
                $o->set_old_fechaingreso($_POST['old_fechaingreso']);
                $o->set_old_horaingreso($_POST['old_horaingreso']);
                $resultado = $o->modificar();
                bitacora::registrarYNotificar(
                    'Modificar',
                    'Se ha modificado una emergencia para el paciente: '.$_POST['cedula_paciente'],
                    $_SESSION['usuario']
                );
                echo json_encode($resultado);
                break;

            default:
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