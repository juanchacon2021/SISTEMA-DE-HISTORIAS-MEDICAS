<?php
require('fpdf.php');

class PDF extends FPDF
{
    // Cabecera de página común
    function Header()
    {
        $logoPath = __DIR__.'/../../img/logo.png';
        
        if(file_exists($logoPath)) {
            $this->Image($logoPath, 180, 5, 20);
        }
        
        $this->SetFont('Arial', 'B', 19);
        $this->Cell(45);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(110, 15, utf8_decode('SISTEMA DE GESTIÓN MÉDICO'), 2, 1, 'C', 0);
        $this->Ln(3);
        $this->SetTextColor(103);

        /* UBICACIÓN */
        $this->Cell(120);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(96, 10, utf8_decode("Ubicación: Calle 40 entre Carreras 32 y 33"), 0, 0, '', 0);
        $this->Ln(5);

        /* TELÉFONO */
        $this->Cell(120);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(59, 10, utf8_decode("Teléfono: 0251-0000000"), 0, 0, '', 0);
        $this->Ln(5);

        /* CORREO */
        $this->Cell(120);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(85, 10, utf8_decode("Correo: correo@clinica.com"), 0, 0, '', 0);
        $this->Ln(5);
    }

    // Pie de página común
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $hoy = date('d/m/Y');
        $this->Cell(540, 10, utf8_decode($hoy), 0, 0, 'C');
    }

    // Método para reporte general de jornadas
    function generarReporteJornadas($bd)
    {
        $this->AddPage('L');
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, utf8_decode("REPORTE GENERAL DE JORNADAS MÉDICAS"), 0, 1, 'C');
        $this->Ln(5);

        // Consulta jornadas
        $consulta = $bd->query("SELECT j.*, 
                              COUNT(p.cedula_personal) as total_participantes 
                              FROM jornadas_medicas j
                              LEFT JOIN participantes_jornadas p ON j.cod_jornada = p.cod_jornada
                              GROUP BY j.cod_jornada
                              ORDER BY j.fecha_jornada DESC");

        // Definir ancho total de la tabla
        $anchoTabla = 15 + 30 + 60 + 40 + 20 + 20 + 25; // = 210
        $margenIzq = ($this->GetPageWidth() - $anchoTabla) / 2;
        
        // Tabla de jornadas
        $this->SetFillColor(226, 37, 53); 
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 10);
        $this->SetX($margenIzq);
        $this->Cell(15, 8, 'ID', 1, 0, 'C', 1);
        $this->Cell(30, 8, 'Fecha', 1, 0, 'C', 1);
        $this->Cell(60, 8, 'Ubicación', 1, 0, 'C', 1);
        $this->Cell(40, 8, 'Participantes', 1, 0, 'C', 1);
        $this->Cell(20, 8, 'Hombres', 1, 0, 'C', 1);
        $this->Cell(20, 8, 'Mujeres', 1, 0, 'C', 1);
        $this->Cell(25, 8, 'Embarazadas', 1, 1, 'C', 1);

        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 9);
        while($jor = $consulta->fetch(PDO::FETCH_OBJ)) {
            $this->SetX($margenIzq);
            $this->Cell(15, 8, $jor->cod_jornada, 1, 0, 'C');
            $this->Cell(30, 8, $jor->fecha_jornada, 1, 0, 'C');
            $this->Cell(60, 8, utf8_decode(substr($jor->ubicacion, 0, 30)), 1, 0, 'L');
            $this->Cell(40, 8, $jor->total_participantes, 1, 0, 'C');
            $this->Cell(20, 8, $jor->pacientes_masculinos, 1, 0, 'C');
            $this->Cell(20, 8, $jor->pacientes_femeninos, 1, 0, 'C');
            $this->Cell(25, 8, $jor->pacientes_embarazadas, 1, 1, 'C');
        }
    }

    // Método para reporte detallado de una jornada
    function generarReporteDetalleJornada($bd, $cod_jornada)
    {
        $this->AddPage('L');
        
        // Obtener datos de la jornada
        $jornada = $bd->prepare("SELECT * FROM jornadas_medicas WHERE cod_jornada = ?");
        $jornada->execute([$cod_jornada]);
        $jor = $jornada->fetch(PDO::FETCH_OBJ);

        if(!$jor) {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 8, 'JORNADA NO ENCONTRADA', 1, 1, 'C', 1);
            return;
        }

        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, utf8_decode("REPORTE DETALLADO DE JORNADA MÉDICA"), 0, 1, 'C');
        $this->Ln(3);
        
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 8, utf8_decode("Jornada #".$jor->cod_jornada." - ".$jor->fecha_jornada), 0, 1, 'C');
        $this->Ln(5);

        // Información general
        $this->SetFillColor(226, 37, 53);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 8, utf8_decode('INFORMACIÓN GENERAL'), 1, 1, 'C', 1);
        
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 10);
        $this->Cell(40, 8, 'Ubicación:', 1, 0, 'L');
        $this->Cell(0, 8, utf8_decode($jor->ubicacion), 1, 1, 'L');
        
        $this->Cell(40, 8, 'Fecha:', 1, 0, 'L');
        $this->Cell(30, 8, $jor->fecha_jornada, 1, 0, 'L');
        $this->Cell(40, 8, 'Total Pacientes:', 1, 0, 'L');
        $this->Cell(20, 8, $jor->total_pacientes, 1, 1, 'C');
        
        $this->Cell(40, 8, 'Pacientes Masculinos:', 1, 0, 'L');
        $this->Cell(20, 8, $jor->pacientes_masculinos, 1, 0, 'C');
        $this->Cell(40, 8, 'Pacientes Femeninos:', 1, 0, 'L');
        $this->Cell(20, 8, $jor->pacientes_femeninos, 1, 0, 'C');
        $this->Cell(40, 8, 'Pacientes Embarazadas:', 1, 0, 'L');
        $this->Cell(20, 8, $jor->pacientes_embarazadas, 1, 1, 'C');
        
        $this->Ln(10);
        
        // Participantes del personal
        $participantes = $bd->prepare("SELECT p.*, per.nombre, per.apellido, per.cargo 
                                      FROM participantes_jornadas p
                                      JOIN personal per ON p.cedula_personal = per.cedula_personal
                                      WHERE p.cod_jornada = ?");
        $participantes->execute([$cod_jornada]);
        
        // Personal participante
        $this->SetFillColor(226, 37, 53);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 8, utf8_decode('PERSONAL PARTICIPANTE'), 1, 1, 'C', 1);

        if($participantes->rowCount() > 0) {
            $anchoTabla = 25 + 50 + 30 + 50; // = 155
            $margenIzq = ($this->GetPageWidth() - $anchoTabla) / 2;
            $this->SetFillColor(200, 200, 200);
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Arial', 'B', 10);
            $this->SetX($margenIzq);
            $this->Cell(25, 8, 'Cédula', 1, 0, 'C', 1);
            $this->Cell(50, 8, 'Nombre', 1, 0, 'C', 1);
            $this->Cell(30, 8, 'Cargo', 1, 0, 'C', 1);
            $this->Cell(50, 8, 'Tipo Participante', 1, 1, 'C', 1);

            $this->SetFont('Arial', '', 9);
            while($part = $participantes->fetch(PDO::FETCH_OBJ)) {
                $this->SetX($margenIzq);
                $this->Cell(25, 8, $part->cedula_personal, 1, 0, 'L');
                $this->Cell(50, 8, utf8_decode($part->nombre.' '.$part->apellido), 1, 0, 'L');
                $this->Cell(30, 8, utf8_decode($part->cargo), 1, 0, 'L');
                $this->Cell(50, 8, utf8_decode($part->tipo_participante), 1, 1, 'L');
            }
        } else {
            $this->SetFont('Arial', 'I', 10);
            $this->Cell(0, 8, 'No hay personal registrado para esta jornada', 1, 1, 'C');
        }
        
        $this->Ln(10);
        
        // Estadísticas de pacientes
        $this->SetFillColor(226, 37, 53);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 8, utf8_decode('ESTADÍSTICAS DE PACIENTES'), 1, 1, 'C', 1);
        
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 10);
        
        // Porcentajes
        $total = $jor->total_pacientes > 0 ? $jor->total_pacientes : 1;
        $porc_hombres = round(($jor->pacientes_masculinos / $total) * 100, 2);
        $porc_mujeres = round(($jor->pacientes_femeninos / $total) * 100, 2);
        $porc_embarazadas = round(($jor->pacientes_embarazadas / $total) * 100, 2);
        
        $anchoTabla = 60 + 30; // = 90
        $margenIzq = ($this->GetPageWidth() - $anchoTabla) / 2;
        $this->SetX($margenIzq);
        $this->Cell(60, 8, 'Porcentaje Hombres:', 1, 0, 'L');
        $this->Cell(30, 8, $porc_hombres.'%', 1, 1, 'C');
        $this->SetX($margenIzq);
        $this->Cell(60, 8, 'Porcentaje Mujeres:', 1, 0, 'L');
        $this->Cell(30, 8, $porc_mujeres.'%', 1, 1, 'C');
        $this->SetX($margenIzq);
        $this->Cell(60, 8, 'Porcentaje Embarazadas:', 1, 0, 'L');
        $this->Cell(30, 8, $porc_embarazadas.'%', 1, 1, 'C');
    }

    // Método para reporte de participación de personal
    function generarReporteParticipacionPersonal($bd)
    {
        $this->AddPage('L');
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, utf8_decode("REPORTE DE PARTICIPACIÓN DEL PERSONAL"), 0, 1, 'C');
        $this->Ln(5);

        // Consulta personal
        $consulta = $bd->query("SELECT p.*, 
                              COUNT(j.cod_jornada) as total_jornadas 
                              FROM personal p
                              LEFT JOIN participantes_jornadas j ON p.cedula_personal = j.cedula_personal
                              GROUP BY p.cedula_personal
                              ORDER BY p.apellido, p.nombre");

        // Tabla de personal
        $this->SetFillColor(226, 37, 53);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 10);
        $anchoTabla = 25 + 50 + 30 + 50 + 30; // = 185
        $margenIzq = ($this->GetPageWidth() - $anchoTabla) / 2;
        $this->SetX($margenIzq);
        $this->Cell(25, 8, 'Cédula', 1, 0, 'C', 1);
        $this->Cell(50, 8, 'Nombre', 1, 0, 'C', 1);
        $this->Cell(30, 8, 'Cargo', 1, 0, 'C', 1);
        $this->Cell(50, 8, 'Correo', 1, 0, 'C', 1);
        $this->Cell(30, 8, 'Jornadas', 1, 1, 'C', 1);

        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 9);
        while($per = $consulta->fetch(PDO::FETCH_OBJ)) {
            $this->SetX($margenIzq);
            $this->Cell(25, 8, $per->cedula_personal, 1, 0, 'L');
            $this->Cell(50, 8, utf8_decode($per->nombre.' '.$per->apellido), 1, 0, 'L');
            $this->Cell(30, 8, utf8_decode($per->cargo), 1, 0, 'L');
            $this->Cell(50, 8, utf8_decode($per->correo), 1, 0, 'L');
            $this->Cell(30, 8, $per->total_jornadas, 1, 1, 'C');
        }
    }
}

// Conexión a la base de datos
function conexion() {
    $host = 'localhost';
    $dbname = 'SGM';
    $user = 'root';
    $pass = '';
    
    try {
        return new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    } catch (PDOException $e) {
        die('Error de conexión: ' . $e->getMessage());
    }
}

// Obtener parámetros
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$cod_jornada = isset($_GET['codigo']) ? $_GET['codigo'] : null;

// Validar parámetros
if(empty($tipo)) {
    die('Tipo de reporte no especificado');
}

// Crear PDF y generar reporte según tipo
$pdf = new PDF();
$bd = conexion();

switch($tipo) {
    case 'jornadas':
        $pdf->generarReporteJornadas($bd);
        break;
    case 'individual':
        if(empty($cod_jornada)) die('Código de jornada no especificado');
        $pdf->generarReporteDetalleJornada($bd, $cod_jornada);
        break;
    case 'participantes':
        $pdf->generarReporteParticipacionPersonal($bd);
        break;
    default:
        die('Tipo de reporte no válido');
}

// Generar PDF
$pdf->Output('Reporte_Jornadas_'.date('Ymd').'.pdf', 'I');
?>