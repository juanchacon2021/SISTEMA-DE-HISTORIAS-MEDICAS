<?php
require('fpdf.php');

class PDF extends FPDF
{
    private $titulo;
    private $filtros;
    private $campos;
    private $datos;
    
    function setTitulo($titulo) {
        $this->titulo = $titulo;
    }
    
    function setFiltros($filtros) {
        $this->filtros = $filtros;
    }
    
    function setCampos($campos) {
        $this->campos = $campos;
    }
    
    function setDatos($datos) {
        $this->datos = $datos;
    }
    
    function Header() {
        $logoPath = __DIR__.'/../../img/logo.png';
        
        if(file_exists($logoPath)) {
            $this->Image($logoPath, 180, 5, 20);
        }
        
        $this->SetFont('Arial', 'B', 19);
        $this->Cell(45);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(110, 15, utf8_decode('SISTEMA DE GESTIÓN MÉDICO'), 2, 1, 'C', 0);
        $this->Ln(3);
        
        // Título del reporte
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, utf8_decode($this->titulo), 0, 1, 'C');
        $this->Ln(5);
        
        // Filtros aplicados
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 6, utf8_decode('Filtros aplicados: '.$this->filtros), 0, 1);
        $this->Ln(5);
        
        // Fecha de generación
        $this->Cell(0, 6, utf8_decode('Generado el: '.date('d/m/Y H:i:s')), 0, 1);
        $this->Ln(10);
        
        // Encabezados de tabla
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(226, 37, 53);
        $this->SetTextColor(255, 255, 255);
        
        $anchos = $this->calcularAnchosColumnas();
        $i = 0;
        foreach($this->campos as $campo) {
            $this->Cell($anchos[$i], 8, utf8_decode($campo['titulo']), 1, 0, 'C', 1);
            $i++;
        }
        $this->Ln();
    }
    
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ').$this->PageNo().'/{nb}', 0, 0, 'C');
    }
    
    function generarReporte() {
        $this->AddPage('L');
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(0, 0, 0);
        
        $anchos = $this->calcularAnchosColumnas();
        
        foreach($this->datos as $fila) {
            $i = 0;
            foreach($this->campos as $campo) {
                $valor = isset($fila[$campo['campo']]) ? $fila[$campo['campo']] : '';
                $this->Cell($anchos[$i], 8, utf8_decode($valor), 1, 0, 'L');
                $i++;
            }
            $this->Ln();
            
            if($this->GetY() > 260) {
                $this->AddPage('L');
            }
        }
    }
    
    private function calcularAnchosColumnas() {
        $anchos = [];
        $totalCampos = count($this->campos);
        $anchoTotal = 270; // Ancho total disponible en modo horizontal
        
        // Asignar anchos proporcionales
        foreach($this->campos as $campo) {
            $anchos[] = $anchoTotal / $totalCampos;
        }
        
        return $anchos;
    }
}

// Conexión a la base de datos
function conexion() {
    $host = 'localhost';
    $dbname = 'sgm';
    $user = 'root';
    $pass = '';
    
    try {
        return new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    } catch (PDOException $e) {
        die('Error de conexión: '.$e->getMessage());
    }
}

// Obtener parámetros
$tabla = isset($_GET['tabla']) ? $_GET['tabla'] : '';
$filtros = isset($_GET['filtros']) ? json_decode($_GET['filtros'], true) : [];
$campos = isset($_GET['campos']) ? json_decode($_GET['campos'], true) : [];

// Validar parámetros
if(empty($tabla) || empty($campos)) {
    die('Parámetros incompletos para generar el reporte');
}

// Crear PDF y generar reporte
$pdf = new PDF();
$bd = conexion();

// Configurar título
$titulosTablas = [
    'paciente' => 'Reporte de Pacientes',
    'consulta' => 'Reporte de Consultas',
    'emergencia' => 'Reporte de Emergencias',
    'examen' => 'Reporte de Exámenes',
    'medicamentos' => 'Reporte de Medicamentos',
    'personal' => 'Reporte de Personal'
];

$pdf->setTitulo($titulosTablas[$tabla] ?? 'Reporte Personalizado');

// Configurar texto de filtros
$textoFiltros = '';
foreach($filtros as $filtro) {
    if(!empty($filtro['valor'])) {
        $textoFiltros .= $filtro['campo'].' '.$filtro['operador'].' '.$filtro['valor'].', ';
    }
}
$textoFiltros = rtrim($textoFiltros, ', ');
if(empty($textoFiltros)) {
    $textoFiltros = 'Sin filtros aplicados';
}
$pdf->setFiltros($textoFiltros);

// Configurar campos
$pdf->setCampos($campos);

// Obtener datos
$sql = "SELECT ";
$camposSql = [];
foreach($campos as $campo) {
    $camposSql[] = $campo['campo'];
}
$sql .= implode(', ', $camposSql)." FROM $tabla";

// Aplicar filtros
$where = [];
$params = [];
foreach($filtros as $filtro) {
    if(!empty($filtro['valor'])) {
        $where[] = $filtro['campo'].' '.$filtro['operador'].' ?';
        $params[] = $filtro['valor'];
    }
}

if(!empty($where)) {
    $sql .= ' WHERE '.implode(' AND ', $where);
}

// Ejecutar consulta
$stmt = $bd->prepare($sql);
$stmt->execute($params);
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdf->setDatos($datos);
$pdf->AliasNbPages();
$pdf->generarReporte();

// Generar PDF
$pdf->Output('Reporte_'.ucfirst($tabla).'_'.date('Ymd_His').'.pdf', 'I');
?>