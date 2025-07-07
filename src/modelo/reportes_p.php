<?php

namespace Shm\Shm\modelo;
use Shm\Shm\modelo\datos;
use PDO;
use Exception;


class reportes extends datos{

    // filepath: c:\xampp\htdocs\SHM\src\modelo\reportes_p.php
    function buscar_emergencias($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();  

    
        try {
            // Consulta preparada con parámetros posicionales
            $stmt = $co->prepare("SELECT *
                FROM emergencia
                WHERE 
                    motingreso LIKE ? OR
                    diagnostico_e LIKE ? OR
                    tratamientos LIKE ? OR
                    procedimiento LIKE ? OR
                    cedula_paciente LIKE ? OR
                    cedula_personal LIKE ? OR
                    horaingreso LIKE ? OR
                    fechaingreso LIKE ?
            ");
            $texto = '%' . $datos['texto'] . '%';
            // Ejecutar la consulta pasando el mismo texto para todos los campos
            $stmt->execute([
                $texto, $texto, $texto, $texto, $texto, $texto, $texto, $texto
            ]);
            // Obtener resultados
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);		
            if ($resultados) {
                $r['resultado'] = 'buscar_emergencias';
                $r['datos'] = $resultados; 
            } else {
                $r['resultado'] = 'buscar_emergencias';
                $r['datos'] = array(); 
            }
            // Cerrar el cursor
            $stmt->closeCursor();
    
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage(); 
        }
    
        return $r;
    }

function buscar_emergencias_date($datos) {
    $co = $this->conecta();
    $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $r = array();  

    try {
        // Construir SQL con filtro por mes y año
        $sql = "SELECT *
                FROM emergencia
                WHERE (
                    motingreso LIKE ? OR
                    diagnostico_e LIKE ? OR
                    tratamientos LIKE ? OR
                    procedimiento LIKE ? OR
                    cedula_paciente LIKE ? OR
                    cedula_personal LIKE ? OR
                    horaingreso LIKE ? OR
                    fechaingreso LIKE ?
                )";
        // Si se pasan mes y año, agrega el filtro
        if (!empty($datos['mes']) && !empty($datos['ano'])) {
            $sql .= " AND MONTH(fechaingreso) = ? AND YEAR(fechaingreso) = ?";
        }

        $stmt = $co->prepare($sql);

        $texto = '%' . $datos['texto'] . '%';
        $params = [$texto, $texto, $texto, $texto, $texto, $texto, $texto, $texto];

        if (!empty($datos['mes']) && !empty($datos['ano'])) {
            $params[] = $datos['mes'];
            $params[] = $datos['ano'];
        }

        $stmt->execute($params);

        // Obtener resultados
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);		
        $r['resultado'] = 'buscar_emergencias';
        $r['datos'] = $resultados ?: array();

        $stmt->closeCursor();

    } catch (Exception $e) {
        $r['resultado'] = 'error';
        $r['mensaje'] = $e->getMessage(); 
    }

    return $r;
}









}

