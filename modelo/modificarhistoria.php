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

	// antcEDENTES
	private $antc_madre;
	private $antc_padre;
	private $antc_hermano;

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
	
	// antcEDENTES

	function set_antc_madre($valor){
		$this->antc_madre = $valor;
	}
	
	function set_antc_padre($valor){
		$this->antc_padre = $valor;
	}
	
	function set_antc_hermano($valor){
		$this->antc_hermano = $valor;
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

	// antcEDENTES	
	
	function get_antc_madre($valor){
		$this->antc_madre = $valor;
	}
	
	function get_antc_padre($valor){
		$this->antc_padre = $valor;
	}
	
	function get_antc_hermano($valor){
		$this->antc_hermano = $valor;
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
								psicosocial = :psicosocial,
								antc_madre = :antc_madre,
								antc_padre = :antc_padre,
								antc_hermano = :antc_hermano
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
					':psicosocial' => $_POST['psicosocial'],
					':antc_madre' => $_POST['antc_madre'],
					':antc_padre' => $_POST['antc_padre'],
					':antc_hermano' => $_POST['antc_hermano']
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

}

class PacienteModel {
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=shm-cdi.2', 'root', '');
    }

    public function getPacienteByCedula($cedula_historia) {
		$stmt = $this->db->prepare("
			SELECT 
				* 
			FROM 
				historias
			WHERE 
				cedula_historia = :cedula_historia
		");
		$stmt->execute(['cedula_historia' => $cedula_historia]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}


    public function actualizarPaciente($id, $cedula_historia, $nombre, $apellido, $fecha_nac, $edad, $telefono, $estadocivil, $direccion, $ocupacion, $hda, $habtoxico, $alergias, $quirurgico, $transsanguineo, $alergias_med, $psicosocial, $antc_madre, $antc_padre, $antc_hermano) {
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
				psicosocial = :psicosocial,
				antc_madre = :antc_madre,
				antc_padre = :antc_padre,
				antc_hermano = :antc_hermano
				
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
		$stmt->bindParam(':antc_madre', $antc_madre);
		$stmt->bindParam(':antc_padre', $antc_padre);
		$stmt->bindParam(':antc_hermano', $antc_hermano);

        return $stmt->execute();
    }
}