<?php
require_once('modelo/datos.php');

class pasantias extends datos {
    // Atributos para estudiantes
    private $cedula_estudiante;
    private $nombre;
    private $apellido;
    private $institucion;
    private $telefono;
    private $cod_area;
    private $fecha_inicio;
    private $fecha_fin;
    private $activo;
    
    // Atributos para áreas
    private $nombre_area;
    private $descripcion;
    private $responsable_id;
    
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
    public function set_responsable_id($valor) { $this->responsable_id = $valor; }

    // Métodos CRUD para estudiantes
    public function incluir_estudiante() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            if(!$this->existe_estudiante($this->cedula_estudiante)) {
                // Primero insertamos en estudiantes_pasantia
                $stmt = $co->prepare("INSERT INTO estudiantes_pasantia VALUES(
                    :cedula, :nombre, :apellido, :institucion)");
                
                $stmt->execute(array(
                    ':cedula' => $this->cedula_estudiante,
                    ':nombre' => $this->nombre,
                    ':apellido' => $this->apellido,
                    ':institucion' => $this->institucion
                ));
                
                // Luego insertamos en asistencia
                $stmt = $co->prepare("INSERT INTO asistencia VALUES(
                    :inicio, :fin, :activo, :cedula, :area)");
                
                $stmt->execute(array(
                    ':inicio' => $this->fecha_inicio,
                    ':fin' => $this->fecha_fin,
                    ':activo' => $this->activo,
                    ':cedula' => $this->cedula_estudiante,
                    ':area' => $this->cod_area
                ));
                
                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Estudiante registrado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'El estudiante ya está registrado';
            }
        } catch(Exception $e) {
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
            if($this->existe_estudiante($this->cedula_estudiante)) {
                // Actualizar datos básicos del estudiante
                $stmt = $co->prepare("UPDATE estudiantes_pasantia SET
                    nombre = :nombre, apellido = :apellido, institucion = :institucion
                    WHERE cedula_estudiante = :cedula");
                
                $stmt->execute(array(
                    ':nombre' => $this->nombre,
                    ':apellido' => $this->apellido,
                    ':institucion' => $this->institucion,
                    ':cedula' => $this->cedula_estudiante
                ));
                
                // Actualizar datos de asistencia
                $stmt = $co->prepare("UPDATE asistencia SET
                    fecha_inicio = :inicio, fecha_fin = :fin, activo = :activo, cod_area = :area
                    WHERE cedula_estudiante = :cedula");
                
                $stmt->execute(array(
                    ':inicio' => $this->fecha_inicio,
                    ':fin' => $this->fecha_fin,
                    ':activo' => $this->activo,
                    ':area' => $this->cod_area,
                    ':cedula' => $this->cedula_estudiante
                ));
                
                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Estudiante actualizado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'El estudiante no existe';
            }
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
            if($this->existe_estudiante($this->cedula_estudiante)) {
                // Primero eliminamos de asistencia
                $co->query("DELETE FROM asistencia WHERE cedula_estudiante = '$this->cedula_estudiante'");
                
                // Luego eliminamos de estudiantes_pasantia
                $co->query("DELETE FROM estudiantes_pasantia WHERE cedula_estudiante = '$this->cedula_estudiante'");
                
                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Estudiante eliminado exitosamente';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'El estudiante no existe';
            }
        } catch(Exception $e) {
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
            $resultado = $co->query("SELECT e.cedula_estudiante, e.nombre, e.apellido, e.institucion, 
                                   a.fecha_inicio, a.fecha_fin, a.activo, a.cod_area,
                                   ar.nombre_area, 
                                   CONCAT(p.nombre, ' ', p.apellido) as responsable
                                   FROM estudiantes_pasantia e
                                   JOIN asistencia a ON e.cedula_estudiante = a.cedula_estudiante
                                   JOIN areas_pasantia ar ON a.cod_area = ar.cod_area
                                   JOIN personal p ON ar.responsable_id = p.cedula_personal
                                   ORDER BY e.apellido, e.nombre");
            
            $r['resultado'] = 'consultar';
            $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    // Métodos CRUD para áreas
    public function incluir_area() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $stmt = $co->prepare("INSERT INTO areas_pasantia VALUES(
                NULL, :nombre, :descripcion, :responsable)");
            
            $stmt->execute(array(
                ':nombre' => $this->nombre_area,
                ':descripcion' => $this->descripcion,
                ':responsable' => $this->responsable_id
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
            $stmt = $co->prepare("UPDATE areas_pasantia SET
                nombre_area = :nombre, descripcion = :descripcion, 
                responsable_id = :responsable
                WHERE cod_area = :codigo");
            
            $stmt->execute(array(
                ':nombre' => $this->nombre_area,
                ':descripcion' => $this->descripcion,
                ':responsable' => $this->responsable_id,
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
            // Verificar si hay estudiantes en esta área
            $resultado = $co->query("SELECT COUNT(*) as total FROM asistencia 
                                   WHERE cod_area = '$this->cod_area'");
            $fila = $resultado->fetch(PDO::FETCH_ASSOC);
            
            if($fila['total'] == 0) {
                $co->query("DELETE FROM areas_pasantia WHERE cod_area = '$this->cod_area'");
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
            $resultado = $co->query("SELECT a.*, CONCAT(p.nombre, ' ', p.apellido) as responsable
                                   FROM areas_pasantia a
                                   JOIN personal p ON a.responsable_id = p.cedula_personal
                                   ORDER BY a.nombre_area");
            
            $r['resultado'] = 'consultar';
            $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    
    // Métodos auxiliares
    public function obtener_areas_select() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("SELECT cod_area, nombre_area FROM areas_pasantia ORDER BY nombre_area");
            
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
            $resultado = $co->query("SELECT cedula_personal, CONCAT(nombre, ' ', apellido) as nombre_completo
                                   FROM personal WHERE cargo = 'Doctor' ORDER BY apellido");
            
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