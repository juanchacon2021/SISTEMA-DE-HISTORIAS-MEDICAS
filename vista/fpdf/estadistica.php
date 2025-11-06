<?php

require('fpdf.php');


class PDF extends FPDF
{

   // Cabecera de página
   function Header()
   {
      //include '../../recursos/Recurso_conexion_bd.php';//llamamos a la conexion BD

      //$consulta_info = $conexion->query(" select *from hotel ");//traemos datos de la empresa desde BD
      //$dato_info = $consulta_info->fetch_object();
      $this->Image('logo.png', 180, 5, 20); //logo de la empresa,moverDerecha,moverAbajo,tamañoIMG
      $this->SetFont('Arial', 'B', 19); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(45); // Movernos a la derecha
      $this->SetTextColor(0, 0, 0); //color
      //creamos una celda o fila
      $this->Cell(110, 15, utf8_decode('CDI Carmen Estella Mendoza de Flores'), 2, 1, 'C', 0); // AnchoCelda,AltoCelda,titulo,borde(1-0),saltoLinea(1-0),posicion(L-C-R),ColorFondo(1-0)
      $this->Ln(3); // Salto de línea
      $this->SetTextColor(103); //color

      /* UBICACION */
      $this->Cell(120);  // mover a la derecha
      $this->SetFont('Arial', 'B', 10);
      $this->Cell(96, 10, utf8_decode("Ubicación : Calle 40 entre Carreras 32 y 33"), 0, 0, '', 0);
      $this->Ln(5);

      /* TELEFONO */
      $this->Cell(120);  // mover a la derecha
      $this->SetFont('Arial', 'B', 10);
      $this->Cell(59, 10, utf8_decode("Teléfono : 0251-0000000"), 0, 0, '', 0);
      $this->Ln(5);

      /* COREEO */
      $this->Cell(120);  // mover a la derecha
      $this->SetFont('Arial', 'B', 10);
      $this->Cell(85, 10, utf8_decode("Correo : correo@corre.com"), 0, 0, '', 0);
      $this->Ln(5);

      /* TITULO DE LA TABLA */
      //color
      $this->SetTextColor(226 ,37 , 53);
      $this->Cell(45); // mover a la derecha
      $this->SetFont('Arial', 'B', 15);
      $this->Cell(100, 10, utf8_decode("REPORTE DE ESTADISTICA"), 0, 1, 'C', 0);
      $this->Ln(7);

      /* CAMPOS DE LA TABLA */
      //color


   }

   // Pie de página
   function Footer()
   {
      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C'); //pie de pagina(numero de pagina)

      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, cursiva, tamañoTexto
      $hoy = date('d/m/Y');
      $this->Cell(540, 10, utf8_decode($hoy), 0, 0, 'C'); // pie de pagina(fecha de pagina)
   }
}

include '../../modelo/datos.php'; 

function conexion() {
    return new PDO('mysql:host=localhost;dbname=sgm2', 'root', '');
}

$pdf = new PDF();
$pdf->AddPage(); 
$pdf->AliasNbPages();

$i = 0;
$pdf->SetFont('Arial', '', 9);
$pdf->SetDrawColor(163, 163, 163); // colorBorde

$bd = conexion();

$mesEmergencia = $_GET['mes_emergencia'] ?? '';
$anioEmergencia = $_GET['anio_emergencia'] ?? '';
$mesConsulta = $_GET['mes_consulta'] ?? '';
$anioConsulta = $_GET['anio_consulta'] ?? '';

if (empty($mesEmergencia) || empty($anioEmergencia) || empty($mesConsulta) || empty($anioConsulta)) {
    die('Faltan datos de estadística');
}

