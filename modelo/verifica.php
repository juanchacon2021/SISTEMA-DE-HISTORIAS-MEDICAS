<?php
require_once("modelo/datos.php");
class verifica {
    function leesesion() {
        if(empty($_SESSION)) {
            session_start();
        }
        return isset($_SESSION['nivel']) ? $_SESSION['nivel'] : "";
    }
    
    function destruyesesion() {
        session_start();
        session_destroy();
        header("Location: . ");
    }
    
    // Función opcional para verificar permisos desde la sesión
    function tiene_permiso($modulo) {
        return isset($_SESSION['permisos']) && in_array($modulo, $_SESSION['permisos']);
    }
}
?>