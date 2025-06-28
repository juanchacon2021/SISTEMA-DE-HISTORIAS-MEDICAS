<?php
if (!is_file("src/modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

use Shm\Shm\modelo\examenes;

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
            case 'modificar_tipo':
            case 'eliminar_tipo':
                echo json_encode($o->gestionar_tipo($datos));
                bitacora::registrar(
                    ucfirst(str_replace('_', ' ', $accion)),
                    'Acción sobre tipo de examen: ' . $datos['cod_examen']
                );
                break;

            case 'incluir_registro':
            case 'modificar_registro':
            case 'eliminar_registro':
                echo json_encode($o->gestionar_registro($datos));
                bitacora::registrar(
                    ucfirst(str_replace('_', ' ', $accion)),
                    'Acción sobre registro de examen: ' . $datos['cedula_paciente']
                );
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