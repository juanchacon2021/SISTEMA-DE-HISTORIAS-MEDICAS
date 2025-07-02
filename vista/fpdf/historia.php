<?php

require('fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('logo.png', 10, 8, 22);
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(45);
        $this->Cell(110, 12, utf8_decode('CDI Carmen Estella Mendoza de Flores'), 0, 1, 'C', 0);
        $this->Ln(2);

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(45);
        $this->Cell(96, 7, utf8_decode("Ubicación: Calle 40 entre Carreras 32 y 33"), 0, 1, 'C', 0);

        $this->Cell(45);
        $this->Cell(59, 7, utf8_decode("Teléfono: 0251-0000000"), 0, 0, 'C', 0);
        $this->Cell(50, 7, utf8_decode("Correo: correo@corre.com"), 0, 1, 'C', 0);

        $this->Ln(2);
        $this->SetTextColor(226,37,53);
        $this->SetFont('Arial','B',15);
        $this->Cell(0, 10, utf8_decode('HISTORIA MÉDICA'), 0, 1, 'C', 0);
        $this->Ln(3);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(100);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->SetY(-10);
        $this->Cell(0, 10, utf8_decode(date('d/m/Y')), 0, 0, 'R');
    }
}

include '../../modelo/datos.php';

function conexion() {
    return new PDO('mysql:host=localhost;dbname=sgm', 'root', '123456');
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

$bd = conexion();
$cedula_paciente = isset($_GET['cedula_paciente']) ? $_GET['cedula_paciente'] : '';

if (empty($cedula_paciente)) {
    die('No se proporcionó una cédula válida.');
}

$consulta_reporte_historia = $bd->prepare("SELECT * FROM paciente WHERE cedula_paciente = :cedula_paciente");
$consulta_reporte_historia->bindParam(':cedula_paciente', $cedula_paciente, PDO::PARAM_STR);
$consulta_reporte_historia->execute();
$datos_reporte = $consulta_reporte_historia->fetch(PDO::FETCH_OBJ);

$ante_reporte = $bd->prepare("SELECT * FROM antecedentes_familiares WHERE cedula_paciente = :cedula_paciente");
$ante_reporte->bindParam(':cedula_paciente', $cedula_paciente, PDO::PARAM_STR);
$ante_reporte->execute();
$antecedente = $ante_reporte->fetch(PDO::FETCH_OBJ);

if ($datos_reporte) {
    // Sección: Datos personales
    $pdf->SetFillColor(245,245,245);
    $pdf->SetTextColor(226,37,53);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,utf8_decode("Datos Personales"),0,1,'L',true);
    $pdf->Ln(2);
    $pdf->SetFont('Arial','',10);
    $pdf->SetTextColor(33,33,33);

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,8,"Cédula:",0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(50,8,utf8_decode($datos_reporte->cedula_paciente),0,1,'L');

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,8,"Nombre:",0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(50,8,utf8_decode($datos_reporte->nombre),0,1,'L');

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,8,"Apellido:",0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(50,8,utf8_decode($datos_reporte->apellido),0,1,'L');

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,8,"Fecha de Nacimiento:",0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(50,8,utf8_decode($datos_reporte->fecha_nac),0,1,'L');

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,8,"Edad:",0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(50,8,utf8_decode($datos_reporte->edad),0,1,'L');

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,8,"Estado Civil:",0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(50,8,utf8_decode($datos_reporte->estadocivil),0,1,'L');

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,8,"Teléfono:",0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(50,8,utf8_decode($datos_reporte->telefono),0,1,'L');

    $pdf->Ln(5);

    // Sección: Dirección y ocupación
    $pdf->SetFillColor(226,37,53);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,utf8_decode("Dirección y Ocupación"),0,1,'L',true);
    $pdf->Ln(2);
    $pdf->SetFont('Arial','',10);
    $pdf->SetTextColor(33,33,33);

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,8,"Dirección:",0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,8,utf8_decode($datos_reporte->direccion),0,1,'L');

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,8,"Ocupación:",0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,8,utf8_decode($datos_reporte->ocupacion),0,1,'L');

    $pdf->Ln(5);

    // Sección: Historia médica
    $pdf->SetFillColor(245,245,245);
    $pdf->SetTextColor(226,37,53);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,utf8_decode("Historia Médica"),0,1,'L',true);
    $pdf->Ln(2);
    $pdf->SetFont('Arial','',10);
    $pdf->SetTextColor(33,33,33);

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,8,"HDA:",0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,8,utf8_decode($datos_reporte->hda),0,1,'L');

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,8,"Alergias:",0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,8,utf8_decode($datos_reporte->alergias),0,1,'L');

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,8,"Alergias Médicas:",0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,8,utf8_decode($datos_reporte->alergias_med),0,1,'L');

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,8,"Quirúrgico:",0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,8,utf8_decode($datos_reporte->quirurgico),0,1,'L');

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,8,"Trans Sanguíneo:",0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,8,utf8_decode($datos_reporte->transsanguineo),0,1,'L');

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,8,"Psicosocial:",0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,8,utf8_decode($datos_reporte->psicosocial),0,1,'L');

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,8,"Hábito Tóxico:",0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,8,utf8_decode($datos_reporte->habtoxico),0,1,'L');

    $pdf->Ln(5);

    // Sección: Antecedentes familiares
    $pdf->SetFillColor(226,37,53);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,utf8_decode("Antecedentes Familiares"),0,1,'L',true);
    $pdf->Ln(2);
    $pdf->SetFont('Arial','',10);
    $pdf->SetTextColor(33,33,33);

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(40,8,"Observaciones:",0,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->MultiCell(0,8,utf8_decode($antecedente ? $antecedente->observaciones : 'Sin antecedentes'),0,'L');

} else {
    die('No se encontraron datos para la cédula especificada.');
}

$pdf->Output();
