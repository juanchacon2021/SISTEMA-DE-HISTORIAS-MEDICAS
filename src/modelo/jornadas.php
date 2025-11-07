<?php

namespace Shm\Shm\modelo;

use Shm\Shm\modelo\datos;
use PDO;
use Exception;

class jornadas extends datos
{
    private $cod_jornada;
    private $fecha_jornada;
    private $ubicacion;
    private $descripcion;
    private $total_pacientes;
    private $pacientes_masculinos;
    private $pacientes_femeninos;
    private $pacientes_embarazadas;
    private $cedula_responsable;
    private $participantes = array();

    // Setters
    public function set_cod_jornada($valor) { $this->cod_jornada = $valor; }
    public function set_fecha_jornada($valor) { $this->fecha_jornada = $valor; }
    public function set_ubicacion($valor) { $this->ubicacion = $valor; }
    public function set_descripcion($valor) { $this->descripcion = $valor; }
    public function set_total_pacientes($valor) { $this->total_pacientes = $valor; }
    public function set_pacientes_masculinos($valor) { $this->pacientes_masculinos = $valor; }
    public function set_pacientes_femeninos($valor) { $this->pacientes_femeninos = $valor; }
    public function set_pacientes_embarazadas($valor) { $this->pacientes_embarazadas = $valor; }
    public function set_cedula_responsable($valor) { $this->cedula_responsable = $valor; }
    public function set_participantes($valor) { $this->participantes = $valor; }

    private function validarPacientes() {
        $masculinos = $this->pacientes_masculinos;
        $femeninos = $this->pacientes_femeninos;
        $embarazadas = $this->pacientes_embarazadas;
        
        $this->total_pacientes = $masculinos + $femeninos;
        
        if($embarazadas > $femeninos) {
            throw new Exception("El número de embarazadas ($embarazadas) no puede ser mayor al número de pacientes femeninos ($femeninos)");
        }
        
        return true;
    }

    public function gestionar_jornada($datos) {
    try {
        $this->set_cod_jornada($datos['cod_jornada'] ?? '');
        $this->set_fecha_jornada($datos['fecha_jornada'] ?? '');
        $this->set_ubicacion($datos['ubicacion'] ?? '');
        $this->set_descripcion($datos['descripcion'] ?? '');
        $this->set_pacientes_masculinos($datos['pacientes_masculinos'] ?? 0);
        $this->set_pacientes_femeninos($datos['pacientes_femeninos'] ?? 0);
        $this->set_pacientes_embarazadas($datos['pacientes_embarazadas'] ?? 0);
        $this->set_cedula_responsable($datos['cedula_responsable'] ?? '');
        $this->set_participantes($datos['participantes'] ?? array());

        if (isset($datos['accion']) && $datos['accion'] === 'eliminar') {
            return $this->eliminar();
        }

        $this->validarPacientes();

        // Validación antes de ejecutar acción
        $val = $this->validar_campos([
            'fecha_jornada' => $this->fecha_jornada,
            'ubicacion' => $this->ubicacion,
            'descripcion' => $this->descripcion,
            'pacientes_masculinos' => $this->pacientes_masculinos,
            'pacientes_femeninos' => $this->pacientes_femeninos,
            'pacientes_embarazadas' => $this->pacientes_embarazadas,
            'cedula_responsable' => $this->cedula_responsable,
            'participantes' => $this->participantes
        ]);
        if (!is_array($val) || !isset($val['codigo'])) {
            return array("resultado" => "error", "mensaje" => "Error al validar campos");
        }
        if ($val['codigo'] !== 0) {
            return array("resultado" => "error", "mensaje" => $val['mensaje']);
        }

        switch ($datos['accion']) {
            case 'incluir':
                return $this->incluir();
            case 'modificar':
                return $this->modificar();
            default:
                return array("resultado" => "error", "mensaje" => "Acción no válida");
        }
    } catch (\Exception $e) {
        return array("resultado" => "error", "mensaje" => $e->getMessage());
    }
}


