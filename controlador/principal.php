<?php
// filepath: controlador/principal.php
require_once("src/modelo/principal.php");
use Shm\Shm\modelo\principal;

// Obtener las últimas 10 notificaciones de la bitácora usando el modelo
$notificaciones = principal::obtenerNotificaciones(10);
$_SESSION['notificaciones'] = $notificaciones;

$datosUsuario = ['nombre' => 'Usuario no encontrado', 'foto_perfil' => 'img/user.png', 'cedula_personal' => ''];
if (isset($_SESSION['usuario'])) {
    $datosUsuario = principal::obtenerDatosUsuario($_SESSION['usuario']);
}

if(is_file("vista/".$pagina.".php")){
    require_once("vista/".$pagina.".php"); 
}
else{
    echo "pagina en construccion";
}
?>