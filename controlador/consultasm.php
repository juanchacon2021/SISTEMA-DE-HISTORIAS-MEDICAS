<?php

if (!is_file("src/modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}
use Shm\Shm\modelo\consultasm;
use Shm\Shm\modelo\observaciones;
require_once("modelo/bitacora.php");

if(is_file("vista/".$pagina.".php")) {

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
                $o->setDatos($_POST);
                $resultado = $o->eliminar();
                bitacora::registrarYNotificar(
                    'Eliminar',
                    'Se ha eliminado la consulta: '.$_POST['cod_consulta'],
                    $_SESSION['usuario']
                );
                echo json_encode($resultado);
                break;

            case 'agregar':
                $p->setDatos($_POST);
                $resultado = $p->incluir2();
                bitacora::registrarYNotificar(
                    'Registrar',
                    'Se ha registrado una observación: '.$_POST['nom_observaciones'] ?? '',
                    $_SESSION['usuario']
                );
                echo json_encode($resultado);
                break;

            case 'actualizar':
                $p->setDatos($_POST);
                $resultado = $p->modificar2();
                bitacora::registrarYNotificar(
                    'Modificar',
                    'Se ha modificado la observación: '.$_POST['nom_observaciones'] ?? '',
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
                $p->setDatos($_POST);
                $resultado = $p->eliminar2();
                bitacora::registrarYNotificar(
                    'Eliminar',
                    'Se ha descartado la observación: '.$_POST['cod_observacion'] ?? '',
                    $_SESSION['usuario']
                );
                echo json_encode($resultado);
                break;

            case 'incluir':
            case 'modificar':
                $o->setDatos($_POST);

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
                echo json_encode(['error' => 'Acción no reconocida']);
        }

        exit;
    }

    require_once("vista/".$pagina.".php");
} else {
    echo "pagina en construccion";
}
?>