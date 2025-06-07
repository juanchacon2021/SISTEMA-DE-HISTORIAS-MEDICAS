<?php
require_once('modelo/datos.php');

class entrada extends datos{
    private $email; 
    private $clave;
    
    function set_email($valor) { $this->email = $valor; }
    function set_clave($valor) { $this->clave = $valor; }
    
    function get_email() { return $this->email; }
    function get_clave() { return $this->clave; }

    function existe() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $stmt = $co->prepare("SELECT id, nombre, email, password, rol_id, cedula_personal FROM usuario WHERE email = :email");
            $stmt->bindParam(':email', $this->email);
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
                    $_SESSION['usuario'] = $usuario['cedula_personal']; // NO el id, sino la cédula
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
        $permisos = array();
        
        try {
            $stmt = $co->prepare("SELECT m.nombre 
                                 FROM permiso p
                                 JOIN modulo m ON p.modulo_id = m.id
                                 WHERE p.rol_id = :rol_id");
            $stmt->bindParam(':rol_id', $rol_id);
            $stmt->execute();
            
            while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $permisos[] = $fila['nombre'];
            }
        } catch (Exception $e) {
            error_log("Error al obtener permisos: " . $e->getMessage());
        }
        
        return $permisos;
    }
}
?>