<?php
session_start();
$pagina = $_GET['pagina'] ?? 'principal';

// Si no hay usuario autenticado, forzar login
if (!isset($_SESSION['usuario']) && $pagina !== 'login') {
    header("Location: ?pagina=login");
    exit();
}

// Normalizar URL limpia -> $_GET['pagina']
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$path = trim(preg_replace('#^'.preg_quote($base,'#').'#','',$uri), '/');

if ($path !== '') {
    // Solo permitir nombres simples (letras, números, guión bajo y guión)
    $page = preg_replace('/[^a-z0-9_\-]/i', '', $path);
    if ($page !== '') {
        $_GET['pagina'] = $page;
    }
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