/* class reportes extends datos {
   
    public function buscar($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        $modulo = $datos['modulo'];
        $texto = $datos['texto'];
        $mes = $datos['mes'];
        $ano = $datos['ano'];
        
        try {
            switch($modulo) {
                case 'pacientes':
                    $sql = $this->sqlPacientes();
                    break;
                    
                case 'personal':
                    $sql = $this->sqlPersonal();
                    break;
                    
                case 'emergencias':
                    $sql = $this->sqlEmergencias($mes, $ano);
                    break;
                    
                case 'consultas':
                    $sql = $this->sqlConsultas($mes, $ano);
                    break;
                    
                case 'inventario':
                    $sql = $this->sqlInventario($mes, $ano);
                    break;
                    
                case 'pasantias':
                    $sql = $this->sqlPasantias();
                    break;
                    
                case 'jornadas':
                    $sql = $this->sqlJornadas($mes, $ano);
                    break;
                    
                case 'examenes':
                    $sql = $this->sqlExamenes($mes, $ano);
                    break;
                    
                case 'patologias':
                    $sql = $this->sqlPatologias();
                    break;
                    
                default:
                    $r['resultado'] = 'error';
                    $r['mensaje'] = 'Módulo no válido';
                    return $r;
            }
            
            $stmt = $co->prepare($sql);
            $textoParam = "%".$texto."%";
            $stmt->bindParam(':texto', $textoParam);
            
            if($mes && $ano && in_array($modulo, ['emergencias', 'consultas', 'inventario', 'jornadas', 'examenes'])) {
                $stmt->bindParam(':mes', $mes, PDO::PARAM_INT);
                $stmt->bindParam(':ano', $ano, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if($resultados) {
                $r['resultado'] = 'buscar';
                $r['datos'] = $resultados;
                $r['modulo'] = $modulo;
            } else {
                $r['resultado'] = 'buscar';
                $r['datos'] = array();
                $r['mensaje'] = 'No se encontraron resultados';
                $r['modulo'] = $modulo;
            }
            
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Error en la consulta: ' . $e->getMessage();
        }
        
        return $r;
    }
    
   
 
    private function sqlPacientes() {
        return "SELECT 
                    cedula_paciente, nombre, apellido, 
                    fecha_nac, edad, telefono, direccion
                FROM paciente 
                WHERE (cedula_paciente LIKE :texto OR 
                     nombre LIKE :texto OR 
                     apellido LIKE :texto OR
                     telefono LIKE :texto OR
                     direccion LIKE :texto)
                ORDER BY apellido, nombre";
    }
    
  

    private function sqlPersonal() {
        return "SELECT 
                    cedula_personal, nombre, apellido, 
                    cargo, correo
                FROM personal 
                WHERE (cedula_personal LIKE :texto OR 
                     nombre LIKE :texto OR 
                     apellido LIKE :texto OR
                     cargo LIKE :texto OR
                     correo LIKE :texto)
                ORDER BY apellido, nombre";
    }
    

    private function sqlEmergencias($mes, $ano) {
        $sql = "SELECT 
                    e.fechaingreso, e.horaingreso, 
                    e.motingreso, e.diagnostico_e, e.tratamientos, e.procedimiento,
                    p.nombre as nombre_paciente, p.apellido as apellido_paciente,
                    per.nombre as nombre_personal, per.apellido as apellido_personal
                FROM emergencia e
                JOIN paciente p ON e.cedula_paciente = p.cedula_paciente
                JOIN personal per ON e.cedula_personal = per.cedula_personal
                WHERE (e.diagnostico_e LIKE :texto OR 
                      e.tratamientos LIKE :texto OR 
                      e.procedimiento LIKE :texto OR
                      e.motingreso LIKE :texto)";
        
        if($mes && $ano) {
            $sql .= " AND MONTH(e.fechaingreso) = :mes AND YEAR(e.fechaingreso) = :ano";
        }
        
        $sql .= " ORDER BY e.fechaingreso DESC, e.horaingreso DESC";
        
        return $sql;
    }
    
    
    private function sqlConsultas($mes, $ano) {
        $sql = "SELECT 
                    c.cod_consulta, c.fechaconsulta, c.Horaconsulta,
                    c.consulta, c.diagnostico, c.tratamientos,
                    p.nombre as nombre_paciente, p.apellido as apellido_paciente,
                    per.nombre as nombre_personal, per.apellido as apellido_personal
                FROM consulta c
                JOIN paciente p ON c.cedula_paciente = p.cedula_paciente
                JOIN personal per ON c.cedula_personal = per.cedula_personal
                WHERE (c.diagnostico LIKE :texto OR 
                      c.tratamientos LIKE :texto OR
                      c.consulta LIKE :texto)";
        
        if($mes && $ano) {
            $sql .= " AND MONTH(c.fechaconsulta) = :mes AND YEAR(c.fechaconsulta) = :ano";
        }
        
        $sql .= " ORDER BY c.fechaconsulta DESC, c.Horaconsulta DESC";
        
        return $sql;
    }
    

    private function sqlInventario($mes, $ano) {
        $sql = "SELECT 
                    sm.fecha, sm.hora, 
                    m.nombre as medicamento, 
                    i.cantidad as cantidad_salida, 
                    l.fecha_vencimiento, l.proveedor,
                    p.nombre as nombre_personal, p.apellido as apellido_personal
                FROM salida_medicamento sm
                JOIN insumos i ON sm.cod_salida = i.cod_movimiento
                JOIN lotes l ON i.cod_lote = l.cod_lote
                JOIN medicamentos m ON l.cod_medicamento = m.cod_medicamento
                JOIN personal p ON sm.cedula_personal = p.cedula_personal
                WHERE m.nombre LIKE :texto";
        
        if($mes && $ano) {
            $sql .= " AND MONTH(sm.fecha) = :mes AND YEAR(sm.fecha) = :ano";
        }
        
        $sql .= " ORDER BY sm.fecha DESC, sm.hora DESC";
        
        return $sql;
    }
    

    private function sqlPasantias() {
        return "SELECT 
                    a.nombre_area, a.descripcion, 
                    p.nombre as nombre_responsable, p.apellido as apellido_responsable
                FROM areas_pasantias a
                JOIN personal p ON a.cedula_responsable = p.cedula_personal
                WHERE (a.nombre_area LIKE :texto OR 
                      a.descripcion LIKE :texto)
                ORDER BY a.nombre_area";
    }
    
   

    private function sqlJornadas($mes, $ano) {
        $sql = "SELECT 
                    cod_jornada, fecha_jornada, ubicacion,
                    descripcion, total_pacientes,
                    pacientes_masculinos, pacientes_femeninos, pacientes_embarazadas
                FROM jornadas_medicas 
                WHERE (ubicacion LIKE :texto OR 
                      descripcion LIKE :texto)";
        
        if($mes && $ano) {
            $sql .= " AND MONTH(fecha_jornada) = :mes AND YEAR(fecha_jornada) = :ano";
        }
        
        $sql .= " ORDER BY fecha_jornada DESC";
        
        return $sql;
    }

    private function sqlExamenes($mes, $ano) {
        $sql = "SELECT 
                    e.fecha_e, e.hora_e, e.observacion_examen, e.ruta_imagen,
                    p.nombre as nombre_paciente, p.apellido as apellido_paciente,
                    te.nombre_examen, te.descripcion_examen
                FROM examen e
                JOIN paciente p ON e.cedula_paciente = p.cedula_paciente
                JOIN tipo_de_examen te ON e.cod_examen = te.cod_examen
                WHERE (e.observacion_examen LIKE :texto OR
                      te.nombre_examen LIKE :texto)";
        
        if($mes && $ano) {
            $sql .= " AND MONTH(e.fecha_e) = :mes AND YEAR(e.fecha_e) = :ano";
        }
        
        $sql .= " ORDER BY e.fecha_e DESC, e.hora_e DESC";
        
        return $sql;
    }
    

    private function sqlPatologias() {
        return "SELECT 
                    pa.*, 
                    p.nombre as nombre_paciente, p.apellido as apellido_paciente,
                    pat.nombre_patologia
                FROM padece pa
                JOIN paciente p ON pa.cedula_paciente = p.cedula_paciente
                JOIN patologia pat ON pa.cod_patologia = pat.cod_patologia
                WHERE (pat.nombre_patologia LIKE :texto OR
                      pa.tratamiento LIKE :texto OR
                      pa.administracion_t LIKE :texto)
                ORDER BY p.apellido, p.nombre";
    }
} */