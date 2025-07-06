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
            $stmt = $co->query("
                SELECT f.*, p.nombre AS nombre_usuario, p.apellido AS apellido_usuario
                FROM feed f
                LEFT JOIN personal p ON p.cedula_personal = f.cedula_personal
                ORDER BY f.fecha DESC
            ");
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

    public function incluir($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            // Procesar datos
            $contenido = $datos['contenido'] ?? '';
            $cedula_personal = $datos['cedula_personal'] ?? '';
            $imagen = '';
            
            // Manejo de la imagen
            if(isset($datos['imagen']) && is_array($datos['imagen']) && $datos['imagen']['tmp_name']) {
                $nombreArchivo = uniqid('pub_').basename($datos['imagen']['name']);
                $ruta = "img/publicaciones/".$nombreArchivo;
                move_uploaded_file($datos['imagen']['tmp_name'], $ruta);
                $imagen = $ruta;
            }
            
            // Llamar al procedimiento almacenado
            $sql = "CALL insertar_publicacion_feed(:contenido, :imagen, :cedula, @cod_generado)";
            $stmt = $co->prepare($sql);
            $stmt->bindParam(':contenido', $contenido);
            $stmt->bindParam(':imagen', $imagen);
            $stmt->bindParam(':cedula', $cedula_personal);
            $stmt->execute();
            
            // Obtener el código generado
            $stmt = $co->query("SELECT @cod_generado as codigo");
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $cod_pub = $resultado['codigo'];
            
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Publicación registrada exitosamente';
            $r['cod_pub'] = $cod_pub;
            
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
            $r['cod_pub'] = null;
        } finally {
            // Cerrar conexión si es necesario
            if(isset($co)) {
                $co = null;
            }
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

    // Obtener medicamento
    public function obtener_medicamento($cod_medicamento) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $stmt = $co->prepare("SELECT * FROM medicamentos WHERE cod_medicamento = ?");
            $stmt->execute([$cod_medicamento]);
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            if($fila) {
                $fila['stock_minimo'] = 0;   // Valor fijo
                $fila['stock_maximo'] = 250; // Valor fijo
                $r['resultado'] = 'obtener_medicamento';
                $r['datos'] = $fila;
                // Lotes
                $lotes = $this->consultar_lotes_medicamento($cod_medicamento);
                if($lotes['resultado'] == 'consultar_lotes') {
                    $r['lotes'] = $lotes['datos'];
                }
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Medicamento no encontrado';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Consultar medicamentos
    public function consultar_medicamentos() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("
                SELECT m.*, COALESCE(SUM(l.cantidad), 0) as stock_total
                FROM medicamentos m
                LEFT JOIN lotes l ON m.cod_medicamento = l.cod_medicamento
                GROUP BY m.cod_medicamento
                ORDER BY m.nombre
            ");
            $datos = $resultado->fetchAll(PDO::FETCH_ASSOC);
            // Agregar stock_minimo y stock_maximo a cada medicamento
            foreach ($datos as &$med) {
                $med['stock_minimo'] = 0;
                $med['stock_maximo'] = 250;
            }
            $r['resultado'] = 'consultar_medicamentos';
            $r['datos'] = $datos;
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // Consultar lotes de un medicamento
    public function consultar_lotes_medicamento($cod_medicamento) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $stmt = $co->prepare("SELECT * FROM lotes WHERE cod_medicamento = ? ORDER BY fecha_vencimiento ASC");
            $stmt->execute([$cod_medicamento]);
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $r['resultado'] = 'consultar_lotes';
            $r['datos'] = $datos;
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
}
?>