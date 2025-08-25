<?php
session_start();
$pagina = $_GET['pagina'] ?? 'principal';

// Si no hay usuario autenticado, forzar login
if (!isset($_SESSION['usuario']) && $pagina !== 'login') {
    header("Location: ?pagina=login");
    exit();
}

require_once("vendor/autoload.php");
$nivel = ""; 
if (is_file("modelo/verifica.php")) {
    require_once("modelo/verifica.php");    
    $v = new verifica();
    if ($pagina == 'salida') {
        if(isset($_SESSION['usuario'])) {
            require_once("modelo/bitacora.php");
            bitacora::registrar('Cierre de sesión', 'Usuario cerró sesión');
        }
        $v->destruyesesion();
    } else {
        $nivel = $v->leesesion();
        
        if($nivel && !in_array($pagina, ['login', 'bitacora'])) {
            require_once("modelo/bitacora.php");
            
        }
    }
}

if (is_file("controlador/" . $pagina . ".php")) {
    require_once("controlador/" . $pagina . ".php");
} else {
    echo "PÁGINA EN CONSTRUCCIÓN";
}
?>