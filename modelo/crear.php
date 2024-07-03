<?php
//llamda al archivo que contiene la clase
//datos, en ella posteriormente se colcora el codigo
//para enlazar a su base de datos
require_once('modelo/datos.php');

//declaracion de la clase usuarios que hereda de la clase datos
//la herencia se declara con la palabra extends y no es mas 
//que decirle a esta clase que puede usar los mismos metodos
//que estan en la clase de dodne hereda (La padre) como sir fueran de el

class crear extends datos{
	//el primer paso dentro de la clase
	//sera declarar los atributos (variables) que describen la clase
	//para nostros no es mas que colcoar los inputs (controles) de
	//la vista como variables aca
	//cada atributo debe ser privado, es decir, ser visible solo dentro de la
	//misma clase, la forma de colcoarlo privado es usando la palabra private
	
	private $cedula; //recuerden que en php, las variables no tienen tipo predefinido
	private $apellidos;
	private $nombres;
	private $fechadenacimiento;
	private $sexo;
	private $gradodeinstruccion;
	
	//Ok ya tenemos los atributos, pero como son privados no podemos acceder a ellos desde fueran
	//por lo que debemos colcoar metodos (funciones) que me permitan leer (get) y colocar (set)
	//valores en ello, esto es  muy mal llamado geters y seters por si alguien se los pregunta
	
	function set_cedula($valor){
		$this->cedula = $valor; //fijencen como se accede a los elementos dentro de una clase
		//this que singnifica esto es decir esta clase luego -> simbolo que indica que apunte
		//a un elemento de this, es decir esta clase
		//luego el nombre del elemento sin el $
	}
	//lo mismo que se hizo para cedula se hace para usuario y clave
	
	function set_apellidos($valor){
		$this->apellidos = $valor;
	}
	
	function set_nombres($valor){
		$this->nombres = $valor;
	}
	
	function set_fechadenacimiento($valor){
		$this->fechadenacimiento = $valor;
	}
	
	function set_sexo($valor){
		$this->sexo = $valor;
	}
	
	function set_gradodeinstruccion($valor){
		$this->gradodeinstruccion = $valor;
	}
	
	//ahora la misma cosa pero para leer, es decir get
	
	function get_cedula(){
		return $this->cedula;
	}
	
	function get_apellidos(){
		return $this->apellidos;
	}
	
	function get_nombres(){
		return $this->nombres;
	}
	
	function get_fechadenacimiento(){
		return $this->fechanacimiento;
	}
	
	function get_sexo(){
		return $this->sexo;
	}
	
	function get_gradodeinstruccion(){
		return $this->gradodeinstruccion;
	}
	
	//Lo siguiente que demos hacer es crear los metodos para incluir, consultar y eliminar
	
	function incluir(){
		//Ok ya tenemos la base de datos y la funcion conecta dentro de la clase
		//datos, ahora debemos ejecutar las operaciones para realizar las consultas 
		
		//Lo primero que debemos hacer es consultar por el campo clave
		//en este caso la cedula, para ello se creo la funcion existe
		//que retorna true en caso de exitir el registro
		
		if(!$this->existe($this->cedula)){
			//si estamos aca es porque la cedula no existe es decir se puede incluir
			//los pasos a seguir son
			//1 Se llama a la funcion conecta 
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//2 Se ejecuta el sql
			$r = array();
			try {
				
					$p = $co->prepare("Insert into personas(
						cedula,
						apellidos,
						nombres,
						fechadenacimiento,
						sexo,
						gradodeinstruccion
						)
						Values(
						:cedula,
						:apellidos,
						:nombres,
						:fechadenacimiento,
						:sexo,
						:gradodeinstruccion
						)");
					$p->bindParam(':cedula',$this->cedula);		
					$p->bindParam(':apellidos',$this->apellidos);
					$p->bindParam(':nombres',$this->nombres);	
					$p->bindParam(':fechadenacimiento',$this->fechadenacimiento);
					$p->bindParam(':sexo',$this->sexo);		
					$p->bindParam(':gradodeinstruccion',$this->gradodeinstruccion);
					
					$p->execute();
					
						$r['resultado'] = 'incluir';
			            $r['mensaje'] =  'Registro Inluido';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
			    $r['mensaje'] =  $e->getMessage();
			}
		}
		else{
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Ya existe la cedula';
		}
		
		//Listo eso es todo y es igual para el resto de las operaciones
		//incluir, modificar y eliminar
		//solo cambia para buscar 
		return $r;
		
	}
	
	function modificar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if($this->existe($this->cedula)){
			try {
				/**
				$aux = "Update personas set 
						apellidos = $this->apellidos,
						nombres = $this->nombres,
						fechadenacimiento = $this->fechadenacimiento,
						sexo = $this->sexo,
						gradodeinstruccion = $this->gradodeinstruccion
						where
						cedula = $this->cedula";
				echo $aux;
				exit;
				**/
				$p = $co->prepare("Update personas set 
						apellidos = :apellidos,
						nombres = :nombres,
						fechadenacimiento = :fechadenacimiento,
						sexo = :sexo,
						gradodeinstruccion = :gradodeinstruccion
						where
						cedula = :cedula
						");
					$p->bindParam(':cedula',$this->cedula);		
					$p->bindParam(':apellidos',$this->apellidos);
					$p->bindParam(':nombres',$this->nombres);	
					$p->bindParam(':fechadenacimiento',$this->fechadenacimiento);
					$p->bindParam(':sexo',$this->sexo);		
					$p->bindParam(':gradodeinstruccion',$this->gradodeinstruccion);
					
					$p->execute();
					
						$r['resultado'] = 'modificar';
			            $r['mensaje'] =  'Registro Modificado';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
			    $r['mensaje'] =  $e->getMessage();
			}
		}
		else{
			$r['resultado'] = 'modificar';
			    $r['mensaje'] =  'No existe la cedula';
		}
		return $r;
	}
	
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if($this->existe($this->cedula)){
			try {
					$p = $co->prepare("delete from personas 
					    where
						cedula = :cedula
						");
					$p->bindParam(':cedula',$this->cedula);		
					
					
					$p->execute();
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
			
			$resultado = $co->query("Select * from personas");
			
			if($resultado){
				
				$respuesta = '';
				foreach($resultado as $r){
					$respuesta = $respuesta."<tr style='cursor:pointer' onclick='coloca(this);'>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['cedula'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['apellidos'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['nombres'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['fechadenacimiento'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['sexo'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['gradodeinstruccion'];
						$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."</tr>";
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
	
	
	private function existe($cedula){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			
			$resultado = $co->query("Select * from personas where cedula='$cedula'");
			
			
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
	
	function consultatr(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
			
			$resultado = $co->query("Select * from personas where cedula='$this->cedula'");
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			if($fila){
			    
				$r['resultado'] = 'encontro';
			    $r['mensaje'] =  $fila;
				
			    
			}
			else{
				
				$r['resultado'] = 'noencontro';
				$r['mensaje'] =  '';
				
			}
			
		}catch(Exception $e){
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
		
	}
	
	function obtienefecha(){
		$r = array();
		
			  $f = date('Y-m-d');
		      $f1 = strtotime ('-18 year' , strtotime($f)); 
		      $f1 = date ('Y-m-d',$f1);
			  $r['resultado'] = 'obtienefecha';
			  $r['mensaje'] =  $f1;
		
		return $r;
	}

	
	
	
}
?>