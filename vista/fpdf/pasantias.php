<?php
require('fpdf.php');

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        $this->Image('logo.png', 180, 5, 20); // Logo de la institución
        $this->SetFont('Arial', 'B', 19);
        $this->Cell(45);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(110, 15, utf8_decode('CDI Carmen Estella Mendoza de Flores'), 0, 1, 'C');
        $this->Ln(3);
        
        // Información de la institución
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(120);
        $this->Cell(96, 10, utf8_decode("Ubicación: Calle 40 entre Carreras 32 y 33"), 0, 0, '');
        $this->Ln(5);
        
        $this->Cell(120);
        $this->Cell(59, 10, utf8_decode("Teléfono: 0251-0000000"), 0, 0, '');
        $this->Ln(5);
        
        $this->Cell(120);
        $this->Cell(85, 10, utf8_decode("Correo: correo@correo.com"), 0, 0, '');
        $this->Ln(10);
        
        // Título del reporte
        $this->SetTextColor(226, 37, 53);
        $this->Cell(45);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(100, 10, utf8_decode("REPORTE DE PASANTÍAS"), 0, 1, 'C');
        $this->Ln(7);
        
        // Encabezados de la tabla
        $this->SetFillColor(226, 37, 53);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(0, 0, 0);
        $this->SetFont('Arial', 'B', 8);
        
        // Ajusté los anchos de las celdas para incluir toda la información necesaria
        $this->Cell(20, 8, utf8_decode('CÉDULA'), 1, 0, 'C', 1);
        $this->Cell(30, 8, utf8_decode('ESTUDIANTE'), 1, 0, 'C', 1);
        $this->Cell(25, 8, utf8_decode('INSTITUCIÓN'), 1, 0, 'C', 1);
        $this->Cell(25, 8, utf8_decode('TELÉFONO'), 1, 0, 'C', 1);
        $this->Cell(30, 8, utf8_decode('ÁREA'), 1, 0, 'C', 1);
        $this->Cell(30, 8, utf8_decode('DOCTOR'), 1, 0, 'C', 1);
        $this->Cell(20, 8, utf8_decode('INICIO'), 1, 0, 'C', 1);
        $this->Cell(20, 8, utf8_decode('FIN'), 1, 0, 'C', 1);
        $this->Cell(15, 8, utf8_decode('ESTADO'), 1, 1, 'C', 1);
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ').$this->PageNo().'/{nb}', 0, 0, 'C');
        
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $hoy = date('d/m/Y H:i:s');
        $this->Cell(0, 10, utf8_decode('Generado el: ').$hoy, 0, 0, 'R');
    }
}

// Conexión a la base de datos
function conexion() {
    return new PDO('mysql:host=localhost;dbname=shm-cdi.2', 'root', '');
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L'); // Orientación horizontal para más espacio

// Establecer fuente para el contenido
$pdf->SetFont('Arial', '', 8);
$pdf->SetDrawColor(163, 163, 163);

$bd = conexion();

// Consulta para obtener los datos de pasantías con joins a las tablas relacionadas
$consulta = $bd->prepare("
    SELECT 
        e.cedula_estudiante,
        CONCAT(e.nombre, ' ', e.apellido) as estudiante,
        e.institucion,
        e.telefono,
        a.nombre_area,
        CONCAT(p.nombre, ' ', p.apellido) as doctor,
        e.fecha_inicio,
        e.fecha_fin,
        CASE WHEN e.activo = 1 THEN 'Activo' ELSE 'Inactivo' END as estado
    FROM estudiantes_pasantia e
    JOIN areas_pasantia a ON e.cod_area = a.cod_area
    JOIN personal p ON a.responsable_id = p.cedula_personal
    ORDER BY e.apellido, e.nombre
");

$consulta->execute();
$pasantias = $consulta->fetchAll(PDO::FETCH_OBJ);

if ($pasantias) {
    foreach ($pasantias as $pasantia) {
        $pdf->Cell(20, 6, utf8_decode($pasantia->cedula_estudiante), 1, 0, 'C');
        $pdf->Cell(30, 6, utf8_decode($pasantia->estudiante), 1, 0, 'L');
        $pdf->Cell(25, 6, utf8_decode($pasantia->institucion), 1, 0, 'L');
        $pdf->Cell(25, 6, utf8_decode($pasantia->telefono), 1, 0, 'C');
        $pdf->Cell(30, 6, utf8_decode($pasantia->nombre_area), 1, 0, 'L');
        $pdf->Cell(30, 6, utf8_decode($pasantia->doctor), 1, 0, 'L');
        $pdf->Cell(20, 6, utf8_decode(date('d/m/Y', strtotime($pasantia->fecha_inicio))), 1, 0, 'C');
        $pdf->Cell(20, 6, utf8_decode($pasantia->fecha_fin ? date('d/m/Y', strtotime($pasantia->fecha_fin)) : 'N/A'), 1, 0, 'C');
        $pdf->Cell(15, 6, utf8_decode($pasantia->estado), 1, 1, 'C');
        
        // Verificar si necesita salto de página
        if($pdf->GetY() > 260) {
            $pdf->AddPage('L');
        }
    }
    
    // Agregar resumen al final
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 10, utf8_decode('Total de pasantes: '.count($pasantias)), 0, 1);
    
    // Estadísticas por área
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 10, utf8_decode('Distribución por áreas:'), 0, 1);
    
    $consulta_areas = $bd->prepare("
        SELECT a.nombre_area, COUNT(e.cedula_estudiante) as total
        FROM areas_pasantia a
        LEFT JOIN estudiantes_pasantia e ON a.cod_area = e.cod_area AND e.activo = 1
        GROUP BY a.nombre_area
    ");
    $consulta_areas->execute();
    $areas = $consulta_areas->fetchAll(PDO::FETCH_OBJ);
    
    $pdf->SetFont('Arial', '', 9);
    foreach ($areas as $area) {
        $pdf->Cell(0, 6, utf8_decode("- {$area->nombre_area}: {$area->total} pasante(s)"), 0, 1);
    }
} else {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, utf8_decode('No se encontraron registros de pasantías'), 0, 1, 'C');
}

$pdf->Output('I', 'Reporte_Pasantias_'.date('Ymd').'.pdf');