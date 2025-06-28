<?php

namespace Shm\Shm\modelo;

use Shm\Shm\modelo\datos;
use PDO;
use Exception;

class pasantias extends datos
{
    private $cedula_estudiante;
    private $nombre;
    private $apellido;
    private $institucion;
    private $telefono;
    private $cod_area;
    private $fecha_inicio;
    private $fecha_fin;
    private $activo;

    private $nombre_area;
    private $descripcion;
    private $cedula_personal;

    // Setters
    public function set_cedula_estudiante($valor)
    {
        $this->cedula_estudiante = $valor;
    }
    public function set_nombre($valor)
    {
        $this->nombre = $valor;
    }
    public function set_apellido($valor)
    {
        $this->apellido = $valor;
    }
    public function set_institucion($valor)
    {
        $this->institucion = $valor;
    }
    public function set_telefono($valor)
    {
        $this->telefono = $valor;
    }
    public function set_cod_area($valor)
    {
        $this->cod_area = $valor;
    }
    public function set_fecha_inicio($valor)
    {
        $this->fecha_inicio = $valor;
    }
    public function set_fecha_fin($valor)
    {
        $this->fecha_fin = $valor;
    }
    public function set_activo($valor)
    {
        $this->activo = $valor;
    }
    public function set_nombre_area($valor)
    {
        $this->nombre_area = $valor;
    }
    public function set_descripcion($valor)
    {
        $this->descripcion = $valor;
    }
    public function set_cedula_personal($valor)
    {
        $this->cedula_personal = $valor;
    }

    // Métodos públicos para gestionar operaciones
    public function gestionar_estudiante($datos)
    {
        $this->set_cedula_estudiante($datos['cedula_estudiante'] ?? '');
        $this->set_nombre($datos['nombre'] ?? '');
        $this->set_apellido($datos['apellido'] ?? '');
        $this->set_institucion($datos['institucion'] ?? '');
        $this->set_telefono($datos['telefono'] ?? '');
        $this->set_cod_area($datos['cod_area'] ?? null);

        switch ($datos['accion']) {
            case 'incluir_estudiante':
                return $this->incluir_estudiante();
            case 'modificar_estudiante':
                return $this->modificar_estudiante();
            case 'eliminar_estudiante':
                return $this->eliminar_estudiante();
            default:
                return array("resultado" => "error", "mensaje" => "Acción no válida");
        }
    }

    public function gestionar_area($datos)
    {
        $this->set_cod_area($datos['cod_area'] ?? '');
        $this->set_nombre_area($datos['nombre_area'] ?? '');
        $this->set_descripcion($datos['descripcion'] ?? '');
        $this->set_cedula_personal($datos['responsable_id'] ?? '');

        switch ($datos['accion']) {
            case 'incluir_area':
                return $this->incluir_area();
            case 'modificar_area':
                return $this->modificar_area();
            case 'eliminar_area':
                return $this->eliminar_area();
            default:
                return array("resultado" => "error", "mensaje" => "Acción no válida");
        }
    }

    public function gestionar_asistencia($datos)
    {
        $this->set_cedula_estudiante($datos['cedula_estudiante'] ?? '');
        $this->set_cod_area($datos['cod_area'] ?? '');
        $this->set_fecha_inicio($datos['fecha_inicio'] ?? '');
        $this->set_fecha_fin($datos['fecha_fin'] ?? null);
        $this->set_activo($datos['activo'] ?? 0);

        switch ($datos['accion']) {
            case 'incluir_asistencia':
                return $this->incluir_asistencia();
            case 'modificar_asistencia':
                return $this->modificar_asistencia();
            case 'eliminar_asistencia':
                return $this->eliminar_asistencia();
            default:
                return array("resultado" => "error", "mensaje" => "Acción no válida");
        }
    }

    // Métodos privados para operaciones con BD
    private function incluir_estudiante()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $conexion->beginTransaction();

