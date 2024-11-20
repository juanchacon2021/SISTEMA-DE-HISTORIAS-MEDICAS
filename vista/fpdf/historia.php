<?php

require('fpdf.php');

class PDF extends FPDF
{

   // Cabecera de página
   function Header()
   {

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
      $this->Cell(100, 10, utf8_decode("HISTORIA MEDICA"), 0, 1, 'C', 0);
      $this->Ln(7);

      /* CAMPOS DE LA TABLA */
      //color
      $this->SetFillColor(226 ,37 , 53); //colorFondo
      $this->SetTextColor(255, 255, 255); //colorTexto
      $this->SetDrawColor(0, 0, 0); //colorBorde
      $this->SetFont('Arial', 'B', 7);
      $this->Cell(20, 7, utf8_decode('CEDULA'), 1, 0, 'C', 1);
      $this->Cell(30, 7, utf8_decode('NOMBRES'), 1, 0, 'C', 1);
      $this->Cell(30, 7, utf8_decode('APELLIDOS'), 1, 0, 'C', 1);
      $this->Cell(35, 7, utf8_decode('FECHA DE NACIMIENTO'), 1, 0, 'C', 1);
      $this->Cell(20, 7, utf8_decode('EDAD'), 1, 0, 'C', 1);
      $this->Cell(30, 7, utf8_decode('ESTADO CIVIL'), 1, 0, 'C', 1);
      $this->Cell(30, 7, utf8_decode('TELEFONO'), 1, 1, 'C', 1);
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
    return new PDO('mysql:host=localhost;dbname=shm-cdi.2', 'root', '123456');
}

$pdf = new PDF();
$pdf->AddPage(); 
$pdf->AliasNbPages();

$i = 0;
$pdf->SetFont('Arial', '', 9);
$pdf->SetDrawColor(163, 163, 163); // colorBorde

$bd = conexion();
$cedula_historia = isset($_GET['cedula_historia']) ? $_GET['cedula_historia'] : '';

if (empty($cedula_historia)) {
    die('No se proporcionó una cédula válida.');
}

$consulta_reporte_historia = $bd->prepare("SELECT * FROM historias WHERE cedula_historia = :cedula_historia");
$consulta_reporte_historia->bindParam(':cedula_historia', $cedula_historia, PDO::PARAM_STR);
$consulta_reporte_historia->execute();

$consulta_reporte = $bd->prepare("SELECT * FROM consultas WHERE cedula_h = :cedula_historia");
$consulta_reporte->bindParam(':cedula_historia', $cedula_historia, PDO::PARAM_STR);
$consulta_reporte->execute();

$datos_reporte = $consulta_reporte_historia->fetch(PDO::FETCH_OBJ);

if ($datos_reporte) {
    /* TABLA */
    $pdf->Cell(20, 8, utf8_decode($datos_reporte->cedula_historia), 1, 0, 'C', 0);
    $pdf->Cell(30, 8, utf8_decode($datos_reporte->nombre), 1, 0, 'C', 0);
    $pdf->Cell(30, 8, utf8_decode($datos_reporte->apellido), 1, 0, 'C', 0);
    $pdf->Cell(35, 8, utf8_decode($datos_reporte->fecha_nac), 1, 0, 'C', 0);
    $pdf->Cell(20, 8, utf8_decode($datos_reporte->edad), 1, 0, 'C', 0);
    $pdf->Cell(30, 8, utf8_decode($datos_reporte->estadocivil), 1, 0, 'C', 0);
    $pdf->Cell(30, 8, utf8_decode($datos_reporte->telefono), 1, 1, 'C', 0);

    $pdf->Ln(5);

    $pdf->SetFillColor(226, 37, 53); // colorFondo
    $pdf->SetTextColor(255, 255, 255); // colorTexto
    $pdf->SetDrawColor(0, 0, 0); // colorBorde
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(70, 7, utf8_decode('DIRECCION'), 1, 0, 'C', 1);
    $pdf->Cell(30, 7, utf8_decode('OCUPACION'), 1, 0, 'C', 1);
    $pdf->Cell(35, 7, utf8_decode('HDA'), 1, 0, 'C', 1);
    $pdf->Cell(60, 7, utf8_decode('ALERGIAS'), 1, 1, 'C', 1);


    // Datos de la segunda tabla
    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(70, 8, utf8_decode($datos_reporte->direccion), 1, 0, 'C', 0);
    $pdf->Cell(30, 8, utf8_decode($datos_reporte->ocupacion), 1, 0, 'C', 0);
    $pdf->Cell(35, 8, utf8_decode($datos_reporte->hda), 1, 0, 'C', 0);
    $pdf->Cell(60, 8, utf8_decode($datos_reporte->alergias), 1, 1, 'C', 0);

    $pdf->Ln(5);

    $pdf->SetFillColor(226, 37, 53); // colorFondo
    $pdf->SetTextColor(255, 255, 255); // colorTexto
    $pdf->SetDrawColor(0, 0, 0); // colorBorde
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(60, 7, utf8_decode('ALERGIAS MEDICAS'), 1, 0, 'C', 1);
    $pdf->Cell(30, 7, utf8_decode('QUIRURGICO'), 1, 0, 'C', 1);
    $pdf->Cell(35, 7, utf8_decode('TRANS SANGUINEO'), 1, 0, 'C', 1);
    $pdf->Cell(35, 7, utf8_decode('PSICOSOCIAL'), 1, 0, 'C', 1);
    $pdf->Cell(35, 7, utf8_decode('HABITO TOXICO'), 1, 1, 'C', 1);


    // Datos de la segunda tabla
    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(60, 8, utf8_decode($datos_reporte->alergias_med), 1, 0, 'C', 0);
    $pdf->Cell(30, 8, utf8_decode($datos_reporte->quirurgico), 1, 0, 'C', 0);
    $pdf->Cell(35, 8, utf8_decode($datos_reporte->transsanguineo), 1, 0, 'C', 0);
    $pdf->Cell(35, 8, utf8_decode($datos_reporte->psicosocial), 1, 0, 'C', 0);
    $pdf->Cell(35, 8, utf8_decode($datos_reporte->habtoxico), 1, 1, 'C', 0);

    $pdf->Ln(5);

    $pdf->SetFillColor(226, 37, 53); // colorFondo
    $pdf->SetTextColor(255, 255, 255); // colorTexto
    $pdf->SetDrawColor(0, 0, 0); // colorBorde
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(65, 7, utf8_decode('ANTECEDENTES PATERNOS'), 1, 0, 'C', 1);
    $pdf->Cell(65, 7, utf8_decode('ANTECEDENTES MATERNOS'), 1, 0, 'C', 1);
    $pdf->Cell(65, 7, utf8_decode('ANTECEDENTES DE HERMANOS'), 1, 1, 'C', 1);

    // Datos de la tercera tabla
    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 8, utf8_decode($datos_reporte->antc_padre), 1, 0, 'C', 0);
    $pdf->Cell(65, 8, utf8_decode($datos_reporte->antc_madre), 1, 0, 'C', 0);
    $pdf->Cell(65, 8, utf8_decode($datos_reporte->antc_hermano), 1, 1, 'C', 0);

    $pdf->Ln(5);


   } else {
    die('No se encontraron datos para la cédula especificada.');
}

$pdf->Output();
