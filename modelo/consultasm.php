<?php

require_once('modelo/datos.php');


class consultas extends datos{

	
	private $cod_consulta; 
	private $fechaconsulta;
	private $diagnostico;
	private $tratamientos;
	private $cedula_p;
	private $cedula_h;

	
	
	function set_cod_consulta($valor){
		$this->cod_consulta = $valor; 
		
	}
	
	
	function set_fechaconsulta($valor){
		$this->fechaconsulta = $valor;
	}
	
	function set_diagnostico($valor){
		$this->diagnostico = $valor;
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
	
	
	
	function get_cod_consulta(){
		return $this->cod_consulta;
	}
	
	function get_fechaconsulta(){
		return $this->fechaconsulta;
	}
	
	function get_diagnostico(){
		return $this->diagnostico;
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
		if(!$this->existe($this->cod_consulta)){
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
					$co->query("Insert into consultas(
						fechaconsulta,
						diagnostico,
						tratamientos,
						cedula_p,
						cedula_h
						) 
						Values(
						'$this->fechaconsulta',
						'$this->diagnostico',
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
		if($this->existe($this->cod_consulta)){
			try {
					$co->query("Update consultas set 
					    fechaconsulta = '$this->fechaconsulta',
						diagnostico = '$this->diagnostico',
						tratamientos = '$this->tratamientos',
						cedula_p = '$this->cedula_p',
						cedula_h = '$this->cedula_h'
						where
						cod_consulta = '$this->cod_consulta'
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
			$r['mensaje'] =  'cod de consulta no registrado';
		}
		return $r;
	}
	
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if($this->existe($this->cod_consulta)){
			try {
					$co->query("delete from consultas 
						where
						cod_consulta = '$this->cod_consulta'
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
			$r['mensaje'] =  'No existe el codigo de consulta';
		}
		return $r;
	}
	
	
	function consultar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
			
			$resultado = $co->query("Select * from consultas");
			
			if($resultado){
				
				$respuesta = '';
				foreach($resultado as $r){
					    $respuesta = $respuesta."<tr>";
					    $respuesta = $respuesta."<td>";
							$respuesta = $respuesta."<button type='button'
							class='btn botonazul w-100 small-width mb-3' 
							onclick='pone(this,0)'
						    >Modificar</button><br/>";
							$respuesta = $respuesta."<button type='button'
							class='btn boton w-100 small-width mt-2' 
							onclick='pone(this,1)'
						    >Eliminar</button><br/>";
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['cod_consulta'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['fechaconsulta'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['diagnostico'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
						$respuesta = $respuesta.$r['tratamientos'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['cedula_h'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['cedula_p'];
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
	
	
	private function existe($cod_consulta){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			
			$resultado = $co->query("Select * from consultas where cod_consulta ='$cod_consulta'");
			
			
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