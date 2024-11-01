<?php

require_once('modelo/datos.php');



class examenes extends datos{
	
	
	private $cod_examenes;
	private $cod_examenes1; 
	private $nombre_examen;
	private $descripcion_examen;
	private $cedula_h;
    private $cod_registro;
	private $fecha_r;
	private $observacion_examen;
	
	
	function set_cod_examenes($valor){
		$this->cod_examenes = $valor; 
	}
	function set_cod_examenes1($valor){
		$this->cod_examenes1 = $valor; 
	}
	
	function set_nombre_examen($valor){
		$this->nombre_examen = $valor;
	}
	
	function set_descripcion_examen($valor){
		$this->descripcion_examen = $valor;
	}
	
	function set_cedula_h($valor){
		$this->cedula_h = $valor;
	}
	function set_cod_registro($valor){
		$this->cod_registro = $valor; 
	}
	function set_fecha_r($valor){
		$this->fecha_r = $valor; 
	}
	function set_observacion_examen($valor){
		$this->observacion_examen = $valor; 
	}
	
	
	function get_cod_examenes(){
		return $this->cod_examenes;
	}
	function get_cod_examenes1(){
		return $this->cod_examenes1;
	}
	
	function get_nombre_examen(){
		return $this->nombre_examen;
	}
	
	function get_descripcion_examen(){
		return $this->descripcion_examen;
	}
	
	function get_cedula_h(){
		return $this->cedula_h;
	}

	function get_cod_registro(){
		return $this->cod_registro;
	}
	function get_fecha_r(){
		return $this->fecha_r;
	}
	function get_observacion_examenes(){
		return $this->observacion_examen;
	}
	

	function listadopacientes(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array(); // en este arreglo
			// se enviara la respuesta a la solicitud y el
			// contenido de la respuesta
		try{
			$resultado = $co->query("Select * from historias");
			$respuesta = '';
			if($resultado){
				foreach($resultado as $r){
					$respuesta = $respuesta."<tr style='cursor:pointer' onclick='colocapacientes(this);'>";
					$respuesta = $respuesta."<td style='display:none'>";
						$respuesta = $respuesta.$r['cedula_historia'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['cedula_historia'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['nombre'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['apellido'];
						$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."</tr>";
				}
			}
				$r['resultado'] = 'listadopacientes';
			    $r['mensaje'] =  $respuesta;
			    
			
			
		}catch(Exception $e){
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
			return $r;
	}
	 
	
	function incluir(){
		
		$r = array();
		if(!$this->existe($this->cod_examenes)){
			 
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//2 Se ejecuta el sql
			try {
					$co->query("Insert into examenes(
						
						nombre_examen,
						descripcion_examen
						) 
						Values(
						
						'$this->nombre_examen',
						'$this->descripcion_examen'
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
			$r['mensaje'] =  'Ya existe el COD de Examen';
		}
		return $r;
		
	}
	function incluir1(){
		
		$r = array();
		if(!$this->existe1($this->cod_registro)){
			 
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			try {
					$co->query("Insert into registro(
						
						fecha_r,
						cedula_h,
						cod_examenes,
						observacion_examen

						) 
						Values(
						
						'$this->fecha_r',
						'$this->cedula_h',
						'$this->cod_examenes1',
						'$this->observacion_examen'
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
			$r['mensaje'] =  'Ya existe el COD de Registro';
		}
		return $r;
		
	}
	
	function modificar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if($this->existe1($this->cod_registro)){
			try {
					$co->query("Update registro set 
					    cod_registro = '$this->cod_registro',
						fecha_r = '$this->fecha_r',					
						cedula_h = '$this->cedula_h',
						cod_examenes = '$this->cod_examenes1',
						observacion_examen = '$this->observacion_examen'
						where
						cod_registro = '$this->cod_registro'
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
			$r['mensaje'] =  'Examen no registrado';
		}
		return $r;
	}
	
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if($this->existe1($this->cod_registro)){
			try {
					$co->query("delete from registro 
						where
						cod_registro = '$this->cod_registro'
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
			$r['mensaje'] =  'No existe el registro';
			
		}
		return $r;
	}
	
	
	function consultar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
			
			$resultado = $co->query("Select * from registro");
			
			if($resultado){
				
				$respuesta = '';
				foreach($resultado as $r){
					    $respuesta = $respuesta."<tr>";
					    $respuesta = $respuesta."<td>";
						$respuesta = $respuesta."<div class='button-container' style='display: flex; justify-content: center; gap: 10px; margin-top: 10px'>
                        
						<button type='button' class='btn btn-success' onclick='pone(this,0)'>
							<img src='img/lapiz.svg' style='width: 20px'>
						</button>";
						$respuesta = $respuesta."<a class='btn btn-danger' onclick='pone(this,1)'>
							<img src='img/trash-can-solid.svg' style='width: 20px;'>
						</a>";
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['cod_registro'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['fecha_r'];
						
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['observacion_examen'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
								$respuesta = $respuesta.$r['cedula_h'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
								$respuesta = $respuesta.$r['cod_examenes'];
                        
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
	
	
	private function existe($cod_examenes){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			
			$resultado = $co->query("Select * from examenes where cod_examenes ='$cod_examenes'");
			
			
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

	private function existe1($cod_registro){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			
			$resultado = $co->query("Select * from registro where cod_registro ='$cod_registro'");
			
			
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
	
	
	
	/*	function obtienefecha(){
		$r = array();
		
			  $f = date('Y-m-d');
		      $f1 = strtotime ('-18 year' , strtotime($f)); 
		      $f1 = date ('Y-m-d',$f1);
			  $r['resultado'] = 'obtienefecha';
			  $r['mensaje'] =  $f1;
		
		return $r;
	}*/

	
	
	
}
?>