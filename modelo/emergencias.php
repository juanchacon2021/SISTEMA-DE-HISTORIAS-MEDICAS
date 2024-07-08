<?php
//llamda al archivo que contiene la clase
//datos, en ella posteriormente se colcora el codigo
//para enlazar a su base de datos
require_once('modelo/datos.php');

//declaracion de la clase usuarios que hereda de la clase datos
//la herencia se declara con la palabra extends y no es mas 
//que decirle a esta clase que puede usar los mismos metodos
//que estan en la clase de dodne hereda (La padre) como sir fueran de el

class emergencias extends datos{
	//el primer paso dentro de la clase
	//sera declarar los atributos (variables) que describen la clase
	//para nostros no es mas que colcoar los inputs (controles) de
	//la vista como variables aca
	//cada atributo debe ser privado, es decir, ser visible solo dentro de la
	//misma clase, la forma de colcoarlo privado es usando la palabra private
	
	private $cod_emergencia; //recuerden que en php, las variables no tienen tipo predefinido
	private $horaingreso;
	private $fechaingreso;
	private $motingreso;
	private $diagnostico_e;
	private $tratamientos;
	private $cedula_p;
	private $cedula_h;

	
	//Ok ya tenemos los atributos, pero como son privados no podemos acceder a ellos desde fueran
	//por lo que debemos colcoar metodos (funciones) que me permitan leer (get) y colocar (set)
	//valores en ello, esto es  muy mal llamado geters y seters por si alguien se los pregunta
	
	function set_cod_emergencia($valor){
		$this->cod_emergencia = $valor; //fijencen como se accede a los elementos dentro de una clase
		//this que singnifica esto es decir esta clase luego -> simbolo que indica que apunte
		//a un elemento de this, es decir esta clase
		//luego el nombre del elemento sin el $
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
	
	//ahora la misma cosa pero para leer, es decir get
	
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

	//Lo siguiente que demos hacer es crear los metodos para incluir, consultar y eliminar
	
	function incluir(){
		//Ok ya tenemos la base de datos y la funcion conecta dentro de la clase
		//datos, ahora debemos ejecutar las operaciones para realizar las emergencias 
		
		//Lo primero que debemos hacer es consultar por el campo clave
		//en este caso la cedula, para ello se creo la funcion existe
		//que retorna true en caso de exitir el registro
		$r = array();
		if(!$this->existe($this->cod_emergencia)){
			//si estamos aca es porque la cedula no existe es decir se puede incluir
			//los pasos a seguir son
			//1 Se llama a la funcion conecta 
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//2 Se ejecuta el sql
			try {
					$co->query("Insert into emergencias(
						horaingreso,
						fechaingreso,
						motingreso,
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
		//Listo eso es todo y es igual para el resto de las operaciones
		//incluir, modificar y eliminar
		//solo cambia para buscar 
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
						motingreso = '$this->diagnostico_e',
						motingreso = '$this->tratamientos',
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
						    >Modificar</button><br/>";
							$respuesta = $respuesta."<button type='button'
							class='btn btn-primary w-100 small-width mt-2' 
							onclick='pone(this,1)'
						    >Eliminar</button><br/>";
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