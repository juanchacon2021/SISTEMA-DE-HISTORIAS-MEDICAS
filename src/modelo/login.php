<?php
namespace Shm\Shm\modelo;
use Shm\Shm\modelo\datos;
use PDO;
use Exception;

class login extends datos{
    private $cedula; 
    private $clave;
    
    function set_cedula($valor) { $this->cedula = $valor; }
    function set_clave($valor) { $this->clave = $valor; }
    
    function get_cedula() { return $this->cedula; }
    function get_clave() { return $this->clave; }

    function existe() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $stmt = $co->prepare("SELECT id, nombre, password, rol_id, cedula_personal FROM usuario WHERE cedula_personal = :cedula");
            $stmt->bindParam(':cedula', $this->cedula);
            $stmt->execute();
            
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario) {
                if (password_verify($this->clave, $usuario['password'])) {
                    $permisos = $this->obtener_permisos_rol($usuario['rol_id']);
                    
                    $r['resultado'] = 'existe';
                    $r['mensaje'] = $usuario['rol_id'];
                    $r['usuario'] = $usuario['id'];
                    $r['nombre'] = $usuario['nombre'];
                    $r['cedula_personal'] = $usuario['cedula_personal'];
                    $r['permisos'] = $permisos;
                    
                    // Al autenticar:
                    $_SESSION['usuario'] = $usuario['cedula_personal'];
                } else {
                    $r['resultado'] = 'noexiste';
                    $r['mensaje'] = "Credenciales incorrectas";
                }
            } else {
                $r['resultado'] = 'noexiste';
                $r['mensaje'] = "Usuario no encontrado";
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

private function obtener_permisos_rol($rol_id) {
    $co = $this->conecta2();
    $permisos = array(
        'modulos' => array(),
        'acciones' => array()
    );
    
    try {
        // Obtener módulos a los que tiene acceso
        $stmt = $co->prepare("SELECT m.nombre 
                             FROM permiso p
                             JOIN modulo m ON p.modulo_id = m.id
                             WHERE p.rol_id = :rol_id");
        $stmt->bindParam(':rol_id', $rol_id);
        $stmt->execute();
        
        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $permisos['modulos'][] = $fila['nombre'];
        }
        
        // Obtener permisos específicos (registrar, modificar, eliminar)
        $stmt = $co->prepare("SELECT m.nombre, p.registrar, p.modificar, p.eliminar
                             FROM permiso p
                             JOIN modulo m ON p.modulo_id = m.id
                             WHERE p.rol_id = :rol_id");
        $stmt->bindParam(':rol_id', $rol_id);
        $stmt->execute();
        
        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $permisos['acciones'][$fila['nombre']] = array(
                'registrar' => $fila['registrar'],
                'modificar' => $fila['modificar'],
                'eliminar' => $fila['eliminar']
            );
        }
    } catch (Exception $e) {
        error_log("Error al obtener permisos: " . $e->getMessage());
    }
    
    return $permisos;
}}
?>