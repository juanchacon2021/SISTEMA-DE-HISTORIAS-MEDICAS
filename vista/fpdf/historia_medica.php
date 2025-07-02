<?php
require '../../vendor/autoload.php';
require '../../vista/fpdf/fpdf.php';
require '../../src/modelo/datos.php';

use Shm\Shm\modelo\datos;

class PDF extends FPDF {
    function Header() {
        // Logo
        $this->Image('../../img/logo.png', 10, 8, 22); // Ajusta la ruta y tamaño según tu logo
        // Nombre del CDI
        $this->SetFont('Arial','B',16);
        $this->SetTextColor(0,0,0);
        $this->Cell(45); // Mover a la derecha
        $this->Cell(110, 12, utf8_decode('CDI Carmen Estella Mendoza de Flores'), 0, 1, 'C', 0);
        $this->Ln(2);

        // Ubicación
        $this->SetFont('Arial','B',10);
        $this->Cell(45);
        $this->Cell(96, 7, utf8_decode("Ubicación: Calle 40 entre Carreras 32 y 33"), 0, 1, 'C', 0);

        // Teléfono y Correo
        $this->Cell(45);
        $this->Cell(59, 7, utf8_decode("Teléfono: 0251-0000000"), 0, 0, 'C', 0);
        $this->Cell(50, 7, utf8_decode("Correo: correo@corre.com"), 0, 1, 'C', 0);

        // Línea y título del reporte
        $this->Ln(2);
        $this->SetTextColor(226,37,53);
        $this->SetFont('Arial','B',15);
        $this->Cell(0, 10, utf8_decode('HISTORIA MÉDICA COMPLETA'), 0, 1, 'C', 0);
        $this->Ln(3);
    }
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(100);
        $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
    }
}

$bd = (new datos())->conecta();
$bd->exec("SET NAMES 'utf8'");
$cedula_paciente = $_GET['cedula_paciente'] ?? '';
if (!$cedula_paciente) die('Cédula no proporcionada');

// 1. Datos del paciente
$stmt = $bd->prepare("SELECT * FROM paciente WHERE cedula_paciente = ?");
$stmt->execute([$cedula_paciente]);
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

// 2. Antecedentes familiares
$stmt = $bd->prepare("SELECT * FROM antecedentes_familiares WHERE cedula_paciente = ?");
$stmt->execute([$cedula_paciente]);
$familiares = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Patologías crónicas
$stmt = $bd->prepare("SELECT pa.nombre_patologia, p.tratamiento, p.administracion_t FROM padece p JOIN patologia pa ON p.cod_patologia = pa.cod_patologia WHERE p.cedula_paciente = ?");
$stmt->execute([$cedula_paciente]);
$patologias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 4. Consultas
$stmt = $bd->prepare("SELECT * FROM consulta WHERE cedula_paciente = ? ORDER BY fechaconsulta DESC");
$stmt->execute([$cedula_paciente]);
$consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 5. Emergencias
$stmt = $bd->prepare("SELECT * FROM emergencia WHERE cedula_paciente = ? ORDER BY fechaingreso DESC");
$stmt->execute([$cedula_paciente]);
$emergencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 6. Exámenes
$stmt = $bd->prepare("SELECT e.*, t.nombre_examen FROM examen e JOIN tipo_de_examen t ON e.cod_examen = t.cod_examen WHERE e.cedula_paciente = ? ORDER BY fecha_e DESC");
$stmt->execute([$cedula_paciente]);
$examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- PDF GENERATION ---
$nombreCompleto = isset($paciente['nombre'], $paciente['apellido']) ? $paciente['nombre'] . ' ' . $paciente['apellido'] : '';
$pdf = new PDF();
$pdf->SetTitle("HISTORIA MEDICA: $nombreCompleto");
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

// Sección: Datos personales
$pdf->SetFillColor(245,245,245);
$pdf->SetTextColor(226,37,53); // Rojo institucional para títulos
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,utf8_decode("Datos Personales"),0,1,'L',true);
$pdf->Ln(2);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(33,33,33);
foreach($paciente as $k=>$v) {
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(55,8,utf8_decode(ucwords(str_replace('_',' ',$k)).":"),0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,8,utf8_decode($v),0,1,'L');
}
$pdf->Ln(5);

