<?php
namespace Shm\Shm\modelo;
use Shm\Shm\modelo\datos;
use Pdo;
use Exception;

class pacientes extends datos {
    private $conexion;
    private $cedula_paciente;
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
	private $id_familiar;
	private $nom_familiar;
	private $ape_familiar;
	private $relacion_familiar;
	private $observaciones;

    function __construct() {
        $this->conexion = $this->conecta(); 
    }

    // Setters
    function set_cedula_paciente($valor) { $this->cedula_paciente = $valor; }
    function set_apellido($valor) { $this->apellido = $valor; }
    function set_nombre($valor) { $this->nombre = $valor; }
    function set_fecha_nac($valor) { $this->fecha_nac = $valor; }
    function set_edad($valor) { $this->edad = $valor; }
    function set_telefono($valor) { $this->telefono = $valor; }
    function set_direccion($valor) { $this->direccion = $valor; }
    function set_estadocivil($valor) { $this->estadocivil = $valor; }
    function set_ocupacion($valor) { $this->ocupacion = $valor; }
    function set_hda($valor) { $this->hda = $valor; }
    function set_habtoxico($valor) { $this->habtoxico = $valor; }
    function set_alergias($valor) { $this->alergias = $valor; }
    function set_alergias_med($valor) { $this->alergias_med = $valor; }
    function set_quirurgico($valor) { $this->quirurgico = $valor; }
    function set_transsanguineo($valor) { $this->transsanguineo = $valor; }
    function set_psicosocial($valor) { $this->psicosocial = $valor; }

	function set_id_familiar($valor) { $this->id_familiar = $valor; }
	function set_nom_familiar($valor) { $this->nom_familiar = $valor; }
	function set_ape_familiar($valor) { $this->ape_familiar = $valor; }
	function set_relacion_familiar($valor) { $this->relacion_familiar = $valor; }
	function set_observaciones($valor) { $this->observaciones = $valor; }
    // Getters
    function get_cedula_paciente() { return $this->cedula_paciente; }
    function get_apellido() { return $this->apellido; }
    function get_nombre() { return $this->nombre; }
    function get_fecha_nac() { return $this->fecha_nac; }
    function get_edad() { return $this->edad; }
    function get_telefono() { return $this->telefono; }
    function get_estadocivil() { return $this->estadocivil; }
    function get_direccion() { return $this->direccion; }
    function get_ocupacion() { return $this->ocupacion; }
    function get_hda() { return $this->hda; }
    function get_habtoxico() { return $this->habtoxico; }
    function get_alergias() { return $this->alergias; }
    function get_alergias_med() { return $this->alergias_med; }
    function get_quirurgico() { return $this->quirurgico; }
    function get_transsanguineo() { return $this->transsanguineo; }
    function get_psicosocial() { return $this->psicosocial; }

	

    function incluir() {
		$r = ['resultado' => 'error', 'mensaje' => ''];
		
		// Validar campos obligatorios
		if(empty($this->cedula_paciente) || empty($this->nombre) || empty($this->apellido)) {
			$r['mensaje'] = 'Cédula, nombre y apellido son obligatorios';
			return $r;
		}

		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		try {
			$co->beginTransaction();
			
			// Insertar paciente
			$stmt = $co->prepare("INSERT INTO paciente(
				cedula_paciente, nombre, apellido, fecha_nac, edad, estadocivil, 
				ocupacion, direccion, telefono, hda, alergias, alergias_med, 
				quirurgico, transsanguineo, psicosocial, habtoxico
			) VALUES (
				:cedula, :nombre, :apellido, :fecha_nac, :edad, :estadocivil, 
				:ocupacion, :direccion, :telefono, :hda, :alergias, :alergias_med, 
				:quirurgico, :transsanguineo, :psicosocial, :habtoxico
			)");

			$stmt->execute([
				':cedula' => $this->cedula_paciente,
				':nombre' => $this->nombre,
				':apellido' => $this->apellido,
				':fecha_nac' => $this->fecha_nac,
				':edad' => $this->edad,
				':estadocivil' => $this->estadocivil,
				':ocupacion' => $this->ocupacion,
				':direccion' => $this->direccion,
				':telefono' => $this->telefono,
				':hda' => $this->hda,
				':alergias' => $this->alergias,
				':alergias_med' => $this->alergias_med,
				':quirurgico' => $this->quirurgico,
				':transsanguineo' => $this->transsanguineo,
				':psicosocial' => $this->psicosocial,
				':habtoxico' => $this->habtoxico
			]);
			
			// Insertar antecedentes si existen
			if(!empty($this->familiares)) {
				foreach($this->familiares as $familiar) {
					$stmt = $co->prepare("INSERT INTO antecedentes_familiares (
						id_familiar, nom_familiar, ape_familiar, relacion_familiar, 
						observaciones, cedula_paciente
					) VALUES (
						:id, :nombre, :apellido, :relacion, :observaciones, :cedula
					)");
					
					$next_id = $co->query("SELECT IFNULL(MAX(id_familiar), 0) + 1 FROM antecedentes_familiares")->fetchColumn();
					
					$stmt->execute([
						':id' => $next_id,
						':nombre' => $familiar['nom_familiar'],
						':apellido' => $familiar['ape_familiar'],
						':relacion' => $familiar['relacion_familiar'],
						':observaciones' => $familiar['observaciones'] ?? '',
						':cedula' => $this->cedula_paciente
					]);
				}
			}
			
			$co->commit();
			return ['resultado' => 'success', 'mensaje' => 'Paciente y antecedentes registrados'];
			
		} catch(PDOException $e) {
			$co->rollBack();
			return ['resultado' => 'error', 'mensaje' => 'Error en base de datos: ' . $e->getMessage()];
		} catch(Exception $e) {
			$co->rollBack();
			return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
		}
	}

