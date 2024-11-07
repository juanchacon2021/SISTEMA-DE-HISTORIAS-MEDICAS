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
			
			$resultado = $co->query("Select *, h.nombre as nombre_h, h.apellido as apellido_h  
										from emergencias e 
										inner join historias h on e.cedula_h = h.cedula_historia
										inner join personal p on e.cedula_p = p.cedula_personal");
			
			if($resultado){
				
				$respuesta = '';
				foreach($resultado as $r){
					    $respuesta = $respuesta."<tr>";
					    $respuesta = $respuesta."<td>";
							$respuesta = $respuesta."<div class='button-containerotro' style='display: flex; justify-content: center; gap: 10px; margin-top: 10px'>
							
							<button type='button' class='btn btn-success' onclick='pone(this,0)'
												' horaingreso='".$r['horaingreso']."'
												' fechaingreso='".$r['fechaingreso']."'
												' motingreso='".$r['motingreso']."'
												' diagnostico_e='".$r['diagnostico_e']."'
												' tratamientos='".$r['tratamientos']."'
												' cedula_p='".$r['cedula_p']."'
												' cedula_h='".$r['cedula_h']."'
												' cargo='".$r['cargo']."'
												' nombre='".$r['nombre']."'
												' apellido='".$r['apellido']."'
							>
                                <img src='img/lapiz.svg' style='width: 20px'>
                            </button>";

							$respuesta = $respuesta."<button type='button'
							class='btn btn-danger' 
							onclick='pone(this,1)'
												' horaingreso='".$r['horaingreso']."'
												' fechaingreso='".$r['fechaingreso']."'
												' motingreso='".$r['motingreso']."'
												' diagnostico_e='".$r['diagnostico_e']."'
												' tratamientos='".$r['tratamientos']."'
												' cedula_p='".$r['cedula_p']."'
												' cedula_h='".$r['cedula_h']."'
												' cargo='".$r['cargo']."'
												' nombre='".$r['nombre']."'
												' apellido='".$r['apellido']."'
						    >
								<img src='img/basura.svg' style='width: 20px'>
							</button>";
							
							$respuesta = $respuesta."<button type='button'
							class='btn btn-primary' 
							onclick='pone(this,2)'
												' horaingreso='".$r['horaingreso']."'
												' fechaingreso='".$r['fechaingreso']."'
												' motingreso='".$r['motingreso']."'
												' diagnostico_e='".$r['diagnostico_e']."'
												' tratamientos='".$r['tratamientos']."'
												' cedula_p='".$r['cedula_p']."'
												' cedula_h='".$r['cedula_h']."'
												' cargo='".$r['cargo']."'
												' nombre='".$r['nombre']."'
												' apellido='".$r['apellido']."'
						    >
								<img src='img/ojo.svg' style='width: 20px'>
							</button></div><br/>";
							$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td style='display:none;'>";
							$respuesta = $respuesta.$r['cod_emergencia'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
						$respuesta = $respuesta.$r['nombre_h'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
						$respuesta = $respuesta.$r['apellido_h'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['horaingreso'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['fechaingreso'];
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
	
	
	private function existe($cod_emergencia){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			
			$resultado = $co->query("SELECT * FROM emergencias e WHERE e.cod_emergencia = '$cod_emergencia'");
			
			
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