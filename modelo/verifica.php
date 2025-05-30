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
    
    function tiene_permiso($modulo) {
        return isset($_SESSION['permisos']) && in_array($modulo, $_SESSION['permisos']);
    }
    
    function validar_captcha($captcha_ingresado) {
        if(empty($_SESSION)) {
            session_start();
        }
        
        if(!isset($_SESSION['captcha_code'])) {
            return false;
        }
        
        $resultado = strtolower($captcha_ingresado) === strtolower($_SESSION['captcha_code']);
        unset($_SESSION['captcha_code']);
        
        return $resultado;
    }
}
?>