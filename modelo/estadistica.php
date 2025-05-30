<?php

require_once('modelo/datos.php');

class estadisticas extends datos {
    
 
    function consultar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $totalHistorias = $co->query("SELECT COUNT(*) as total FROM historias")->fetch(PDO::FETCH_ASSOC)['total'];

            $distribucionEdad = $co->query("
                SELECT 
                    SUM(CASE WHEN edad BETWEEN 0 AND 12 THEN 1 ELSE 0 END) AS Ninos,
                    SUM(CASE WHEN edad BETWEEN 13 AND 17 THEN 1 ELSE 0 END) AS Adolescentes,
                    SUM(CASE WHEN edad BETWEEN 18 AND 64 THEN 1 ELSE 0 END) AS Adultos,
                    SUM(CASE WHEN edad >= 65 THEN 1 ELSE 0 END) AS AdultosMayores
                FROM historias
            ")->fetch(PDO::FETCH_ASSOC);

            $r['resultado'] = 'consultar';
            $r['totalHistorias'] = $totalHistorias;
            $r['distribucionEdad'] = $distribucionEdad;

        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

	function consultarCronicos() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$totalCronicos = $co->query("SELECT COUNT(DISTINCT cedula_h) as totalCronicos FROM p_cronicos")->fetch(PDO::FETCH_ASSOC)['totalCronicos'];
	
			$distribucion = $co->query("
				SELECT 
					SUM(CASE WHEN patologia_cronica LIKE '%Cardiopatía%' THEN 1 ELSE 0 END) AS Cardiopatia,
					SUM(CASE WHEN patologia_cronica LIKE '%Hipertensión%' THEN 1 ELSE 0 END) AS Hipertension,
					SUM(CASE WHEN patologia_cronica LIKE '%Endocrinometabólico%' THEN 1 ELSE 0 END) AS Endocrinometabolico,
					SUM(CASE WHEN patologia_cronica LIKE '%Asmático%' THEN 1 ELSE 0 END) AS Asmatico,
					SUM(CASE WHEN patologia_cronica LIKE '%Renal%' THEN 1 ELSE 0 END) AS Renal,
					SUM(CASE WHEN patologia_cronica LIKE '%Mental%' THEN 1 ELSE 0 END) AS Mental
				FROM p_cronicos
			")->fetch(PDO::FETCH_ASSOC);
	
			$r['resultado'] = 'consultarCronicos';
			$r['totalCronicos'] = $totalCronicos;
			$r['distribucionCronicos'] = $distribucion;
	
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

	public function totalHistorias() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$total = $co->query("SELECT COUNT(*) as total FROM historias")
						->fetch(PDO::FETCH_ASSOC)['total'];
	
			$r['resultado'] = 'total_historias';
			$r['total'] = $total;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

	public function totalp_cronicos() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$total = $co->query("SELECT COUNT(*) as total FROM p_cronicos")
						->fetch(PDO::FETCH_ASSOC)['total'];
	
			$r['resultado'] = 'total_cronicos';
			$r['total'] = $total;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

	public function total_emergencias() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$total = $co->query("SELECT COUNT(*) as total FROM emergencias")
						->fetch(PDO::FETCH_ASSOC)['total'];
	
			$r['resultado'] = 'total_emergencias';
			$r['total'] = $total;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

	public function total_consultas() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$total = $co->query("SELECT COUNT(*) as total FROM consultas")
						->fetch(PDO::FETCH_ASSOC)['total'];
	
			$r['resultado'] = 'total_consultas';
			$r['total'] = $total;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

	public function emergenciasPorDiaMesActual() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
	
		try {
			$mesActual = date('m');
			$anioActual = date('Y');
	
			$stmt = $co->prepare("
				SELECT DAY(fechaingreso) AS dia, COUNT(*) AS total
				FROM emergencias
				WHERE MONTH(fechaingreso) = :mes AND YEAR(fechaingreso) = :anio
				GROUP BY dia
				ORDER BY dia ASC
			");
			$stmt->bindParam(':mes', $mesActual);
			$stmt->bindParam(':anio', $anioActual);
			$stmt->execute();
			$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
			$r['resultado'] = 'ok';
			$r['datos'] = $resultados;
	
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
	
		return $r;
	}

	public function consultasPorDiaMesActual() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
	
		try {
			$mesActual = date('m');
			$anioActual = date('Y');
	
			$stmt = $co->prepare("
				SELECT DAY(fechaconsulta) AS dia, COUNT(*) AS total
				FROM consultas
				WHERE MONTH(fechaconsulta) = :mes AND YEAR(fechaconsulta) = :anio
				GROUP BY dia
				ORDER BY dia ASC
			");
			$stmt->bindParam(':mes', $mesActual);
			$stmt->bindParam(':anio', $anioActual);
			$stmt->execute();
			$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
			$r['resultado'] = 'ok';
			$r['datos'] = $resultados;
	
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
	
		return $r;
	}
	
}
?>