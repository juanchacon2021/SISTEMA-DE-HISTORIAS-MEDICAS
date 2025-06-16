<?php
require_once('modelo/datos.php');

class usuarios extends datos {
    private $id;
    private $nombre;
    private $cedula;
    private $password;
    private $foto_perfil;
    private $rol_id;
    private $nombre_rol;
    private $descripcion_rol;
    private $id_modulo;
    private $nombre_modulo;
    private $descripcion_modulo;
    
    public function set_id($valor) { $this->id = $valor; }
    public function set_nombre($valor) { $this->nombre = $valor; }
    public function set_cedula($valor) { $this->cedula = $valor; }
    public function set_password($valor) { $this->password = $valor; }
    public function set_foto_perfil($valor) { $this->foto_perfil = $valor; }
    public function set_rol_id($valor) { $this->rol_id = $valor; }
    public function set_nombre_rol($valor) { $this->nombre_rol = $valor; }
    public function set_descripcion_rol($valor) { $this->descripcion_rol = $valor; }
    public function set_id_modulo($valor) { $this->id_modulo = $valor; }
    public function set_nombre_modulo($valor) { $this->nombre_modulo = $valor; }
    public function set_descripcion_modulo($valor) { $this->descripcion_modulo = $valor; }

    public function incluir_usuario() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            if(!$this->existe_cedula($this->cedula)) {
                $stmt = $co->prepare("INSERT INTO usuario (nombre, cedula_personal, password, rol_id, foto_perfil) VALUES(
                    :nombre, :cedula, :password, :rol_id, :foto_perfil)");
                
                $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
                
                $stmt->execute(array(
                    ':nombre' => $this->nombre,
                    ':cedula' => $this->cedula,
                    ':password' => $password_hash,
                    ':rol_id' => $this->rol_id,
                    ':foto_perfil' => $this->foto_perfil
                ));
                
                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Usuario registrado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'La cédula ya está registrada';
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
            if($this->cedula_valida_para_edicion()) {
                $sql = "UPDATE usuario SET nombre = :nombre, cedula_personal = :cedula, rol_id = :rol_id";
                $params = array(
                    ':nombre' => $this->nombre,
                    ':cedula' => $this->cedula,
                    ':rol_id' => $this->rol_id,
                    ':id' => $this->id
                );
                
                if(!empty($this->password)) {
                    $sql .= ", password = :password";
                    $params[':password'] = password_hash($this->password, PASSWORD_DEFAULT);
                }
                
                if(!empty($this->foto_perfil)) {
                    $sql .= ", foto_perfil = :foto_perfil";
                    $params[':foto_perfil'] = $this->foto_perfil;
                }
                
                $sql .= " WHERE id = :id";
                
                $stmt = $co->prepare($sql);
                $stmt->execute($params);
                
                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Usuario actualizado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'La cédula ya está registrada en otro usuario';
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
    
    public function eliminar_foto_perfil($usuario_id) {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $stmt = $co->prepare("UPDATE usuario SET foto_perfil = NULL WHERE id = :id");
            $stmt->bindParam(':id', $usuario_id);
            $stmt->execute();
            
            $r['resultado'] = 'exito';
            $r['mensaje'] = 'Foto de perfil eliminada correctamente';
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
            $resultado = $co->query("SELECT u.id, u.nombre, u.cedula_personal as cedula, u.fecha_creacion, u.foto_perfil,
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
            if($this->id == 3) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede modificar el rol administrador principal';
                return $r;
            }
            
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
            if($this->id == 3) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede eliminar el rol administrador principal';
                return $r;
            }
            
            $stmt = $co->prepare("SELECT COUNT(*) as total FROM usuario WHERE rol_id = :id");
            $stmt->execute(array(':id' => $this->id));
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($resultado['total'] == 0) {
                $co->query("DELETE FROM permiso WHERE rol_id = $this->id");
                
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

    public function consultar_modulos_tabla() {
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
        $stmt = $co->prepare("SELECT modulo_id, registrar, modificar, eliminar FROM permiso WHERE rol_id = :rol_id");
        $stmt->execute(array(':rol_id' => $rol_id));
        
        $permisos = array();
        while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $permisos[] = array(
                'modulo_id' => $fila['modulo_id'],
                'registrar' => $fila['registrar'],
                'modificar' => $fila['modificar'],
                'eliminar' => $fila['eliminar']
            );
        }
        
        $r['resultado'] = 'consultar';
        $r['datos'] = $permisos;
    } catch(Exception $e) {
        $r['resultado'] = 'error';
        $r['mensaje'] = $e->getMessage();
    }
    return $r;
}

public function actualizar_permisos($rol_id, $permisos) {
    $co = $this->conecta2();
    $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $r = array();
    
    try {
        if($rol_id == 3) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'No se pueden modificar los permisos del rol administrador principal';
            return $r;
        }
        
        $co->beginTransaction();
        
        // Primero eliminar todos los permisos existentes para este rol
        $stmt = $co->prepare("DELETE FROM permiso WHERE rol_id = :rol_id");
        $stmt->execute(array(':rol_id' => $rol_id));
        
        // Procesar los nuevos permisos
        $modulos_con_permisos = array();
        foreach($permisos as $permiso) {
            $modulo_id = $permiso['modulo_id'];
            $tipo = $permiso['tipo'];
            
            if(!isset($modulos_con_permisos[$modulo_id])) {
                $modulos_con_permisos[$modulo_id] = array(
                    'registrar' => 0,
                    'modificar' => 0,
                    'eliminar' => 0
                );
            }
            
            $modulos_con_permisos[$modulo_id][$tipo] = 1;
        }
        
        // Insertar los permisos consolidados
        $stmt = $co->prepare("INSERT INTO permiso (rol_id, modulo_id, registrar, modificar, eliminar) 
                             VALUES (:rol_id, :modulo_id, :registrar, :modificar, :eliminar)");
        
        foreach($modulos_con_permisos as $modulo_id => $permisos_modulo) {
            $stmt->execute(array(
                ':rol_id' => $rol_id,
                ':modulo_id' => $modulo_id,
                ':registrar' => $permisos_modulo['registrar'],
                ':modificar' => $permisos_modulo['modificar'],
                ':eliminar' => $permisos_modulo['eliminar']
            ));
        }
        
        $co->commit();
        
        $r['resultado'] = 'modificar';
        $r['mensaje'] = 'Permisos actualizados exitosamente';
    } catch(Exception $e) {
        $co->rollBack();
        $r['resultado'] = 'error';
        $r['mensaje'] = $e->getMessage();
    }
    return $r;
}    
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

    public function incluir_modulo() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            if(!$this->existe_modulo($this->nombre_modulo)) {
                $stmt = $co->prepare("INSERT INTO modulo (nombre, descripcion) VALUES(
                    :nombre, :descripcion)");
                
                $stmt->execute(array(
                    ':nombre' => $this->nombre_modulo,
                    ':descripcion' => $this->descripcion_modulo
                ));
                
                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Módulo registrado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'El módulo ya existe';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function modificar_modulo() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            if($this->nombre_modulo_valido_para_edicion()) {
                $stmt = $co->prepare("UPDATE modulo SET nombre = :nombre, descripcion = :descripcion
                                    WHERE id = :id");
                
                $stmt->execute(array(
                    ':nombre' => $this->nombre_modulo,
                    ':descripcion' => $this->descripcion_modulo,
                    ':id' => $this->id_modulo
                ));
                
                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Módulo actualizado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Ya existe un módulo con ese nombre';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function obtener_personal() {
    $co = $this->conecta();
    $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $r = array();
    
    try {
        $resultado = $co->query("
            SELECT cedula_personal, CONCAT(nombre, ' ', apellido) as nombre_completo
            FROM personal
            ORDER BY apellido, nombre
        ");
        
        $r['resultado'] = 'consultar';
        $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
        $r['resultado'] = 'error';
        $r['mensaje'] = $e->getMessage();
    }
    return $r;
}

    public function eliminar_modulo() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $co->beginTransaction();
            
            // Primero eliminar los permisos asociados
            $stmt = $co->prepare("DELETE FROM permiso WHERE modulo_id = :id");
            $stmt->execute(array(':id' => $this->id_modulo));
            
            // Luego eliminar el módulo
            $stmt = $co->prepare("DELETE FROM modulo WHERE id = :id");
            $stmt->execute(array(':id' => $this->id_modulo));
            
            $co->commit();
            
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Módulo eliminado exitosamente';
        } catch(Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
        private function existe_cedula($cedula) {
            $co = $this->conecta2();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            try {
                $stmt = $co->prepare("SELECT * FROM usuario WHERE cedula_personal = :cedula");
                $stmt->execute(array(':cedula' => $cedula));
                return ($stmt->rowCount() > 0);
            } catch(Exception $e) {
                return false;
            }
        }    
    private function cedula_valida_para_edicion() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            $stmt = $co->prepare("SELECT * FROM usuario WHERE cedula_personal = :cedula AND id != :id");
            $stmt->execute(array(
                ':cedula' => $this->cedula,
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

    private function existe_modulo($nombre) {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            $stmt = $co->prepare("SELECT * FROM modulo WHERE nombre = :nombre");
            $stmt->execute(array(':nombre' => $nombre));
            return ($stmt->rowCount() > 0);
        } catch(Exception $e) {
            return false;
        }
    }

    private function nombre_modulo_valido_para_edicion() {
        $co = $this->conecta2();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            $stmt = $co->prepare("SELECT * FROM modulo WHERE nombre = :nombre AND id != :id");
            $stmt->execute(array(
                ':nombre' => $this->nombre_modulo,
                ':id' => $this->id_modulo
            ));
            return ($stmt->rowCount() == 0);
        } catch(Exception $e) {
            return false;
        }
    }
}
?>