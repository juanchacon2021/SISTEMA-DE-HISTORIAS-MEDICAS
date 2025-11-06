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

    // Método para reporte de estudiantes
    function generarReporteEstudiantes($bd)
    {
        $this->AddPage('L');
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, utf8_decode("REPORTE DE ESTUDIANTES EN PASANTÍAS"), 0, 1, 'C');
        $this->Ln(5);

        // Consulta estudiantes
        $consulta = $bd->query("SELECT e.*, a.nombre_area FROM estudiantes_pasantia e LEFT JOIN asistencia asi ON e.cedula_estudiante = asi.cedula_estudiante AND asi.activo = 1 LEFT JOIN areas_pasantias a ON asi.cod_area = a.cod_area ORDER BY e.apellido, e.nombre");

        // Tabla de estudiantes
        $this->SetFillColor(226, 37, 53);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(25, 8, 'Cédula', 1, 0, 'C', 1);
        $this->Cell(40, 8, 'Apellidos', 1, 0, 'C', 1);
        $this->Cell(40, 8, 'Nombres', 1, 0, 'C', 1);
        $this->Cell(40, 8, 'Institución', 1, 0, 'C', 1);
        $this->Cell(25, 8, 'Teléfono', 1, 0, 'C', 1);
        $this->Cell(40, 8, 'Área', 1, 1, 'C', 1);

        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 9);
        while($est = $consulta->fetch(PDO::FETCH_OBJ)) {
            $this->Cell(25, 8, $est->cedula_estudiante, 1, 0, 'L');
            $this->Cell(40, 8, utf8_decode($est->apellido), 1, 0, 'L');
            $this->Cell(40, 8, utf8_decode($est->nombre), 1, 0, 'L');
            $this->Cell(40, 8, utf8_decode($est->institucion), 1, 0, 'L');
            $this->Cell(25, 8, $est->telefono ?: 'N/A', 1, 0, 'L');
            $this->Cell(40, 8, utf8_decode($est->nombre_area ?: 'Sin asignar'), 1, 1, 'L');
        }
    }

    // Método para reporte por áreas
    function generarReporteAreas($bd)
    {
        $this->AddPage('L');
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, utf8_decode("REPORTE DE ASISTENCIA POR ÁREAS"), 0, 1, 'C');
        $this->Ln(5);

        // Consulta áreas
        $consulta = $bd->query("SELECT a.*, CONCAT(p.nombre, ' ', p.apellido) as responsable FROM areas_pasantias a LEFT JOIN personal p ON a.cedula_responsable = p.cedula_personal ORDER BY a.nombre_area");

        while($area = $consulta->fetch(PDO::FETCH_OBJ)) {
            $this->SetFillColor(226, 37, 53);
            $this->SetTextColor(255, 255, 255);
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 8, utf8_decode('Área: '.$area->nombre_area.' - Responsable: '.$area->responsable), 1, 1, 'L', 1);
            
            // Estudiantes en esta área
            $estudiantes = $bd->prepare("SELECT e.*, asi.fecha_inicio, asi.fecha_fin FROM asistencia asi JOIN estudiantes_pasantia e ON asi.cedula_estudiante = e.cedula_estudiante WHERE asi.cod_area = ? AND asi.activo = 1");
            $estudiantes->execute([$area->cod_area]);
            
            if($estudiantes->rowCount() > 0) {
                $this->SetFillColor(200, 200, 200);
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('Arial', 'B', 10);
                $this->Cell(60, 8, 'Estudiante', 1, 0, 'C', 1);
                $this->Cell(40, 8, 'Fecha Inicio', 1, 0, 'C', 1);
                $this->Cell(40, 8, 'Fecha Fin', 1, 1, 'C', 1);
                
                $this->SetFont('Arial', '', 9);
                while($est = $estudiantes->fetch(PDO::FETCH_OBJ)) {
                    $this->Cell(60, 8, utf8_decode($est->apellido.', '.$est->nombre), 1, 0, 'L');
                    $this->Cell(40, 8, $est->fecha_inicio, 1, 0, 'C');
                    $this->Cell(40, 8, $est->fecha_fin ?: 'En curso', 1, 1, 'C');
                }
            } else {
                $this->SetFont('Arial', 'I', 10);
                $this->Cell(0, 8, 'No hay estudiantes asignados', 1, 1, 'C');
            }
            $this->Ln(5);
        }
    }

    // Método para reporte individual
    function generarReporteIndividual($bd, $cedula)
    {
        $this->AddPage();
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, utf8_decode("REPORTE INDIVIDUAL DE ESTUDIANTE"), 0, 1, 'C');
        $this->Ln(5);

        // Datos del estudiante
        $estudiante = $bd->prepare("SELECT * FROM estudiantes_pasantia WHERE cedula_estudiante = ?");
        $estudiante->execute([$cedula]);
        $est = $estudiante->fetch(PDO::FETCH_OBJ);

        if($est) {
            $this->SetFillColor(226, 37, 53);
            $this->SetTextColor(255, 255, 255);
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 8, utf8_decode('INFORMACIÓN PERSONAL'), 1, 1, 'C', 1);
            
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Arial', '', 10);
            $this->Cell(50, 8, 'Cédula:', 1, 0, 'L');
            $this->Cell(0, 8, $est->cedula_estudiante, 1, 1, 'L');
            
            $this->Cell(50, 8, 'Nombres:', 1, 0, 'L');
            $this->Cell(0, 8, utf8_decode($est->nombre), 1, 1, 'L');
            
            $this->Cell(50, 8, 'Apellidos:', 1, 0, 'L');
            $this->Cell(0, 8, utf8_decode($est->apellido), 1, 1, 'L');
            
            $this->Cell(50, 8, 'Institución:', 1, 0, 'L');
            $this->Cell(0, 8, utf8_decode($est->institucion), 1, 1, 'L');
            
            $this->Cell(50, 8, 'Teléfono:', 1, 0, 'L');
            $this->Cell(0, 8, $est->telefono ?: 'N/A', 1, 1, 'L');
            
            $this->Ln(10);
            
            // Historial de asistencia
            $asistencias = $bd->prepare("SELECT a.*, ar.nombre_area FROM asistencia a JOIN areas_pasantias ar ON a.cod_area = ar.cod_area WHERE a.cedula_estudiante = ? ORDER BY a.fecha_inicio DESC");
            $asistencias->execute([$cedula]);
            
            $this->SetFillColor(226, 37, 53);
            $this->SetTextColor(255, 255, 255);
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 8, utf8_decode('HISTORIAL DE ASISTENCIA'), 1, 1, 'C', 1);
            
            if($asistencias->rowCount() > 0) {
                $this->SetFillColor(200, 200, 200);
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('Arial', 'B', 10);
                $this->Cell(80, 8, 'Área', 1, 0, 'C', 1);
                $this->Cell(40, 8, 'Fecha Inicio', 1, 0, 'C', 1);
                $this->Cell(40, 8, 'Fecha Fin', 1, 0, 'C', 1);
                $this->Cell(0, 8, 'Estado', 1, 1, 'C', 1);
                
                $this->SetFont('Arial', '', 9);
                while($asi = $asistencias->fetch(PDO::FETCH_OBJ)) {
                    $this->Cell(80, 8, utf8_decode($asi->nombre_area), 1, 0, 'L');
                    $this->Cell(40, 8, $asi->fecha_inicio, 1, 0, 'C');
                    $this->Cell(40, 8, $asi->fecha_fin ?: 'En curso', 1, 0, 'C');
                    $this->Cell(0, 8, $asi->activo ? 'Activo' : 'Inactivo', 1, 1, 'C');
                }
            } else {
                $this->SetFont('Arial', 'I', 10);
                $this->Cell(0, 8, 'No se encontraron registros de asistencia', 1, 1, 'C');
            }
        } else {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 8, 'ESTUDIANTE NO ENCONTRADO', 1, 1, 'C', 1);
        }
    }
}

// Conexión a la base de datos
function conexion() {
    $host = 'localhost';
    $dbname = 'SGM2';
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
$cedula = isset($_GET['cedula']) ? $_GET['cedula'] : '';

// Validar parámetros
if(empty($tipo)) {
    die('Tipo de reporte no especificado');
}

// Crear PDF y generar reporte según tipo
$pdf = new PDF();
$bd = conexion();

switch($tipo) {
    case 'estudiantes':
        $pdf->generarReporteEstudiantes($bd);
        break;
    case 'areas':
        $pdf->generarReporteAreas($bd);
        break;
    case 'individual':
        if(empty($cedula)) die('Cédula no especificada');
        $pdf->generarReporteIndividual($bd, $cedula);
        break;
    default:
        die('Tipo de reporte no válido');
}

// Generar PDF
$pdf->Output('Reporte_Pasantias_'.date('Ymd').'.pdf', 'I');
?>