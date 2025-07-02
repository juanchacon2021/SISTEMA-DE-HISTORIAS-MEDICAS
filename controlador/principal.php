<?php
require_once(__DIR__ . '/../src/modelo/principal.php');
use Shm\Shm\modelo\principal;

if (!empty($_POST) && isset($_POST['accion']) && $_POST['accion'] == 'totales_generales') {
    $cedula = $_SESSION['usuario'] ?? null;
    if ($cedula) {
        echo json_encode(principal::obtenerTotales($cedula));
    } else {
        echo json_encode([
            'pacientes' => 0,
            'personal' => 0,
            'notificaciones' => 0
        ]);
    }
    exit;
}

// Obtener las últimas 10 notificaciones de la bitácora usando el modelo
$notificaciones = principal::obtenerNotificaciones(10);
$_SESSION['notificaciones'] = $notificaciones;

$datosUsuario = ['nombre' => 'Usuario no encontrado', 'foto_perfil' => 'img/user.png', 'cedula_personal' => ''];
if (isset($_SESSION['usuario'])) {
    $datosUsuario = principal::obtenerDatosUsuario($_SESSION['usuario']);
}

if(is_file("vista/".$pagina.".php")){
    require_once("vista/".$pagina.".php"); 
} else {
    echo "pagina en construccion";
}
?>