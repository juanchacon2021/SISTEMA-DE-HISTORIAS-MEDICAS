<?php

namespace Shm\Shm\modelo;
use Shm\Shm\modelo\datos;
use PDO;
use Exception;


class consultasm extends datos{
   
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

	function incluir($datos, $observaciones = array()) {
		$r = array();
		if(!$this->existe($datos['cod_consulta'])) {
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			try {
				// Iniciar transacción
				$co->beginTransaction();

				// 1. Llamar al procedimiento almacenado para insertar la consulta
				$stmtConsulta = $co->prepare("CALL insertar_consulta(
					:fecha, 
					:hora, 
					:consulta, 
					:diagnostico, 
					:tratamiento, 
					:cedula_personal, 
					:cedula_paciente, 
					@cod_generado)");

				$stmtConsulta->execute([
					':fecha' => $datos['fechaconsulta'],
					':hora' => $datos['Horaconsulta'],
					':consulta' => $datos['consulta'],
					':diagnostico' => $datos['diagnostico'],
					':tratamiento' => $datos['tratamientos'],
					':cedula_personal' => $datos['cedula_personal'],
					':cedula_paciente' => $datos['cedula_paciente']
				]);

				// Obtener el código generado por el procedimiento
				$stmtCodigo = $co->query("SELECT @cod_generado AS cod_consulta");
				$result = $stmtCodigo->fetch(PDO::FETCH_ASSOC);
				$cod_consulta = $result['cod_consulta'];

				// 2. Insertar observaciones (si existen)
				if (!empty($observaciones) && is_array($observaciones)) {
					$stmtObservaciones = $co->prepare("INSERT INTO observacion_consulta 
						(cod_consulta, cod_observacion, observacion) 
						VALUES (?, ?, ?)");

					foreach ($observaciones as $obs) {
						if (!empty($obs['cod_observacion']) && isset($obs['observacion'])) {
							$stmtObservaciones->execute([
								$cod_consulta,
								$obs['cod_observacion'],
								$obs['observacion']
							]);
						}
					}
				}

				// Confirmar transacción
				$co->commit();

				$r['resultado'] = 'incluir';
				$r['mensaje'] = 'Registro Incluido';
				$r['cod_consulta'] = $cod_consulta; // Devuelve el código generado

			} catch(Exception $e) {
				// Revertir en caso de error
				$co->rollBack();
				$r['resultado'] = 'error';
				$r['mensaje'] = $e->getMessage();
			}
		} else {
			$r['resultado'] = 'incluir';
			$r['mensaje'] = 'Ya existe el Cod de Consulta';
		}
		return $r;
	}
	
	function modificar($datos, $observaciones = array()) {
		$r = array();
		if($this->existe($datos['cod_consulta'])) {
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			try {
				// Iniciar transacción
				$co->beginTransaction();

				// 1. Actualizar la consulta principal (CONSULTA PREPARADA)
				$stmtUpdate = $co->prepare("UPDATE consulta SET
					fechaconsulta = ?,
					Horaconsulta = ?,
					consulta = ?,
					diagnostico = ?,
					tratamientos = ?,
					cedula_personal = ?,
					cedula_paciente = ?
					WHERE cod_consulta = ?");
				
				$stmtUpdate->execute([
					$datos['fechaconsulta'],
					$datos['Horaconsulta'],
					$datos['consulta'],
					$datos['diagnostico'],
					$datos['tratamientos'],
					$datos['cedula_personal'],
					$datos['cedula_paciente'],
					$datos['cod_consulta']
				]);

				// 2. Eliminar observaciones anteriores (CONSULTA PREPARADA)
				$stmtDelete = $co->prepare("DELETE FROM observacion_consulta WHERE cod_consulta = ?");
				$stmtDelete->execute([$datos['cod_consulta']]);

				// 3. Insertar nuevas observaciones (si existen)
				if (!empty($observaciones) && is_array($observaciones)) {
					$stmtInsert = $co->prepare("INSERT INTO observacion_consulta 
						(cod_consulta, cod_observacion, observacion) 
						VALUES (?, ?, ?)");
					
					foreach ($observaciones as $obs) {
						if (!empty($obs['cod_observacion']) && isset($obs['observacion'])) {
							$stmtInsert->execute([
								$datos['cod_consulta'],
								$obs['cod_observacion'],
								$obs['observacion']
							]);
						}
					}
				}

				// Confirmar transacción
				$co->commit();

				$r['resultado'] = 'modificar';
				$r['mensaje'] = 'Registro Modificado';
				$r['cod_consulta'] = $datos['cod_consulta'];

			} catch(Exception $e) {
				// Revertir en caso de error
				$co->rollBack();
				$r['resultado'] = 'error';
				$r['mensaje'] = $e->getMessage();
			}
		} else {
			$r['resultado'] = 'modificar';
			$r['mensaje'] = 'No existe el Cod de Consulta';
		}
		return $r;
	}
	

	function obtener_observaciones_consulta($cod_consulta) {
		$co = $this->conecta();
		$stmt = $co->prepare("SELECT oc.cod_observacion, tobs.nom_observaciones, oc.observacion
							FROM observacion_consulta oc
							JOIN tipo_observacion tobs ON oc.cod_observacion = tobs.cod_observacion
							WHERE oc.cod_consulta = ?");
		$stmt->execute([$cod_consulta]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function eliminar($datos) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		
		if ($this->existe($datos['cod_consulta'])) {
			try {
				// Iniciar transacción para asegurar atomicidad
				$co->beginTransaction();

				// 1. Eliminar observaciones primero (por integridad referencial)
				$stmt1 = $co->prepare("DELETE FROM observacion_consulta WHERE cod_consulta = ?");
				$stmt1->execute([$datos['cod_consulta']]);

				// 2. Eliminar la consulta
				$stmt2 = $co->prepare("DELETE FROM consulta WHERE cod_consulta = ?");
				$stmt2->execute([$datos['cod_consulta']]);

				// Confirmar cambios
				$co->commit();

				$r['resultado'] = 'eliminar';
				$r['mensaje'] = 'Registro Eliminado';
			} catch (Exception $e) {
				// Revertir en caso de error
				$co->rollBack();
				$r['resultado'] = 'error';
				$r['mensaje'] = $e->getMessage();
			}
		} else {
			$r['resultado'] = 'eliminar';
			$r['mensaje'] = 'No existe el registro';
		}
		return $r;
	}

	function consultar() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		
		try {
			// Consulta preparada
			$stmt = $co->prepare("SELECT *, h.nombre as nombre_h, h.apellido as apellido_h  
									FROM consulta c 
									INNER JOIN paciente h ON c.cedula_paciente = h.cedula_paciente
									INNER JOIN personal p ON c.cedula_personal = p.cedula_personal");
			
			// Ejecutar la consulta
			$stmt->execute();
			
			// Obtener resultados
			$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			if ($resultados) {
				$r['resultado'] = 'consultar';
				$r['datos'] = $resultados;
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
	
	
	
	
	
	private function existe($cod_consulta) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		try {
			// Consulta preparada
			$stmt = $co->prepare("SELECT * FROM consulta WHERE cod_consulta = ?");
			$stmt->execute([$cod_consulta]);
			
			// Verificar si existe al menos un registro
			return $stmt->fetch() !== false;
			
		} catch(Exception $e) {
			return false;
		}
	}
	
	
}
class observaciones extends datos {
	

	function listado_observaciones() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		
		try {
			// Consulta preparada
			$stmt = $co->prepare("SELECT * FROM tipo_observacion");
			
			// Ejecutar consulta
			$stmt->execute();
			
			// Obtener resultados
			$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			if ($resultados) {
				$r['resultado'] = 'listado_observaciones';
				$r['datos'] = $resultados;
			} else {
				$r['resultado'] = 'listado_observaciones';
				$r['datos'] = array();
			}
			
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		
		return $r;
	}



    function incluir2($datos) {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Llamar al procedimiento simple
            $stmt = $co->prepare("CALL sp_insertar_observacion_simple(?, @codigo)");
            $stmt->execute([$datos['nom_observaciones']]);

            // Obtener código generado
            $cod_generado = $co->query("SELECT @codigo")->fetchColumn();

            // Verificar si realmente se insertó
            if($this->existe2($cod_generado)) {
                $r['resultado'] = 'agregar';
                $r['mensaje'] = 'Registro Incluido';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se pudo generar el código';
            }

        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }


    function eliminar2($datos){
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        if($this->existe2($datos['cod_observacion'])){
            try {
                $stmt = $co->prepare("DELETE FROM tipo_observacion WHERE cod_observacion = :cod_observacion");
                $stmt->execute([':cod_observacion' => $datos['cod_observacion']]);
                $r['resultado'] = 'descartar';
                $r['mensaje'] =  'Registro Eliminado';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] =  $e->getMessage();
            }
        }
        else{
            $r['resultado'] = 'descartar';
            $r['mensaje'] =  'No existe el registro';
        }
        return $r;
    }

    function modificar2($datos) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        if($this->existe2($datos['cod_observacion'])) {
            try {
                // Consulta preparada
                $stmt = $co->prepare("UPDATE tipo_observacion SET 
                                    nom_observaciones = ?
                                    WHERE cod_observacion = ?");
                // Ejecutar con parámetros
                $stmt->execute([
                    $datos['nom_observaciones'],
                    $datos['cod_observacion']
                ]);
                $r['resultado'] = 'actualizar';
                $r['mensaje'] = 'Registro Modificado';
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'actualizar';
            $r['mensaje'] = 'Código no registrado';
        }

        return $r;
    }

	private function existe2($cod_observacion) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		try {
			// Consulta preparada
			$stmt = $co->prepare("SELECT * FROM tipo_observacion WHERE cod_observacion = ?");
			$stmt->execute([$cod_observacion]);
			
			// Verificar si existe al menos un registro
			return $stmt->fetch() !== false;
			
		} catch(Exception $e) {
			return false;
		}
	}

	

	
}

?>