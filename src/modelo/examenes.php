<?php

namespace Shm\Shm\modelo;

use Shm\Shm\modelo\datos;
use PDO;
use Exception;

class examenes extends datos
{
    private $cod_examen;
    private $nombre_examen;
    private $descripcion_examen;
    private $cedula_paciente;
    private $fecha_e;
    private $hora_e;
    private $observacion_examen;
    private $ruta_imagen;

    // Setters
    public function set_cod_examen($valor)
    {
        $this->cod_examen = $valor;
    }
    public function set_nombre_examen($valor)
    {
        $this->nombre_examen = $valor;
    }
    public function set_descripcion_examen($valor)
    {
        $this->descripcion_examen = $valor;
    }
    public function set_cedula_paciente($valor)
    {
        $this->cedula_paciente = $valor;
    }
    public function set_fecha_e($valor)
    {
        $this->fecha_e = $valor;
    }
    public function set_hora_e($valor)
    {
        $this->hora_e = $valor;
    }
    public function set_observacion_examen($valor)
    {
        $this->observacion_examen = $valor;
    }
    public function set_ruta_imagen($valor)
    {
        $this->ruta_imagen = $valor;
    }

    // Métodos públicos para gestionar operaciones
    public function gestionar_tipo($datos)
    {
        $this->set_cod_examen($datos['cod_examen'] ?? '');
        $this->set_nombre_examen($datos['nombre_examen'] ?? '');
        $this->set_descripcion_examen($datos['descripcion_examen'] ?? '');

        switch ($datos['accion']) {
            case 'incluir_tipo':
                return $this->incluir_tipo();
            case 'modificar_tipo':
                return $this->modificar_tipo();
            case 'eliminar_tipo':
                return $this->eliminar_tipo();
            default:
                return array("resultado" => "error", "mensaje" => "Acción no válida");
        }
    }

    public function gestionar_registro($datos)
    {
        $this->set_cedula_paciente($datos['cedula_paciente'] ?? '');
        $this->set_cod_examen($datos['cod_examen'] ?? '');
        $this->set_fecha_e($datos['fecha_e'] ?? '');
        $this->set_hora_e($datos['hora_e'] ?? '');
        $this->set_observacion_examen($datos['observacion_examen'] ?? '');

        switch ($datos['accion']) {
            case 'incluir_registro':
                return $this->incluir_registro();
            case 'modificar_registro':
                return $this->modificar_registro();
            case 'eliminar_registro':
                return $this->eliminar_registro();
            default:
                return array("resultado" => "error", "mensaje" => "Acción no válida");
        }
    }

    // Métodos para tipos de examen
    private function incluir_tipo()
    {
        $r = array(
            'resultado' => 'error',
            'mensaje' => '',
            'cod_examen' => null
        );

        $conexion = $this->conecta();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Validar nombre del examen
            if(empty($this->nombre_examen)) {
                throw new Exception("El nombre del examen es requerido");
            }

            // Verificar si ya existe el tipo de examen
            if($this->existe_tipo($this->nombre_examen)) {
                $r['mensaje'] = 'Ya existe un tipo de examen con ese nombre';
                return $r;
            }

            $conexion->beginTransaction();

            // Llamar al procedimiento almacenado
            $stmt = $conexion->prepare("CALL insertar_tipo_examen(:nombre, :descripcion, @cod_generado)");
            $stmt->execute(array(
                ':nombre' => $this->nombre_examen,
                ':descripcion' => $this->descripcion_examen
            ));

            // Obtener el código generado
            $stmt = $conexion->query("SELECT @cod_generado as codigo");
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $cod_examen = $resultado['codigo'];

            $conexion->commit();

            $r['resultado'] = 'success';
            $r['mensaje'] = 'Tipo de examen registrado exitosamente';
            $r['cod_examen'] = $cod_examen;

        } catch (Exception $e) {
            if($conexion->inTransaction()) {
                $conexion->rollBack();
            }
            $r['mensaje'] = 'Error al registrar: ' . $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }

