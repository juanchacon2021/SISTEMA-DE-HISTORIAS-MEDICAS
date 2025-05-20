<?php
require_once('modelo/datos.php');

class usuarios extends datos {
    // Atributos para usuarios
    private $id;
    private $nombre;
    private $email;
    private $password;
    private $rol_id;
    
    // Atributos para roles
    private $nombre_rol;
    private $descripcion_rol;
    
    // Setters
    public function set_id($valor) { $this->id = $valor; }
    public function set_nombre($valor) { $this->nombre = $valor; }
    public function set_email($valor) { $this->email = $valor; }
    public function set_password($valor) { $this->password = $valor; }
    public function set_rol_id($valor) { $this->rol_id = $valor; }
    public function set_nombre_rol($valor) { $this->nombre_rol = $valor; }
    public function set_descripcion_rol($valor) { $this->descripcion_rol = $valor; }

    // Métodos CRUD para usuarios
    public function incluir_usuario() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            if(!$this->existe_email($this->email)) {
                $stmt = $co->prepare("INSERT INTO usuario (nombre, email, password, rol_id) VALUES(
                    :nombre, :email, :password, :rol_id)");
                
                $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
                
                $stmt->execute(array(
                    ':nombre' => $this->nombre,
                    ':email' => $this->email,
                    ':password' => $password_hash,
                    ':rol_id' => $this->rol_id
                ));
                
                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Usuario registrado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'El email ya está registrado';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function modificar_usuario() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            // Verificar si el email ya existe en otro usuario
            if($this->email_valido_para_edicion()) {
                $sql = "UPDATE usuario SET nombre = :nombre, email = :email, rol_id = :rol_id";
                $params = array(
                    ':nombre' => $this->nombre,
                    ':email' => $this->email,
                    ':rol_id' => $this->rol_id,
                    ':id' => $this->id
                );
                
                // Si se proporcionó una nueva contraseña
                if(!empty($this->password)) {
                    $sql .= ", password = :password";
                    $params[':password'] = password_hash($this->password, PASSWORD_DEFAULT);
                }
                
                $sql .= " WHERE id = :id";
                
                $stmt = $co->prepare($sql);
                $stmt->execute($params);
                
                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Usuario actualizado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'El email ya está registrado en otro usuario';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function eliminar_usuario() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            // No permitir eliminar al usuario admin principal
            if($this->id == 3) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede eliminar el usuario administrador principal';
                return $r;
            }
            
            $stmt = $co->prepare("DELETE FROM usuario WHERE id = :id");
            $stmt->execute(array(':id' => $this->id));
            
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Usuario eliminado exitosamente';
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function consultar_usuarios() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("SELECT u.id, u.nombre, u.email, u.fecha_creacion, 
                                    r.nombre as rol_nombre
                                    FROM usuario u
                                    JOIN rol r ON u.rol_id = r.id
                                    ORDER BY u.nombre");
            
            $r['resultado'] = 'consultar';
            $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    // Métodos CRUD para roles
    public function incluir_rol() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            if(!$this->existe_rol($this->nombre_rol)) {
                $stmt = $co->prepare("INSERT INTO rol (nombre, descripcion) VALUES(
                    :nombre, :descripcion)");
                
                $stmt->execute(array(
                    ':nombre' => $this->nombre_rol,
                    ':descripcion' => $this->descripcion_rol
                ));
                
                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Rol registrado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'El rol ya existe';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function modificar_rol() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            // No permitir modificar el rol admin principal
            if($this->id == 3) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede modificar el rol administrador principal';
                return $r;
            }
            
            // Verificar si el nombre ya existe en otro rol
            if($this->nombre_rol_valido_para_edicion()) {
                $stmt = $co->prepare("UPDATE rol SET nombre = :nombre, descripcion = :descripcion
                                    WHERE id = :id");
                
                $stmt->execute(array(
                    ':nombre' => $this->nombre_rol,
                    ':descripcion' => $this->descripcion_rol,
                    ':id' => $this->id
                ));
                
                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Rol actualizado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Ya existe un rol con ese nombre';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function eliminar_rol() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            // No permitir eliminar el rol admin principal
            if($this->id == 3) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede eliminar el rol administrador principal';
                return $r;
            }
            
            // Verificar si hay usuarios con este rol
            $stmt = $co->prepare("SELECT COUNT(*) as total FROM usuario WHERE rol_id = :id");
            $stmt->execute(array(':id' => $this->id));
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($resultado['total'] == 0) {
                // Primero eliminar los permisos asociados
                $co->query("DELETE FROM permiso WHERE rol_id = $this->id");
                
                // Luego eliminar el rol
                $co->query("DELETE FROM rol WHERE id = $this->id");
                
                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Rol eliminado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede eliminar, hay usuarios asignados a este rol';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function consultar_roles() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("SELECT * FROM rol ORDER BY nombre");
            
            $r['resultado'] = 'consultar';
            $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    // Métodos para permisos
    public function consultar_modulos() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("SELECT * FROM modulo ORDER BY nombre");
            
            $r['resultado'] = 'consultar';
            $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function consultar_permisos_rol($rol_id) {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $stmt = $co->prepare("SELECT modulo_id FROM permiso WHERE rol_id = :rol_id");
            $stmt->execute(array(':rol_id' => $rol_id));
            
            $permisos = array();
            while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $permisos[] = $fila['modulo_id'];
            }
            
            $r['resultado'] = 'consultar';
            $r['datos'] = $permisos;
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function actualizar_permisos($rol_id, $modulos) {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            // No permitir modificar permisos del rol admin principal
            if($rol_id == 3) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se pueden modificar los permisos del rol administrador principal';
                return $r;
            }
            
            // Iniciar transacción
            $co->beginTransaction();
            
            // Eliminar todos los permisos actuales del rol
            $stmt = $co->prepare("DELETE FROM permiso WHERE rol_id = :rol_id");
            $stmt->execute(array(':rol_id' => $rol_id));
            
            // Insertar los nuevos permisos
            if(!empty($modulos)) {
                $stmt = $co->prepare("INSERT INTO permiso (rol_id, modulo_id) VALUES (:rol_id, :modulo_id)");
                
                foreach($modulos as $modulo_id) {
                    $stmt->execute(array(
                        ':rol_id' => $rol_id,
                        ':modulo_id' => $modulo_id
                    ));
                }
            }
            
            // Confirmar transacción
            $co->commit();
            
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Permisos actualizados exitosamente';
        } catch(Exception $e) {
            // Revertir transacción en caso de error
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    // Métodos auxiliares
    public function obtener_roles_select() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("SELECT id, nombre FROM rol ORDER BY nombre");
            
            $r['resultado'] = 'consultar';
            $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    // Métodos de validación
    private function existe_email($email) {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            $stmt = $co->prepare("SELECT * FROM usuario WHERE email = :email");
            $stmt->execute(array(':email' => $email));
            return ($stmt->rowCount() > 0);
        } catch(Exception $e) {
            return false;
        }
    }
    
    private function email_valido_para_edicion() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            $stmt = $co->prepare("SELECT * FROM usuario WHERE email = :email AND id != :id");
            $stmt->execute(array(
                ':email' => $this->email,
                ':id' => $this->id
            ));
            return ($stmt->rowCount() == 0);
        } catch(Exception $e) {
            return false;
        }
    }
    
    private function existe_rol($nombre) {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            $stmt = $co->prepare("SELECT * FROM rol WHERE nombre = :nombre");
            $stmt->execute(array(':nombre' => $nombre));
            return ($stmt->rowCount() > 0);
        } catch(Exception $e) {
            return false;
        }
    }
    
    private function nombre_rol_valido_para_edicion() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            $stmt = $co->prepare("SELECT * FROM rol WHERE nombre = :nombre AND id != :id");
            $stmt->execute(array(
                ':nombre' => $this->nombre_rol,
                ':id' => $this->id
            ));
            return ($stmt->rowCount() == 0);
        } catch(Exception $e) {
            return false;
        }
    }
}
?>