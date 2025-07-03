<?php
if (!is_file("src/modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

use Shm\Shm\modelo\personal;

require_once("modelo/bitacora.php");

if (is_file("vista/" . $pagina . ".php")) {
    if (!empty($_POST)) {
        $o = new personal();
        $accion = $_POST['accion'];

        // Convertir $_POST a array y filtrar datos
        $datos = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        switch ($accion) {
            case 'consultar':
                echo json_encode($o->consultar());
                break;

            case 'incluir':
            case 'modificar':
            case 'eliminar':
                if (isset($_POST['telefonos']) && is_string($_POST['telefonos'])) {
                    $_POST['telefonos'] = json_decode($_POST['telefonos'], true);
                }
                $resultado = $o->gestionar_personal($_POST);
                bitacora::registrarYNotificar(
                    ucfirst($accion),
                    'Acción sobre personal: ' . $_POST['cedula_personal'],
                    $_SESSION['usuario']
                );
                echo json_encode($resultado);
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
    echo "pagina en construccion";
}
