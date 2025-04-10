
<?php
class planificacion {
    private $db;
    
    public function __construct() {
        try {
            $this->db = new PDO("mysql:host=localhost;dbname=shm-cdi.2", "root", "");
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function guardarPublicacion($cedula_p, $contenido, $imagen = null) {
        $fecha = date('Y-m-d H:i:s');
        $rutaImagen = null;
        
        if ($imagen) {
            $nombreImagen = uniqid() . '_' . basename($imagen['name']);
            $rutaImagen = 'img/publicaciones/' . $nombreImagen;
            
            if (!move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {
                return array("resultado" => "error", "mensaje" => "Error al subir la imagen");
            }
        }
        
        $stmt = $this->db->prepare("INSERT INTO feed (fecha, contenido, imagen, cedula_p) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$fecha, $contenido, $rutaImagen, $cedula_p]);
        
        if ($result) {
            return array("resultado" => "success", "mensaje" => "Publicación guardada", "id" => $this->db->lastInsertId());
        } else {
            return array("resultado" => "error", "mensaje" => "Error al guardar la publicación");
        }
    }

    public function modificarPublicacion($cod_pub, $contenido, $imagen = null) {
        $rutaImagen = null;
        $sql = "UPDATE feed SET contenido = ?";
        $params = [$contenido];
        
        if ($imagen) {
            // Primero obtenemos la imagen anterior para borrarla
            $stmt = $this->db->prepare("SELECT imagen FROM feed WHERE cod_pub = ?");
            $stmt->execute([$cod_pub]);
            $publicacion = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($publicacion['imagen'] && file_exists($publicacion['imagen'])) {
                unlink($publicacion['imagen']);
            }
            
            $nombreImagen = uniqid() . '_' . basename($imagen['name']);
            $rutaImagen = 'img/publicaciones/' . $nombreImagen;
            
            if (!move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {
                return array("resultado" => "error", "mensaje" => "Error al subir la imagen");
            }
            
            $sql .= ", imagen = ?";
            $params[] = $rutaImagen;
        }
        
        $sql .= " WHERE cod_pub = ?";
        $params[] = $cod_pub;
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($params);
        
        if ($result) {
            return array("resultado" => "success", "mensaje" => "Publicación actualizada");
        } else {
            return array("resultado" => "error", "mensaje" => "Error al actualizar la publicación");
        }
    }

    public function obtenerPublicaciones() {
        $stmt = $this->db->prepare("
            SELECT f.*, p.nombre, p.apellido 
            FROM feed f
            JOIN personal p ON f.cedula_p = p.cedula_personal
            ORDER BY f.fecha DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPublicacion($cod_pub) {
        $stmt = $this->db->prepare("SELECT * FROM feed WHERE cod_pub = ?");
        $stmt->execute([$cod_pub]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function eliminarPublicacion($cod_pub) {
        // Primero obtenemos la imagen para borrarla del servidor
        $stmt = $this->db->prepare("SELECT imagen FROM feed WHERE cod_pub = ?");
        $stmt->execute([$cod_pub]);
        $publicacion = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($publicacion['imagen'] && file_exists($publicacion['imagen'])) {
            unlink($publicacion['imagen']);
        }
        
        $stmt = $this->db->prepare("DELETE FROM feed WHERE cod_pub = ?");
        $result = $stmt->execute([$cod_pub]);
        
        if ($result) {
            return array("resultado" => "success", "mensaje" => "Publicación eliminada");
        } else {
            return array("resultado" => "error", "mensaje" => "Error al eliminar la publicación");
        }
    }
}
?>