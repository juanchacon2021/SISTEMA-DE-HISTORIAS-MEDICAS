<?php
require_once 'modelo/pacientes.php';

if (!empty($_POST)) {
    $accion = $_POST['accion'];
    $o = new historias();

    if ($accion == 'consultar') {
        $resultado = $o->consultar();
        if (json_last_error() === JSON_ERROR_NONE) {
            echo json_encode($resultado);
        } else {
            echo "Error JSON: " . json_last_error_msg();
        }
    } else {
        $o->set_cedula_historia($_POST['cedula_historia']);
        $o->set_apellido($_POST['apellido']);
        $o->set_nombre($_POST['nombre']);
        $o->set_fecha_nac($_POST['fecha_nac']);
        $o->set_edad($_POST['edad']);
        $o->set_telefono($_POST['telefono']);
    }
} else {
    function mostrarPacientes() {
        $modelo = new historias();
        $resultado = $modelo->consultar();

        if ($resultado['resultado'] == 'consultar') {
            $datos = $resultado['datos'];
            $nivel = 'Doctor';
            require 'vista/pacientes.php';
        } else {
            echo "Error: " . $resultado['mensaje'];
        }
    }

    mostrarPacientes();
}
?>