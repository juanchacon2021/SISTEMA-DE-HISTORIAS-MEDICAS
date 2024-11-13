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
    return new PDO('mysql:host=localhost;dbname=shm-cdi', 'root', '123456');
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

$datos_reporte = $consulta_reporte_historia->fetch(PDO::FETCH_OBJ);

// Consulta para la tabla examenes_r
$consulta_examenes_r = $bd->prepare("SELECT * FROM examenes_r WHERE cedula_h = :cedula_historia");
$consulta_examenes_r->bindParam(':cedula_historia', $cedula_historia, PDO::PARAM_STR);
$consulta_examenes_r->execute();
$datos_examenes_r = $consulta_examenes_r->fetch(PDO::FETCH_OBJ);

// Consulta para la tabla examenes_s
$consulta_examenes_s = $bd->prepare("SELECT * FROM examenes_s WHERE cedula_h = :cedula_historia");
$consulta_examenes_s->bindParam(':cedula_historia', $cedula_historia, PDO::PARAM_STR);
$consulta_examenes_s->execute();
$datos_examenes_s = $consulta_examenes_s->fetch(PDO::FETCH_OBJ);

// Consulta para la tabla antecedentes
$consulta_antecedentes = $bd->prepare("SELECT * FROM antecedentes WHERE cedula_h = :cedula_historia");
$consulta_antecedentes->bindParam(':cedula_historia', $cedula_historia, PDO::PARAM_STR);
$consulta_antecedentes->execute();
$datos_antecedentes = $consulta_antecedentes->fetch(PDO::FETCH_OBJ);

// Consulta para la tabla examenes_f
$consulta_examenes_f = $bd->prepare("SELECT * FROM examenes_F WHERE cedula_h = :cedula_historia");
$consulta_examenes_f->bindParam(':cedula_historia', $cedula_historia, PDO::PARAM_STR);
$consulta_examenes_f->execute();
$datos_examenes_f = $consulta_examenes_f->fetch(PDO::FETCH_OBJ);

if ($datos_reporte && $datos_examenes_r && $datos_examenes_s && $datos_antecedentes && $datos_examenes_f) {
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
    $pdf->Cell(32.5, 7, utf8_decode('CABEZA-CRANEO'), 1, 0, 'C', 1);
    $pdf->Cell(32.5, 7, utf8_decode('OJOS'), 1, 0, 'C', 1);
    $pdf->Cell(32.5, 7, utf8_decode('NARIZ'), 1, 0, 'C', 1);
    $pdf->Cell(32.5, 7, utf8_decode('BOCA ABIERTA'), 1, 0, 'C', 1);
    $pdf->Cell(32.5, 7, utf8_decode('BOCA CERRADA'), 1, 0, 'C', 1);
    $pdf->Cell(32.5, 7, utf8_decode('TIROIDES'), 1, 1, 'C', 1);
   //  



    // Datos de la segunda tabla
    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(32.5, 8, utf8_decode($datos_examenes_r->cabeza_craneo), 1, 0, 'C', 0);
    $pdf->Cell(32.5, 8, utf8_decode($datos_examenes_r->ojos), 1, 0, 'C', 0);
    $pdf->Cell(32.5, 8, utf8_decode($datos_examenes_r->nariz), 1, 0, 'C', 0);
    $pdf->Cell(32.5, 8, utf8_decode($datos_examenes_r->boca_abierta), 1, 0, 'C', 0);
    $pdf->Cell(32.5, 8, utf8_decode($datos_examenes_r->boca_cerrada), 1, 0, 'C', 0);
    $pdf->Cell(32.5, 8, utf8_decode($datos_examenes_r->tiroides), 1, 1, 'C', 0);


   $pdf->Ln(5);

    $pdf->SetFillColor(226, 37, 53); // colorFondo
    $pdf->SetTextColor(255, 255, 255); // colorTexto
    $pdf->SetDrawColor(0, 0, 0); // colorBorde
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(32.5, 7, utf8_decode('EXTREMIDADES'), 1, 0, 'C', 1);
    $pdf->Cell(32.5, 7, utf8_decode('RESPIRATORIO'), 1, 0, 'C', 1);
    $pdf->Cell(32.5, 7, utf8_decode('CARDIOVASCULAR'), 1, 0, 'C', 1);
    $pdf->Cell(32.5, 7, utf8_decode('ABDOMEN'), 1, 0, 'C', 1);
    $pdf->Cell(32.5, 7, utf8_decode('EXTREMIDADES S'), 1, 0, 'C', 1);
    $pdf->Cell(32.5, 7, utf8_decode('NEUROLOGICOS'), 1, 1, 'C', 1);


    // Datos de la tercera tabla
    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(32.5, 8, utf8_decode($datos_examenes_r->extremidades), 1, 0, 'C', 0);
    $pdf->Cell(32.5, 8, utf8_decode($datos_examenes_s->respiratorio), 1, 0, 'C', 0);
    $pdf->Cell(32.5, 8, utf8_decode($datos_examenes_s->cardiovascular), 1, 0, 'C', 0);
    $pdf->Cell(32.5, 8, utf8_decode($datos_examenes_s->abdomen), 1, 0, 'C', 0);
    $pdf->Cell(32.5, 8, utf8_decode($datos_examenes_s->extremidades_s), 1, 0, 'C', 0);
    $pdf->Cell(32.5, 8, utf8_decode($datos_examenes_s->neurologicos), 1, 1, 'C', 0);


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
    $pdf->Cell(65, 8, utf8_decode($datos_antecedentes->antec_padre), 1, 0, 'C', 0);
    $pdf->Cell(65, 8, utf8_decode($datos_antecedentes->antec_madre), 1, 0, 'C', 0);
    $pdf->Cell(65, 8, utf8_decode($datos_antecedentes->antec_hermano), 1, 1, 'C', 0);

    $pdf->Ln(5);

    $pdf->SetFillColor(226, 37, 53); // colorFondo
    $pdf->SetTextColor(255, 255, 255); // colorTexto
    $pdf->SetDrawColor(0, 0, 0); // colorBorde
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(195, 7, utf8_decode('EXAMEN FISICO GENERAL'), 1, 1, 'C', 1);
    // Datos de la tercera tabla
    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(195, 8, utf8_decode($datos_examenes_f->general), 1, 1, 'C', 0);




   } else {
    die('No se encontraron datos para la cédula especificada.');
}

$pdf->Output();