    private function incluir() {
        // validar_campos ya fue ejecutado desde gestionar_jornada
        $conexion = $this->conecta();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $conexion->beginTransaction();
            
            // Usar procedure para insertar la jornada
            $stmt = $conexion->prepare("CALL insertar_jornada_medica(
                :fecha, :ubicacion, :descripcion, :total, 
                :masculinos, :femeninos, :embarazadas, 
                @cod_jornada_generado)");
            
            $stmt->execute(array(
                ':fecha' => $this->fecha_jornada,
                ':ubicacion' => $this->ubicacion,
                ':descripcion' => $this->descripcion,
                ':total' => $this->total_pacientes,
                ':masculinos' => $this->pacientes_masculinos,
                ':femeninos' => $this->pacientes_femeninos,
                ':embarazadas' => $this->pacientes_embarazadas
            ));
            
            // Obtener el código generado
            $stmt = $conexion->query("SELECT @cod_jornada_generado as codigo");
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $cod_jornada = $resultado['codigo'];
            
            if(empty($cod_jornada)) {
                throw new Exception("No se pudo generar el código de la jornada");
            }

            // Insertar responsable usando procedure (si tienes uno, si no, deja el INSERT)
            $stmt = $conexion->prepare("CALL insertar_participante_jornada(:jornada, :personal, 'responsable')");
            $stmt->execute(array(
                ':jornada' => $cod_jornada,
                ':personal' => $this->cedula_responsable
            ));
            
            // Insertar participantes (excluyendo al responsable si está en la lista)
            foreach($this->participantes as $participante) {
                if($participante != $this->cedula_responsable) {
                    $stmt = $conexion->prepare("CALL insertar_participante_jornada(:jornada, :personal, 'participante')");
                    $stmt->execute(array(
                        ':jornada' => $cod_jornada,
                        ':personal' => $participante
                    ));
                }
            }
            
            $conexion->commit();
            
            $r['resultado'] = 'success';
            $r['mensaje'] = 'Jornada registrada exitosamente';
            $r['cod_jornada'] = $cod_jornada;
        } catch(Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
            $r['cod_jornada'] = null;
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }
    
