<?php

require_once('modelo/datos.php');


class consultasm extends datos{
	
	
	private $cod_consulta; 
	private $fechaconsulta;
	private $consulta;
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
		
	function set_consulta($valor){
		$this->consulta = $valor;
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

	function get_consulta(){
		return $this->consulta;
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

	function listadopersonal(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array(); // en este arreglo
			// se enviara la respuesta a la solicitud y el
			// contenido de la respuesta
		try{
			$resultado = $co->query("Select * from personal");
			$respuesta = '';
			if($resultado){
				foreach($resultado as $r){
					$respuesta = $respuesta."<tr style='cursor:pointer' onclick='colocapersonal(this);'>";
					$respuesta = $respuesta."<td style='display:none'>";
						$respuesta = $respuesta.$r['cedula_personal'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['cedula_personal'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['nombre'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['apellido'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['cargo'];
						$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."</tr>";
				}
			}
				$r['resultado'] = 'listadopersonal';
			    $r['mensaje'] =  $respuesta;
			    
			
			
		}catch(Exception $e){
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
			return $r;
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
		if(!$this->existe($this->cod_consulta)){
			
				
						
					$co = $this->conecta();
					$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				
					try {
							$co->query("Insert into consultas(
								fechaconsulta,
								consulta,
								diagnostico,
								tratamientos,
								cedula_p,
								cedula_h
								) 
								Values(					
								'$this->fechaconsulta',
								'$this->consulta',
								'$this->diagnostico',
								'$this->tratamientos',
								'$this->cedula_p',
								'$this->cedula_h'
								)");
								$r['resultado'] = 'incluir';
								$r['mensaje'] =  'Registro Inluido';
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
		if($this->existe($this->cod_consulta)){
			try {
					$co->query("Update consultas set 
					    cod_consulta = '$this->cod_consulta',
						fechaconsulta = '$this->fechaconsulta',
						consulta = '$this->consulta',
						diagnostico = '$this->diagnostico',
						tratamientos= '$this->tratamientos',
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
			$r['mensaje'] =  'Cedula no registrada';
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
			$r['mensaje'] =  'No existe la cedula';
		}
		return $r;
	}
	
	
	function consultar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
			
			$resultado = $co->query("Select *, h.nombre as nombre_h, h.apellido as apellido_h  
										from consultas c 
										inner join historias h on c.cedula_h = h.cedula_historia
										inner join personal p on c.cedula_p = p.cedula_personal");
			
			if($resultado){
				
				$respuesta = '';
				foreach($resultado as $r){
					    $respuesta = $respuesta."<tr>";
					    $respuesta = $respuesta."<td>";
						
							$respuesta = $respuesta."
							<div class='button-container' style='display: flex; justify-content: center; gap: 10px; margin-top: 10px'>
							
							<button type='button'
							class='btn btn-success' 
							onclick='pone(this,0)'
												' fechaconsulta='".$r['fechaconsulta']."'
												' consulta='".$r['consulta']."'
												' diagnostico='".$r['diagnostico']."'
												' tratamientos='".$r['tratamientos']."'
												' cedula_p='".$r['cedula_p']."'
												' cedula_h='".$r['cedula_h']."'
						    ><img src='img/lapiz.svg' style='width: 20px'></button>
							<button type='button'
							class='btn btn-danger' 
							onclick='pone(this,1)'
												' fechaconsulta='".$r['fechaconsulta']."'
												' consulta='".$r['consulta']."'
												' diagnostico='".$r['diagnostico']."'
												' tratamientos='".$r['tratamientos']."'
												' cedula_p='".$r['cedula_p']."'
												' cedula_h='".$r['cedula_h']."'
						    ><img src='img/basura.svg' style='width: 20px'></button>
							
							<button type='button'
							class='btn btn-primary' 
							onclick='pone(this,2)'
												' fechaconsulta='".$r['fechaconsulta']."'
												' consulta='".$r['consulta']."'
												' diagnostico='".$r['diagnostico']."'
												' tratamientos='".$r['tratamientos']."'
												' cedula_p='".$r['cedula_p']."'
												' cedula_h='".$r['cedula_h']."'
						    ><img src='img/ojo.svg' style='width: 20px'></button></div><br/>";
							$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td style='display:none;'>";
							$respuesta = $respuesta.$r['cod_consulta'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
						$respuesta = $respuesta.$r['nombre_h'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
						$respuesta = $respuesta.$r['apellido_h'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['fechaconsulta'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
						$respuesta = $respuesta.$r['cedula_h'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
						$respuesta = $respuesta.$r['nombre'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
						$respuesta = $respuesta.$r['apellido'];
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
			
			$resultado = $co->query("SELECT * FROM consultas c WHERE c.cod_consulta = '$cod_consulta'");
			
			
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