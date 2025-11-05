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

        // Validar campos
        $val = $this->validar_campos([
            'cedula_estudiante' => $this->cedula_estudiante,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'institucion' => $this->institucion,
            'telefono' => $this->telefono,
            'cod_area' => $this->cod_area
        ]);
        if (!is_array($val) || !isset($val['codigo'])) {
            return ['resultado' => 'error', 'mensaje' => 'Error al validar campos'];
        }
        if ($val['codigo'] !== 0) {
            return ['resultado' => 'error', 'mensaje' => $val['mensaje']];
        }

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
        // Usar procedure para insertar estudiante de pasantía
        $sql = "CALL insertar_estudiante_pasantia(:cedula, :nombre, :apellido, :institucion, :telefono)";
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
        // Usar procedure para insertar asistencia
        $sql = "CALL insertar_asistencia_pasantia(:area, :cedula, :inicio)";
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

        // Validar campos
        $val = $this->validar_campos([
            'cedula_estudiante' => $this->cedula_estudiante,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'institucion' => $this->institucion,
            'telefono' => $this->telefono
        ]);
        if (!is_array($val) || !isset($val['codigo'])) {
            return ['resultado' => 'error', 'mensaje' => 'Error al validar campos'];
        }
        if ($val['codigo'] !== 0) {
            return ['resultado' => 'error', 'mensaje' => $val['mensaje']];
        }

        $conexion = $this->conecta();

        try {
            // Usar procedure para modificar estudiante
            $sql = "CALL modificar_estudiante_pasantia(:cedula, :nombre, :apellido, :institucion, :telefono)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute(array(
                ':cedula' => $this->cedula_estudiante,
                ':nombre' => $this->nombre,
                ':apellido' => $this->apellido,
                ':institucion' => $this->institucion,
                ':telefono' => $this->telefono
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

        // Validar cédula antes de eliminar
        $val = $this->validar_campos(['cedula_estudiante' => $this->cedula_estudiante]);
        if (!is_array($val) || !isset($val['codigo'])) {
            return ['resultado' => 'error', 'mensaje' => 'Error al validar campos'];
        }
        if ($val['codigo'] !== 0) {
            return ['resultado' => 'error', 'mensaje' => $val['mensaje']];
        }

        $conexion = $this->conecta();

        try {
            $conexion->beginTransaction();

            // Usar procedure para eliminar asistencias del estudiante
            $sql = "CALL eliminar_asistencias_pasantia(:cedula)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([':cedula' => $this->cedula_estudiante]);

            // Usar procedure para eliminar estudiante
            $sql2 = "CALL eliminar_estudiante_pasantia(:cedula)";
            $stmt2 = $conexion->prepare($sql2);
            $stmt2->execute([':cedula' => $this->cedula_estudiante]);

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

        // Validar asistencia
        $val = $this->validar_campos([
            'cedula_estudiante' => $this->cedula_estudiante,
            'cod_area' => $this->cod_area,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'activo' => $this->activo
        ]);
        if (!is_array($val) || !isset($val['codigo'])) {
            return ['resultado' => 'error', 'mensaje' => 'Error al validar campos'];
        }
        if ($val['codigo'] !== 0) {
            return ['resultado' => 'error', 'mensaje' => $val['mensaje']];
        }

        $conexion = $this->conecta();

        try {
            // Usar procedure para insertar asistencia
            $sql = "CALL insertar_asistencia_pasantia_completa(:area, :cedula, :inicio, :fin, :activo)";
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

        // Validar asistencia
        $val = $this->validar_campos([
            'cedula_estudiante' => $this->cedula_estudiante,
            'cod_area' => $this->cod_area,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'activo' => $this->activo
        ]);
        if (!is_array($val) || !isset($val['codigo'])) {
            return ['resultado' => 'error', 'mensaje' => 'Error al validar campos'];
        }
        if ($val['codigo'] !== 0) {
            return ['resultado' => 'error', 'mensaje' => $val['mensaje']];
        }

        $conexion = $this->conecta();

        try {
            // Usar procedure para modificar asistencia
            $sql = "CALL modificar_asistencia_pasantia(:area, :cedula, :inicio, :fin, :activo)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute(array(
                ':area' => $this->cod_area,
                ':cedula' => $this->cedula_estudiante,
                ':inicio' => $this->fecha_inicio,
                ':fin' => $this->fecha_fin,
                ':activo' => $this->activo
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

        // Validar campos
        $val = $this->validar_campos([
            'cedula_estudiante' => $this->cedula_estudiante,
            'fecha_inicio' => $this->fecha_inicio
        ]);
        if (!is_array($val) || !isset($val['codigo'])) {
            return ['resultado' => 'error', 'mensaje' => 'Error al validar campos'];
        }
        if ($val['codigo'] !== 0) {
            return ['resultado' => 'error', 'mensaje' => $val['mensaje']];
        }

        $conexion = $this->conecta();

        try {
            // Usar procedure para eliminar asistencia
            $sql = "CALL eliminar_asistencia_pasantia(:cedula, :fecha_inicio)";
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
                FROM periodo_pasantias a
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

    // Validar campos del área
    $val = $this->validar_campos([
        'nombre_area' => $this->nombre_area,
        'descripcion' => $this->descripcion,
        'cedula_personal' => $this->cedula_personal
    ]);
    if (!is_array($val) || !isset($val['codigo'])) {
        return ['resultado' => 'error', 'mensaje' => 'Error al validar campos'];
    }
    if ($val['codigo'] !== 0) {
        return ['resultado' => 'error', 'mensaje' => $val['mensaje']];
    }

    $conexion = $this->conecta();

    try {
        // Llamamos al procedimiento almacenado que genera el código automático
        $sql = "CALL insertar_area_pasantia(:nombre, :descripcion, :responsable, @cod_generado)";

        $stmt = $conexion->prepare($sql);
        $stmt->execute(array(
            ':nombre' => $this->nombre_area,
            ':descripcion' => $this->descripcion,
            ':responsable' => $this->cedula_personal
        ));

        // Obtenemos el código generado
        $stmt = $conexion->query("SELECT @cod_generado as codigo");
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $codigo_generado = $resultado['codigo'];

        $r['resultado'] = 'incluir';
        $r['mensaje'] = 'Área registrada exitosamente';
        $r['codigo'] = $codigo_generado; // Devolvemos el código generado
    } catch (Exception $e) {
        $r['resultado'] = 'error';
        $r['mensaje'] = $e->getMessage();
        $r['codigo'] = null;
    } finally {
        $this->cerrar_conexion($conexion);
    }
    return $r;
}

    private function modificar_area()
    {
        $r = array();

        // Validar campos del área
        $val = $this->validar_campos([
            'cod_area' => $this->cod_area,
            'nombre_area' => $this->nombre_area,
            'descripcion' => $this->descripcion,
            'cedula_personal' => $this->cedula_personal
        ]);
        if (!is_array($val) || !isset($val['codigo'])) {
            return ['resultado' => 'error', 'mensaje' => 'Error al validar campos'];
        }
        if ($val['codigo'] !== 0) {
            return ['resultado' => 'error', 'mensaje' => $val['mensaje']];
        }

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

        // Validar cod_area
        $val = $this->validar_campos(['cod_area' => $this->cod_area]);
        if (!is_array($val) || !isset($val['codigo'])) {
            return ['resultado' => 'error', 'mensaje' => 'Error al validar campos'];
        }
        if ($val['codigo'] !== 0) {
            return ['resultado' => 'error', 'mensaje' => $val['mensaje']];
        }

        $conexion = $this->conecta();

        try {
            $sql = "SELECT COUNT(*) as total FROM periodo_pasantias
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

    // Nueva función de validación de campos (similar a consultasm::validar_campos)
    private function validar_campos($datos)
    {
        $r = array('codigo' => 0, 'mensaje' => 'Campos válidos');

        // Cedula estudiante (si está presente)
        if (isset($datos['cedula_estudiante'])) {
            if (!preg_match('/^[0-9]{7,8}$/', $datos['cedula_estudiante'])) {
                $r['codigo'] = 1;
                $r['mensaje'] = 'Formato de cédula de estudiante inválido (7-8 dígitos)';
                return $r;
            }
        }

        // Nombre y apellido
        if (isset($datos['nombre']) && !preg_match('/^[A-Za-z\sñÑáéíóúÁÉÍÓÚ]{3,30}$/u', $datos['nombre'])) {
            $r['codigo'] = 2;
            $r['mensaje'] = 'Nombre inválido (3-30 letras)';
            return $r;
        }
        if (isset($datos['apellido']) && !preg_match('/^[A-Za-z\sñÑáéíóúÁÉÍÓÚ]{3,30}$/u', $datos['apellido'])) {
            $r['codigo'] = 3;
            $r['mensaje'] = 'Apellido inválido (3-30 letras)';
            return $r;
        }

        // Teléfono opcional
        if (isset($datos['telefono']) && trim($datos['telefono']) !== '' && !preg_match('/^[0-9]{11}$/', $datos['telefono'])) {
            $r['codigo'] = 4;
            $r['mensaje'] = 'Teléfono inválido (11 dígitos)';
            return $r;
        }

        // Fechas formato YYYY-MM-DD
        if (isset($datos['fecha_inicio']) && !(\DateTime::createFromFormat('Y-m-d', $datos['fecha_inicio']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $datos['fecha_inicio']))) {
            $r['codigo'] = 5;
            $r['mensaje'] = 'Formato de fecha_inicio inválido (YYYY-MM-DD)';
            return $r;
        }
        if (isset($datos['fecha_fin']) && $datos['fecha_fin'] !== null && !(\DateTime::createFromFormat('Y-m-d', $datos['fecha_fin']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $datos['fecha_fin']))) {
            $r['codigo'] = 6;
            $r['mensaje'] = 'Formato de fecha_fin inválido (YYYY-MM-DD)';
            return $r;
        }
        if (isset($datos['fecha_inicio']) && isset($datos['fecha_fin']) && $datos['fecha_fin'] !== null) {
            $d1 = \DateTime::createFromFormat('Y-m-d', $datos['fecha_inicio']);
            $d2 = \DateTime::createFromFormat('Y-m-d', $datos['fecha_fin']);
            if ($d1 && $d2 && $d2 < $d1) {
                $r['codigo'] = 7;
                $r['mensaje'] = 'fecha_fin no puede ser anterior a fecha_inicio';
                return $r;
            }
        }

        // cod_area si existe debe ser numérico
        if (isset($datos['cod_area']) && $datos['cod_area'] !== null && !is_numeric($datos['cod_area'])) {
            $r['codigo'] = 8;
            $r['mensaje'] = 'Código de área inválido';
            return $r;
        }

        // Validar área: nombre y descripción
        if (isset($datos['nombre_area']) && !preg_match('/^[A-Za-z0-9\s\-\.,]{3,100}$/u', $datos['nombre_area'])) {
            $r['codigo'] = 9;
            $r['mensaje'] = 'Nombre de área inválido (3-100 caracteres)';
            return $r;
        }
        if (isset($datos['descripcion']) && mb_strlen($datos['descripcion']) > 1000) {
            $r['codigo'] = 10;
            $r['mensaje'] = 'Descripción de área demasiado larga';
            return $r;
        }

        // Cedula responsable formato y existencia en personal (si viene)
        if (isset($datos['cedula_personal'])) {
            if (!preg_match('/^[0-9]{7,8}$/', $datos['cedula_personal'])) {
                $r['codigo'] = 11;
                $r['mensaje'] = 'Formato de cédula del responsable inválido';
                return $r;
            }
            // Verificar existencia en BD
            try {
                $co = $this->conecta();
                $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $co->prepare("SELECT 1 FROM personal WHERE cedula_personal = ? LIMIT 1");
                $stmt->execute([$datos['cedula_personal']]);
                $existe = ($stmt->fetchColumn() !== false);
                if (!$existe) {
                    $r['codigo'] = 12;
                    $r['mensaje'] = 'La cédula del responsable no existe en la base de datos';
                    return $r;
                }
            } catch (Exception $e) {
                $r['codigo'] = 13;
                $r['mensaje'] = 'Error al validar responsable: ' . $e->getMessage();
                return $r;
            } finally {
                if (isset($co)) $co = null;
            }
        }

        return $r;
    }
}
