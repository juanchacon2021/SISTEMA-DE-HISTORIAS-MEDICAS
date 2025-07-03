<?php

namespace Shm\Shm\modelo;

use Shm\Shm\modelo\datos;
use Pdo;
use Exception;


class usuarios extends datos
{
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

    // Setters
    public function set_id($valor)
    {
        $this->id = $valor;
    }
    public function set_nombre($valor)
    {
        $this->nombre = $valor;
    }
    public function set_cedula($valor)
    {
        $this->cedula = $valor;
    }
    public function set_password($valor)
    {
        $this->password = $valor;
    }
    public function set_foto_perfil($valor)
    {
        $this->foto_perfil = $valor;
    }
    public function set_rol_id($valor)
    {
        $this->rol_id = $valor;
    }
    public function set_nombre_rol($valor)
    {
        $this->nombre_rol = $valor;
    }
    public function set_descripcion_rol($valor)
    {
        $this->descripcion_rol = $valor;
    }
    public function set_id_modulo($valor)
    {
        $this->id_modulo = $valor;
    }
    public function set_nombre_modulo($valor)
    {
        $this->nombre_modulo = $valor;
    }
    public function set_descripcion_modulo($valor)
    {
        $this->descripcion_modulo = $valor;
    }

    // Método unificado para gestionar usuarios
    public function gestionar_usuario($datos)
    {
        $this->set_id($datos['id'] ?? '');
        $this->set_nombre($datos['nombre'] ?? '');
        $this->set_cedula($datos['cedula'] ?? '');
        $this->set_password($datos['password'] ?? '');
        $this->set_rol_id($datos['rol_id'] ?? '');

        if (!empty($datos['foto_perfil'])) {
            $this->set_foto_perfil($datos['foto_perfil']);
        }

        switch ($datos['accion']) {
            case 'incluir_usuario':
                return $this->incluir_usuario();
            case 'modificar_usuario':
                return $this->modificar_usuario();
            case 'eliminar_usuario':
                return $this->eliminar_usuario();
            default:
                return array("resultado" => "error", "mensaje" => "Acción no válida");
        }
    }

    // Método unificado para gestionar roles
    public function gestionar_rol($datos)
    {
        $this->set_id($datos['id'] ?? '');
        $this->set_nombre_rol($datos['nombre'] ?? '');
        $this->set_descripcion_rol($datos['descripcion'] ?? '');

        switch ($datos['accion']) {
            case 'incluir_rol':
                return $this->incluir_rol();
            case 'modificar_rol':
                return $this->modificar_rol();
            case 'eliminar_rol':
                return $this->eliminar_rol();
            default:
                return array("resultado" => "error", "mensaje" => "Acción no válida");
        }
    }

    // Método unificado para gestionar módulos
    public function gestionar_modulo($datos)
    {
        $this->set_id_modulo($datos['id'] ?? '');
        $this->set_nombre_modulo($datos['nombre'] ?? '');
        $this->set_descripcion_modulo($datos['descripcion'] ?? '');

        switch ($datos['accion']) {
            case 'incluir_modulo':
                return $this->incluir_modulo();
            case 'modificar_modulo':
                return $this->modificar_modulo();
            case 'eliminar_modulo':
                return $this->eliminar_modulo();
            default:
                return array("resultado" => "error", "mensaje" => "Acción no válida");
        }
    }

