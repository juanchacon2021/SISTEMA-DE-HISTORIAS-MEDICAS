<?php
require_once('modelo/datos.php');

class jornadas extends datos {
    // Atributos
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

    // Métodos CRUD
    public function incluir() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $co->beginTransaction();
            
            // Insertar jornada
            $stmt = $co->prepare("INSERT INTO jornadas_medicas (
                fecha_jornada, ubicacion, descripcion, total_pacientes, 
                pacientes_masculinos, pacientes_femeninos, pacientes_embarazadas, 
                cedula_responsable, created_at
            ) VALUES (
                :fecha, :ubicacion, :descripcion, :total, 
                :masculinos, :femeninos, :embarazadas, 
                :responsable, NOW()
            )");
            
            $stmt->execute(array(
                ':fecha' => $this->fecha_jornada,
                ':ubicacion' => $this->ubicacion,
                ':descripcion' => $this->descripcion,
                ':total' => $this->total_pacientes,
                ':masculinos' => $this->pacientes_masculinos,
                ':femeninos' => $this->pacientes_femeninos,
                ':embarazadas' => $this->pacientes_embarazadas,
                ':responsable' => $this->cedula_responsable
            ));
            
            $cod_jornada = $co->lastInsertId();
            
            // Insertar participantes
            foreach($this->participantes as $participante) {
                $stmt = $co->prepare("INSERT INTO participantes_jornadas (
                    cod_jornada, cedula_personal
                ) VALUES (
                    :jornada, :personal
                )");
                
                $stmt->execute(array(
                    ':jornada' => $cod_jornada,
                    ':personal' => $participante
                ));
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
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $co->beginTransaction();
            
            // Actualizar jornada
            $stmt = $co->prepare("UPDATE jornadas_medicas SET
                fecha_jornada = :fecha,
                ubicacion = :ubicacion,
                descripcion = :descripcion,
                total_pacientes = :total,
                pacientes_masculinos = :masculinos,
                pacientes_femeninos = :femeninos,
                pacientes_embarazadas = :embarazadas,
                cedula_responsable = :responsable
                WHERE cod_jornada = :codigo");
            
            $stmt->execute(array(
                ':fecha' => $this->fecha_jornada,
                ':ubicacion' => $this->ubicacion,
                ':descripcion' => $this->descripcion,
                ':total' => $this->total_pacientes,
                ':masculinos' => $this->pacientes_masculinos,
                ':femeninos' => $this->pacientes_femeninos,
                ':embarazadas' => $this->pacientes_embarazadas,
                ':responsable' => $this->cedula_responsable,
                ':codigo' => $this->cod_jornada
            ));
            
            // Eliminar participantes existentes
            $co->query("DELETE FROM participantes_jornadas WHERE cod_jornada = '$this->cod_jornada'");
            
            // Insertar nuevos participantes
            foreach($this->participantes as $participante) {
                $stmt = $co->prepare("INSERT INTO participantes_jornadas (
                    cod_jornada, cedula_personal
                ) VALUES (
                    :jornada, :personal
                )");
                
                $stmt->execute(array(
                    ':jornada' => $this->cod_jornada,
                    ':personal' => $participante
                ));
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
            $resultado = $co->query("SELECT j.*, CONCAT(p.nombre, ' ', p.apellido) as responsable
                                   FROM jornadas_medicas j
                                   JOIN personal p ON j.cedula_responsable = p.cedula_personal
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
                // Participantes
                $stmt = $co->prepare("SELECT pj.cedula_personal, CONCAT(p.nombre, ' ', p.apellido) as nombre_completo
                                    FROM participantes_jornadas pj
                                    JOIN personal p ON pj.cedula_personal = p.cedula_personal
                                    WHERE pj.cod_jornada = :codigo");
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