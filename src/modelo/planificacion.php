<?php
namespace Shm\Shm\modelo;
use Shm\Shm\modelo\datos;
use PDO;
use Exception;

class planificacion extends datos {

    // Consultar publicaciones
    public function consultar_publicaciones($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $stmt = $co->query("SELECT * FROM feed ORDER BY fecha DESC");
            $r['resultado'] = 'consultar_publicaciones';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Obtener una publicación específica
    public function obtener_publicacion($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $cod_pub = $datos['cod_pub'] ?? '';
            $stmt = $co->query("SELECT * FROM feed WHERE cod_pub = '$cod_pub' LIMIT 1");
            $r['resultado'] = 'obtener_publicacion';
            $r['datos'] = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Incluir publicación
    public function incluir($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $contenido = $datos['contenido'] ?? '';
            $cedula_personal = $datos['cedula_personal'] ?? '';
            $imagen = '';
            if(isset($datos['imagen']) && is_array($datos['imagen']) && $datos['imagen']['tmp_name']) {
                $nombreArchivo = uniqid('pub_').basename($datos['imagen']['name']);
                $ruta = "img/publicaciones/".$nombreArchivo;
                move_uploaded_file($datos['imagen']['tmp_name'], $ruta);
                $imagen = $ruta;
            }
            $sql = "INSERT INTO feed (contenido, imagen, cedula_personal, fecha) VALUES ('$contenido', '$imagen', '$cedula_personal', NOW())";
            $co->exec($sql);
            $cod_pub = $co->lastInsertId();
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Publicación registrada exitosamente';
            $r['cod_pub'] = $cod_pub;
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Modificar publicación
    public function modificar($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $cod_pub = $datos['cod_pub'] ?? '';
            $contenido = $datos['contenido'] ?? '';
            $imagen = '';
            if(isset($datos['imagen']) && is_array($datos['imagen']) && $datos['imagen']['tmp_name']) {
                $nombreArchivo = uniqid('pub_').basename($datos['imagen']['name']);
                $ruta = "img/publicaciones/".$nombreArchivo;
                move_uploaded_file($datos['imagen']['tmp_name'], $ruta);
                $imagen = $ruta;
                $sql = "UPDATE feed SET contenido='$contenido', imagen='$imagen' WHERE cod_pub='$cod_pub'";
            } else {
                $sql = "UPDATE feed SET contenido='$contenido' WHERE cod_pub='$cod_pub'";
            }
            $co->exec($sql);
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Publicación modificada exitosamente';
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Eliminar publicación
    public function eliminar($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $cod_pub = $datos['cod_pub'] ?? '';
            $sql = "DELETE FROM feed WHERE cod_pub='$cod_pub'";
            $co->exec($sql);
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Publicación eliminada exitosamente';
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Verificar si el usuario es el autor de la publicación
    public function verificar_autor($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $cod_pub = $datos['cod_pub'] ?? '';
        $cedula_personal = $datos['cedula_personal'] ?? '';
        $stmt = $co->query("SELECT cedula_personal FROM feed WHERE cod_pub = '$cod_pub'");
        $publicacion = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($publicacion && $publicacion['cedula_personal'] == $cedula_personal);
    }
}
?>