// Sección: Antecedentes familiares
$pdf->SetFillColor(226,37,53); // Fondo rojo institucional
$pdf->SetTextColor(255,255,255); // Texto blanco
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,utf8_decode("Antecedentes Familiares"),0,1,'L',true);
$pdf->Ln(2);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(33,33,33);
if($familiares) {
    foreach($familiares as $f) {
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(0,7,utf8_decode("{$f['nom_familiar']} {$f['ape_familiar']} ({$f['relacion_familiar']}):"),0,1,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->MultiCell(0,7,utf8_decode($f['observaciones']),0,'L');
        $pdf->Ln(1);
    }
} else {
    $pdf->Cell(0,7,"Sin antecedentes familiares registrados",0,1,'L');
}
$pdf->Ln(5);

// Sección: Patologías crónicas
$pdf->SetFillColor(245,245,245);
$pdf->SetTextColor(226,37,53);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,utf8_decode("Patologías Crónicas"),0,1,'L',true);
$pdf->Ln(2);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(33,33,33);
if($patologias) {
    foreach($patologias as $p) {
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(0,7,utf8_decode($p['nombre_patologia']),0,1,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(40,7,utf8_decode("Tratamiento:"),0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,7,utf8_decode($p['tratamiento']),0,1,'L');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(40,7,utf8_decode("Administración:"),0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,7,utf8_decode($p['administracion_t']),0,1,'L');
        $pdf->Ln(2);
    }
} else {
    $pdf->Cell(0,7,"Sin patologías crónicas registradas",0,1,'L');
}
$pdf->Ln(5);

// Sección: Consultas
$pdf->SetFillColor(226,37,53);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,utf8_decode("Consultas Médicas"),0,1,'L',true);
$pdf->Ln(2);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(33,33,33);
if($consultas) {
    foreach($consultas as $c) {
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(35,7,utf8_decode("Fecha:"),0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,7,utf8_decode($c['fechaconsulta']),0,1,'L');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(35,7,utf8_decode("Diagnóstico:"),0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->MultiCell(0,7,utf8_decode($c['diagnostico']),0,'L');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(35,7,utf8_decode("Tratamientos:"),0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->MultiCell(0,7,utf8_decode($c['tratamientos']),0,'L');
        $pdf->Ln(2);
    }
} else {
    $pdf->Cell(0,7,"Sin consultas registradas",0,1,'L');
}
$pdf->Ln(5);

// Sección: Emergencias
$pdf->SetFillColor(245,245,245);
$pdf->SetTextColor(226,37,53);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,utf8_decode("Emergencias"),0,1,'L',true);
$pdf->Ln(2);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(33,33,33);
if($emergencias) {
    foreach($emergencias as $e) {
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(35,7,utf8_decode("Fecha:"),0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,7,utf8_decode($e['fechaingreso']),0,1,'L');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(35,7,utf8_decode("Motivo:"),0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->MultiCell(0,7,utf8_decode($e['motingreso']),0,'L');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(35,7,utf8_decode("Diagnóstico:"),0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->MultiCell(0,7,utf8_decode($e['diagnostico_e']),0,'L');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(35,7,utf8_decode("Tratamientos:"),0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->MultiCell(0,7,utf8_decode($e['tratamientos']),0,'L');
        $pdf->Ln(2);
    }
} else {
    $pdf->Cell(0,7,"Sin emergencias registradas",0,1,'L');
}
$pdf->Ln(5);

// Sección: Exámenes
$pdf->SetFillColor(226,37,53);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,10,utf8_decode("Exámenes Realizados"),0,1,'L',true);
$pdf->Ln(2);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(33,33,33);
if($examenes) {
    foreach($examenes as $ex) {
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(35,7,utf8_decode("Fecha:"),0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,7,utf8_decode($ex['fecha_e']),0,1,'L');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(35,7,utf8_decode("Tipo:"),0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,7,utf8_decode($ex['nombre_examen']),0,1,'L');
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(35,7,utf8_decode("Observación:"),0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->MultiCell(0,7,utf8_decode($ex['observacion_examen']),0,'L');
        $pdf->Ln(2);
    }
} else {
    $pdf->Cell(0,7,"Sin exámenes registrados",0,1,'L');
}

$pdf->Output();
?>