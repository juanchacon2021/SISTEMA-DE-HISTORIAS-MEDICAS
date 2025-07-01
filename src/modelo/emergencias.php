<?php

namespace Shm\Shm\modelo;
use Shm\Shm\modelo\datos;
use PDO;
use Exception;


class emergencias extends datos{
	
	
	
	private $horaingreso;
	private $fechaingreso;
	private $motingreso;
	private $diagnostico_e;
	private $tratamientos;
	private $cedula_personal;
	private $cedula_paciente;
	private $procedimiento;

	private $old_cedula_paciente;
	private $old_cedula_personal;
	private $old_fechaingreso;
	private $old_horaingreso;

	
	

	
	public function setDatos($datos, $accion) {
		// Comunes a todas las acciones
		if (isset($datos['horaingreso'])) $this->horaingreso = $datos['horaingreso'];
		if (isset($datos['fechaingreso'])) $this->fechaingreso = $datos['fechaingreso'];
		if (isset($datos['motingreso'])) $this->motingreso = $datos['motingreso'];
		if (isset($datos['diagnostico_e'])) $this->diagnostico_e = $datos['diagnostico_e'];
		if (isset($datos['tratamientos'])) $this->tratamientos = $datos['tratamientos'];
		if (isset($datos['cedula_personal'])) $this->cedula_personal = $datos['cedula_personal'];
		if (isset($datos['cedula_paciente'])) $this->cedula_paciente = $datos['cedula_paciente'];
		if (isset($datos['procedimiento'])) $this->procedimiento = $datos['procedimiento'];

		// Solo para modificar
		if ($accion === 'modificar') {
			if (isset($datos['old_cedula_paciente'])) $this->old_cedula_paciente = $datos['old_cedula_paciente'];
			if (isset($datos['old_cedula_personal'])) $this->old_cedula_personal = $datos['old_cedula_personal'];
			if (isset($datos['old_fechaingreso'])) $this->old_fechaingreso = $datos['old_fechaingreso'];
			if (isset($datos['old_horaingreso'])) $this->old_horaingreso = $datos['old_horaingreso'];
		}
	}


	function listadopersonal() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();  
		
