<?php

namespace Shm\Shm\modelo;
use Shm\Shm\modelo\datos;
use PDO;
use Exception;


class p_cronicos extends datos{
	
	
	private $cod_patologia; 
	private $cedula_paciente;
	private $tratamiento;
	private $administracion_t;

	
	function set_cod_patologia($valor){
		$this->cod_patologia = $valor;
	}
	
	function set_cedula_paciente($valor){
		$this->cedula_paciente = $valor;
	}

	function set_tratamiento($valor){
		$this->tratamiento = $valor;
	}
	function set_administracion_t($valor){
		$this->administracion_t = $valor;
	}
	
	
	function get_cod_patologia(){
		return $this->cod_patologia;
	}
	
	function get_cedula_paciente(){
		return $this->cedula_paciente;
	}


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

   function incluir($cedula_paciente, $patologias = array()) {
		$r = array();
		$co = $this->conecta();
		if(!$this->existe($cedula_paciente)){

				$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
				if (!empty($patologias) && is_array($patologias)) {
					$stmt = $co->prepare("INSERT INTO padece (cedula_paciente, cod_patologia, tratamiento, administracion_t) VALUES (?, ?, ?, ?)");
					foreach ($patologias as $pat) {
						
		
							$stmt->execute([
								$cedula_paciente,
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

	
	

	function modificar($patologias = array()) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		
		try {
			// Verificar si el paciente tiene patologías registradas
			if(!$this->existe($this->cedula_paciente)) {
				$r['resultado'] = 'error';
				$r['mensaje'] = 'El paciente no tiene patologías registradas para modificar';
				return $r;
			}

			// 1. Eliminar todas las patologías actuales del paciente
			$stmtDelete = $co->prepare("DELETE FROM padece WHERE cedula_paciente = ?");
			$stmtDelete->execute([$this->cedula_paciente]);

			// 2. Insertar las nuevas patologías
			if (!empty($patologias)) {
				$stmtInsert = $co->prepare("INSERT INTO padece 
										(cedula_paciente, cod_patologia, tratamiento, administracion_t) 
										VALUES (?, ?, ?, ?)");
				
				foreach ($patologias as $pat) {
					$stmtInsert->execute([
						$this->cedula_paciente,
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
	
	function eliminar() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		
		try {
			// Verificar si el paciente tiene patologías registradas
			if($this->existe($this->cedula_paciente)) {
				// Eliminar todas las patologías del paciente
				$stmtDelete = $co->prepare("DELETE FROM padece WHERE cedula_paciente = ?");
				$stmtDelete->execute([$this->cedula_paciente]);
				
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
	
	
}

class patologias extends datos{

	private $cod_patologia;
	private $nombre_patologia;

	function set_cod_patologia($valor){
		$this->cod_patologia = $valor;
	}

	function set_nombre_patologia($valor){
		$this->nombre_patologia = $valor;
	}

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

	function incluir2() {
		$r = array();
		
		if(!$this->existe2($this->cod_patologia)) {
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			try {
				// Consulta preparada con parámetros
				$stmt = $co->prepare("INSERT INTO patologia(nombre_patologia) VALUES(:nombre)");
				
				// Vinculamos los parámetros
				$stmt->bindParam(':nombre', $this->nombre_patologia);
				
				// Ejecutamos la consulta
				$stmt->execute();
				
				$r['resultado'] = 'agregar';
				$r['mensaje'] = 'Registro Incluido';
				
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] = $e->getMessage();
			}
		} else {
			$r['resultado'] = 'agregar';
			$r['mensaje'] = 'Ya existe el Cod de Consulta';
		}
		
		return $r;
	}

	function modificar2() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		
		if($this->existe2($this->cod_patologia)) {
			try {
				// Consulta preparada con parámetros
				$stmt = $co->prepare("UPDATE patologia SET 
									nombre_patologia = :nombre
									WHERE cod_patologia = :cod");
				
				// Vinculamos los parámetros
				$stmt->bindParam(':nombre', $this->nombre_patologia);
				$stmt->bindParam(':cod', $this->cod_patologia);
				
				// Ejecutamos la consulta
				$stmt->execute();
				
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

	function eliminar2(){

		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if($this->existe2($this->cod_patologia)){
			try {
				$stmt = $co->prepare("DELETE FROM patologia WHERE cod_patologia = :cod_patologia");
				$stmt->bindParam(':cod_patologia', $this->cod_patologia);
				$stmt->execute();
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

	/* private function existe2($cod_patologia){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{

			$resultado = $co->query("SELECT * FROM patologia c WHERE c.cod_patologia = '$cod_patologia'");

			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			if($fila){

				return true;
			    
			}
			else{

				return false;
			}
			
		}catch(Exception $e){

			return false;

		}
	} */

	private function existe2($cod_patologia) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		try {
			// Consulta preparada manteniendo tu estructura original pero segura
			$stmt = $co->prepare("SELECT * FROM patologia c WHERE c.cod_patologia = :cod");
			$stmt->bindParam(':cod', $cod_patologia);
			$stmt->execute();
			
			// Manteniendo tu lógica original con fetchAll()
			$fila = $stmt->fetchAll(PDO::FETCH_BOTH);
			
			return !empty($fila); // Retorna true si hay resultados, false si no
				
		} catch(Exception $e) {
			return false;
		}
	}


}
?>