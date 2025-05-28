<?php
if (!is_file("modelo/notificaciones.php")){
    echo "Falta definir la clase notificaciones";
    exit;
}
require_once("modelo/notificaciones.php");

if (!empty($_POST)) {
    $o = new notificaciones();
    $accion = $_POST['accion'] ?? '';
    if ($accion == 'obtener_notificaciones') {
        echo json_encode($o->obtener_notificaciones());
        exit;
    }
}
?>