	// Nuevo método para manejar familiares temporalmente
	private $familiares = [];

	function set_familiares($familiares) {
    $this->familiares = $familiares;
	}

	function agregarFamiliarTemporal($familiar) {
		$this->familiares[] = $familiar;
		return ['resultado' => 'success', 'mensaje' => 'Familiar agregado temporalmente'];
	}

    function modificar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $co->beginTransaction();

        // Actualizar paciente
        $stmt = $co->prepare("UPDATE paciente SET
            nombre = :nombre,
            apellido = :apellido,
            fecha_nac = :fecha_nac,
            edad = :edad,
            estadocivil = :estadocivil,
            ocupacion = :ocupacion,
            direccion = :direccion,
            telefono = :telefono,
            hda = :hda,
            alergias = :alergias,
            alergias_med = :alergias_med,
            quirurgico = :quirurgico,
            transsanguineo = :transsanguineo,
            psicosocial = :psicosocial,
            habtoxico = :habtoxico
            WHERE cedula_paciente = :cedula");

        $stmt->execute([
            ':nombre' => $this->nombre,
            ':apellido' => $this->apellido,
            ':fecha_nac' => $this->fecha_nac,
            ':edad' => $this->edad,
            ':estadocivil' => $this->estadocivil,
            ':ocupacion' => $this->ocupacion,
            ':direccion' => $this->direccion,
            ':telefono' => $this->telefono,
            ':hda' => $this->hda,
            ':alergias' => $this->alergias,
            ':alergias_med' => $this->alergias_med,
            ':quirurgico' => $this->quirurgico,
            ':transsanguineo' => $this->transsanguineo,
            ':psicosocial' => $this->psicosocial,
            ':habtoxico' => $this->habtoxico,
            ':cedula' => $this->cedula_paciente
        ]);

        // Si hay familiares enviados, eliminamos los actuales y agregamos los nuevos
        if (!empty($this->familiares)) {
            // Eliminar familiares actuales
            $stmt = $co->prepare("DELETE FROM antecedentes_familiares WHERE cedula_paciente = :cedula");
            $stmt->execute([':cedula' => $this->cedula_paciente]);

            // Insertar los nuevos familiares
            foreach ($this->familiares as $familiar) {
                $next_id = $co->query("SELECT IFNULL(MAX(id_familiar), 0) + 1 FROM antecedentes_familiares")->fetchColumn();
                $stmt = $co->prepare("INSERT INTO antecedentes_familiares (
                    id_familiar, nom_familiar, ape_familiar, relacion_familiar, 
                    observaciones, cedula_paciente
                ) VALUES (
                    :id, :nombre, :apellido, :relacion, :observaciones, :cedula
                )");
                $stmt->execute([
                    ':id' => $next_id,
                    ':nombre' => $familiar['nom_familiar'],
                    ':apellido' => $familiar['ape_familiar'],
                    ':relacion' => $familiar['relacion_familiar'],
                    ':observaciones' => $familiar['observaciones'] ?? '',
                    ':cedula' => $this->cedula_paciente
                ]);
            }
        }

        $co->commit();
        return ["resultado" => "modificar", "mensaje" => "Paciente y familiares actualizados exitosamente"];
    } catch(Exception $e) {
        $co->rollBack();
        $r['resultado'] = 'error';
        $r['mensaje'] = $e->getMessage();
        return $r;
    }
    }

    function consultar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $resultado = $co->query("SELECT * FROM paciente");
            
            if($resultado){
                $r['resultado'] = 'consultar';
                $r['datos'] = $resultado->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = 'No hay pacientes registrados';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    function consultarAntecedentes() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            $stmt = $co->prepare("SELECT * FROM antecedentes_familiares WHERE cedula_paciente = :cedula");
            $stmt->execute([':cedula' => $this->cedula_paciente]);
            
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if($resultado){
                $r['resultado'] = 'consultar';
                $r['datos'] = $resultado;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = 'No hay antecedentes familiares registrados';
            }
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    function eliminar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        try {
            // Primero eliminamos los antecedentes familiares asociados
            $stmt1 = $co->prepare("DELETE FROM antecedentes_familiares WHERE cedula_paciente = :cedula");
            $stmt1->execute([':cedula' => $this->cedula_paciente]);
            
            // Luego eliminamos al paciente
            $stmt2 = $co->prepare("DELETE FROM paciente WHERE cedula_paciente = :cedula");
            $stmt2->execute([':cedula' => $this->cedula_paciente]);
            
            return ["resultado" => "eliminar", "mensaje" => "Paciente eliminado exitosamente"];
        } catch(Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
            return $r;
        }
    }

	function agregarFamiliar() {
		$r = ['resultado' => 'error', 'mensaje' => ''];
		
		// Validar datos mínimos
		if(empty($this->cedula_paciente) || empty($this->nom_familiar) || empty($this->ape_familiar) || empty($this->relacion_familiar)) {
			$r['mensaje'] = 'Datos incompletos del familiar';
			return $r;
		}

		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		try {
			// Verificar existencia del paciente
			$stmt = $co->prepare("SELECT 1 FROM paciente WHERE cedula_paciente = ? LIMIT 1");
			$stmt->execute([$this->cedula_paciente]);
			
			if(!$stmt->fetch()) {
				$r['mensaje'] = 'El paciente no existe. Regístrelo primero.';
				return $r;
			}

			// Obtener próximo ID
			$stmt = $co->query("SELECT IFNULL(MAX(id_familiar), 0) + 1 as next_id FROM antecedentes_familiares");
			$next_id = $stmt->fetch(PDO::FETCH_ASSOC)['next_id'];
			
			// Insertar antecedente
			$stmt = $co->prepare("INSERT INTO antecedentes_familiares (
				id_familiar, nom_familiar, ape_familiar, relacion_familiar, 
				observaciones, cedula_paciente
			) VALUES (?, ?, ?, ?, ?, ?)");
			
			$success = $stmt->execute([
				$next_id,
				$this->nom_familiar,
				$this->ape_familiar,
				$this->relacion_familiar,
				$this->observaciones,
				$this->cedula_paciente
			]);
			
			if($success) {
				return [
					"resultado" => "success", 
					"mensaje" => "Antecedente familiar registrado", 
					"id" => $next_id
				];
			} else {
				$r['mensaje'] = 'Error al registrar antecedente';
				return $r;
			}
			
		} catch(PDOException $e) {
			$r['mensaje'] = 'Error en base de datos: ' . $e->getMessage();
			return $r;
		}
	}

	function eliminarFamiliar() {
		$r = array();
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		try {
			$stmt = $co->prepare("DELETE FROM antecedentes_familiares WHERE id_familiar = :id");
			$stmt->execute([':id' => $this->id_familiar]);
			
			return ["resultado" => "success", "mensaje" => "Familiar eliminado exitosamente"];
		} catch(Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
			return $r;
		}
	}
	

    private function existe($cedula_paciente) {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $co->prepare("SELECT * FROM paciente WHERE cedula_paciente = :cedula");
            $stmt->execute([':cedula' => $cedula_paciente]);
            $fila = $stmt->fetchAll(PDO::FETCH_BOTH);
            
            return $fila ? true : false;
        } catch(Exception $e) {
            return false;
        }
    }

    public static function obtenerUsuarioPersonal($cedula_personal) {
        $co = (new self())->conecta();
        $stmt = $co->prepare("SELECT nombre, apellido FROM personal WHERE cedula_personal = ?");
        $stmt->execute([$cedula_personal]);
        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$usuario) {
            $usuario = ['nombre' => 'Desconocido', 'apellido' => '', 'foto_perfil' => ''];
        }
        return $usuario;
    }
}
?>