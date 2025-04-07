<?php

require_once('modelo/datos.php');



class personal extends datos{
	
	
	private $cedula_personal; 
	private $nombre;
	private $apellido;
	private $correo;
	private $telefono;
	private $cargo;
	private $clave;
	
	
	
	function set_cedula_personal($valor){
		$this->cedula_personal = $valor; 
		
	}
	
	
	function set_apellido($valor){
		$this->apellido = $valor;
	}
	
	function set_nombre($valor){
		$this->nombre = $valor;
	}
	
	function set_correo($valor){
		$this->correo = $valor;
	}
	
	function set_telefono($valor){
		$this->telefono = $valor;
	}
	
	function set_cargo($valor){
		$this->cargo = $valor;
	}

	function set_clave($valor){
		$this->clave = $valor;
	}
	
	
	function get_cedula_personal(){
		return $this->cedula_personal;
	}
	
	function get_apellido(){
		return $this->apellido;
	}
	
	function get_nombre(){
		return $this->nombre;
	}
	
	function get_correo(){
		return $this->correo;
	}
	
	function get_telefono(){
		return $this->telefono;
	}
	
	function get_cargo(){
		return $this->cargo;
	}

	function get_clave(){
		return $this->clave;
	}
	
	
	
	function incluir(){
		
		$r = array();
		if(!$this->existe($this->cedula_personal)){
			
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
			try {
					$co->query("Insert into personal(
						cedula_personal,
						apellido,
						nombre,
						correo,
						telefono,
						cargo,
						clave
						)
						Values(
						'$this->cedula_personal',
						'$this->apellido',
						'$this->nombre',
						'$this->correo',
						'$this->telefono',
						'$this->cargo',
						'$this->clave'
						)");
						$r['resultado'] = 'incluir';
			            $r['mensaje'] =  'Registro Incluido';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
			    $r['mensaje'] =  $e->getMessage();
			}
		}
		else{
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Ya existe la cedula';
		}
		return $r;
		
	}
	
	function modificar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if($this->existe($this->cedula_personal)){
			try {
					$co->query("Update personal set 
					    cedula_personal = '$this->cedula_personal',
						apellido = '$this->apellido',
						nombre = '$this->nombre',
						correo = '$this->correo',
						telefono = '$this->telefono',
						cargo = '$this->cargo'
						
						where
						cedula_personal = '$this->cedula_personal'
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
		if($this->existe($this->cedula_personal)){
			try {
					$co->query("delete from personal 
						where
						cedula_personal = '$this->cedula_personal'
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
	
	
	public function consultar(){
		
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("SELECT * from personal");
			
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
		private function existe($cedula_personal){
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try{
				
				$resultado = $co->query("Select * from personal where cedula_personal ='$cedula_personal'");
				
				
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