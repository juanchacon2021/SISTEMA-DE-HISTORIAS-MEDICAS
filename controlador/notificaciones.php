<?php
// Verificar autenticación
if(!isset($_SESSION['usuario'])) {
    echo json_encode([
        'resultado' => 'error',
        'mensaje' => 'Usuario no autenticado'
    ]);
    exit;
}

// Verificar que el modelo exista
$ruta_modelo = __DIR__.'/../modelo/notificaciones.php';
if (!file_exists($ruta_modelo)) {
    echo json_encode([
        'resultado' => 'error',
        'mensaje' => 'Archivo del modelo no encontrado: '.$ruta_modelo
    ]);
    exit;
}

require_once($ruta_modelo);

header('Content-Type: application/json');

try {
    $o = new notificaciones();
    
    if(isset($_GET['accion']) && $_GET['accion'] == 'obtener_notificaciones') {
        $resultado = $o->obtener_notificaciones();
        echo json_encode($resultado);
        exit;
    }
    
    throw new Exception("Acción no especificada o no válida");
    
} catch(Exception $e) {
    error_log("Error en controlador: ".$e->getMessage());
    echo json_encode([
        'resultado' => 'error',
        'mensaje' => $e->getMessage()
    ]);
}
?>