<?php

if (!is_file("src/modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}
use Shm\Shm\modelo\consultasm;
use Shm\Shm\modelo\observaciones;
require_once("modelo/bitacora.php");

if(is_file("vista/".$pagina.".php")){

    if (!empty($_POST)) {
        $o = new consultasm();
        $p = new observaciones();

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

            case 'listado_observaciones':
                $respuesta = $p->listado_observaciones();
                echo json_encode($respuesta);
                break;

            case 'eliminar':
                $o->set_cod_consulta($_POST['cod_consulta']);
                $resultado = $o->eliminar();
                bitacora::registrarYNotificar(
                    'Eliminar',
                    'Se ha eliminado la consulta: '.$_POST['cod_consulta'],
                    $_SESSION['usuario']
                );
                echo json_encode($resultado);
                break;

            case 'agregar':
                $p->set_cod_observacion($_POST['cod_observacion']);
                $p->set_nom_observaciones($_POST['nom_observaciones']);
                $resultado = $p->incluir2();
                bitacora::registrarYNotificar(
                    'Registrar',
                    'Se ha registrado la observación: '.$_POST['nom_observaciones'],
                    $_SESSION['usuario']
                );
                echo json_encode($resultado);
                break;

            case 'actualizar':
                $p->set_cod_observacion($_POST['cod_observacion']);
                $p->set_nom_observaciones($_POST['nom_observaciones']);
                $resultado = $p->modificar2();
                bitacora::registrarYNotificar(
                    'Modificar',
                    'Se ha modificado la observación: '.$_POST['nom_observaciones'],
                    $_SESSION['usuario']
                );
                echo json_encode($resultado);
                break;

            case 'obtener_observaciones_consulta':
                $cod_consulta = $_POST['cod_consulta'];
                $observaciones = $o->obtener_observaciones_consulta($cod_consulta);
                echo json_encode([
                    'resultado' => 'obtener_observaciones_consulta',
                    'datos' => $observaciones
                ]);
                break;

            case 'descartar':
                $p->set_cod_observacion($_POST['cod_observacion']);
                $resultado = $p->eliminar2();
                bitacora::registrarYNotificar(
                    'Eliminar',
                    'Se ha descartado la observación: '.$_POST['cod_observacion'],
                    $_SESSION['usuario']
                );
                echo json_encode($resultado);
                break;

            case 'incluir':
            case 'modificar':
                // Configuración común para incluir y modificar
                $o->set_cod_consulta($_POST['cod_consulta']);
                $o->set_fechaconsulta($_POST['fechaconsulta']);
                $o->set_Horaconsulta($_POST['Horaconsulta']);
                $o->set_consulta($_POST['consulta']);
                $o->set_diagnostico($_POST['diagnostico']);
                $o->set_tratamientos($_POST['tratamientos']);
                $o->set_cedula_personal($_POST['cedula_personal']);
                $o->set_cedula_paciente($_POST['cedula_paciente']);

                // Prepara el array de observaciones
                $observaciones = [];
                if (!empty($_POST['cod_observacion'])) {
                    foreach ($_POST['cod_observacion'] as $cod_observacion) {
                        $observaciones[] = [
                            'cod_observacion' => $cod_observacion,
                            'observacion' => $_POST['observacion_' . $cod_observacion]
                        ];
                    }
                }

                if ($accion == 'incluir') {
                    $resultado = $o->incluir($observaciones);
                    bitacora::registrarYNotificar(
                        'Registrar',
                        'Se ha registrado una consulta: '.$_POST['cod_consulta'],
                        $_SESSION['usuario']
                    );
                } else {
                    $resultado = $o->modificar($observaciones);
                    bitacora::registrarYNotificar(
                        'Modificar',
                        'Se ha modificado la consulta: '.$_POST['cod_consulta'],
                        $_SESSION['usuario']
                    );
                }
                echo json_encode($resultado);
                break;

            default:
                // Manejo de acción no reconocida
                echo json_encode(['error' => 'Acción no reconocida']);
        }

        exit;
    }

    require_once("vista/".$pagina.".php");
} else {
    echo "pagina en construccion";
}
?>