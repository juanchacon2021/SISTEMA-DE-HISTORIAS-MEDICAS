<?php
require_once("modelo/datos.php");

class verifica {
    
    function leesesion() {
        // La verificación correcta de si la sesión está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['nivel']) ? $_SESSION['nivel'] : "";
    }
    
    function destruyesesion() {
        // La verificación es necesaria también para poder llamar a session_destroy()
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: ?pagina=login");
        exit();
    }
    
    function tiene_permiso($modulo) {
        return isset($_SESSION['permisos']) && in_array($modulo, $_SESSION['permisos']);
    }
    
    function validar_captcha($captcha_ingresado) {
        if (session_status() === PHP_SESSION_NONE) {
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