        return $r;
    }

    private function modificar_tipo()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "UPDATE tipo_de_examen SET
                    nombre_examen = :nombre, 
                    descripcion_examen = :descripcion
                    WHERE cod_examen = :codigo";

            $stmt = $conexion->prepare($sql);
            $stmt->execute(array(
                ':nombre' => $this->nombre_examen,
                ':descripcion' => $this->descripcion_examen,
                ':codigo' => $this->cod_examen
            ));

            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Tipo de examen actualizado exitosamente';
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    private function eliminar_tipo()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $conexion->beginTransaction();

            // Verificar si hay registros asociados
            $sql = "SELECT COUNT(*) as total FROM examen 
                   WHERE cod_examen = :cod_examen";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([':cod_examen' => $this->cod_examen]);
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($fila['total'] == 0) {
                $sql = "DELETE FROM tipo_de_examen WHERE cod_examen = :cod_examen";
                $stmt = $conexion->prepare($sql);
                $stmt->execute([':cod_examen' => $this->cod_examen]);

                $conexion->commit();
                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Tipo de examen eliminado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede eliminar, hay registros asociados a este tipo de examen';
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

    public function consultar_tipos()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "SELECT cod_examen, nombre_examen, descripcion_examen 
                    FROM tipo_de_examen 
                    ORDER BY nombre_examen";

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

    public function obtener_tipos_select()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "SELECT cod_examen, nombre_examen FROM tipo_de_examen ORDER BY nombre_examen";
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

    // Métodos para registros de examen
    private function incluir_registro()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $conexion->beginTransaction();

            $sql = "INSERT INTO examen (fecha_e, hora_e, cedula_paciente, cod_examen, observacion_examen) 
                    VALUES(:fecha, :hora, :cedula, :examen, :observacion)";

            $stmt = $conexion->prepare($sql);
            $stmt->execute(array(
                ':fecha' => $this->fecha_e,
                ':hora' => $this->hora_e,
                ':cedula' => $this->cedula_paciente,
                ':examen' => $this->cod_examen,
                ':observacion' => $this->observacion_examen
            ));

            // Guardar imagen si existe
            if (isset($_FILES['imagenarchivo'])) {
                $nombre_archivo = $this->cedula_paciente . '-' . $this->fecha_e . '-' . $this->cod_examen . '.jpeg';
                $ruta = 'vista/fpdf/usuarios/' . $nombre_archivo;

                if (move_uploaded_file($_FILES['imagenarchivo']['tmp_name'], $ruta)) {
                    $sql = "UPDATE examen SET ruta_imagen = :ruta 
                            WHERE cedula_paciente = :cedula AND fecha_e = :fecha AND cod_examen = :examen";

                    $stmt = $conexion->prepare($sql);
                    $stmt->execute([
                        ':ruta' => $ruta,
                        ':cedula' => $this->cedula_paciente,
                        ':fecha' => $this->fecha_e,
                        ':examen' => $this->cod_examen
                    ]);
                }
            }

            $conexion->commit();
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Registro de examen creado exitosamente';
        } catch (Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    private function modificar_registro()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $conexion->beginTransaction();

            $sql = "UPDATE examen SET
                    fecha_e = :fecha,
                    hora_e = :hora,
                    cod_examen = :examen,
                    observacion_examen = :observacion
                    WHERE cedula_paciente = :cedula AND fecha_e = :fecha_original AND cod_examen = :examen_original";

            $stmt = $conexion->prepare($sql);
            $stmt->execute(array(
                ':fecha' => $this->fecha_e,
                ':hora' => $this->hora_e,
                ':examen' => $this->cod_examen,
                ':observacion' => $this->observacion_examen,
                ':cedula' => $this->cedula_paciente,
                ':fecha_original' => $this->fecha_e,
                ':examen_original' => $this->cod_examen
            ));

            // Actualizar imagen si existe
            if (isset($_FILES['imagenarchivo'])) {
                $nombre_archivo = $this->cedula_paciente . '-' . $this->fecha_e . '-' . $this->cod_examen . '.jpeg';
                $ruta = 'vista/fpdf/usuarios/' . $nombre_archivo;

                if (move_uploaded_file($_FILES['imagenarchivo']['tmp_name'], $ruta)) {
                    $sql = "UPDATE examen SET ruta_imagen = :ruta 
                            WHERE cedula_paciente = :cedula AND fecha_e = :fecha AND cod_examen = :examen";

                    $stmt = $conexion->prepare($sql);
                    $stmt->execute([
                        ':ruta' => $ruta,
                        ':cedula' => $this->cedula_paciente,
                        ':fecha' => $this->fecha_e,
                        ':examen' => $this->cod_examen
                    ]);
                }
            }

            $conexion->commit();
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Registro de examen actualizado exitosamente';
        } catch (Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    private function eliminar_registro()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $conexion->beginTransaction();

            // Primero obtener la ruta de la imagen para eliminarla
            $sql = "SELECT ruta_imagen FROM examen WHERE cedula_paciente = :cedula AND fecha_e = :fecha AND cod_examen = :examen";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':cedula' => $this->cedula_paciente,
                ':fecha' => $this->fecha_e,
                ':examen' => $this->cod_examen
            ]);
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($fila && $fila['ruta_imagen'] && file_exists($fila['ruta_imagen'])) {
                unlink($fila['ruta_imagen']);
            }

            // Luego eliminar el registro
            $sql = "DELETE FROM examen WHERE cedula_paciente = :cedula AND fecha_e = :fecha AND cod_examen = :examen";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':cedula' => $this->cedula_paciente,
                ':fecha' => $this->fecha_e,
                ':examen' => $this->cod_examen
            ]);

            $conexion->commit();
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Registro de examen eliminado exitosamente';
        } catch (Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    public function consultar_registros()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "SELECT e.fecha_e, e.hora_e, e.observacion_examen, e.ruta_imagen,
                    p.cedula_paciente, CONCAT(p.nombre, ' ', p.apellido) as paciente,
                    t.cod_examen, t.nombre_examen
                    FROM examen e
                    JOIN paciente p ON e.cedula_paciente = p.cedula_paciente
                    JOIN tipo_de_examen t ON e.cod_examen = t.cod_examen
                    ORDER BY e.fecha_e DESC, e.hora_e DESC";

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

    public function obtener_pacientes_select()
    {
        $r = array();
        $conexion = $this->conecta();

        try {
            $sql = "SELECT cedula_paciente, CONCAT(nombre, ' ', apellido) as nombre_completo 
                    FROM paciente 
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

    private function existe_tipo($nombre)
    {
        $conexion = $this->conecta();

        try {
            $sql = "SELECT * FROM tipo_de_examen WHERE nombre_examen = :nombre";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([':nombre' => $nombre]);

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