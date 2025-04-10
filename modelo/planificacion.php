<?php
require_once(__DIR__ . '/datos.php');

class planificacion extends datos {
    private $cedula_p;
    private $contenido;
    private $imagen;
    private $fecha;

    public function setCedulaP($cedula_p) {
        $this->cedula_p = htmlspecialchars(strip_tags($cedula_p));
    }

    public function setContenido($contenido) {
        $this->contenido = htmlspecialchars(strip_tags($contenido));
    }

    public function setImagen($imagen) {
        $this->imagen = htmlspecialchars(strip_tags($imagen));
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function guardarPublicacion() {
        $conexion = $this->conecta();
        $sql = "INSERT INTO feed (cedula_p, contenido, imagen, fecha) 
                VALUES (:cedula_p, :contenido, :imagen, :fecha)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':cedula_p', $this->cedula_p);
        $stmt->bindParam(':contenido', $this->contenido);
        $stmt->bindParam(':imagen', $this->imagen);
        $stmt->bindParam(':fecha', $this->fecha);

        if (!$stmt->execute()) {
            throw new Exception("Error al guardar la publicación en la base de datos.");
        }
    }
}
?>