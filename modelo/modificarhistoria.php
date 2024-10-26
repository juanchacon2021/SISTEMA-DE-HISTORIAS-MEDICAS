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
	
	
	
	function modificar(){
		$r = array();
		if($this->existe($this->cedula_historia)){
		  	$co = $this->conecta();
		  	$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		  	try {
				// Aqui inicia la transaccion
				$co->beginTransaction();
			
				// Aqui hice la primera consulta UPDATE
				$stmt1 = $co->prepare(query:"UPDATE historias SET
								cedula_historia = :cedula_historia,
								apellido = :apellido,
								nombre = :nombre,
								fecha_nac = :fecha_nac,
								edad = :edad,
								telefono = :telefono,
								estadocivil = :estadocivil,
								direccion = :direccion,
								ocupacion = :ocupacion,
								hda = :hda,
								habtoxico = :habtoxico,
								alergias = :alergias,
								quirurgico = :quirurgico,
								transsanguineo = :transsanguineo,
								alergias_med = :alergias_med,
								psicosocial = :psicosocial
						WHERE cedula_historia = :cedula_historia");
			
				$stmt1->execute(params:[
					':cedula_historia' => $_POST['cedula_historia'],
					':apellido' => $_POST['apellido'],
					':nombre' => $_POST['nombre'],
					':fecha_nac' => $_POST['fecha_nac'],
					':edad' => $_POST['edad'],
					':telefono' => $_POST['telefono'],
					':estadocivil' => $_POST['estadocivil'],
					':direccion' => $_POST['direccion'],
					':ocupacion' => $_POST['ocupacion'],
					':hda' => $_POST['hda'],
					':habtoxico' => $_POST['habtoxico'],
					':alergias' =>$_POST['alergias'],
					':quirurgico' => $_POST['quirurgico'],
					':transsanguineo' => $_POST['transsanguineo'],
					':alergias_med' =>$_POST['alergias_med'],
					':psicosocial' => $_POST['psicosocial']
				]);
			
				// Aqui hice la segunda consulta INSERT	
				$stmt2 = $co->prepare(query:"UPDATE antecedentes SET
									antec_madre = :antec_madre,
									antec_padre = :antec_padre,
									antec_hermano = :antec_hermano
								WHERE cedula_h = :cedula_h");
			
				$stmt2->execute(params:[
					':cedula_h' => $_POST['cedula_historia'],
					':antec_madre' => $_POST['antec_madre'],
					':antec_padre' => $_POST['antec_padre'],
					':antec_hermano' => $_POST['antec_hermano']
				]);

				// Aqui hice la tercera consulta INSERT	
				$stmt3 = $co->prepare(query:"UPDATE examenes_r SET
									cabeza_craneo = :cabeza_craneo,
									ojos = :ojos,
									nariz = :nariz,
									tiroides = :tiroides,
									extremidades = :extremidades,
									boca_abierta = :boca_abierta,
									boca_cerrada = :boca_cerrada,
									oidos = :oidos
								WHERE cedula_h = :cedula_h");
			
				$stmt3->execute(params:[
					':cedula_h' => $_POST['cedula_historia'],
					':cabeza_craneo' => $_POST['cabeza_craneo'],
					':ojos' => $_POST['ojos'],
					':nariz' => $_POST['nariz'],
					':tiroides' => $_POST['tiroides'],
					':extremidades' => $_POST['extremidades'],
					':boca_abierta' => $_POST['boca_abierta'],
					':boca_cerrada' => $_POST['boca_cerrada'],
					':oidos' => $_POST['oidos']
				]);

				// Aqui hice la cuarta consulta INSERT	
				$stmt4 = $co->prepare(query:"UPDATE examenes_s SET
									respiratorio = :respiratorio,
									cardiovascular = :cardiovascular,
									abdomen = :abdomen,
									neurologicos = :neurologicos,
									extremidades_s = :extremidades_s
									WHERE cedula_h = :cedula_h");
				$stmt4->execute(params:[
					':cedula_h' => $_POST['cedula_historia'],	
					':respiratorio' => $_POST['respiratorio'],
					':cardiovascular' => $_POST['cardiovascular'],
					':abdomen' => $_POST['abdomen'],
					':neurologicos' => $_POST['neurologicos'],
					':extremidades_s' => $_POST['extremidades_s']
				]);

				// Aqui hice la quinta consulta INSERT	
				$stmt4 = $co->prepare(query:"UPDATE examenes_f SET
											general = :general
										WHERE cedula_h = :cedula_h");
				
				$stmt4->execute(params:[
					':cedula_h' => $_POST['cedula_historia'],	
					':general' => $_POST['general']
				]);
			
				// esto es para confirmar la transaccion
				$co->commit();

				$r['resultado'] = 'modificar';
				$r['mensaje'] =  'Paciente Modificado Exitosamente';
		  	} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
		  }
		}
		else{
		  $r['resultado'] = 'modificar';
		  $r['mensaje'] =  'Ya existe la cedula';
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
			
			$resultado = $co->query("SELECT * FROM historias WHERE cedula_historia='$cedula_historia'");

			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			if($fila){
				return true;
			}
			else{
				return false;
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

class PacienteModel {
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=sss', 'root', '123456');
    }

    public function getPacienteByCedula($cedula_historia) {
		$stmt = $this->db->prepare("
			SELECT 
				h.*, 
				t2.*, 
				t3.*, 
				t4.*,
				t5.*
			FROM 
				historias h
			JOIN 
				antecedentes t2 ON h.cedula_historia = t2.cedula_h
			JOIN 
				examenes_f t3 ON h.cedula_historia = t3.cedula_h
			JOIN 
				examenes_s t4 ON h.cedula_historia = t4.cedula_h
			JOIN 
				examenes_r t5 ON h.cedula_historia = t5.cedula_h
			WHERE 
				h.cedula_historia = :cedula_historia
		");
		$stmt->execute([':cedula_historia' => $cedula_historia]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}


    public function actualizarPaciente($id, $cedula_historia, $nombre, $apellido, $fecha_nac, $edad, $telefono, $estadocivil, $direccion, $ocupacion, $hda, $habtoxico, $alergias, $quirurgico, $transsanguineo, $alergias_med, $psicosocial) {
        $sql = "UPDATE historias SET 
				cedula_historia = :cedula_historia, 
				nombre = :nombre, 
				apellido = :apellido, 
				fecha_nac = :fecha_nac, 
				edad = :edad, 
				telefono = :telefono, 
				estadocivil = :estadocivil, 
				direccion = :direccion, 
				ocupacion = :ocupacion, 
				hda = :hda, 
				habtoxico = :habtoxico, 
				alergias = :alergias,
				quirurgico = :quirurgico,
				transsanguineo = :transsanguineo,
				alergias_med = :alergias_med,
				psicosocial = :psicosocial
				
				WHERE cedula_historia = :cedula_historia";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cedula_historia', $cedula_historia);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':fecha_nac', $fecha_nac);
		$stmt->bindParam(':edad', $edad);
		$stmt->bindParam(':telefono', $telefono);
		$stmt->bindParam(':estadocivil', $estadocivil);
		$stmt->bindParam(':direccion', $direccion);
		$stmt->bindParam(':ocupacion', $ocupacion);
		$stmt->bindParam(':hda', $hda);
		$stmt->bindParam(':habtoxico', $habtoxico);
		$stmt->bindParam(':alergias', $alergias);
		$stmt->bindParam(':quirurgico', $quirurgico);
		$stmt->bindParam(':transsanguineo', $transsanguineo);
		$stmt->bindParam(':alergias_med', $alergias_med);
		$stmt->bindParam(':psicosocial', $psicosocial);

        return $stmt->execute();
    }
}