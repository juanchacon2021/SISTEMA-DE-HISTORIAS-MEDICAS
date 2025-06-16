<?php
require_once('modelo/datos.php');

class jornadas extends datos {
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
        
        // Calcular el total automáticamente
        $this->total_pacientes = $masculinos + $femeninos;
        
        if($embarazadas > $femeninos) {
            throw new Exception("El número de embarazadas ($embarazadas) no puede ser mayor al número de pacientes femeninos ($femeninos)");
        }
        
        return true;
    }

    public function incluir() {
        $this->validarPacientes();
        
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $co->beginTransaction();
            
            $stmt = $co->prepare("INSERT INTO jornadas_medicas (
                fecha_jornada, ubicacion, descripcion, total_pacientes, 
                pacientes_masculinos, pacientes_femeninos, pacientes_embarazadas, 
                created_at
            ) VALUES (
                :fecha, :ubicacion, :descripcion, :total, 
                :masculinos, :femeninos, :embarazadas, 
                NOW()
            )");
            
            $stmt->execute(array(
                ':fecha' => $this->fecha_jornada,
                ':ubicacion' => $this->ubicacion,
                ':descripcion' => $this->descripcion,
                ':total' => $this->total_pacientes,
                ':masculinos' => $this->pacientes_masculinos,
                ':femeninos' => $this->pacientes_femeninos,
                ':embarazadas' => $this->pacientes_embarazadas
            ));
            
            $cod_jornada = $co->lastInsertId();
            
            // Insertar responsable como participante
            $stmt = $co->prepare("INSERT INTO participantes_jornadas (
                cod_jornada, cedula_personal, tipo_participante
            ) VALUES (
                :jornada, :personal, 'responsable'
            )");
            $stmt->execute(array(
                ':jornada' => $cod_jornada,
                ':personal' => $this->cedula_responsable
            ));
            
            // Insertar otros participantes
            foreach($this->participantes as $participante) {
                if($participante != $this->cedula_responsable) { // Evitar duplicar al responsable
                    $stmt = $co->prepare("INSERT INTO participantes_jornadas (
                        cod_jornada, cedula_personal, tipo_participante
                    ) VALUES (
                        :jornada, :personal, 'participante'
                    )");
                    
                    $stmt->execute(array(
                        ':jornada' => $cod_jornada,
                        ':personal' => $participante
                    ));
                }
            }
            
            $co->commit();
            
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Jornada registrada exitosamente';
        } catch(Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function modificar() {
        $this->validarPacientes();
        
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $co->beginTransaction();
            
            $stmt = $co->prepare("UPDATE jornadas_medicas SET
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
            $co->query("DELETE FROM participantes_jornadas WHERE cod_jornada = '$this->cod_jornada'");
            
            // Insertar responsable como participante
            $stmt = $co->prepare("INSERT INTO participantes_jornadas (
                cod_jornada, cedula_personal, tipo_participante
            ) VALUES (
                :jornada, :personal, 'responsable'
            )");
            $stmt->execute(array(
                ':jornada' => $this->cod_jornada,
                ':personal' => $this->cedula_responsable
            ));
            
            // Insertar otros participantes
            foreach($this->participantes as $participante) {
                if($participante != $this->cedula_responsable) { // Evitar duplicar al responsable
                    $stmt = $co->prepare("INSERT INTO participantes_jornadas (
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
            
            $co->commit();
            
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Jornada actualizada exitosamente';
        } catch(Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function eliminar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $co->beginTransaction();
            
            // Eliminar participantes
            $co->query("DELETE FROM participantes_jornadas WHERE cod_jornada = '$this->cod_jornada'");
            
            // Eliminar jornada
            $co->query("DELETE FROM jornadas_medicas WHERE cod_jornada = '$this->cod_jornada'");
            
            $co->commit();
            
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Jornada eliminada exitosamente';
        } catch(Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function consultar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("SELECT 
                j.*, 
                (SELECT CONCAT(p.nombre, ' ', p.apellido) 
                 FROM participantes_jornadas pj 
                 JOIN personal p ON pj.cedula_personal = p.cedula_personal 
                 WHERE pj.cod_jornada = j.cod_jornada AND pj.tipo_participante = 'responsable' LIMIT 1) as responsable
               FROM jornadas_medicas j
               ORDER BY j.fecha_jornada DESC");
            
            $r['resultado'] = 'consultar';
            $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function consultar_jornada($codigo) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
       
        try {
            // Datos de la jornada
            $stmt = $co->prepare("SELECT * FROM jornadas_medicas WHERE cod_jornada = :codigo");
            $stmt->execute(array(':codigo' => $codigo));
            $jornada = $stmt->fetch(PDO::FETCH_ASSOC);
           
            if($jornada) {
                // Obtener responsable
                $stmt = $co->prepare("SELECT pj.cedula_personal 
                                    FROM participantes_jornadas pj 
                                    WHERE pj.cod_jornada = :codigo AND pj.tipo_participante = 'responsable' LIMIT 1");
                $stmt->execute(array(':codigo' => $codigo));
                $responsable = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $jornada['cedula_responsable'] = $responsable ? $responsable['cedula_personal'] : null;
                
                // Participantes (excluyendo al responsable)
                $stmt = $co->prepare("SELECT pj.cedula_personal, CONCAT(p.nombre, ' ', p.apellido) as nombre_completo
                                    FROM participantes_jornadas pj
                                    JOIN personal p ON pj.cedula_personal = p.cedula_personal
                                    WHERE pj.cod_jornada = :codigo AND pj.tipo_participante = 'participante'");
                $stmt->execute(array(':codigo' => $codigo));
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
        }
        return $r;
    }
    
    public function obtener_personal() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("SELECT cedula_personal, CONCAT(nombre, ' ', apellido) as nombre_completo
                                   FROM personal ORDER BY apellido, nombre");
            
            $r['resultado'] = 'consultar';
            $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function obtener_responsables() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("SELECT cedula_personal, CONCAT(nombre, ' ', apellido) as nombre_completo
                                   FROM personal WHERE cargo = 'Doctor' ORDER BY apellido, nombre");
            
            $r['resultado'] = 'consultar';
            $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
}
?>