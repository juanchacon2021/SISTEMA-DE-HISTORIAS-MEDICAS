<?php
if (!is_file("src/modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

use Shm\Shm\modelo\reportes_p;

if (is_file("vista/" . $pagina . ".php")) {
    if (!empty($_POST)) {
        $o = new reportes_p();
        $accion = $_POST['accion'];

        // Convertir $_POST a array y filtrar datos
        $datos = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        switch ($accion) {
            case 'consultar_estructura':
                echo json_encode($o->obtenerEstructuraTabla($datos['tabla']));
                break;

            case 'consultar_datos':
                $filtros = isset($datos['filtros']) ? json_decode($datos['filtros'], true) : array();
                $campos = isset($datos['campos']) ? json_decode($datos['campos'], true) : array();
                echo json_encode($o->obtenerDatosTabla($datos['tabla'], $filtros, $campos));
                break;

            case 'consultar_tablas':
                echo json_encode($o->obtenerTablasDisponibles());
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