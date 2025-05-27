<?php
require_once('modelo/datos.php');

class notificaciones extends datos {
    private $id_usuario;

    public function __construct() {
        $this->id_usuario = $_SESSION['usuario'] ?? null;
        
        if(!$this->id_usuario) {
            throw new Exception("Usuario no autenticado");
        }
    }

    public function obtener_notificaciones() {
        $co = $this->conecta();
        if(!$co) {
            throw new Exception("Error al conectar con la base de datos");
        }

        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            // Consulta mejorada con manejo de errores
            $sql = "SELECT b.*, m.nombre as modulo 
                    FROM `seguridad`.`bitacora` b
                    JOIN `seguridad`.`modulo` m ON b.modulo_id = m.id
                    WHERE b.usuario_id = :usuario_id
                    ORDER BY b.fecha_hora DESC
                    LIMIT 10";
            
            $stmt = $co->prepare($sql);
            $stmt->bindParam(':usuario_id', $this->id_usuario, PDO::PARAM_INT);
            
            if(!$stmt->execute()) {
                throw new Exception("Error al ejecutar consulta");
            }
            
            $r['resultado'] = 'ok';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $r['total'] = count($r['datos']);
            
        } catch(PDOException $e) {
            error_log("Error PDO: " . $e->getMessage());
            $r['resultado'] = 'error';
            $r['mensaje'] = "Error en la base de datos: " . $e->getMessage();
        } catch(Exception $e) {
            error_log("Error General: " . $e->getMessage());
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
}
?>