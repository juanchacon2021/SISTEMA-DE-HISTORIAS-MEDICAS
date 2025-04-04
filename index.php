<?php
$pagina = "principal"; 

if (!empty($_GET['pagina'])){
    $pagina = $_GET['pagina'];  
}

$nivel = ""; 
if (is_file("modelo/verifica.php")) {
    require_once("modelo/verifica.php");	
    $v = new verifica();
    if ($pagina == 'salida') {
        $v->destruyesesion();
    } else {
        $nivel = $v->leesesion();
    }
}
 
if (is_file("controlador/" . $pagina . ".php")) {
    require_once("controlador/" . $pagina . ".php");
} else {
    echo "PAGINA EN CONSTRUCCIÓN";
}
?>