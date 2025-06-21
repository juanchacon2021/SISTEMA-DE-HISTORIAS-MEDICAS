<?php
namespace Shm\Shm\modelo;
use Shm\Shm\modelo\datos;
use PDO;
use Exception;

class planificacion extends datos {
    private $cod_pub;
    private $contenido;
    private $imagen;
    private $id_usuario;
    private $ruta_imagen;
    private $cedula_personal;

    public function set_cod_pub($valor) { $this->cod_pub = $valor; }
    public function set_contenido($valor) { $this->contenido = $valor; }
    public function set_imagen($valor) { $this->imagen = $valor; }
    public function set_id_usuario($valor) { $this->id_usuario = $valor; }
    public function set_ruta_imagen($valor) { $this->ruta_imagen = $valor; }
    public function set_cedula_personal($valor) { $this->cedula_personal = $valor; }

    public function get_cod_pub() { return $this->cod_pub; }
    public function get_contenido() { return $this->contenido; }
    public function get_imagen() { return $this->imagen; }
    public function get_id_usuario() { return $this->id_usuario; }
    public function get_ruta_imagen() { return $this->ruta_imagen; }
    public function get_cedula_personal() { return $this->cedula_personal; }

    public function consultar_publicaciones() {
    $co = $this->conecta();
    $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $r = array();
    
    try {
        $resultado = $co->query("
            SELECT 
                f.*,
                CONCAT(p.nombre, ' ', p.apellido) AS nombre_usuario
            FROM feed f
            JOIN personal p ON f.cedula_personal = p.cedula_personal
            ORDER BY f.fecha DESC
        ");
        
        if($resultado) {
            $r['resultado'] = 'consultar_publicaciones';
            $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch(Exception $e) {
        $r['resultado'] = 'error';
        $r['mensaje'] = $e->getMessage();
    }
    return $r;
}

    public function obtener_publicacion() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("
                SELECT * FROM feed 
                WHERE cod_pub = '$this->cod_pub'
            ");
            
            if($resultado) {
                $fila = $resultado->fetch(PDO::FETCH_ASSOC);
                if($fila) {
                    $r['resultado'] = 'obtener_publicacion';
                    $r['datos'] = $fila;
                } else {
                    $r['resultado'] = 'error';
                    $r['mensaje'] = 'Publicación no encontrada';
                }
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Error al consultar publicación';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function incluir() {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            $co->beginTransaction();
            
            // Procesar imagen si existe
            $ruta_imagen = null;
            if(isset($this->imagen)) {
                $directorio = "img/publicaciones/";
                if(!is_dir($directorio)) {
                    mkdir($directorio, 0777, true);
                }
                
                $nombre_archivo = uniqid() . "_" . basename($this->imagen['name']);
                $ruta_imagen = $directorio . $nombre_archivo;
                
                if(!move_uploaded_file($this->imagen['tmp_name'], $ruta_imagen)) {
                    throw new Exception("Error al subir la imagen");
                }
            }
            
            // Insertar publicación
            $co->query("
                INSERT INTO feed(
                    fecha, contenido, imagen, cedula_personal
                ) VALUES(
                    NOW(), '$this->contenido', '$ruta_imagen', '$this->cedula_personal'
                )
            ");
            
            $co->commit();
            $r['resultado'] = 'incluir_publicacion';
            $r['mensaje'] = 'Publicación registrada con éxito';
        } catch(Exception $e) {
            $co->rollBack();
            // Eliminar imagen si hubo error
            if(isset($ruta_imagen) && file_exists($ruta_imagen)) {
                unlink($ruta_imagen);
            }
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function modificar() {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            $co->beginTransaction();
            
            // Obtener publicación actual
            $publicacion = $co->query("
                SELECT imagen FROM feed 
                WHERE cod_pub = '$this->cod_pub'
            ")->fetch(PDO::FETCH_ASSOC);

            $ruta_imagen = $publicacion['imagen'];

            // Procesar nueva imagen si existe
            if(isset($this->imagen)) {
                // Eliminar imagen anterior si existe
                if($ruta_imagen && file_exists($ruta_imagen)) {
                    unlink($ruta_imagen);
                }
                
                $directorio = "img/publicaciones/";
                $nombre_archivo = uniqid() . "_" . basename($this->imagen['name']);
                $ruta_imagen = $directorio . $nombre_archivo;
                
                if(!move_uploaded_file($this->imagen['tmp_name'], $ruta_imagen)) {
                    throw new Exception("Error al subir la nueva imagen");
                }
            }
            
            $co->query("
                UPDATE feed SET
                    contenido = '$this->contenido',
                    imagen = " . ($ruta_imagen ? "'$ruta_imagen'" : "imagen") . "
                WHERE cod_pub = '$this->cod_pub'
            ");
            
            $co->commit();
            $r['resultado'] = 'modificar_publicacion';
            $r['mensaje'] = 'Publicación actualizada con éxito';
        } catch(Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function eliminar() {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            $co->beginTransaction();
            
            // Obtener imagen para eliminarla
            $publicacion = $co->query("
                SELECT imagen FROM feed 
                WHERE cod_pub = '$this->cod_pub'
            ")->fetch(PDO::FETCH_ASSOC);
            
            // Eliminar publicación
            $co->query("
                DELETE FROM feed 
                WHERE cod_pub = '$this->cod_pub'
            ");
            
            // Eliminar imagen si existe
            if($publicacion['imagen'] && file_exists($publicacion['imagen'])) {
                unlink($publicacion['imagen']);
            }
            
            $co->commit();
            $r['resultado'] = 'eliminar_publicacion';
            $r['mensaje'] = 'Publicación eliminada con éxito';
        } catch(Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
}
?>