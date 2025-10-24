<?php

namespace Shm\Shm\modelo;
use Shm\Shm\modelo\datos;
use PDO;
use Exception;


class p_cronicos extends datos{
	

	function listadopacientes() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array(); 
		try {
			$sql = "SELECT * FROM paciente";
			$stmt = $co->prepare($sql);
			$stmt->execute();
			$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$r['resultado'] = 'listadopacientes';
			$r['datos'] = $datos ? $datos : array(); 
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage(); 
		}
		return $r;
	}

    function incluir($datos, $patologias = array()) {
        $r = array();

        // Validar que la cédula del paciente exista en la tabla paciente
        $val = $this->validar_paciente($datos['cedula_paciente']);
        if (!is_array($val) || !isset($val['codigo'])) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Error al validar paciente';
            return $r;
        }
        if ($val['codigo'] !== 0) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $val['mensaje'];
            return $r;
        }

        $co = $this->conecta();
        if(!$this->existe($datos['cedula_paciente'])){

                $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                if (!empty($patologias) && is_array($patologias)) {
                    $stmt = $co->prepare("INSERT INTO padece (cedula_paciente, cod_patologia, tratamiento, administracion_t) VALUES (?, ?, ?, ?)");
                    foreach ($patologias as $pat) {
                        $stmt->execute([
                            $datos['cedula_paciente'],
                            $pat['cod_patologia'],
                            $pat['tratamiento'],
                            $pat['administracion_t']
                        ]);
                    }
                    $r['resultado'] = 'incluir';
                    $r['mensaje'] = 'Registro Incluido';
                } else {
                    $r['resultado'] = 'error';
                    $r['mensaje'] = 'No se recibieron patologías';
                }
            } catch(Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        }
        else {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'El paciente ya esta registrado ';
        }

        return $r;
    }

    function modificar($datos, $patologias = array()) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        // Validar que la cédula del paciente exista en la tabla paciente
        $val = $this->validar_paciente($datos['cedula_paciente']);
        if (!is_array($val) || !isset($val['codigo'])) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Error al validar paciente';
            return $r;
        }
        if ($val['codigo'] !== 0) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $val['mensaje'];
            return $r;
        }

        try {
            // Verificar si el paciente tiene patologías registradas
            if(!$this->existe($datos['cedula_paciente'])) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'El paciente no tiene patologías registradas para modificar';
                return $r;
            }

            // 1. Eliminar todas las patologías actuales del paciente
            $stmtDelete = $co->prepare("DELETE FROM padece WHERE cedula_paciente = ?");
            $stmtDelete->execute([$datos['cedula_paciente']]);

            // 2. Insertar las nuevas patologías
            if (!empty($patologias)) {
                $stmtInsert = $co->prepare("INSERT INTO padece 
                                        (cedula_paciente, cod_patologia, tratamiento, administracion_t) 
                                        VALUES (?, ?, ?, ?)");
                
                foreach ($patologias as $pat) {
                    $stmtInsert->execute([
                        $datos['cedula_paciente'],
                        $pat['cod_patologia'],
                        $pat['tratamiento'] ?? null,
                        $pat['administracion_t'] ?? null
                    ]);
                }
            }

            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Registro Modificado';
            
        } catch(PDOException $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Error al modificar patologías: ' . $e->getMessage();
        }
        
        return $r;
    }
	
	function eliminar($datos) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();

		try {
			// Verificar si el paciente tiene patologías registradas
			if($this->existe($datos['cedula_paciente'])) {
				// Eliminar todas las patologías del paciente
				$stmtDelete = $co->prepare("DELETE FROM padece WHERE cedula_paciente = ?");
				$stmtDelete->execute([$datos['cedula_paciente']]);

				$r['resultado'] = 'eliminar';
				$r['mensaje'] = 'Registro Eliminado';
			} else {
				$r['resultado'] = 'error';
				$r['mensaje'] = 'El paciente no tiene patologías registradas';
			}
		} catch(Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = 'Error al eliminar: ' . $e->getMessage();
		}

		return $r;
	}
	
	
	function consultar() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$sql = "SELECT p.cedula_paciente, p.nombre, p.apellido, GROUP_CONCAT(pa.nombre_patologia SEPARATOR ', ') AS patologias
					FROM paciente p 
					JOIN padece pd ON p.cedula_paciente = pd.cedula_paciente
					JOIN patologia pa ON pd.cod_patologia = pa.cod_patologia
					GROUP BY p.cedula_paciente, p.nombre, p.apellido";
			$stmt = $co->prepare($sql);
			$stmt->execute();
			$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$r['resultado'] = 'consultar';
			$r['datos'] = $datos ? $datos : array();
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

		function obtener_patologias_paciente($cedula_paciente) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		try {
			$stmt = $co->prepare("SELECT p.cedula_paciente, p.cod_patologia, 
										pa.nombre_patologia, p.tratamiento, p.administracion_t
								FROM padece p 
								JOIN patologia pa ON p.cod_patologia = pa.cod_patologia
								WHERE p.cedula_paciente = ?");
			$stmt->execute([$cedula_paciente]);
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		} catch(PDOException $e) {
			// Puedes loggear el error si es necesario
			return [];
		}
	}


	private function existe($cedula_paciente) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		try {
			$stmt = $co->prepare("SELECT * FROM padece WHERE cedula_paciente = ?");
			$stmt->execute([$cedula_paciente]);
			
			$fila = $stmt->fetch();  // Cambiado de fetchAll() a fetch()
			
			return (bool)$fila;  // Simplificado el if/else
			
		} catch(Exception $e) {
			return false;
		}
	}

	   public function validar_paciente($cedula_paciente) {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $co->prepare("SELECT 1 FROM paciente WHERE cedula_paciente = ? LIMIT 1");
            $stmt->execute([$cedula_paciente]);
            $existe = ($stmt->fetchColumn() !== false);

            $r['resultado'] = 'validar_paciente';
            $r['codigo'] = $existe ? 0 : 1; // 0 = existe, 1 = no existe
            $r['mensaje'] = $existe ? 'Paciente existe' : 'Paciente no encontrado';
            $r['existe_paciente'] = $existe;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['codigo'] = 1;
            $r['mensaje'] = $e->getMessage();
            $r['existe_paciente'] = false;
        }

        return $r;
    }
	
	
}

