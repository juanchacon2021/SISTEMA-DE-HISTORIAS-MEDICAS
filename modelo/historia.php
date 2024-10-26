<?php
require_once('modelo/datos.php');

class historias extends datos{
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

	// ANTECEDENTES
	private $antec_madre;
	private $antec_padre;
	private $antec_hermano;
	private $cedula_h;

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
	 private $extremidades_s;
	 private $neurologicos;
	 private $general;
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
	
	// ANTECEDENTES

	function set_antec_madre($valor){
		$this->antec_madre = $valor;
	}
	
	function set_antec_padre($valor){
		$this->antec_padre = $valor;
	}
	
	function set_antec_hermano($valor){
		$this->antec_hermano = $valor;
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
	
	 function set_extremidades_s($valor){
	 	$this->extremidades_s = $valor;
	 }
	
	 function set_neurologicos($valor){
	 	$this->neurologicos = $valor;
	 }
	
	 function set_general($valor){
	 	$this->general = $valor;
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

	// ANTECEDENTES	
	
	function get_antec_madre($valor){
		$this->antec_madre = $valor;
	}
	
	function get_antec_padre($valor){
		$this->antec_padre = $valor;
	}
	
	function get_antec_hermano($valor){
		$this->antec_hermano = $valor;
	}
	
	function get_cedula_h($valor){
		$this->cedula_h = $valor;
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
	
	 function get_extremidades_s(){
	 	return $this->extremidades_s;
	 }
	
	 function get_neurologicos(){
	 	return $this->neurologicos;
	 }
	
	 function get_general(){
	 	return $this->general;
	 }
	
	
	
	function incluir(){
		$r = array();
		if(!$this->existe($this->cedula_historia)){
		  	$co = $this->conecta();
		  	$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		  	try {
				// Aqui inicia la transaccion
				$co->beginTransaction();
			
				// Aqui hice la primera consulta INSERT
				$stmt1 = $co->prepare("INSERT INTO historias(
					cedula_historia, apellido, nombre, fecha_nac, edad, telefono, estadocivil, direccion, ocupacion, hda, habtoxico,
					alergias, quirurgico, transsanguineo, alergias_med, psicosocial
				) VALUES (
					:cedula_historia, :apellido, :nombre, :fecha_nac, :edad, :telefono, :estadocivil, :direccion, :ocupacion,
					:hda, :habtoxico, :alergias, :quirurgico, :transsanguineo, :alergias_med, :psicosocial
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
					':psicosocial' => $this->psicosocial
				]);
			
				// Aqui hice la segunda consulta INSERT	
				$stmt2 = $co->prepare("INSERT INTO antecedentes(
					cedula_h, antec_madre, antec_padre, antec_hermano
				) VALUES (
					:cedula_h, :antec_madre, :antec_padre, :antec_hermano
				)");
			
				$stmt2->execute([
					':cedula_h' => $this->cedula_historia,
					':antec_madre' => $this->antec_madre,
					':antec_padre' => $this->antec_padre,
					':antec_hermano' => $this->antec_hermano
				]);

				// Aqui hice la tercera consulta INSERT	
				$stmt3 = $co->prepare("INSERT INTO examenes_r(
					cedula_h, cabeza_craneo, ojos, nariz, tiroides, extremidades, boca_abierta, boca_cerrada, oidos
				) VALUES (
					:cedula_h, :cabeza_craneo, :ojos, :nariz, :tiroides, :extremidades, :boca_abierta, :boca_cerrada, :oidos
				)");
			
				$stmt3->execute([
					':cedula_h' => $this->cedula_historia,
					':cabeza_craneo' => $this->cabeza_craneo,
					':ojos' => $this->ojos,
					':nariz' => $this->nariz,
					':tiroides' => $this->tiroides,
					':extremidades' => $this->extremidades,
					':boca_abierta' => $this->boca_abierta,
					':boca_cerrada' => $this->boca_cerrada,
					':oidos' => $this->oidos
				]);

				// Aqui hice la cuarta consulta INSERT	
				$stmt4 = $co->prepare("INSERT INTO examenes_s(
					cedula_h, respiratorio, cardiovascular, abdomen, neurologicos, extremidades_s
				) VALUES (
					:cedula_h, :respiratorio, :cardiovascular, :abdomen, :neurologicos, :extremidades_s
				)");
				$stmt4->execute([
					':cedula_h' => $this->cedula_historia,
					':respiratorio' => $this->respiratorio,
					':cardiovascular' => $this->cardiovascular,
					':abdomen' => $this->abdomen,
					':neurologicos' => $this->neurologicos,
					':extremidades_s' => $this->extremidades
				]);

				// Aqui hice la quinta consulta INSERT	
				$stmt4 = $co->prepare("INSERT INTO examenes_f(
					cedula_h, general
				) VALUES (
					:cedula_h, :general
				)");
				$stmt4->execute([
					':cedula_h' => $this->cedula_historia,
					':general' => $this->general
				]);
			
				// esto es para confirmar la transaccion
				$co->commit();

				$r['resultado'] = 'incluir';
				$r['mensaje'] =  'Paciente Registrado Exitosamente';
		  	} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  'Ha ocurrido un error, por favor revisar los datos ingresados';
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
					$co->query("Update historias set 
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

						where
						cedula_historia = '$this->cedula_historia'
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
		if($this->existe($this->cedula_historia)){
			try {
					$co->query("delete from historias 
						where
						cedula_historia = '$this->cedula_historia'
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
			
			$resultado = $co->query("Select * from historias");
			
			if($resultado){
				
				$respuesta = '';
				foreach($resultado as $r){
					$respuesta = $respuesta."<tr>";
					    $respuesta = $respuesta."<td>";

						$respuesta = $respuesta."<div class='button-container' style='display: flex; justify-content: center; gap: 10px; margin-top: 10px'>
                        
                            <button type='button' class='btn btn-danger' onclick='pone(this,0)'>
                                <img src='img/lapiz.svg' style='width: 20px'>
                            </button>

                            <a class='btn btn-success' href='vista/fpdf/historia.php' target='_blank'>
                                <img src='img/descarga.svg' style='width: 20px;'>
                            </a>

                        </div><br/>";

							$respuesta = $respuesta."<td>".$r['cedula_historia']."</td>";
							$respuesta = $respuesta."<td>".$r['apellido']."</td>";
							$respuesta = $respuesta."<td>".$r['nombre']."</td>";
							$respuesta = $respuesta."<td>".$r['fecha_nac']."</td>";
							$respuesta = $respuesta."<td>".$r['edad']."</td>";
							$respuesta = $respuesta."<td>".$r['telefono']."</td>";
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