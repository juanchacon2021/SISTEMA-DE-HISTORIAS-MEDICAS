<?php
require_once('modelo/datos.php');
class historias extends datos{
	private $cedula_historia; 
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
	
	
	
	//Lo siguiente que demos hacer es crear los metodos para incluir y consultar

	public function consultar() {
        try {
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$resultado = $co->query("SELECT * FROM historias");
            if ($resultado) {
                return [
                    'resultado' => 'consultar',
                    'datos' => $resultado
                ];
            } else {
                return [
                    'resultado' => 'consultar',
                    'datos' => []
                ];
            }
        } catch (Exception $e) {
            return [
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ];
        }
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
	
	
	
	// function obtienefecha(){
	// 	$r = array();
		
	// 		  $f = date('Y-m-d');
	// 	      $f1 = strtotime ('-18 year' , strtotime($f)); 
	// 	      $f1 = date ('Y-m-d',$f1);
	// 		  $r['resultado'] = 'obtienefecha';
	// 		  $r['mensaje'] =  $f1;
		
	// 	return $r;
	// }

	
	
	
}