class patologias extends datos{


	function listado_patologias() {
    $co = $this->conecta();
    $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $r = array(); 
    
    try {
        // Preparamos la consulta
        $stmt = $co->prepare("SELECT * FROM patologia");
        
        // Ejecutamos la consulta
        $stmt->execute();
        
        // Obtenemos los resultados
        $resultado = $stmt;
        
        if ($resultado) {
            $r['resultado'] = 'listado_patologias';
            $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC); 
        } else {
            $r['resultado'] = 'listado_patologias';
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

		// Verifica si ya existe una patología con ese nombre
		if(!$this->existeNombrePatologia($datos['nombre_patologia'])) {
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			try {
				// Llamar al procedimiento almacenado
				$stmt = $co->prepare("CALL insertar_patologia(:nombre, @cod_generado)");
				$stmt->execute([':nombre' => $datos['nombre_patologia']]);
				
				// Obtener el código generado
				$stmt = $co->query("SELECT @cod_generado as codigo");
				$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
				$cod_patologia = $resultado['codigo'];

				$r['resultado'] = 'agregar';
				$r['mensaje'] = 'Patología registrada exitosamente';
				$r['cod_patologia'] = $cod_patologia;
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] = $e->getMessage();
				$r['cod_patologia'] = null;
			} finally {
				// Cerrar conexión
				if(isset($co)) {
					$co = null;
				}
			}
		} else {
			$r['resultado'] = 'error';
			$r['mensaje'] = 'Ya existe una patología con ese nombre';
			$r['cod_patologia'] = null;
		}

		return $r;
	}

	function modificar2($datos) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();

		if($this->existe2($datos['cod_patologia'])) {
			try {
				$stmt = $co->prepare("UPDATE patologia SET nombre_patologia = :nombre WHERE cod_patologia = :cod");
				$stmt->execute([
					':nombre' => $datos['nombre_patologia'],
					':cod' => $datos['cod_patologia']
				]);
				$r['resultado'] = 'actualizar';
				$r['mensaje'] = 'Registro Modificado';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] = $e->getMessage();
			}
		} else {
			$r['resultado'] = 'actualizar';
			$r['mensaje'] = 'Código de patología no registrado';
		}
		return $r;
	}

	function eliminar2($datos) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if($this->existe2($datos['cod_patologia'])){
			try {
				$stmt = $co->prepare("DELETE FROM patologia WHERE cod_patologia = :cod_patologia");
				$stmt->execute([':cod_patologia' => $datos['cod_patologia']]);
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

	private function existe2($cod_patologia) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		try {
			// Consulta preparada manteniendo tu estructura original pero segura
			$stmt = $co->prepare("SELECT * FROM patologia c WHERE c.cod_patologia = :cod");
			$stmt->bindParam(':cod', $cod_patologia);
			$stmt->execute();
			
			// Manteniendo tu lógica original con fetchAll()
			$fila = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return !empty($fila); // Retorna true si hay resultados, false si no
				
		} catch(Exception $e) {
			return false;
		}
	}

	private function existeNombrePatologia($nombre_patologia) {
    $co = $this->conecta();
    $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $stmt = $co->prepare("SELECT * FROM patologia WHERE nombre_patologia = :nombre");
        $stmt->execute([':nombre' => $nombre_patologia]);
        $fila = $stmt->fetch();
        return (bool)$fila;
    } catch(Exception $e) {
        return false;
    }
}


}
?>