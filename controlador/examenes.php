<?php
if (!is_file("src/modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

use Shm\Shm\modelo\examenes;
require_once("modelo/bitacora.php");
require_once("src/modelo/datos.php");

if (is_file("vista/" . $pagina . ".php")) {
    if (!empty($_POST)) {
        $o = new examenes();
        $accion = $_POST['accion'];

        // Convertir $_POST a array y filtrar datos
        $datos = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        switch ($accion) {
            case 'consultar_tipos':
                echo json_encode($o->consultar_tipos());
                break;

            case 'consultar_registros':
                echo json_encode($o->consultar_registros());
                break;

            case 'incluir_tipo':
                $resultado = $o->gestionar_tipo($datos);
                bitacora::registrarYNotificar(
                    'Registrar',
                    'Se ha registrado el tipo de examen: ' . $datos['cod_examen'],
                    $_SESSION['usuario']
                );
                echo json_encode($resultado);
                break;

            case 'modificar_tipo':
                $resultado = $o->gestionar_tipo($datos);
                bitacora::registrarYNotificar(
                    'Modificar',
                    'Se ha modificado el tipo de examen: ' . $datos['cod_examen'],
                    $_SESSION['usuario']
                );
                echo json_encode($resultado);
                break;

            case 'eliminar_tipo':
                $resultado = $o->gestionar_tipo($datos);
                bitacora::registrarYNotificar(
                    'Eliminar',
                    'Se ha eliminado el tipo de examen: ' . $datos['cod_examen'],
                    $_SESSION['usuario']
                );
                echo json_encode($resultado);
                break;

            case 'incluir_registro':
                $resultado = $o->gestionar_registro($datos);
                bitacora::registrarYNotificar(
                    'Registrar',
                    'Se ha registrado un examen para el paciente: ' . $datos['cedula_paciente'],
                    $_SESSION['usuario']
                );
                echo json_encode($resultado);
                break;

            case 'modificar_registro':
                $resultado = $o->gestionar_registro($datos);
                bitacora::registrarYNotificar(
                    'Modificar',
                    'Se ha modificado un examen para el paciente: ' . $datos['cedula_paciente'],
                    $_SESSION['usuario']
                );
                echo json_encode($resultado);
                break;

            case 'eliminar_registro':
                $resultado = $o->gestionar_registro($datos);
                bitacora::registrarYNotificar(
                    'Eliminar',
                    'Se ha eliminado un examen para el paciente: ' . $datos['cedula_paciente'],
                    $_SESSION['usuario']
                );
                echo json_encode($resultado);
                break;

            case 'obtener_tipos_select':
                echo json_encode($o->obtener_tipos_select());
                break;

            case 'obtener_pacientes_select':
                echo json_encode($o->obtener_pacientes_select());
                break;

            default:
                echo json_encode(array("resultado" => "error", "mensaje" => "Acción no válida"));
        }
        exit;
    }

    require_once("vista/" . $pagina . ".php");
} else {
    echo "Página en construcción";
}