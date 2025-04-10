<?php
require_once(__DIR__ . '/../modelo/planificacion.php');

try {
    if (!isset($_SESSION['usuario'])) {
        throw new Exception("Usuario no autenticado.");
    }

    if (!isset($_POST['contenido']) || empty(trim($_POST['contenido']))) {
        throw new Exception("El campo contenido es obligatorio.");
    }

    $cedula_p = $_SESSION['usuario']; // Obtiene la cédula del usuario desde la sesión

    $planificacion = new planificacion();
    $planificacion->setCedulaP($cedula_p); // Asigna la cédula del usuario automáticamente
    $planificacion->setContenido($_POST['contenido']);

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = time() . '-' . basename($_FILES['imagen']['name']);
        $rutaCarpeta = __DIR__ . '/../img/publicaciones/';
        if (!is_dir($rutaCarpeta)) {
            mkdir($rutaCarpeta, 0777, true);
        }
        $rutaImagen = $rutaCarpeta . $nombreArchivo;
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
            throw new Exception("Error al mover el archivo cargado.");
        }
        $planificacion->setImagen($nombreArchivo);
    } else {
        $planificacion->setImagen(null); // Imagen opcional
    }

    $planificacion->setFecha(date('Y-m-d H:i:s'));
    $planificacion->guardarPublicacion();

    echo json_encode([
        "resultado" => "incluir",
        "mensaje" => "Registro incluido correctamente"
    ]);
} catch (Exception $e) {
    echo json_encode([
        "resultado" => "error",
        "mensaje" => $e->getMessage()
    ]);
}

require_once("vista/planificacion.php");

?>