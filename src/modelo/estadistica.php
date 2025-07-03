<?php

namespace Shm\Shm\modelo;
use Shm\Shm\modelo\datos;
use PDO;
use Exception;


class estadisticas extends datos {
    
 
    function consultar() {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $totalHistorias = $co->query("SELECT COUNT(*) as total FROM paciente")->fetch(PDO::FETCH_ASSOC)['total'];

            $distribucionEdad = $co->query("
                SELECT 
                    SUM(CASE WHEN edad BETWEEN 0 AND 12 THEN 1 ELSE 0 END) AS Ninos,
                    SUM(CASE WHEN edad BETWEEN 13 AND 17 THEN 1 ELSE 0 END) AS Adolescentes,
                    SUM(CASE WHEN edad BETWEEN 18 AND 64 THEN 1 ELSE 0 END) AS Adultos,
                    SUM(CASE WHEN edad >= 65 THEN 1 ELSE 0 END) AS AdultosMayores
                FROM paciente
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
			$stmt = $co->prepare("
				SELECT 
					nombre_patologia, 
					pacientes
				FROM (
					SELECT 
						p.nombre_patologia, 
						COUNT(pc.cedula_paciente) as pacientes,
						1 as orden
					FROM patologia p
					JOIN padece pc ON p.cod_patologia = pc.cod_patologia
					GROUP BY p.cod_patologia, p.nombre_patologia
					ORDER BY pacientes DESC
					LIMIT 10
				) AS top10

				UNION ALL

				SELECT 
					'Otros' as nombre_patologia, 
					COALESCE(SUM(pacientes), 0) as pacientes
				FROM (
					SELECT 
						COUNT(pc.cedula_paciente) as pacientes
					FROM patologia p
					JOIN padece pc ON p.cod_patologia = pc.cod_patologia
					GROUP BY p.cod_patologia, p.nombre_patologia
					ORDER BY COUNT(pc.cedula_paciente) DESC
					LIMIT 18446744073709551615 OFFSET 10
				) AS resto

				ORDER BY CASE WHEN nombre_patologia = 'Otros' THEN 2 ELSE 1 END, pacientes DESC
			");
			$stmt->execute();
			$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$r['resultado'] = 'consultarCronicos';
			$r['datos'] = $resultados;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

	public function medicamentosPorVencer() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$stmt = $co->prepare("
				SELECT 
					l.cod_lote, 
					m.nombre AS medicamento, 
					m.unidad_medida, 
					l.fecha_vencimiento, 
					l.cantidad
				FROM lotes l
				JOIN medicamentos m ON l.cod_medicamento = m.cod_medicamento
				WHERE l.cantidad > 0
				ORDER BY l.fecha_vencimiento ASC
				LIMIT 10
			");
			$stmt->execute();
			$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$r['resultado'] = 'medicamentosPorVencer';
			$r['datos'] = $resultados;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

    public function totalesGenerales() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$sql = "
				SELECT
					(SELECT COUNT(*) FROM paciente) AS total_historias,
					(SELECT COUNT(*) FROM padece) AS total_cronicos,
					(SELECT COUNT(*) FROM emergencia) AS total_emergencias,
					(SELECT COUNT(*) FROM consulta) AS total_consultas,
					(SELECT COUNT(*) FROM lotes WHERE cantidad > 0) AS cantidad_lotes_con_existencia,
					(SELECT COUNT(*) FROM medicamentos) AS total_de_medicamentos
			";
			$result = $co->query($sql)->fetch(PDO::FETCH_ASSOC);

			$r['resultado'] = 'totales_generales';
			$r['totales'] = $result;
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
			// Obtener mes y año de los parámetros POST o usar el actual
			$mes = isset($_POST['mes']) ? $_POST['mes'] : date('m');
			$anio = isset($_POST['anio']) ? $_POST['anio'] : date('Y');

			$stmt = $co->prepare("
				SELECT DAY(fechaingreso) AS dia, COUNT(*) AS total
				FROM emergencia
				WHERE MONTH(fechaingreso) = :mes AND YEAR(fechaingreso) = :anio
				GROUP BY dia
				ORDER BY dia ASC
			");
			$stmt->bindParam(':mes', $mes);
			$stmt->bindParam(':anio', $anio);
			$stmt->execute();
			$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$r['resultado'] = 'ok';
			$r['datos'] = $resultados;
			$r['mes'] = $mes; // Enviar el mes usado para mostrarlo en el título
			$r['anio'] = $anio; // Enviar el año usado

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
			// Obtener mes y año de los parámetros POST o usar el actual
			$mes = isset($_POST['mes']) ? $_POST['mes'] : date('m');
			$anio = isset($_POST['anio']) ? $_POST['anio'] : date('Y');

			$stmt = $co->prepare("
				SELECT DAY(fechaconsulta) AS dia, COUNT(*) AS total
				FROM consulta
				WHERE MONTH(fechaconsulta) = :mes AND YEAR(fechaconsulta) = :anio
				GROUP BY dia
				ORDER BY dia ASC
			");
			$stmt->bindParam(':mes', $mes);
			$stmt->bindParam(':anio', $anio);
			$stmt->execute();
			$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$r['resultado'] = 'ok';
			$r['datos'] = $resultados;
			$r['mes'] = $mes; // Enviar el mes usado para mostrarlo en el título
			$r['anio'] = $anio; // Enviar el año usado

		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}

		return $r;
	}
	
	public function mesConMasEmergencias() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$stmt = $co->prepare("
				SELECT YEAR(fechaingreso) AS anio, MONTH(fechaingreso) AS mes, COUNT(*) AS total
				FROM emergencia
				GROUP BY anio, mes
				ORDER BY total DESC
				LIMIT 1
			");
			$stmt->execute();
			$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
	
			if ($resultado) {
				$r['resultado'] = 'ok';
				$r['mes'] = $resultado['mes'];
				$r['anio'] = $resultado['anio'];
				$r['total'] = $resultado['total'];
			} else {
				$r['resultado'] = 'ok';
				$r['mes'] = null;
				$r['anio'] = null;
				$r['total'] = 0;
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

	public function medicamentosMasUsados() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$stmt = $co->prepare("
				SELECT 
					m.nombre AS medicamento,
					SUM(i.cantidad) AS cantidad_total
				FROM 
					medicamentos m
				JOIN 
					lotes l ON m.cod_medicamento = l.cod_medicamento
				JOIN 
					insumos i ON l.cod_lote = i.cod_lote
				GROUP BY 
					m.nombre
				ORDER BY 
					cantidad_total DESC
				LIMIT 10
			");
			$stmt->execute();
			$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$r['resultado'] = 'medicamentosMasUsados';
			$r['datos'] = $resultados;
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

	public function mesConMasConsultas() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$stmt = $co->prepare("
				SELECT YEAR(fechaconsulta) AS anio, MONTH(fechaconsulta) AS mes, COUNT(*) AS total
				FROM consulta
				GROUP BY anio, mes
				ORDER BY total DESC
				LIMIT 1
			");
			$stmt->execute();
			$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($resultado) {
				$r['resultado'] = 'ok';
				$r['mes'] = $resultado['mes'];
				$r['anio'] = $resultado['anio'];
				$r['total'] = $resultado['total'];
			} else {
				$r['resultado'] = 'ok';
				$r['mes'] = null;
				$r['anio'] = null;
				$r['total'] = 0;
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}


	
}
?>