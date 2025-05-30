<?php
require_once('modelo/datos.php');
class historias extends datos{
	private $conexion;
	private $cedula_historia;
	private $apellido;
	private $nombre;
	private $fecha_nac;
	private $edad;
	private $telefono;
	private $estadocivil;
	private $direccion;
	private $ocupacion;
	private $hda;
	private $habtoxico;
	private $alergias;
	private $alergias_med;
	private $quirurgico;
	private $transsanguineo;
	private $psicosocial;

	private $antc_madre;
	private $antc_padre;
	private $antc_hermano;

	 function __construct() {
		$this->conexion = $this->conecta(); 
	}
	function set_cedula_historia($valor){
		$this->cedula_historia = $valor; 
	}
	
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
	
	function set_estadocivil($valor){
		$this->estadocivil = $valor;
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
	
	function set_alergias_med($valor){
		$this->alergias_med = $valor;
	}
	
	function set_quirurgico($valor){
		$this->quirurgico = $valor;
	}
	
	function set_transsanguineo($valor){
		$this->transsanguineo = $valor;
	}
	
	function set_psicosocial($valor){
		$this->psicosocial = $valor;
	}
	

	function set_antc_madre($valor){
		$this->antc_madre = $valor;
	}
	
	function set_antc_padre($valor){
		$this->antc_padre = $valor;
	}
	
	function set_antc_hermano($valor){
		$this->antc_hermano = $valor;
	}
	
	
	
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
	
	function get_estadocivil(){
		return $this->estadocivil;
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
	
	function get_alergias_med($valor){
		$this->alergias_med = $valor;
	}
	
	function get_quirurgico($valor){
		$this->quirurgico = $valor;
	}
	
	function get_transsanguineo($valor){
		$this->transsanguineo = $valor;
	}
	
	function get_psicosocial($valor){
		$this->psicosocial = $valor;
	}

	
	function get_antc_madre($valor){
		$this->antc_madre = $valor;
	}
	
	function get_antc_padre($valor){
		$this->antc_padre = $valor;
	}
	
	function get_antc_hermano($valor){
		$this->antc_hermano = $valor;
	}
	
	function incluir(){
		$r = array();
		if(!$this->existe($this->cedula_historia)){
		  	$co = $this->conecta();
		  	$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		  	try {
				$co->beginTransaction();
			
				$stmt1 = $co->prepare("INSERT INTO historias(
					cedula_historia, apellido, nombre, fecha_nac, edad, telefono, estadocivil, direccion, ocupacion, hda, habtoxico,
					alergias, quirurgico, transsanguineo, alergias_med, psicosocial, antc_madre, antc_padre, antc_hermano
				) VALUES (
					:cedula_historia, :apellido, :nombre, :fecha_nac, :edad, :telefono, :estadocivil, :direccion, :ocupacion,
					:hda, :habtoxico, :alergias, :quirurgico, :transsanguineo, :alergias_med, :psicosocial, :antc_madre, :antc_padre, :antc_hermano
				)");
			
				$stmt1->execute([
					':cedula_historia' => $this->cedula_historia,
					':apellido' => $this->apellido,
					':nombre' => $this->nombre,
					':fecha_nac' => $this->fecha_nac,
					':edad' => $this->edad,
					':telefono' => $this->telefono,
					':estadocivil' => $this->estadocivil,
					':direccion' => $this->direccion,
					':ocupacion' => $this->ocupacion,
					':hda' => $this->hda,
					':habtoxico' => $this->habtoxico,
					':alergias' => $this->alergias,
					':quirurgico' => $this->quirurgico,
					':transsanguineo' => $this->transsanguineo,
					':alergias_med' => $this->alergias_med,
					':psicosocial' => $this->psicosocial,
					':antc_madre' => $this->antc_madre,
					':antc_padre' => $this->antc_padre,
					':antc_hermano' => $this->antc_hermano
				]);
			
			
				$co->commit();

				return ["resultado" => "incluir", "mensaje" => "Registro Incluido"];
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
		if($this->existe($this->cedula_historia)){
			try {
					$co->query("UPDATE historias SET
						cedula_historia = '$this->cedula_historia',
						apellido = '$this->apellido',
						nombre = '$this->nombre',
						fecha_nac = '$this->fecha_nac',
						edad = '$this->edad',
						telefono = '$this->telefono',
						estadocivil = '$this->estadocivil',
						direccion = '$this->direccion',
						ocupacion = '$this->ocupacion',
						hda = '$this->hda',
						habtoxico = '$this->habtoxico',
						alergias = '$this->alergias',
						quirurgico = '$this->quirurgico',
						transsanguineo = '$this->transsanguineo',
						alergias_med = '$this->alergias_med',
						psicosocial = '$this->psicosocial',
						antc_madre = '$this->antc_madre',
						antc_padre = '$this->antc_padre',
						antc_hermano = '$this->antc_hermano'	
						WHERE
						cedula_historia = '$this->cedula_historia'
						");
						return ["resultado" => "modificar", "mensaje" => "Registro Modificado"];
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


	function consultar() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("SELECT * FROM historias");
			
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

	private function existe($cedula_historia){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			$resultado = $co->query("SELECT * FROM historias WHERE cedula_historia='$cedula_historia'");
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