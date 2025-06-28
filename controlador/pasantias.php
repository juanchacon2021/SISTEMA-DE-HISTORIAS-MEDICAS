<?php
if (!is_file("src/modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

use Shm\Shm\modelo\pasantias;

if (is_file("vista/" . $pagina . ".php")) {
    if (!empty($_POST)) {
        $o = new pasantias();
        $accion = $_POST['accion'];

        // Convertir $_POST a array y filtrar datos
        $datos = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        switch ($accion) {
            case 'consultar_estudiantes':
                echo json_encode($o->consultar_estudiantes());
                break;

            case 'consultar_asistencia':
                echo json_encode($o->consultar_asistencia());
                break;

            case 'consultar_areas':
                echo json_encode($o->consultar_areas());
                break;

            case 'incluir_estudiante':
            case 'modificar_estudiante':
            case 'eliminar_estudiante':
                echo json_encode($o->gestionar_estudiante($datos));
                bitacora::registrar(
                    ucfirst(str_replace('_', ' ', $accion)),
                    'Acción sobre estudiante: ' . $datos['cedula_estudiante']
                );
                break;

            case 'incluir_asistencia':
            case 'modificar_asistencia':
            case 'eliminar_asistencia':
                echo json_encode($o->gestionar_asistencia($datos));
                bitacora::registrar(
                    ucfirst(str_replace('_', ' ', $accion)),
                    'Acción sobre asistencia: ' . $datos['cedula_estudiante']
                );
                break;

            case 'incluir_area':
            case 'modificar_area':
            case 'eliminar_area':
                echo json_encode($o->gestionar_area($datos));
                $id = isset($datos['cod_area']) ? $datos['cod_area'] : 'nueva área';
                bitacora::registrar(
                    ucfirst(str_replace('_', ' ', $accion)),
                    'Acción sobre área: ' . $id
                );
                break;

            case 'obtener_areas_select':
                echo json_encode($o->obtener_areas_select());
                break;

            case 'obtener_doctores':
                echo json_encode($o->obtener_doctores());
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
