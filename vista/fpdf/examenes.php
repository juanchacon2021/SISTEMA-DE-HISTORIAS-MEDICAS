<?php
require('fpdf.php');

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        // Ruta relativa al logo desde la ubicación del script
        $logoPath = __DIR__.'/../../img/logo.png';
        
        // Verificar si el logo existe antes de intentar mostrarlo
        if(file_exists($logoPath)) {
            $this->Image($logoPath, 180, 5, 20);
        } else {
            // Opcional: Mostrar mensaje de logo no encontrado (solo para depuración)
            // $this->SetFont('Arial','I',8);
            // $this->Cell(0,10,'Logo no encontrado: '.$logoPath,0,1);
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

        /* TÍTULO DE LA TABLA */
        $this->SetTextColor(226, 37, 53);
        $this->Cell(45);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(100, 10, utf8_decode("REPORTE DE EXAMEN MÉDICO"), 0, 1, 'C', 0);
        $this->Ln(7);
    }

    // Pie de página
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

// Crear instancia de PDF
$pdf = new PDF();
$pdf->AddPage(); 
$pdf->AliasNbPages();

// Obtener parámetros
$cedula = isset($_GET['cedula']) ? $_GET['cedula'] : '';
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$cod_examen = isset($_GET['cod_examen']) ? $_GET['cod_examen'] : '';

// Validar parámetros
if (empty($cedula) || empty($fecha) || empty($cod_examen)) {
    die('Parámetros incompletos para generar el reporte');
}

// Conectar a la base de datos
$bd = conexion();

// Consulta para obtener los datos del examen
$consulta_reporte = $bd->prepare("
    SELECT e.*, p.nombre, p.apellido, t.nombre_examen, t.descripcion_examen
    FROM examen e
    JOIN paciente p ON e.cedula_paciente = p.cedula_paciente
    JOIN tipo_de_examen t ON e.cod_examen = t.cod_examen
    WHERE e.cedula_paciente = :cedula 
    AND e.fecha_e = :fecha 
    AND e.cod_examen = :cod_examen
");

$consulta_reporte->bindParam(':cedula', $cedula, PDO::PARAM_INT);
$consulta_reporte->bindParam(':fecha', $fecha, PDO::PARAM_STR);
$consulta_reporte->bindParam(':cod_examen', $cod_examen, PDO::PARAM_INT);
$consulta_reporte->execute();

$datos_reporte = $consulta_reporte->fetch(PDO::FETCH_OBJ);

if (!$datos_reporte) {
    die('No se encontró el registro del examen');
}

// Encabezado de información del paciente
$pdf->SetFillColor(226, 37, 53);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(190, 7, utf8_decode('INFORMACIÓN DEL PACIENTE'), 1, 1, 'C', 1);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 10);

// Datos del paciente
$pdf->Cell(47.5, 7, utf8_decode('Nombre:'), 1, 0, 'L', 0);
$pdf->Cell(47.5, 7, utf8_decode($datos_reporte->nombre), 1, 0, 'L', 0);
$pdf->Cell(47.5, 7, utf8_decode('Apellido:'), 1, 0, 'L', 0);
$pdf->Cell(47.5, 7, utf8_decode($datos_reporte->apellido), 1, 1, 'L', 0);

$pdf->Cell(47.5, 7, utf8_decode('Cédula:'), 1, 0, 'L', 0);
$pdf->Cell(47.5, 7, utf8_decode($datos_reporte->cedula_paciente), 1, 0, 'L', 0);
$pdf->Cell(47.5, 7, utf8_decode('Fecha Examen:'), 1, 0, 'L', 0);
$pdf->Cell(47.5, 7, utf8_decode($datos_reporte->fecha_e), 1, 1, 'L', 0);

$pdf->Ln(10);

// Información del examen
$pdf->SetFillColor(226, 37, 53);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(190, 7, utf8_decode('INFORMACIÓN DEL EXAMEN'), 1, 1, 'C', 1);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 10);

$pdf->Cell(47.5, 7, utf8_decode('Tipo de Examen:'), 1, 0, 'L', 0);
$pdf->Cell(142.5, 7, utf8_decode($datos_reporte->nombre_examen), 1, 1, 'L', 0);

$pdf->Cell(47.5, 7, utf8_decode('Descripción:'), 1, 0, 'L', 0);
$pdf->Cell(142.5, 7, utf8_decode($datos_reporte->descripcion_examen ?: 'No especificada'), 1, 1, 'L', 0);

$pdf->Cell(47.5, 7, utf8_decode('Fecha:'), 1, 0, 'L', 0);
$pdf->Cell(47.5, 7, utf8_decode($datos_reporte->fecha_e), 1, 0, 'L', 0);
$pdf->Cell(47.5, 7, utf8_decode('Hora:'), 1, 0, 'L', 0);
$pdf->Cell(47.5, 7, utf8_decode($datos_reporte->hora_e), 1, 1, 'L', 0);

$pdf->Ln(10);

// Observaciones
$pdf->SetFillColor(226, 37, 53);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(190, 7, utf8_decode('OBSERVACIONES'), 1, 1, 'C', 1);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 10);
$observaciones = $datos_reporte->observacion_examen ?: 'No se registraron observaciones';
$pdf->MultiCell(190, 7, utf8_decode($observaciones), 1, 'L');
$pdf->Ln(10);

// Imagen del examen si existe
if ($datos_reporte->ruta_imagen) {
    // Convertir ruta relativa a absoluta si es necesario
    $imagenPath = strpos($datos_reporte->ruta_imagen, '/') === 0 ? 
                 $datos_reporte->ruta_imagen : 
                 __DIR__.'/../../'.$datos_reporte->ruta_imagen;
    
    if (file_exists($imagenPath)) {
        $pdf->SetFillColor(226, 37, 53);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 7, utf8_decode('RESULTADO DEL EXAMEN'), 1, 1, 'C', 1);
        
        // Calcular posición Y para la imagen
        $yPos = $pdf->GetY() + 5;
        $pdf->Image($imagenPath, 55, $yPos, 100);
        
        // Ajustar posición después de la imagen
        $pdf->SetY($yPos + 105);
    } else {
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(190, 7, utf8_decode('La imagen del examen no se encuentra disponible'), 0, 1, 'C');
    }
} else {
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(190, 7, utf8_decode('No hay imagen adjunta para este examen'), 0, 1, 'C');
}

// Generar PDF
$pdf->Output('Reporte_Examen_'.$cedula.'_'.$fecha.'.pdf', 'I');