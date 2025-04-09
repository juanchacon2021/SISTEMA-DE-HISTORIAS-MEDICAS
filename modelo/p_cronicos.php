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
			$resultado = $co->query("SELECT * FROM historias");
			if ($resultado) {
				$r['resultado'] = 'listadopacientes';
				$r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC); // Devuelve los datos como un arreglo asociativo
			} else {
				$r['resultado'] = 'listadopacientes';
				$r['datos'] = array(); // Devuelve un arreglo vacío si no hay resultados
			}
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
							$co->query("Insert into p_cronicos(
								patologia_cronica,
								Tratamiento,
								admistracion_t,
								cedula_h
								) 
								Values(
								'$this->patologia_cronica',
								'$this->Tratamiento',
								'$this->admistracion_t',
								'$this->cedula_h'
								)");
								$r['resultado'] = 'incluir';
								$r['mensaje'] =  'Registro Incluido';
					} catch(Exception $e) {
						$r['resultado'] = 'error';
						$r['mensaje'] =  'Un error en alguna de las cedulas';
					}
				
					
				}								
				else{
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
					$co->query("Update p_cronicos set 
					    cod_cronico = '$this->cod_cronico',
						patologia_cronica = '$this->patologia_cronica',
						Tratamiento = '$this->Tratamiento',
						admistracion_t = '$this->admistracion_t',
						cedula_h = '$this->cedula_h'
						where
						cod_cronico = '$this->cod_cronico'
						");
						$r['resultado'] = 'modificar';
			            $r['mensaje'] =  'Registro Modificado';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
			    $r['mensaje'] =  $e->getMessage();
			}
		}
		else{
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
					$co->query("delete from p_cronicos 
						where
						cod_cronico = '$this->cod_cronico'
						");
						$r['resultado'] = 'eliminar';
			            $r['mensaje'] =  'Registro Eliminado';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
			    $r['mensaje'] =  $e->getMessage();
			}
		}
		else{
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
			$resultado = $co->query("SELECT *, h.nombre as nombre_h, h.apellido as apellido_h  
									FROM p_cronicos p 
									INNER JOIN historias h ON p.cedula_h = h.cedula_historia");
			
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
	
	
	private function existe($cod_cronico){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			
			$resultado = $co->query("SELECT * FROM p_cronicos p WHERE p.cod_cronico = '$cod_cronico'");
			
			
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			if($fila){

				return true;
			    
			}
			else{
				
				return false;;
			}
			
		}catch(Exception $e){
			return false;
		}
	}
	
	

	
	
	
}
?>