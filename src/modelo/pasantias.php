<?php
namespace Shm\Shm\modelo;
use Shm\Shm\modelo\datos;
use PDO;
use Exception;

class pasantias extends datos {
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
    public function set_cedula_estudiante($valor) { $this->cedula_estudiante = $valor; }
    public function set_nombre($valor) { $this->nombre = $valor; }
    public function set_apellido($valor) { $this->apellido = $valor; }
    public function set_institucion($valor) { $this->institucion = $valor; }
    public function set_telefono($valor) { $this->telefono = $valor; }
    public function set_cod_area($valor) { $this->cod_area = $valor; }
    public function set_fecha_inicio($valor) { $this->fecha_inicio = $valor; }
    public function set_fecha_fin($valor) { $this->fecha_fin = $valor; }
    public function set_activo($valor) { $this->activo = $valor; }
    public function set_nombre_area($valor) { $this->nombre_area = $valor; }
    public function set_descripcion($valor) { $this->descripcion = $valor; }
    public function set_cedula_personal($valor) { $this->cedula_personal = $valor; }

    // Métodos para estudiantes
public function incluir_estudiante() {
    $co = $this->conecta();
    $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $r = array();
    
    try {
        $co->beginTransaction();
        
        if(!$this->existe_estudiante($this->cedula_estudiante)) {
            // Insertar estudiante
            $stmt = $co->prepare("INSERT INTO estudiantes_pasantia (cedula_estudiante, nombre, apellido, institucion, telefono) 
                                 VALUES(:cedula, :nombre, :apellido, :institucion, :telefono)");
            $stmt->execute(array(
                ':cedula' => $this->cedula_estudiante,
                ':nombre' => $this->nombre,
                ':apellido' => $this->apellido,
                ':institucion' => $this->institucion,
                ':telefono' => $this->telefono
            ));
            
            // Si se proporcionó un área, crear registro de asistencia
            if(!empty($this->cod_area)) {
                $fechaActual = date('Y-m-d');
                $stmt = $co->prepare("INSERT INTO asistencia (cod_area, cedula_estudiante, fecha_inicio, activo) 
                                     VALUES(:area, :cedula, :inicio, 1)");
                $stmt->execute(array(
                    ':area' => $this->cod_area,
                    ':cedula' => $this->cedula_estudiante,
                    ':inicio' => $fechaActual
                ));
            }
            
            $co->commit();
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Estudiante registrado exitosamente';
        } else {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'El estudiante ya está registrado';
        }
    } catch(Exception $e) {
        $co->rollBack();
        $r['resultado'] = 'error';
        $r['mensaje'] = $e->getMessage();
    }
    return $r;
}    
    public function modificar_estudiante() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $stmt = $co->prepare("UPDATE estudiantes_pasantia SET 
                nombre = :nombre, 
                apellido = :apellido, 
                institucion = :institucion,
                telefono = :telefono
                WHERE cedula_estudiante = :cedula");
            
            $stmt->execute(array(
                ':nombre' => $this->nombre,
                ':apellido' => $this->apellido,
                ':institucion' => $this->institucion,
                ':telefono' => $this->telefono,
                ':cedula' => $this->cedula_estudiante
            ));
            
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Estudiante actualizado exitosamente';
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function eliminar_estudiante() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $co->beginTransaction();
            
            // Primero eliminar las asistencias relacionadas
            $co->query("DELETE FROM asistencia WHERE cedula_estudiante = '$this->cedula_estudiante'");
            
            // Luego eliminar el estudiante
            $co->query("DELETE FROM estudiantes_pasantia WHERE cedula_estudiante = '$this->cedula_estudiante'");
            
            $co->commit();
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Estudiante eliminado exitosamente';
        } catch(Exception $e) {
            $co->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function consultar_estudiantes() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("
                SELECT 
                    cedula_estudiante, 
                    nombre, 
                    apellido, 
                    institucion,
                    telefono
                FROM estudiantes_pasantia
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

    // Métodos para asistencia
    public function incluir_asistencia() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $stmt = $co->prepare("INSERT INTO asistencia VALUES(:area, :cedula, :inicio, :fin, :activo)");
            $stmt->execute(array(
                ':area' => $this->cod_area,
                ':cedula' => $this->cedula_estudiante,
                ':inicio' => $this->fecha_inicio,
                ':fin' => $this->fecha_fin,
                ':activo' => $this->activo
            ));
            
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Asistencia registrada exitosamente';
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function modificar_asistencia() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $stmt = $co->prepare("UPDATE asistencia SET
                cod_area = :area,
                fecha_fin = :fin,
                activo = :activo
                WHERE cedula_estudiante = :cedula AND fecha_inicio = :inicio");
            
            $stmt->execute(array(
                ':area' => $this->cod_area,
                ':fin' => $this->fecha_fin,
                ':activo' => $this->activo,
                ':cedula' => $this->cedula_estudiante,
                ':inicio' => $this->fecha_inicio
            ));
            
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Asistencia actualizada exitosamente';
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function eliminar_asistencia() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $stmt = $co->prepare("DELETE FROM asistencia 
                                WHERE cedula_estudiante = :cedula 
                                AND fecha_inicio = :fecha_inicio");
            
            $stmt->execute(array(
                ':cedula' => $this->cedula_estudiante,
                ':fecha_inicio' => $this->fecha_inicio
            ));
            
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Asistencia eliminada exitosamente';
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function consultar_asistencia() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("
                SELECT 
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
                ORDER BY a.fecha_inicio DESC
            ");
            
            $r['resultado'] = 'consultar';
            $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    // Métodos para áreas
    public function incluir_area() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $stmt = $co->prepare("INSERT INTO areas_pasantias (nombre_area, descripcion, cedula_personal) 
                                VALUES(:nombre, :descripcion, :responsable)");
            
            $stmt->execute(array(
                ':nombre' => $this->nombre_area,
                ':descripcion' => $this->descripcion,
                ':responsable' => $this->cedula_personal
            ));
            
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Área registrada exitosamente';
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function modificar_area() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $stmt = $co->prepare("UPDATE areas_pasantias SET
                nombre_area = :nombre, 
                descripcion = :descripcion, 
                cedula_personal = :responsable
                WHERE cod_area = :codigo");
            
            $stmt->execute(array(
                ':nombre' => $this->nombre_area,
                ':descripcion' => $this->descripcion,
                ':responsable' => $this->cedula_personal,
                ':codigo' => $this->cod_area
            ));
            
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Área actualizada exitosamente';
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function eliminar_area() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("SELECT COUNT(*) as total FROM asistencia 
                                   WHERE cod_area = '$this->cod_area'");
            $fila = $resultado->fetch(PDO::FETCH_ASSOC);
            
            if($fila['total'] == 0) {
                $co->query("DELETE FROM areas_pasantias WHERE cod_area = '$this->cod_area'");
                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Área eliminada exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede eliminar, hay estudiantes asignados a esta área';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function consultar_areas() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("
                SELECT 
                    a.cod_area, 
                    a.nombre_area, 
                    a.descripcion, 
                    CONCAT(p.nombre, ' ', p.apellido) as responsable,
                    p.cedula_personal as responsable_id
                FROM areas_pasantias a
                JOIN personal p ON a.cedula_personal = p.cedula_personal
                ORDER BY a.nombre_area
            ");
            
            $r['resultado'] = 'consultar';
            $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function obtener_areas_select() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("SELECT cod_area, nombre_area FROM areas_pasantias ORDER BY nombre_area");
            
            $r['resultado'] = 'consultar';
            $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    public function obtener_doctores() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("
                SELECT cedula_personal, CONCAT(nombre, ' ', apellido) as nombre_completo
                FROM personal 
                WHERE cargo = 'Doctor' 
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
    
    private function existe_estudiante($cedula) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            $resultado = $co->query("SELECT * FROM estudiantes_pasantia
                                   WHERE cedula_estudiante = '$cedula'");
            return ($resultado->rowCount() > 0);
        } catch(Exception $e) {
            return false;
        }
    }
}
?>