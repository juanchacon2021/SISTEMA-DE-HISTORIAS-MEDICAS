<?php

ini_set('session.cookie_httponly', 1); 

ini_set('session.use_strict_mode', 1);

session_start();


$pagina = 'principal';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$path = trim(preg_replace('#^'.preg_quote($base,'#').'#','',$uri), '/');

if ($path !== '') {
    $sanitized_page = preg_replace('/[^a-z0-9_\-]/i', '', $path);
    if ($sanitized_page !== '') {
        $pagina = $sanitized_page;
    }
} else {
    $pagina = preg_replace('/[^a-z0-9_\-]/i', '', $_GET['pagina'] ?? 'principal');
}

if (!isset($_SESSION['usuario']) && $pagina !== 'login') {
    header("Location: ?pagina=login"); 
    exit();
}

require_once("vendor/autoload.php");
$nivel = ""; 

if (is_file("modelo/bitacora.php")) {
    require_once("modelo/bitacora.php");
}

if (is_file("modelo/verifica.php")) {
    require_once("modelo/verifica.php");    
    $v = new verifica();
    
    if ($pagina == 'salida') {
        if(isset($_SESSION['usuario']) && class_exists('bitacora')) { 
            bitacora::registrar('Cierre de sesión', 'Usuario cerró sesión');
        }
        $v->destruyesesion();
    } else {
        $nivel = $v->leesesion();
        
    }
}

if (is_file("controlador/" . $pagina . ".php")) {
    require_once("controlador/" . $pagina . ".php");
} else {
    if ($pagina !== 'principal') {
        http_response_code(404);
    }
    echo "PÁGINA EN CONSTRUCCIÓN";
}
?>