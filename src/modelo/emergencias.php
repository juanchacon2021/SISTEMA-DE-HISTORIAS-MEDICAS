<?php

namespace Shm\Shm\modelo;
use Shm\Shm\modelo\datos;
use PDO;
use Exception;


class emergencias extends datos{
	


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



 function incluir($datos) {
        $r = array();

        // Validar cédulas antes de cualquier operación
        $val = $this->validar_cedulas($datos['cedula_paciente'], $datos['cedula_personal']);
        if (!is_array($val) || !isset($val['codigo'])) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Error al validar cédulas';
            return $r;
        }
        if ($val['codigo'] !== 0) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $val['mensaje'];
            return $r;
        }

        if(!$this->existe($datos['cedula_paciente'], $datos['cedula_personal'], $datos['fechaingreso'], $datos['horaingreso'])) {
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

                // Ejecutar la consulta con el array de datos
                $stmt->execute([
                    ':horaingreso' => $datos['horaingreso'],
                    ':fechaingreso' => $datos['fechaingreso'],
                    ':motingreso' => $datos['motingreso'],
                    ':diagnostico_e' => $datos['diagnostico_e'],
                    ':tratamientos' => $datos['tratamientos'],
                    ':cedula_personal' => $datos['cedula_personal'],
                    ':cedula_paciente' => $datos['cedula_paciente'],
                    ':procedimiento' => $datos['procedimiento']
                ]);

                if($stmt->rowCount() > 0) {
                    $r['resultado'] = 'incluir';
                    $r['mensaje'] = 'Registro Incluido';
                } else {
                    $r['resultado'] = 'error';
                    $r['mensaje'] = 'No se pudo insertar el registro';
                }

                $stmt->closeCursor();

            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Error al insertar: ' . $e->getMessage();
            }
        } else {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Ya existe el registro con estos datos';
        }

        return $r;
    }
    
    function modificar($datos){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        // Validar las cédulas nuevas antes de modificar
        $val = $this->validar_cedulas($datos['cedula_paciente'], $datos['cedula_personal']);
        if (!is_array($val) || !isset($val['codigo'])) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Error al validar cédulas';
            return $r;
        }
        if ($val['codigo'] !== 0) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $val['mensaje'];
            return $r;
        }

        if($this->existe(
            $datos['old_cedula_paciente'],
            $datos['old_cedula_personal'],
            $datos['old_fechaingreso'],
            $datos['old_horaingreso']
        )){
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

                $stmt->execute([
                    ':motingreso' => $datos['motingreso'],
                    ':diagnostico_e' => $datos['diagnostico_e'],
                    ':tratamientos' => $datos['tratamientos'],
                    ':procedimiento' => $datos['procedimiento'],
                    ':cedula_paciente' => $datos['cedula_paciente'],
                    ':cedula_personal' => $datos['cedula_personal'],
                    ':fechaingreso' => $datos['fechaingreso'],
                    ':horaingreso' => $datos['horaingreso'],
                    ':old_cedula_paciente' => $datos['old_cedula_paciente'],
                    ':old_cedula_personal' => $datos['old_cedula_personal'],
                    ':old_fechaingreso' => $datos['old_fechaingreso'],
                    ':old_horaingreso' => $datos['old_horaingreso'],
                ]);

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

	function eliminar($datos){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();

		if($this->existe($datos['cedula_paciente'], $datos['cedula_personal'], $datos['fechaingreso'], $datos['horaingreso'])){
			try {
				$stmt = $co->prepare("
					DELETE FROM emergencia 
					WHERE cedula_paciente = :cedula_paciente
					AND cedula_personal = :cedula_personal
					AND fechaingreso = :fechaingreso
					AND horaingreso = :horaingreso
				");

				$stmt->execute([
					':cedula_paciente' => $datos['cedula_paciente'],
					':cedula_personal' => $datos['cedula_personal'],
					':fechaingreso' => $datos['fechaingreso'],
					':horaingreso' => $datos['horaingreso']
				]);

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


	
    public function validar_cedulas($cedula_paciente, $cedula_personal) {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Verificar existencia paciente
            $stmt = $co->prepare("SELECT 1 FROM paciente WHERE cedula_paciente = ? LIMIT 1");
            $stmt->execute([$cedula_paciente]);
            $existePaciente = ($stmt->fetchColumn() !== false);

            // Verificar existencia personal
            $stmt = $co->prepare("SELECT cedula_personal FROM personal WHERE cedula_personal = ? LIMIT 1");
            $stmt->execute([$cedula_personal]);
            $existePersonal = ($stmt->fetchColumn() !== false);

            // Código: 0 = ambas existen, 1 = paciente no existe, 2 = personal no existe, 3 = ninguna existe
            if ($existePaciente && $existePersonal) {
                $codigo = 0;
                $mensaje = 'Ambas cédulas existen';
            } elseif (!$existePaciente && $existePersonal) {
                $codigo = 1;
                $mensaje = 'La cédula del paciente no existe';
            } elseif ($existePaciente && !$existePersonal) {
                $codigo = 2;
                $mensaje = 'La cédula del personal no existe';
            } else {
                $codigo = 3;
                $mensaje = 'Ninguna de las cédulas existe';
            }

            $r['resultado'] = 'validar_cedulas';
            $r['codigo'] = $codigo;
            $r['mensaje'] = $mensaje;
            $r['existe_paciente'] = $existePaciente;
            $r['existe_personal'] = $existePersonal;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }
	

	
	
	
}
?>