    public function incluir_usuario()
    {
        $r = array();
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $conexion->beginTransaction();

            if (!$this->existe_cedula($this->cedula)) {
                $stmt = $conexion->prepare("INSERT INTO usuario (nombre, cedula_personal, password, rol_id, foto_perfil) 
                                          VALUES(:nombre, :cedula, :password, :rol_id, :foto_perfil)");

                $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

                $stmt->execute(array(
                    ':nombre' => $this->nombre,
                    ':cedula' => $this->cedula,
                    ':password' => $password_hash,
                    ':rol_id' => $this->rol_id,
                    ':foto_perfil' => $this->foto_perfil
                ));

                $conexion->commit();
                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Usuario registrado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'La cédula ya está registrada';
            }
        } catch (Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function modificar_usuario()
    {
        $r = array();
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $conexion->beginTransaction();

            if ($this->cedula_valida_para_edicion()) {
                $sql = "UPDATE usuario SET nombre = :nombre, cedula_personal = :cedula, rol_id = :rol_id";
                $params = array(
                    ':nombre' => $this->nombre,
                    ':cedula' => $this->cedula,
                    ':rol_id' => $this->rol_id,
                    ':id' => $this->id
                );

                if (!empty($this->password)) {
                    $sql .= ", password = :password";
                    $params[':password'] = password_hash($this->password, PASSWORD_DEFAULT);
                }

                if (!empty($this->foto_perfil)) {
                    $sql .= ", foto_perfil = :foto_perfil";
                    $params[':foto_perfil'] = $this->foto_perfil;
                }

                $sql .= " WHERE id = :id";

                $stmt = $conexion->prepare($sql);
                $stmt->execute($params);

                $conexion->commit();
                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Usuario actualizado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'La cédula ya está registrada en otro usuario';
            }
        } catch (Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function eliminar_usuario()
    {
        $r = array();
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $conexion->beginTransaction();

            if ($this->id == 3) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede eliminar el usuario administrador principal';
                return $r;
            }

            $stmt = $conexion->prepare("DELETE FROM usuario WHERE id = :id");
            $stmt->execute(array(':id' => $this->id));

            $conexion->commit();
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Usuario eliminado exitosamente';
        } catch (Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function eliminar_foto_perfil($usuario_id)
    {
        $r = array();
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $conexion->beginTransaction();

            $stmt = $conexion->prepare("UPDATE usuario SET foto_perfil = NULL WHERE id = :id");
            $stmt->bindParam(':id', $usuario_id);
            $stmt->execute();

            $conexion->commit();
            $r['resultado'] = 'exito';
            $r['mensaje'] = 'Foto de perfil eliminada correctamente';
        } catch (Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function consultar_usuarios()
    {
        $r = array();
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $conexion->prepare("SELECT u.id, u.nombre, u.cedula_personal as cedula, u.fecha_creacion, u.foto_perfil,
                                      r.nombre as rol_nombre, r.id as rol_id
                                      FROM usuario u
                                      JOIN rol r ON u.rol_id = r.id
                                      ORDER BY u.nombre");
            $stmt->execute();

            $r['resultado'] = 'consultar';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function incluir_rol()
    {
        $r = array();
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $conexion->beginTransaction();

            if (!$this->existe_rol($this->nombre_rol)) {
                $stmt = $conexion->prepare("INSERT INTO rol (nombre, descripcion) VALUES(:nombre, :descripcion)");

                $stmt->execute(array(
                    ':nombre' => $this->nombre_rol,
                    ':descripcion' => $this->descripcion_rol
                ));

                $conexion->commit();
                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Rol registrado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'El rol ya existe';
            }
        } catch (Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function modificar_rol()
    {
        $r = array();
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $conexion->beginTransaction();

            if ($this->id == 3) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede modificar el rol administrador principal';
                return $r;
            }

            if ($this->nombre_rol_valido_para_edicion()) {
                $stmt = $conexion->prepare("UPDATE rol SET nombre = :nombre, descripcion = :descripcion
                                          WHERE id = :id");

                $stmt->execute(array(
                    ':nombre' => $this->nombre_rol,
                    ':descripcion' => $this->descripcion_rol,
                    ':id' => $this->id
                ));

                $conexion->commit();
                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Rol actualizado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Ya existe un rol con ese nombre';
            }
        } catch (Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function eliminar_rol()
    {
        $r = array();
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $conexion->beginTransaction();

            if ($this->id == 3) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede eliminar el rol administrador principal';
                return $r;
            }

            $stmt = $conexion->prepare("SELECT COUNT(*) as total FROM usuario WHERE rol_id = :id");
            $stmt->execute(array(':id' => $this->id));
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado['total'] == 0) {
                $stmt = $conexion->prepare("DELETE FROM permiso WHERE rol_id = :id");
                $stmt->execute(array(':id' => $this->id));

                $stmt = $conexion->prepare("DELETE FROM rol WHERE id = :id");
                $stmt->execute(array(':id' => $this->id));

                $conexion->commit();
                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Rol eliminado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede eliminar, hay usuarios asignados a este rol';
            }
        } catch (Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function consultar_roles()
    {
        $r = array();
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $conexion->prepare("SELECT * FROM rol ORDER BY nombre");
            $stmt->execute();

            $r['resultado'] = 'consultar';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function consultar_modulos()
    {
        $r = array();
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $conexion->prepare("SELECT * FROM modulo ORDER BY nombre");
            $stmt->execute();

            $r['resultado'] = 'consultar';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function consultar_modulos_tabla()
    {
        $r = array();
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $conexion->prepare("SELECT * FROM modulo ORDER BY nombre");
            $stmt->execute();

            $r['resultado'] = 'consultar';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function consultar_permisos_rol($rol_id)
    {
        $r = array();
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $conexion->prepare("SELECT modulo_id, registrar, modificar, eliminar FROM permiso WHERE rol_id = :rol_id");
            $stmt->execute(array(':rol_id' => $rol_id));

            $permisos = array();
            while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $permisos[] = array(
                    'modulo_id' => $fila['modulo_id'],
                    'registrar' => $fila['registrar'],
                    'modificar' => $fila['modificar'],
                    'eliminar' => $fila['eliminar']
                );
            }

            $r['resultado'] = 'consultar';
            $r['datos'] = $permisos;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function actualizar_permisos($rol_id, $permisos)
    {
        $r = array();
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            if ($rol_id == 3) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se pueden modificar los permisos del rol administrador principal';
                return $r;
            }

            $conexion->beginTransaction();

            // Eliminar permisos existentes para este rol
            $stmt = $conexion->prepare("DELETE FROM permiso WHERE rol_id = :rol_id");
            $stmt->execute(array(':rol_id' => $rol_id));

            // Agrupar permisos por módulo
            $permisosPorModulo = array();
            foreach ($permisos as $permiso) {
                $modulo_id = $permiso['modulo_id'];
                if (!isset($permisosPorModulo[$modulo_id])) {
                    $permisosPorModulo[$modulo_id] = [
                        'registrar' => 0,
                        'modificar' => 0,
                        'eliminar' => 0
                    ];
                }
                $permisosPorModulo[$modulo_id][$permiso['tipo']] = 1;
            }

            // Insertar los permisos agrupados
            $stmt = $conexion->prepare("INSERT INTO permiso (rol_id, modulo_id, registrar, modificar, eliminar) 
                                   VALUES (:rol_id, :modulo_id, :registrar, :modificar, :eliminar)");

            foreach ($permisosPorModulo as $modulo_id => $tipos) {
                $stmt->execute(array(
                    ':rol_id' => $rol_id,
                    ':modulo_id' => $modulo_id,
                    ':registrar' => $tipos['registrar'],
                    ':modificar' => $tipos['modificar'],
                    ':eliminar' => $tipos['eliminar']
                ));
            }

            $conexion->commit();
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Permisos actualizados exitosamente';
        } catch (Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function obtener_roles_select()
    {
        $r = array();
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $conexion->prepare("SELECT id, nombre FROM rol ORDER BY nombre");
            $stmt->execute();

            $r['resultado'] = 'consultar';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function incluir_modulo()
    {
        $r = array();
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $conexion->beginTransaction();

            if (!$this->existe_modulo($this->nombre_modulo)) {
                $stmt = $conexion->prepare("INSERT INTO modulo (nombre, descripcion) VALUES(:nombre, :descripcion)");

                $stmt->execute(array(
                    ':nombre' => $this->nombre_modulo,
                    ':descripcion' => $this->descripcion_modulo
                ));

                $conexion->commit();
                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Módulo registrado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'El módulo ya existe';
            }
        } catch (Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function modificar_modulo()
    {
        $r = array();
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $conexion->beginTransaction();

            if ($this->nombre_modulo_valido_para_edicion()) {
                $stmt = $conexion->prepare("UPDATE modulo SET nombre = :nombre, descripcion = :descripcion
                                          WHERE id = :id");

                $stmt->execute(array(
                    ':nombre' => $this->nombre_modulo,
                    ':descripcion' => $this->descripcion_modulo,
                    ':id' => $this->id_modulo
                ));

                $conexion->commit();
                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Módulo actualizado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Ya existe un módulo con ese nombre';
            }
        } catch (Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function obtener_personal()
    {
        $r = array();
        $conexion = $this->conecta();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $conexion->prepare("SELECT cedula_personal, CONCAT(nombre, ' ', apellido) as nombre_completo
                                      FROM personal
                                      ORDER BY apellido, nombre");
            $stmt->execute();

            $r['resultado'] = 'consultar';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    public function eliminar_modulo()
    {
        $r = array();
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $conexion->beginTransaction();

            // Eliminar permisos asociados
            $stmt = $conexion->prepare("DELETE FROM permiso WHERE modulo_id = :id");
            $stmt->execute(array(':id' => $this->id_modulo));

            // Eliminar módulo
            $stmt = $conexion->prepare("DELETE FROM modulo WHERE id = :id");
            $stmt->execute(array(':id' => $this->id_modulo));

            $conexion->commit();
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Módulo eliminado exitosamente';
        } catch (Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    private function existe_cedula($cedula)
    {
        $conexion = $this->conecta();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $conexion->prepare("SELECT * FROM usuario WHERE cedula_personal = :cedula");
            $stmt->execute(array(':cedula' => $cedula));
            return ($stmt->rowCount() > 0);
        } catch (Exception $e) {
            return false;
        }
    }

    private function cedula_valida_para_edicion()
    {
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $conexion->prepare("SELECT * FROM usuario WHERE cedula_personal = :cedula AND id != :id");
            $stmt->execute(array(
                ':cedula' => $this->cedula,
                ':id' => $this->id
            ));
            return ($stmt->rowCount() == 0);
        } catch (Exception $e) {
            return false;
        }
    }

    private function existe_rol($nombre)
    {
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $conexion->prepare("SELECT * FROM rol WHERE nombre = :nombre");
            $stmt->execute(array(':nombre' => $nombre));
            return ($stmt->rowCount() > 0);
        } catch (Exception $e) {
            return false;
        }
    }

    private function nombre_rol_valido_para_edicion()
    {
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $conexion->prepare("SELECT * FROM rol WHERE nombre = :nombre AND id != :id");
            $stmt->execute(array(
                ':nombre' => $this->nombre_rol,
                ':id' => $this->id
            ));
            return ($stmt->rowCount() == 0);
        } catch (Exception $e) {
            return false;
        }
    }

    private function existe_modulo($nombre)
    {
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $conexion->prepare("SELECT * FROM modulo WHERE nombre = :nombre");
            $stmt->execute(array(':nombre' => $nombre));
            return ($stmt->rowCount() > 0);
        } catch (Exception $e) {
            return false;
        }
    }

    private function nombre_modulo_valido_para_edicion()
    {
        $conexion = $this->conecta2();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $conexion->prepare("SELECT * FROM modulo WHERE nombre = :nombre AND id != :id");
            $stmt->execute(array(
                ':nombre' => $this->nombre_modulo,
                ':id' => $this->id_modulo
            ));
            return ($stmt->rowCount() == 0);
        } catch (Exception $e) {
            return false;
        }
    }
}
