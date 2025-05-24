<?php

require_once('modelo/datos.php');


class p_cronicos extends datos{
	
	
	private $cod_cronico; 
	private $patologia_cronica;
	private $Tratamiento;
	private $admistracion_t;
	private $cedula_h;

	
	function set_cod_cronico($valor){
		$this->cod_cronico = $valor;
	}
	
	
	function set_patologia_cronica($valor){
		$this->patologia_cronica = $valor;
	}
	
	function set_Tratamiento($valor){
		$this->Tratamiento = $valor;
	}

	function set_admistracion_t($valor){
		$this->admistracion_t = $valor;
	}
	
	function set_cedula_h($valor){
		$this->cedula_h = $valor;
	}
	
	
	function get_cod_cronico(){
		return $this->cod_cronico;
	}
	
	function get_patologia_cronica(){
		return $this->patologia_cronica;
	}
	
	function get_Tratamiento(){
		return $this->Tratamiento;
	}

	function get_admistracion_t(){
		return $this->admistracion_t;
	}
	
	function get_cedula_h(){
		return $this->cedula_h;
	}


		function listadopacientes() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array(); // En este arreglo se enviará la respuesta a la solicitud
		try {
			$sql = "SELECT * FROM historias";
			$stmt = $co->prepare($sql);
			$stmt->execute();
			$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$r['resultado'] = 'listadopacientes';
			$r['datos'] = $datos ? $datos : array(); // Devuelve los datos o un arreglo vacío
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage(); // Devuelve el mensaje de error en caso de excepción
		}
		return $r;
	}



		function incluir(){
		$r = array();
		if(!$this->existe($this->cod_cronico)){
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
				$sql = "INSERT INTO p_cronicos (
							patologia_cronica,
							Tratamiento,
							admistracion_t,
							cedula_h
						) VALUES (
							:patologia_cronica,
							:Tratamiento,
							:admistracion_t,
							:cedula_h
						)";
				$stmt = $co->prepare($sql);
				$stmt->bindParam(':patologia_cronica', $this->patologia_cronica);
				$stmt->bindParam(':Tratamiento', $this->Tratamiento);
				$stmt->bindParam(':admistracion_t', $this->admistracion_t);
				$stmt->bindParam(':cedula_h', $this->cedula_h);
				$stmt->execute();
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  'Registro Incluido';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  'Un error en alguna de las cedulas';
			}
		} else {
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Ya existe el Cod de Consulta';
		}
		return $r;
	}
	
		function modificar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if($this->existe($this->cod_cronico)){
			try {
				$sql = "UPDATE p_cronicos SET 
							patologia_cronica = :patologia_cronica,
							Tratamiento = :Tratamiento,
							admistracion_t = :admistracion_t,
							cedula_h = :cedula_h
						WHERE cod_cronico = :cod_cronico";
				$stmt = $co->prepare($sql);
				$stmt->bindParam(':patologia_cronica', $this->patologia_cronica);
				$stmt->bindParam(':Tratamiento', $this->Tratamiento);
				$stmt->bindParam(':admistracion_t', $this->admistracion_t);
				$stmt->bindParam(':cedula_h', $this->cedula_h);
				$stmt->bindParam(':cod_cronico', $this->cod_cronico);
				$stmt->execute();
				$r['resultado'] = 'modificar';
				$r['mensaje'] =  'Registro Modificado';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		} else {
			$r['resultado'] = 'modificar';
			$r['mensaje'] =  'Cedula no registrada';
		}
		return $r;
	}
	
		function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if($this->existe($this->cod_cronico)){
			try {
				$sql = "DELETE FROM p_cronicos WHERE cod_cronico = :cod_cronico";
				$stmt = $co->prepare($sql);
				$stmt->bindParam(':cod_cronico', $this->cod_cronico);
				$stmt->execute();
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  'Registro Eliminado';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		} else {
			$r['resultado'] = 'eliminar';
			$r['mensaje'] =  'No existe la cedula';
		}
		return $r;
	}
	
	
	function consultar() {
    $co = $this->conecta();
    $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $r = array();
    try {
        $sql = "SELECT *, h.nombre as nombre_h, h.apellido as apellido_h  
                FROM p_cronicos p 
                INNER JOIN historias h ON p.cedula_h = h.cedula_historia";
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
	
	
	private function existe($cod_cronico){
    $co = $this->conecta();
    $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try{
        $sql = "SELECT * FROM p_cronicos WHERE cod_cronico = :cod_cronico";
        $stmt = $co->prepare($sql);
        $stmt->bindParam(':cod_cronico', $cod_cronico);
        $stmt->execute();
        $fila = $stmt->fetchAll(PDO::FETCH_BOTH);
        return !empty($fila);
    } catch(Exception $e){
        return false;
    }
}
	
	

	
	
	
}
?>