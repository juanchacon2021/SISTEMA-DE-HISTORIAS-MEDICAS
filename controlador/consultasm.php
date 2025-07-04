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

            case 'listado_observaciones':
                $respuesta = $p->listado_observaciones();
                echo json_encode($respuesta);
                break;

            case 'eliminar':
                $resultado = $o->eliminar($datos);
                echo json_encode($resultado);
                break;

            case 'agregar':
                $resultado = $p->incluir2($datos);
                echo json_encode($resultado);
                break;

            case 'actualizar':
                $resultado = $p->modificar2($datos);
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
                $resultado = $p->eliminar2($datos);
                echo json_encode($resultado);
                break;

            case 'incluir':
            case 'modificar':


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
                    $resultado = $o->incluir($datos ,$observaciones);
                } else {
                    $resultado = $o->modificar($datos ,$observaciones);
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