$consultar_edades = $bd->prepare("
    SELECT 
                    SUM(CASE WHEN edad BETWEEN 0 AND 12 THEN 1 ELSE 0 END) AS Ninos,
                    SUM(CASE WHEN edad BETWEEN 13 AND 17 THEN 1 ELSE 0 END) AS Adolescentes,
                    SUM(CASE WHEN edad BETWEEN 18 AND 64 THEN 1 ELSE 0 END) AS Adultos,
                    SUM(CASE WHEN edad >= 65 THEN 1 ELSE 0 END) AS AdultosMayores
                FROM paciente
");

$consultar_edades->execute();

$datos_reporte = $consultar_edades->fetch(PDO::FETCH_OBJ);

$total_historias = $datos_reporte->Ninos + $datos_reporte->Adolescentes + $datos_reporte->Adultos + $datos_reporte->AdultosMayores;

$consultar_cronicos = $bd->prepare("
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

$consultar_cronicos->execute();

if (!$consultar_cronicos) {
    die('Error al consultar los datos de patologías crónicas');
}

$datos_cronicos = $consultar_cronicos->fetchAll(PDO::FETCH_OBJ);

$consultar_medicamentos = $bd->prepare("
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
$consultar_medicamentos->execute();

$datos_medicamentos = $consultar_medicamentos->fetchAll(PDO::FETCH_OBJ);


$consultar_mas_usados = $bd->prepare("
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
$consultar_mas_usados->execute();

$datos_mas_usados = $consultar_mas_usados->fetchAll(PDO::FETCH_OBJ);




$consultar_consultas = $bd->prepare("
    SELECT DAY(fechaconsulta) AS dia, COUNT(*) AS total
    FROM consulta
    WHERE MONTH(fechaconsulta) = :mes AND YEAR(fechaconsulta) = :anio
    GROUP BY dia
    ORDER BY dia ASC
");
$consultar_consultas->bindParam(':mes', $mesConsulta);
$consultar_consultas->bindParam(':anio', $anioConsulta);
$consultar_consultas->execute();
$datos_consultas = $consultar_consultas->fetchAll(PDO::FETCH_OBJ);


$consultar_emergencias = $bd->prepare("
    SELECT DAY(fechaingreso) AS dia, COUNT(*) AS total
    FROM emergencia
    WHERE MONTH(fechaingreso) = :mes AND YEAR(fechaingreso) = :anio
    GROUP BY dia
    ORDER BY dia ASC
");
$consultar_emergencias->bindParam(':mes', $mesEmergencia);
$consultar_emergencias->bindParam(':anio', $anioEmergencia);
$consultar_emergencias->execute();
$datos_emergencias = $consultar_emergencias->fetchAll(PDO::FETCH_OBJ);





$consultar_vencidos = $bd->prepare("
    SELECT SUM(l.cantidad) AS total_vencidos
    FROM lotes l
    WHERE l.fecha_vencimiento < CURDATE()
");
$consultar_vencidos->execute();
$row_vencidos = $consultar_vencidos->fetch(PDO::FETCH_OBJ);
$total_vencidos = $row_vencidos->total_vencidos ?? 0;



$total_medicamentos = 0;
foreach ($datos_medicamentos as $row) {
    $total_medicamentos += (int)$row->cantidad;
}

$total_cronicos = 0;
foreach ($datos_cronicos as $row) {
    $total_cronicos += (int)$row->pacientes;
}


$total_consultas = 0;
foreach ($datos_consultas as $row) {
    $total_consultas += (int)$row->total;
}

$total_emergencias = 0;
foreach ($datos_emergencias as $row) {
    $total_emergencias += (int)$row->total;
}

// Sumar total de medicamentos usados
$total_usados = 0;
foreach ($datos_mas_usados as $row) {
    $total_usados += (int)$row->cantidad_total;
}




if ($datos_reporte) {
   
    $pdf->Ln(10);


    $pdf->SetFillColor(226, 37, 53); // colorFondo
    $pdf->SetTextColor(255, 255, 255); // colorTexto
    $pdf->SetDrawColor(0, 0, 0); // colorBorde
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 7, utf8_decode('INFORMACIÓN SOBRE LA POBLACIÓN'), 1, 1, 'C', 1);

    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->MultiCell(190, 5, utf8_decode('Número total de historias registradas: ' . $total_historias), 1, 'C',0);

    $pdf->SetFillColor(255, 255, 255); // colorFondo
    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetDrawColor(0, 0, 0); // colorBorde
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(70, 7, utf8_decode('Rango de edades'), 1, 0, 'C', 1);
    $pdf->Cell(50, 7, utf8_decode('Cantidad'), 1, 0, 'C', 1);
    $pdf->Cell(70, 7, utf8_decode('Porcentaje'), 1, 1, 'C', 1);

    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(70, 7, utf8_decode('0-12 años'), 1, 0, 'C', 1);
    $pdf->Cell(50, 7, utf8_decode($datos_reporte->Ninos), 1, 0, 'C', 1);
    $pdf->Cell(70, 7, utf8_decode(($total_historias > 0 ? number_format($datos_reporte->Ninos * 100 / $total_historias, 2) : '0.00') . '%'), 1, 1, 'C', 1);

    $pdf->Cell(70, 7, utf8_decode('13-17 años'), 1, 0, 'C', 1);
    $pdf->Cell(50, 7, utf8_decode($datos_reporte->Adolescentes), 1, 0, 'C', 1);
    $pdf->Cell(70, 7, utf8_decode(($total_historias > 0 ? number_format($datos_reporte->Adolescentes * 100 / $total_historias, 2) : '0.00') . '%'), 1, 1, 'C', 1);

    $pdf->Cell(70, 7, utf8_decode('18-64 años'), 1, 0, 'C', 1);
    $pdf->Cell(50, 7, utf8_decode($datos_reporte->Adultos), 1, 0, 'C', 1);
    $pdf->Cell(70, 7, utf8_decode(($total_historias > 0 ? number_format($datos_reporte->Adultos * 100 / $total_historias, 2) : '0.00') . '%'), 1, 1, 'C', 1);

    $pdf->Cell(70, 7, utf8_decode('65 años y más'), 1, 0, 'C', 1);
    $pdf->Cell(50, 7, utf8_decode($datos_reporte->AdultosMayores), 1, 0, 'C', 1);
    $pdf->Cell(70, 7, utf8_decode(($total_historias > 0 ? number_format($datos_reporte->AdultosMayores * 100 / $total_historias, 2) : '0.00') . '%'), 1, 1, 'C', 1);

    $pdf->Ln(10);

    $pdf->SetFillColor(226, 37, 53); // colorFondo
    $pdf->SetTextColor(255, 255, 255); // colorTexto
    $pdf->SetDrawColor(0, 0, 0); // colorBorde
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 7, utf8_decode('INFORMACIÓN SOBRE LOS PACIENTES CRÓNICOS'), 1, 1, 'C', 1);

    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->MultiCell(190, 5, utf8_decode('Número total de pacientes crónicos registrados:  ' . $total_cronicos), 1, 'C',0);
    $pdf->SetFillColor(255, 255, 255); // colorFondo
    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetDrawColor(0, 0, 0); // colorBorde
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(70, 7, utf8_decode('Patologias'), 1, 0, 'C', 1);
    $pdf->Cell(50, 7, utf8_decode('Cantidad'), 1, 0, 'C', 1);
    $pdf->Cell(70, 7, utf8_decode('Porcentaje'), 1, 1, 'C', 1);

    foreach ($datos_cronicos as $row) {
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(70, 7, utf8_decode($row->nombre_patologia), 1, 0, 'C', 1);
        $pdf->Cell(50, 7, utf8_decode($row->pacientes), 1, 0, 'C', 1);
        $pdf->Cell(70, 7, utf8_decode(($total_cronicos > 0 ? number_format($row->pacientes * 100 / $total_cronicos, 2) : '0.00') . '%'), 1, 1, 'C', 1);
    }

    $pdf->Ln(100);
    $pdf->SetFillColor(226, 37, 53); // colorFondo
    $pdf->SetTextColor(255, 255, 255); // colorTexto
    $pdf->SetDrawColor(0, 0, 0); // colorBorde
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 7, utf8_decode('TOP 10 MEDICAMENTOS MÁS USADOS'), 1, 1, 'C', 1);

    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetFont('Arial', 'B', 10);

    // Mostrar el medicamento más usado y cuántas veces fue utilizado
    if (count($datos_mas_usados) > 0) {
        $medicamento_mas_usado = $datos_mas_usados[0]->medicamento;
        $cantidad_mas_usado = $datos_mas_usados[0]->cantidad_total;
        $pdf->MultiCell(
            190,
            5,
            utf8_decode('Medicamento más usado: ' . $medicamento_mas_usado . ' (' . $cantidad_mas_usado . ' veces)'),
            1,
            'C',
            0
        );
    }

    $pdf->SetFillColor(255, 255, 255); // colorFondo
    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetDrawColor(0, 0, 0); // colorBorde
    $pdf->SetFont('Arial', 'B', 9);
    // Encabezado de tabla
    $pdf->Cell( 95, 7, utf8_decode('Medicamento'), 1, 0, 'C', 1);
    $pdf->Cell( 95, 7, utf8_decode('Cantidad Total'), 1, 1, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    // Filas de la tabla
    foreach ($datos_mas_usados as $row) {
        
        $pdf->Cell(95, 7, utf8_decode($row->medicamento), 1, 0, 'C', 1);
        $pdf->Cell(95, 7, utf8_decode($row->cantidad_total), 1, 1, 'C', 1);
    }

    $pdf->Ln(10);
    $pdf->SetFillColor(226, 37, 53); // colorFondo
    $pdf->SetTextColor(255, 255, 255); // colorTexto
    $pdf->SetDrawColor(0, 0, 0); // colorBorde
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 7, utf8_decode('MEDICAMENTOS POR VENCER (TOP 10 PRÓXIMOS)'), 1, 1, 'C', 1);

    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->MultiCell(190, 5, utf8_decode('Cantidad total de medicamentos ya vencidos: ' . $total_vencidos), 1, 'C', 0);

    $pdf->SetFillColor(255, 255, 255); // colorFondo
    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetDrawColor(0, 0, 0); // colorBorde
    $pdf->SetFont('Arial', 'B', 9);
    // Encabezado de tabla
    $pdf->Cell(30, 7, utf8_decode('Lote'), 1, 0, 'C', 1);
    $pdf->Cell(60, 7, utf8_decode('Medicamento'), 1, 0, 'C', 1);
    $pdf->Cell(30, 7, utf8_decode('Unidad'), 1, 0, 'C', 1);
    $pdf->Cell(40, 7, utf8_decode('Vence'), 1, 0, 'C', 1);
    $pdf->Cell(30, 7, utf8_decode('Cantidad'), 1, 1, 'C', 1);
    $pdf->SetFont('Arial', '', 8);
    // Filas de la tabla
    foreach ($datos_medicamentos as $row) {
        $pdf->Cell(30, 7, utf8_decode($row->cod_lote), 1, 0, 'C', 1);
        $pdf->Cell(60, 7, utf8_decode($row->medicamento), 1, 0, 'C', 1);
        $pdf->Cell(30, 7, utf8_decode($row->unidad_medida), 1, 0, 'C', 1);
        $pdf->Cell(40, 7, utf8_decode($row->fecha_vencimiento), 1, 0, 'C', 1);
        $pdf->Cell(30, 7, utf8_decode($row->cantidad), 1, 1, 'C', 1);
    }

    $pdf->Ln(100);
    $pdf->SetFillColor(226, 37, 53); // fondo rojo
    $pdf->SetTextColor(255, 255, 255); // letras blancas
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 7, utf8_decode("CONSULTAS POR DÍA EN $mesConsulta/$anioConsulta"), 1, 1, 'C', 1);

    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->MultiCell(190, 5, utf8_decode('Numero total de consultas este mes:' . $total_consultas), 1, 'C', 0);

    // Resto de la tabla en blanco con letras negras
    $pdf->SetFillColor(255, 255, 255); // fondo blanco
    $pdf->SetTextColor(0, 0, 0); // letras negras
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(95, 7, utf8_decode('Día'), 1, 0, 'C', 1);
    $pdf->Cell(95, 7, utf8_decode('Total Consultas'), 1, 1, 'C', 1);
    $pdf->SetFont('Arial', '', 8);

    foreach ($datos_consultas as $row) {
        $pdf->Cell(95, 7, utf8_decode($row->dia), 1, 0, 'C', 1);
        $pdf->Cell(95, 7, utf8_decode($row->total), 1, 1, 'C', 1);
    }

    $pdf->Ln(10);
    $pdf->SetFillColor(226, 37, 53); // fondo rojo
    $pdf->SetTextColor(255, 255, 255); // letras blancas
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 7, utf8_decode("EMERGENCIAS POR DÍA EN $mesEmergencia/$anioEmergencia"), 1, 1, 'C', 1);

    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->MultiCell(190, 5, utf8_decode('Numero total de emergencias este mes:' . $total_emergencias), 1, 'C', 0);

    // Encabezado de tabla en blanco con letras negras
    $pdf->SetFillColor(255, 255, 255); // fondo blanco
    $pdf->SetTextColor(0, 0, 0); // letras negras
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(95, 7, utf8_decode('Día'), 1, 0, 'C', 1);
    $pdf->Cell(95, 7, utf8_decode('Total Emergencias'), 1, 1, 'C', 1);
    $pdf->SetFont('Arial', '', 8);

    foreach ($datos_emergencias as $row) {
        $pdf->Cell(95, 7, utf8_decode($row->dia), 1, 0, 'C', 1);
        $pdf->Cell(95, 7, utf8_decode($row->total), 1, 1, 'C', 1);
    }

    ob_end_clean();
    



   } else {
    die('No se a podido generar el reporte de la emergncia');
}

$pdf->Output();
