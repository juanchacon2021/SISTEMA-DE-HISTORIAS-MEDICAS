<?php

require_once('modelo/datos.php');


class emergencias extends datos{

	
	private $cod_emergencia; 
	private $horaingreso;
	private $fechaingreso;
	private $motingreso;
	private $diagnostico_e;
	private $tratamientos;
	private $cedula_p;
	private $cedula_h;

	
	function set_cod_emergencia($valor){
		$this->cod_emergencia = $valor;
	}
	
	
	function set_horaingreso($valor){
		$this->horaingreso = $valor;
	}
	
	function set_fechaingreso($valor){
		$this->fechaingreso = $valor;
	}

	function set_motingreso($valor){
		$this->motingreso = $valor;
	}

	function set_diagnostico_e($valor){
		$this->diagnostico_e = $valor;
	}

	function set_tratamientos($valor){
		$this->tratamientos = $valor;
	}
	
	function set_cedula_p($valor){
		$this->cedula_p = $valor;
	}
	
	
	function set_cedula_h($valor){
		$this->cedula_h = $valor;
	}
	
	
	function get_cod_emergencia(){
		return $this->cod_emergencia;
	}
	
	function get_horaingreso(){
		return $this->horaingreso;
	}
	
	function get_fechaingreso(){
		return $this->fechaingreso;
	}

	function get_motingreso(){
		return $this->motingreso;
	}

	function get_diagnostico_e(){
		return $this->diagnostico_e;
	}

	function get_tratamientos(){
		return $this->tratamientos;
	}

	function get_cedula_p(){
		return $this->cedula_p;
	}
	
	function get_cedula_h(){
		return $this->cedula_h;
	}

	function incluir(){
		
		$r = array();
		if(!$this->existe($this->cod_emergencia)){
		
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
			try {
					$co->query("Insert into emergencias(
						horaingreso,
						fechaingreso,
						motingreso,
						diagnostico_e,
						tratamientos,
						cedula_p,
						cedula_h
						) 
						Values(
						'$this->horaingreso',
						'$this->fechaingreso',
						'$this->motingreso',
						'$this->diagnostico_e',
						'$this->tratamientos',
						'$this->cedula_p',
						'$this->cedula_h'
						)");
						$r['resultado'] = 'incluir';
			            $r['mensaje'] =  'Registro Inluido';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
			    $r['mensaje'] =  $e->getMessage();
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
		if($this->existe($this->cod_emergencia)){
			try {
					$co->query("Update emergencias set 
					    cod_emergencia = '$this->cod_emergencia',
						horaingreso = '$this->horaingreso',
						fechaingreso = '$this->fechaingreso',
						motingreso = '$this->motingreso',
						diagnostico_e = '$this->diagnostico_e',
						tratamientos= '$this->tratamientos',
						cedula_p = '$this->cedula_p',
						cedula_h = '$this->cedula_h'
						where
						cod_emergencia = '$this->cod_emergencia'
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
		if($this->existe($this->cod_emergencia)){
			try {
					$co->query("delete from emergencias 
						where
						cod_emergencia = '$this->cod_emergencia'
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
	
	
	function consultar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
			
			$resultado = $co->query("Select * from emergencias");
			
			if($resultado){
				
				$respuesta = '';
				foreach($resultado as $r){
					    $respuesta = $respuesta."<tr>";
					    $respuesta = $respuesta."<td>";
							$respuesta = $respuesta."<button type='button'
							class='btn btn-primary w-100 small-width mb-3' 
							onclick='pone(this,0)'
						    style='background: #00AC0D'>Modificar</button><br/>";
							$respuesta = $respuesta."<button type='button'
							class='btn btn-primary w-100 small-width mt-2' 
							onclick='pone(this,1)'
						    style='background: #D30000'>Eliminar</button><br/>";
							$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['cod_emergencia'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['horaingreso'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['fechaingreso'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
						$respuesta = $respuesta.$r['motingreso'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
						$respuesta = $respuesta.$r['diagnostico_e'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
						$respuesta = $respuesta.$r['tratamientos'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
						$respuesta = $respuesta.$r['cedula_p'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['cedula_h'];
							
                        
				}
				
			    $r['resultado'] = 'consultar';
				$r['mensaje'] =  $respuesta;
			}
			else{
				$r['resultado'] = 'consultar';
				$r['mensaje'] =  '';
			}
			
		}catch(Exception $e){
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}
	
	
	private function existe($cod_emergencia){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			
			$resultado = $co->query("Select * from emergencias where cod_emergencia ='$cod_emergencia'");
			
			
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