            if (!$this->existe_estudiante($this->cedula_estudiante)) {
                $this->ejecutar_insert_estudiante($conexion);

                if (!empty($this->cod_area)) {
                    $this->ejecutar_insert_asistencia($conexion);
                }

                $conexion->commit();
                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Estudiante registrado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'El estudiante ya está registrado';
            }
        } catch (Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    private function ejecutar_insert_estudiante($conexion)
    {
        $sql = "INSERT INTO estudiantes_pasantia (cedula_estudiante, nombre, apellido, institucion, telefono) 
                VALUES(:cedula, :nombre, :apellido, :institucion, :telefono)";

        $stmt = $conexion->prepare($sql);
        $stmt->execute(array(
            ':cedula' => $this->cedula_estudiante,
            ':nombre' => $this->nombre,
            ':apellido' => $this->apellido,
            ':institucion' => $this->institucion,
            ':telefono' => $this->telefono
        ));
    }

    private function ejecutar_insert_asistencia($conexion)
    {
        $fechaActual = date('Y-m-d');
        $sql = "INSERT INTO asistencia (cod_area, cedula_estudiante, fecha_inicio, activo) 
                VALUES(:area, :cedula, :inicio, 1)";

        $stmt = $conexion->prepare($sql);
        $stmt->execute(array(
            ':area' => $this->cod_area,
            ':cedula' => $this->cedula_estudiante,
            ':inicio' => $fechaActual
        ));
    }

    private function modificar_estudiante()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "UPDATE estudiantes_pasantia SET 
                    nombre = :nombre, 
                    apellido = :apellido, 
                    institucion = :institucion,
                    telefono = :telefono
                    WHERE cedula_estudiante = :cedula";

            $stmt = $conexion->prepare($sql);
            $stmt->execute(array(
                ':nombre' => $this->nombre,
                ':apellido' => $this->apellido,
                ':institucion' => $this->institucion,
                ':telefono' => $this->telefono,
                ':cedula' => $this->cedula_estudiante
            ));

            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Estudiante actualizado exitosamente';
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    private function eliminar_estudiante()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $conexion->beginTransaction();

            $this->ejecutar_delete_asistencias($conexion);
            $this->ejecutar_delete_estudiante($conexion);

            $conexion->commit();
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Estudiante eliminado exitosamente';
        } catch (Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    private function ejecutar_delete_asistencias($conexion)
    {
        $sql = "DELETE FROM asistencia WHERE cedula_estudiante = :cedula";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([':cedula' => $this->cedula_estudiante]);
    }

    private function ejecutar_delete_estudiante($conexion)
    {
        $sql = "DELETE FROM estudiantes_pasantia WHERE cedula_estudiante = :cedula";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([':cedula' => $this->cedula_estudiante]);
    }

    // Métodos para consultas
    public function consultar_estudiantes()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "SELECT cedula_estudiante, nombre, apellido, institucion, telefono
                    FROM estudiantes_pasantia
                    ORDER BY apellido, nombre";

            $stmt = $conexion->prepare($sql);
            $stmt->execute();

            $r['resultado'] = 'consultar';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    private function incluir_asistencia()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "INSERT INTO asistencia VALUES(:area, :cedula, :inicio, :fin, :activo)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute(array(
                ':area' => $this->cod_area,
                ':cedula' => $this->cedula_estudiante,
                ':inicio' => $this->fecha_inicio,
                ':fin' => $this->fecha_fin,
                ':activo' => $this->activo
            ));

            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Asistencia registrada exitosamente';
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    private function modificar_asistencia()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "UPDATE asistencia SET
                    cod_area = :area,
                    fecha_fin = :fin,
                    activo = :activo
                    WHERE cedula_estudiante = :cedula AND fecha_inicio = :inicio";

            $stmt = $conexion->prepare($sql);
            $stmt->execute(array(
                ':area' => $this->cod_area,
                ':fin' => $this->fecha_fin,
                ':activo' => $this->activo,
                ':cedula' => $this->cedula_estudiante,
                ':inicio' => $this->fecha_inicio
            ));

            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Asistencia actualizada exitosamente';
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    private function eliminar_asistencia()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "DELETE FROM asistencia 
                    WHERE cedula_estudiante = :cedula 
                    AND fecha_inicio = :fecha_inicio";

            $stmt = $conexion->prepare($sql);
            $stmt->execute(array(
                ':cedula' => $this->cedula_estudiante,
                ':fecha_inicio' => $this->fecha_inicio
            ));

            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Asistencia eliminada exitosamente';
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    public function consultar_asistencia()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "SELECT 
                    e.cedula_estudiante, 
                    CONCAT(e.nombre, ' ', e.apellido) as estudiante,
                    a.fecha_inicio, 
                    a.fecha_fin, 
                    a.activo, 
                    a.cod_area,
                    ar.nombre_area
                FROM asistencia a
                JOIN estudiantes_pasantia e ON a.cedula_estudiante = e.cedula_estudiante
                JOIN areas_pasantias ar ON a.cod_area = ar.cod_area
                ORDER BY a.fecha_inicio DESC";

            $stmt = $conexion->prepare($sql);
            $stmt->execute();

            $r['resultado'] = 'consultar';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    private function incluir_area()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "INSERT INTO areas_pasantias (nombre_area, descripcion, cedula_responsable) 
                    VALUES(:nombre, :descripcion, :responsable)";

            $stmt = $conexion->prepare($sql);
            $stmt->execute(array(
                ':nombre' => $this->nombre_area,
                ':descripcion' => $this->descripcion,
                ':responsable' => $this->cedula_personal
            ));

            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Área registrada exitosamente';
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    private function modificar_area()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "UPDATE areas_pasantias SET
                    nombre_area = :nombre, 
                    descripcion = :descripcion, 
                    cedula_responsable = :cedula_responsable
                    WHERE cod_area = :codigo";

            $stmt = $conexion->prepare($sql);
            $stmt->execute(array(
                ':nombre' => $this->nombre_area,
                ':descripcion' => $this->descripcion,
                ':cedula_responsable' => $this->cedula_personal,
                ':codigo' => $this->cod_area
            ));

            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Área actualizada exitosamente';
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    private function eliminar_area()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "SELECT COUNT(*) as total FROM asistencia 
                   WHERE cod_area = :cod_area";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([':cod_area' => $this->cod_area]);
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($fila['total'] == 0) {
                $sql = "DELETE FROM areas_pasantias WHERE cod_area = :cod_area";
                $stmt = $conexion->prepare($sql);
                $stmt->execute([':cod_area' => $this->cod_area]);

                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Área eliminada exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede eliminar, hay estudiantes asignados a esta área';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    public function consultar_areas()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "SELECT 
                    a.cod_area, 
                    a.nombre_area, 
                    a.descripcion, 
                    CONCAT(p.nombre, ' ', p.apellido) as responsable,
                    p.cedula_personal as cedula_responsable
                FROM areas_pasantias a
                JOIN personal p ON a.cedula_responsable = p.cedula_personal
                ORDER BY a.nombre_area";

            $stmt = $conexion->prepare($sql);
            $stmt->execute();

            $r['resultado'] = 'consultar';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    public function obtener_areas_select()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "SELECT cod_area, nombre_area FROM areas_pasantias ORDER BY nombre_area";
            $stmt = $conexion->prepare($sql);
            $stmt->execute();

            $r['resultado'] = 'consultar';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    public function obtener_doctores()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "SELECT cedula_personal, CONCAT(nombre, ' ', apellido) as nombre_completo
                    FROM personal 
                    WHERE cargo = 'Doctor' 
                    ORDER BY apellido, nombre";

            $stmt = $conexion->prepare($sql);
            $stmt->execute();

            $r['resultado'] = 'consultar';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    private function existe_estudiante($cedula)
    {
        $conexion = $this->conecta();

        try {
            $sql = "SELECT * FROM estudiantes_pasantia
                   WHERE cedula_estudiante = :cedula";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([':cedula' => $cedula]);
            return ($stmt->rowCount() > 0);
        } catch (Exception $e) {
            return false;
        } finally {
            $this->cerrar_conexion($conexion);
        }
    }

    private function cerrar_conexion(&$conexion)
    {
        if ($conexion) {
            $conexion = null;
        }
    }
}
