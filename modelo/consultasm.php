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
	private $boca_abierta;
	private $boca_cerrada;
	private $oidos;
	private $cabeza_craneo;
	private $ojos;
	private $nariz;
	private $respiratorio;
	private $abdomen;
	private $extremidades_r;
	private $extremidades_s;
	private $neurologicos;
	private $general;
	private $cardiovascular;

	
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
   
   
	function set_respiratorio($valor){
		$this->respiratorio = $valor;
	}
   
	function set_abdomen($valor){
		$this->abdomen = $valor;
	}
   
	function set_extremidades_s($valor){
		$this->extremidades_s = $valor;
	}
	
	function set_extremidades_r($valor){
	   $this->extremidades_r = $valor;
   }
   
	function set_neurologicos($valor){
		$this->neurologicos = $valor;
	}
   
	function set_general($valor){
		$this->general = $valor;
	}
	function set_cardiovascular($valor){
	   $this->cardiovascular = $valor;
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

	function get_respiratorio(){
		return $this->respiratorio;
	}
   
	function get_abdomen(){
		return $this->abdomen;
	}
   
	function get_extremidades_s(){
		return $this->extremidades_s;
	}
	function get_extremidades_r(){
	   return $this->extremidades_r;
   }
   
	function get_neurologicos(){
		return $this->neurologicos;
	}
   
	function get_general(){
		return $this->general;
	}
	function get_cardiovascular(){
	   return $this->cardiovascular;
   }

   function listadopersonal() {
	$co = $this->conecta();
	$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$r = array();
	try {
		$resultado = $co->query("SELECT * FROM personal");
		if ($resultado) {
			$r['resultado'] = 'listadopersonal';
			$r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
		} else {
			$r['resultado'] = 'listadopersonal';
			$r['datos'] = array(); 
		}
	} catch (Exception $e) {
		$r['resultado'] = 'error';
		$r['mensaje'] = $e->getMessage(); 
	}
	return $r;
}

function listadopacientes() {
	$co = $this->conecta();
	$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$r = array(); 
	try {
		$resultado = $co->query("SELECT * FROM historias");
		if ($resultado) {
			$r['resultado'] = 'listadopacientes';
			$r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC); 
		} else {
			$r['resultado'] = 'listadopacientes';
			$r['datos'] = array(); 
		}
	} catch (Exception $e) {
		$r['resultado'] = 'error';
		$r['mensaje'] = $e->getMessage();
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
								cedula_h,
								boca_abierta,
								boca_cerrada,
								oidos,
								cabeza_craneo,
								ojos,
								nariz,
								respiratorio,
								abdomen,
								extremidades_r,
								extremidades_s,
								neurologicos,
								general,
								cardiovascular

								) 
								Values(					
								'$this->fechaconsulta',
								'$this->consulta',
								'$this->diagnostico',
								'$this->tratamientos',
								'$this->cedula_p',
								'$this->cedula_h',
								'$this->boca_abierta',
								'$this->boca_cerrada',
								'$this->oidos',
								'$this->cabeza_craneo',
								'$this->ojos',
								'$this->nariz',						
								'$this->respiratorio',
								'$this->abdomen',
								'$this->extremidades_r',
								'$this->extremidades_s',
								'$this->neurologicos',
								'$this->general',
								'$this->cardiovascular'
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
					    cod_consulta = '$this->cod_consulta',
						fechaconsulta = '$this->fechaconsulta',
						consulta = '$this->consulta',
						diagnostico = '$this->diagnostico',
						tratamientos= '$this->tratamientos',
						cedula_p = '$this->cedula_p',
						cedula_h = '$this->cedula_h',
						boca_abierta = '$this->boca_abierta',
						boca_cerrada = '$this->boca_cerrada',
						oidos = '$this->oidos',
						cabeza_craneo = '$this->cabeza_craneo',
						ojos = '$this->ojos',
						nariz = '$this->nariz',
						respiratorio = '$this->respiratorio',
						abdomen = '$this->abdomen',
						extremidades_r = '$this->extremidades_r',
						extremidades_s = '$this->extremidades_s',
						neurologicos = '$this->neurologicos',
						general = '$this->general',
						cardiovascular = '$this->cardiovascular'
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


	function consultar() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("SELECT *, h.nombre as nombre_h, h.apellido as apellido_h  
									FROM consultas c 
									INNER JOIN historias h ON c.cedula_h = h.cedula_historia
									INNER JOIN personal p ON c.cedula_p = p.cedula_personal");
			
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