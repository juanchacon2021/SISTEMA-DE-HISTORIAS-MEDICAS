<?php
if (!is_file("src/modelo/historias.php")) {
    echo "Falta definir la clase historias";
    exit;
}
use Shm\Shm\modelo\historias;

if (is_file("vista/historias.php")) {
    if (!empty($_POST)) {
        $o = new historias();
        $accion = $_POST['accion'];
        switch ($accion) {
            case 'consultar':
                echo json_encode($o->consultar());
                break;
        }
        exit;
    }
    require_once("vista/historias.php");
} else {
    echo "pagina en construccion";
}