    private function modificar() {
        $this->validarPacientes();
        $conexion = $this->conecta();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $conexion->beginTransaction();
            
            $stmt = $conexion->prepare("UPDATE jornadas_medicas SET
                fecha_jornada = :fecha,
                ubicacion = :ubicacion,
                descripcion = :descripcion,
                total_pacientes = :total,
                pacientes_masculinos = :masculinos,
                pacientes_femeninos = :femeninos,
                pacientes_embarazadas = :embarazadas
                WHERE cod_jornada = :codigo");
            
            $stmt->execute(array(
                ':fecha' => $this->fecha_jornada,
                ':ubicacion' => $this->ubicacion,
                ':descripcion' => $this->descripcion,
                ':total' => $this->total_pacientes,
                ':masculinos' => $this->pacientes_masculinos,
                ':femeninos' => $this->pacientes_femeninos,
                ':embarazadas' => $this->pacientes_embarazadas,
                ':codigo' => $this->cod_jornada
            ));
            
            // Eliminar participantes existentes
            $stmt = $conexion->prepare("DELETE FROM participantes_jornadas WHERE cod_jornada = :codigo");
            $stmt->execute([':codigo' => $this->cod_jornada]);
            
            // Insertar responsable
            $stmt = $conexion->prepare("INSERT INTO participantes_jornadas (
                cod_jornada, cedula_personal, tipo_participante
            ) VALUES (
                :jornada, :personal, 'responsable'
            )");
            $stmt->execute(array(
                ':jornada' => $this->cod_jornada,
                ':personal' => $this->cedula_responsable
            ));
            
            // Insertar participantes
            foreach($this->participantes as $participante) {
                if($participante != $this->cedula_responsable) {
                    $stmt = $conexion->prepare("INSERT INTO participantes_jornadas (
                        cod_jornada, cedula_personal, tipo_participante
                    ) VALUES (
                        :jornada, :personal, 'participante'
                    )");
                    $stmt->execute(array(
                        ':jornada' => $this->cod_jornada,
                        ':personal' => $participante
                    ));
                }
            }
            
            $conexion->commit();
            
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Jornada actualizada exitosamente';
        } catch(Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }
    
    private function eliminar() {
        $conexion = $this->conecta();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $conexion->beginTransaction();
            
            // Eliminar participantes
            $stmt = $conexion->prepare("DELETE FROM participantes_jornadas WHERE cod_jornada = :codigo");
            $stmt->execute([':codigo' => $this->cod_jornada]);
            
            // Eliminar jornada
            $stmt = $conexion->prepare("DELETE FROM jornadas_medicas WHERE cod_jornada = :codigo");
            $stmt->execute([':codigo' => $this->cod_jornada]);
            
            $conexion->commit();
            
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Jornada eliminada exitosamente';
        } catch(Exception $e) {
            $conexion->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }
    
    public function consultar() {
        $conexion = $this->conecta();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $sql = "SELECT 
                j.*, 
                (SELECT CONCAT(p.nombre, ' ', p.apellido) 
                 FROM participantes_jornadas pj 
                 JOIN personal p ON pj.cedula_personal = p.cedula_personal 
                 WHERE pj.cod_jornada = j.cod_jornada AND pj.tipo_participante = 'responsable' LIMIT 1) as responsable
               FROM jornadas_medicas j
               ORDER BY j.fecha_jornada DESC";
            
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            
            $r['resultado'] = 'consultar';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }
    
    public function consultar_jornada($codigo) {
        $conexion = $this->conecta();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
       
        try {
            // Datos de la jornada
            $stmt = $conexion->prepare("SELECT * FROM jornadas_medicas WHERE cod_jornada = :codigo");
            $stmt->execute([':codigo' => $codigo]);
            $jornada = $stmt->fetch(PDO::FETCH_ASSOC);
           
            if($jornada) {
                // Obtener responsable
                $stmt = $conexion->prepare("SELECT pj.cedula_personal 
                                          FROM participantes_jornadas pj 
                                          WHERE pj.cod_jornada = :codigo AND pj.tipo_participante = 'responsable' LIMIT 1");
                $stmt->execute([':codigo' => $codigo]);
                $responsable = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $jornada['cedula_responsable'] = $responsable ? $responsable['cedula_personal'] : null;
                
                // Participantes (excluyendo al responsable)
                $stmt = $conexion->prepare("SELECT pj.cedula_personal, CONCAT(p.nombre, ' ', p.apellido) as nombre_completo
                                          FROM participantes_jornadas pj
                                          JOIN personal p ON pj.cedula_personal = p.cedula_personal
                                          WHERE pj.cod_jornada = :codigo AND pj.tipo_participante = 'participante'");
                $stmt->execute([':codigo' => $codigo]);
                $participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
               
                $r['resultado'] = 'consultar';
                $r['jornada'] = $jornada;
                $r['participantes'] = $participantes;
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Jornada no encontrada';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }
    
    public function obtener_personal() {
        $conexion = $this->conecta();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $sql = "SELECT cedula_personal, CONCAT(nombre, ' ', apellido) as nombre_completo
                   FROM personal ORDER BY apellido, nombre";
            
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            
            $r['resultado'] = 'consultar';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }
    
    public function obtener_responsables() {
        $conexion = $this->conecta();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $sql = "SELECT cedula_personal, CONCAT(nombre, ' ', apellido) as nombre_completo
                   FROM personal WHERE cargo = 'Doctor' ORDER BY apellido, nombre";
            
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            
            $r['resultado'] = 'consultar';
            $r['datos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        } finally {
            $this->cerrar_conexion($conexion);
        }
        return $r;
    }

    // Validaciones similares a consultasm::validar_campos
    private function validar_campos($datos) {
        $r = array('codigo' => 0, 'mensaje' => 'Campos válidos');

        // Fecha YYYY-MM-DD
        if (!isset($datos['fecha_jornada']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $datos['fecha_jornada']) || !\DateTime::createFromFormat('Y-m-d', $datos['fecha_jornada'])) {
            $r['codigo'] = 1;
            $r['mensaje'] = 'Formato de fecha inválido (YYYY-MM-DD)';
            return $r;
        }

        // Ubicación: 3-100 caracteres (letras, números, espacios)
        if (!isset($datos['ubicacion']) || !preg_match('/^[A-Za-z0-9\s\-\.,]{3,100}$/u', $datos['ubicacion'])) {
            $r['codigo'] = 2;
            $r['mensaje'] = 'Ubicación inválida (3-100 caracteres)';
            return $r;
        }

        // Descripción opcional, hasta 1000 chars
        if (isset($datos['descripcion']) && mb_strlen($datos['descripcion']) > 1000) {
            $r['codigo'] = 3;
            $r['mensaje'] = 'Descripción demasiado larga';
            return $r;
        }

        // Conteos numéricos no negativos
        foreach (['pacientes_masculinos','pacientes_femeninos','pacientes_embarazadas'] as $campo) {
            if (!isset($datos[$campo]) || !is_numeric($datos[$campo]) || $datos[$campo] < 0) {
                $r['codigo'] = 4;
                $r['mensaje'] = "El campo {$campo} debe ser un número entero no negativo";
                return $r;
            }
        }

        // coherencia: embarazadas <= femeninos
        if ($datos['pacientes_embarazadas'] > $datos['pacientes_femeninos']) {
            $r['codigo'] = 5;
            $r['mensaje'] = 'El número de embarazadas no puede ser mayor al número de pacientes femeninos';
            return $r;
        }

        // Cedula responsable formato 7-8 dígitos
        if (!isset($datos['cedula_responsable']) || !preg_match('/^[0-9]{7,8}$/', $datos['cedula_responsable'])) {
            $r['codigo'] = 6;
            $r['mensaje'] = 'Formato de cédula del responsable inválido';
            return $r;
        }

        // Verificar existencia del responsable en la BD
        try {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $co->prepare("SELECT 1 FROM personal WHERE cedula_personal = ? LIMIT 1");
            $stmt->execute([$datos['cedula_responsable']]);
            $existeResp = ($stmt->fetchColumn() !== false);
            if (!$existeResp) {
                $r['codigo'] = 7;
                $r['mensaje'] = 'La cédula del responsable no existe en la base de datos';
                return $r;
            }
        } catch (Exception $e) {
            $r['codigo'] = 8;
            $r['mensaje'] = 'Error al validar responsable: ' . $e->getMessage();
            return $r;
        }

        // Participantes: si vienen, deben ser array de cédulas válidas y existentes
        if (isset($datos['participantes']) && is_array($datos['participantes'])) {
            try {
                $co = $this->conecta();
                $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $co->prepare("SELECT 1 FROM personal WHERE cedula_personal = ? LIMIT 1");
                foreach ($datos['participantes'] as $p) {
                    if (!preg_match('/^[0-9]{7,8}$/', $p)) {
                        $r['codigo'] = 9;
                        $r['mensaje'] = "Formato de cédula de participante inválido: {$p}";
                        return $r;
                    }
                    // comprobar existencia
                    $stmt->execute([$p]);
                    if ($stmt->fetchColumn() === false) {
                        $r['codigo'] = 10;
                        $r['mensaje'] = "La cédula de participante no existe: {$p}";
                        return $r;
                    }
                }
            } catch (Exception $e) {
                $r['codigo'] = 11;
                $r['mensaje'] = 'Error al validar participantes: ' . $e->getMessage();
                return $r;
            }
        }

        return $r;
    }

    private function cerrar_conexion(&$conexion) {
        if ($conexion) {
            $conexion = null;
        }
    }
}
