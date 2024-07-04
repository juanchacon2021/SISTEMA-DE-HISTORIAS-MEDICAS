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
	
	private $cedula_historia; //recuerden que en php, las variables no tienen tipo predefinido
	private $apellido;
	private $nombre;
	private $fecha_nac;
	private $edad;
	private $telefono;
	private $estadocivi;
	private $direccion;
	private $ocupacion;
	private $hda;
	private $habtoxico;
	private $alergias;
	private $quirurgico;
	private $transsanguineo;
	private $boca_abierta;
	private $boca_cerrada;
	private $oidos;
	private $cabeza_craneo;
	private $ojos;
	private $nariz;
	private $tiroides;
	private $cardiovascular;
	private $respiratorio;
	private $abdomen;
	private $extremidades;
	private $neurologico;
	//Ok ya tenemos los atributos, pero como son privados no podemos acceder a ellos desde fueran
	//por lo que debemos colcoar metodos (funciones) que me permitan leer (get) y colocar (set)
	//valores en ello, esto es  muy mal llamado geters y seters por si alguien se los pregunta
	
	function set_cedula_historia($valor){
		$this->cedula_historia = $valor; //fijencen como se accede a los elementos dentro de una clase
		//this que singnifica esto es decir esta clase luego -> simbolo que indica que apunte
		//a un elemento de this, es decir esta clase
		//luego el nombre del elemento sin el $
	}
	//lo mismo que se hizo para cedula se hace para usuario y clave
	
	function set_apellido($valor){
		$this->apellido = $valor;
	}
	
	function set_nombre($valor){
		$this->nombre = $valor;
	}
	
	function set_fecha_nac($valor){
		$this->fecha_nac = $valor;
	}
	
	function set_edad($valor){
		$this->edad = $valor;
	}
	
	function set_telefono($valor){
		$this->telefono = $valor;
	}
	
	function set_direccion($valor){
		$this->direccion = $valor;
	}
	
	function set_estadocivi($valor){
		$this->estadocivi = $valor;
	}
	
	function set_ocupacion($valor){
		$this->ocupacion = $valor;
	}
	
	function set_hda($valor){
		$this->hda = $valor;
	}
	
	function set_habtoxico($valor){
		$this->habtoxico = $valor;
	}
	
	function set_alergias($valor){
		$this->alergias = $valor;
	}
	
	function set_quirurgico($valor){
		$this->quirurgico = $valor;
	}
	
	function set_transsanguineo($valor){
		$this->transsanguineo = $valor;
	}
	
	function set_boca_abierta($valor){
		$this->boca_abierta = $valor;
	}
	
	function set_boca_cerrada($valor){
		$this->boca_cerrada = $valor;
	}
	
	function set_oidos($valor){
		$this->oidos = $valor;
	}
	
	function set_cabeza_craneo($valor){
		$this->cabeza_craneo = $valor;
	}
	
	function set_ojos($valor){
		$this->ojos = $valor;
	}
	
	function set_nariz($valor){
		$this->nariz = $valor;
	}
	
	function set_tiroides($valor){
		$this->tiroides = $valor;
	}
	
	function set_cardiovascular($valor){
		$this->cardiovascular = $valor;
	}
	
	function set_respiratorio($valor){
		$this->respiratorio = $valor;
	}
	
	function set_abdomen($valor){
		$this->abdomen = $valor;
	}
	
	function set_extremidades($valor){
		$this->extremidades = $valor;
	}
	
	function set_neurologico($valor){
		$this->neurologico = $valor;
	}
	
	//ahora la misma cosa pero para leer, es decir get
	
	function get_cedula_historia(){
		return $this->cedula_historia;
	}
	
	function get_apellido(){
		return $this->apellido;
	}
	
	function get_nombre(){
		return $this->nombre;
	}
	
	function get_fecha_nac(){
		return $this->fecha_nac;
	}
	
	function get_edad(){
		return $this->edad;
	}
	
	function get_telefono(){
		return $this->telefono;
	}
	
	function get_estadocivi(){
		return $this->estadocivi;
	}
	
	function get_direccion(){
		return $this->direccion;
	}
	
	function get_ocupacion(){
		return $this->ocupacion;
	}
	
	function get_hda(){
		return $this->hda;
	}
	
	function get_habtoxico(){
		return $this->habtoxico;
	}
	
	function get_alergias(){
		return $this->alergias;
	}
	
	function get_quirurgico(){
		return $this->quirurgico;
	}
	
	function get_transsanguineo(){
		return $this->transsanguineo;
	}
	
	function get_boca_abierta(){
		return $this->boca_abierta;
	}
	
	function get_boca_cerrada(){
		return $this->boca_cerrada;
	}
	
	function get_oidos(){
		return $this->oidos;
	}
	
	function get_cabeza_craneo(){
		return $this->cabeza_craneo;
	}
	
	function get_ojos(){
		return $this->ojos;
	}
	
	function get_nariz(){
		return $this->nariz;
	}
	
	function get_tiroides(){
		return $this->tiroides;
	}
	
	function get_cardiovascular(){
		return $this->cardiovascular;
	}
	
	function get_respiratorio(){
		return $this->respiratorio;
	}
	
	function get_abdomen(){
		return $this->abdomen;
	}
	
	function get_extremidades(){
		return $this->extremidades;
	}
	
	function get_neurologico(){
		return $this->neurologico;
	}
	
	//Lo siguiente que demos hacer es crear los metodos para incluir, consultar y eliminar
	
	function incluir(){
		//Ok ya tenemos la base de datos y la funcion conecta dentro de la clase
		//datos, ahora debemos ejecutar las operaciones para realizar las consultas 
		
		//Lo primero que debemos hacer es consultar por el campo clave
		//en este caso la cedula, para ello se creo la funcion existe
		//que retorna true en caso de exitir el registro
		
		if(!$this->existe($this->cedula_historia)){
			//si estamos aca es porque la cedula no existe es decir se puede incluir
			//los pasos a seguir son
			//1 Se llama a la funcion conecta 
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//2 Se ejecuta el sql
			$r = array();
			try {
				
					$p = $co->prepare("Insert into historias(
						cedula_historia,
						apellido,
						nombre,
						fecha_nac,
						edad,
						telefono,
						estadocivi,
						direccion,
						ocupacion,
						hda,
						habtoxico,
						alergias,
						quirurgico,
						transsanguineo,
						boca_abierta,
						boca_cerrada,
						oidos,
						cabeza_craneo,
						ojos,
						nariz,
						tiroides,
						cardiovascular,
						respiratorio,
						abdomen,
						extremidades,
						neurologico
						)
						Values(
						:cedula_historia,
						:apellido,
						:nombre,
						:fecha_nac,
						:edad,
						:telefono,
						:estadocivi,
						:direccion,
						:ocupacion,
						:hda,
						:habtoxico,
						:alergias,
						:quirurgico,
						:transsanguineo,
						:boca_abierta,
						:boca_cerrada,
						:oidos,
						:cabeza_craneo,
						:ojos,
						:nariz,
						:tiroides,
						:cardiovascular,
						:respiratorio,
						:abdomen,
						:extremidades,
						:neurologico
						)");
					$p->bindParam(':cedula_historia',$this->cedula_historia);		
					$p->bindParam(':apellido',$this->apellido);
					$p->bindParam(':nombre',$this->nombre);	
					$p->bindParam(':fecha_nac',$this->fecha_nac);
					$p->bindParam(':edad',$this->edad);		
					$p->bindParam(':telefono',$this->telefono);
					$p->bindParam(':estadocivi',$this->estadocivi);
					$p->bindParam(':direccion',$this->direccion);	
					$p->bindParam(':ocupacion',$this->ocupacion);
					$p->bindParam(':hda',$this->hda);		
					$p->bindParam(':habtoxico',$this->habtoxico);
					$p->bindParam(':alergias',$this->alergias);
					$p->bindParam(':quirurgico',$this->quirurgico);	
					$p->bindParam(':transsanguineo',$this->transsanguineo);
					$p->bindParam(':boca_abierta',$this->boca_abierta);		
					$p->bindParam(':boca_cerrada',$this->boca_cerrada);
					$p->bindParam(':oidos',$this->oidos);
					$p->bindParam(':cabeza_craneo',$this->cabeza_craneo);	
					$p->bindParam(':ojos',$this->ojos);
					$p->bindParam(':nariz',$this->nariz);		
					$p->bindParam(':tiroides',$this->tiroides);
					$p->bindParam(':cardiovascular',$this->cardiovascular);
					$p->bindParam(':respiratorio',$this->respiratorio);
					$p->bindParam(':abdomen',$this->abdomen);		
					$p->bindParam(':extremidades',$this->extremidades);
					$p->bindParam(':neurologico',$this->neurologico);
					
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
		if($this->existe($this->cedula_historia)){
			try {
				/**
				$aux = "Update personas set 
						apellido = $this->apellido,
						nombre = $this->nombre,
						fecha_nac = $this->fecha_nac,
						edad = $this->edad,
						telefono = $this->telefono
						where
						cedula = $this->cedula";
				echo $aux;
				exit;
				**/
				$p = $co->prepare("Update historias set 
						apellido = :apellido,
						nombre = :nombre,
						fecha_nac = :fecha_nac,
						edad = :edad,
						telefono = :telefono,
						estadocivi = :estadocivi
						direccion = :direccion,
						ocupacion = :ocupacion,
						hda = :hda,
						habtoxico = :habtoxico,
						alergias = :alergias,
						quirurgico = :quirurgico,
						transsanguineo = :transsanguineo,
						boca_abierta = :boca_abierta,
						boca_cerrada = :boca_cerrada,
						oidos = :oidos,
						cabeza_craneo = :cabeza_craneo,
						ojos = :ojos,
						nariz = :nariz,
						tiroides = :tiroides,
						cardiovascular = :cardiovascular,
						respiratorio = :respiratorio,
						abdomen = :abdomen,
						extremidades = :extremidades,
						neurologico = :neurologico
						where
						cedula_historia = :cedula_historia
						");
					$p->bindParam(':cedula_historia',$this->cedula_historia);		
					$p->bindParam(':apellido',$this->apellido);
					$p->bindParam(':nombre',$this->nombre);	
					$p->bindParam(':fecha_nac',$this->fecha_nac);
					$p->bindParam(':edad',$this->edad);		
					$p->bindParam(':telefono',$this->telefono);
					$p->bindParam(':estadocivi',$this->estadocivi);
					$p->bindParam(':direccion',$this->direccion);	
					$p->bindParam(':ocupacion',$this->ocupacion);
					$p->bindParam(':hda',$this->hda);		
					$p->bindParam(':habtoxico',$this->habtoxico);
					$p->bindParam(':alergias',$this->alergias);
					$p->bindParam(':quirurgico',$this->quirurgico);	
					$p->bindParam(':transsanguineo',$this->transsanguineo);
					$p->bindParam(':boca_abierta',$this->boca_abierta);		
					$p->bindParam(':boca_cerrada',$this->boca_cerrada);
					$p->bindParam(':oidos',$this->oidos);
					$p->bindParam(':cabeza_craneo',$this->cabeza_craneo);	
					$p->bindParam(':ojos',$this->ojos);
					$p->bindParam(':nariz',$this->nariz);		
					$p->bindParam(':tiroides',$this->tiroides);
					$p->bindParam(':cardiovascular',$this->cardiovascular);
					$p->bindParam(':respiratorio',$this->respiratorio);
					$p->bindParam(':abdomen',$this->abdomen);		
					$p->bindParam(':extremidades',$this->extremidades);
					$p->bindParam(':neurologico',$this->neurologico);
					
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
		if($this->existe($this->cedula_historia)){
			try {
					$p = $co->prepare("delete from historias 
					    where
						cedula_historia = :cedula_historia
						");
					$p->bindParam(':cedula_historia',$this->cedula_historia);		
					
					
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
			
			$resultado = $co->query("Select * from historias");
			
			if($resultado){
				
				$respuesta = '';
				foreach($resultado as $r){
					$respuesta = $respuesta."<tr style='cursor:pointer' onclick='coloca(this);'>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['cedula_historia'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['apellido'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['nombre'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['fecha_nac'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['edad'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['telefono'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['estadocivi'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['direccion'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['ocupacion'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['hda'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['habtoxico'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['alergias'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['quirurgico'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['transsanguineo'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."</td>";
							$respuesta = $respuesta.$r['boca_abierta'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['boca_cerrada'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['oidos'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['cabeza_craneo'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['ojos'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['nariz'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['tiroides'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['cardiovascular'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['respiratorio'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['abdomen'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['extremidades'];
						$respuesta = $respuesta."</td>";
						$respuesta = $respuesta."<td>";
							$respuesta = $respuesta.$r['neurologico'];
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
	
	
	private function existe($cedula_historia){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			
			$resultado = $co->query("Select * from historias where cedula_historia='$cedula_historia'");
			
			
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
			
			$resultado = $co->query("Select * from historias where cedula_historia='$this->cedula_historia'");
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