		try {
			// Consulta preparada
			$stmt = $co->prepare("SELECT * FROM personal");			
			// Ejecutar la consulta
			$stmt->execute();
			// Obtener resultados
			$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);		
			if ($resultados) {
				$r['resultado'] = 'listadopersonal';
				$r['datos'] = $resultados; 
			} else {
				$r['resultado'] = 'listadopersonal';
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


	function listadopacientes() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		
		try {
			// Consulta preparada
			$stmt = $co->prepare("SELECT * FROM paciente");		
			// Ejecutar la consulta
			$stmt->execute();
			// Obtener resultados
			$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if ($resultados) {
				$r['resultado'] = 'listadopacientes';
				$r['datos'] = $resultados; 
			} else {
				$r['resultado'] = 'listadopacientes';
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



	function incluir() {
		$r = array();
		
		if(!$this->existe($this->cedula_paciente, $this->cedula_personal, $this->fechaingreso, $this->horaingreso)) {
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			try {
				// Consulta preparada con parámetros nombrados
				$stmt = $co->prepare("INSERT INTO emergencia (
					horaingreso,
					fechaingreso,
					motingreso,
					diagnostico_e,
					tratamientos,
					cedula_personal,
					cedula_paciente,
					procedimiento
				) VALUES (
					:horaingreso,
					:fechaingreso,
					:motingreso,
					:diagnostico_e,
					:tratamientos,
					:cedula_personal,
					:cedula_paciente,
					:procedimiento
				)");

				// Vincular parámetros
				$stmt->bindParam(':horaingreso', $this->horaingreso);
				$stmt->bindParam(':fechaingreso', $this->fechaingreso);
				$stmt->bindParam(':motingreso', $this->motingreso);
				$stmt->bindParam(':diagnostico_e', $this->diagnostico_e);
				$stmt->bindParam(':tratamientos', $this->tratamientos);
				$stmt->bindParam(':cedula_personal', $this->cedula_personal);
				$stmt->bindParam(':cedula_paciente', $this->cedula_paciente);
				$stmt->bindParam(':procedimiento', $this->procedimiento);

				// Ejecutar la consulta
				$stmt->execute();

				// Verificar si se insertó correctamente
				if($stmt->rowCount() > 0) {
					$r['resultado'] = 'incluir';
					$r['mensaje'] = 'Registro Incluido';
				} else {
					$r['resultado'] = 'error';
					$r['mensaje'] = 'No se pudo insertar el registro';
				}

				// Cerrar el cursor
				$stmt->closeCursor();

			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] = 'Error al insertar: ' . $e->getMessage();
			}
		} else {
			$r['resultado'] = 'incluir';
			$r['mensaje'] = 'Ya existe el registro con estos datos';
		}

		return $r;
	}
	
	function modificar(){
    $co = $this->conecta();
    $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $r = array();

    // Usa los valores originales para buscar el registro
    if($this->existe($this->old_cedula_paciente, $this->old_cedula_personal, $this->old_fechaingreso, $this->old_horaingreso)){
        try {
            $stmt = $co->prepare("
                UPDATE emergencia SET 
                    motingreso = :motingreso,
                    diagnostico_e = :diagnostico_e,
                    tratamientos = :tratamientos,
					procedimiento = :procedimiento,
                    cedula_paciente = :cedula_paciente,
                    cedula_personal = :cedula_personal,
                    fechaingreso = :fechaingreso,
                    horaingreso = :horaingreso
                WHERE cedula_paciente = :old_cedula_paciente
                  AND cedula_personal = :old_cedula_personal
                  AND fechaingreso = :old_fechaingreso
                  AND horaingreso = :old_horaingreso
            ");

            $stmt->bindParam(':motingreso', $this->motingreso);
            $stmt->bindParam(':diagnostico_e', $this->diagnostico_e);
            $stmt->bindParam(':tratamientos', $this->tratamientos);
            $stmt->bindParam(':cedula_paciente', $this->cedula_paciente);
            $stmt->bindParam(':cedula_personal', $this->cedula_personal);
            $stmt->bindParam(':fechaingreso', $this->fechaingreso);
            $stmt->bindParam(':horaingreso', $this->horaingreso);
            $stmt->bindParam(':procedimiento', $this->procedimiento);

            $stmt->bindParam(':old_cedula_paciente', $this->old_cedula_paciente);
            $stmt->bindParam(':old_cedula_personal', $this->old_cedula_personal);
            $stmt->bindParam(':old_fechaingreso', $this->old_fechaingreso);
            $stmt->bindParam(':old_horaingreso', $this->old_horaingreso);

            $stmt->execute();

            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Registro Modificado';
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
    } else {
        $r['resultado'] = 'modificar';
        $r['mensaje'] = 'Registro no encontrado';
    }

    return $r;
}
	
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();

		if($this->existe($this->cedula_paciente, $this->cedula_personal, $this->fechaingreso, $this->horaingreso)){
			try {
				$stmt = $co->prepare("
					DELETE FROM emergencia 
					WHERE cedula_paciente = :cedula_paciente
					AND cedula_personal = :cedula_personal
					AND fechaingreso = :fechaingreso
					AND horaingreso = :horaingreso
				");

				$stmt->bindParam(':cedula_paciente', $this->cedula_paciente);
				$stmt->bindParam(':cedula_personal', $this->cedula_personal);
				$stmt->bindParam(':fechaingreso', $this->fechaingreso);
				$stmt->bindParam(':horaingreso', $this->horaingreso);

				$stmt->execute();

				$r['resultado'] = 'eliminar';
				$r['mensaje'] = 'Registro Eliminado';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] = $e->getMessage();
			}
		} else {
			$r['resultado'] = 'eliminar';
			$r['mensaje'] = 'Registro no encontrado';
		}

		return $r;
	}
	
	
	function consultar() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("SELECT *, h.nombre as nombre_h, h.apellido as apellido_h  
									FROM emergencia e 
									INNER JOIN paciente h ON e.cedula_paciente = h.cedula_paciente
									INNER JOIN personal p ON e.cedula_personal = p.cedula_personal");
			
			if ($resultado) {
				$r['resultado'] = 'consultar';
				$r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$r['resultado'] = 'consultar';
				$r['datos'] = array();
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}
	
	
	private function existe($cedula_paciente, $cedula_personal, $fechaingreso, $horaingreso){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		try {
			$stmt = $co->prepare("
				SELECT * FROM emergencia 
				WHERE cedula_paciente = :cedula_paciente 
				AND cedula_personal = :cedula_personal 
				AND fechaingreso = :fechaingreso 
				AND horaingreso = :horaingreso
			");

			$stmt->bindParam(':cedula_paciente', $cedula_paciente);
			$stmt->bindParam(':cedula_personal', $cedula_personal);
			$stmt->bindParam(':fechaingreso', $fechaingreso);
			$stmt->bindParam(':horaingreso', $horaingreso);
			$stmt->execute();

			$fila = $stmt->fetch(PDO::FETCH_ASSOC);

			return $fila !== false;
		} catch(Exception $e) {
			return false;
		}
	}
	
	

	
	
	
}
?>