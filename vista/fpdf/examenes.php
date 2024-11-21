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
      $this->Cell(100, 10, utf8_decode("REPORTE DE EMERGENCIA"), 0, 1, 'C', 0);
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
$cod_registro = isset($_GET['cod_registro']) ? $_GET['cod_registro'] : '';

if (empty($cod_registro)) {
    die('No se proporcionó una examenes');
}
echo "Valor de cod_consulta: " . $cod_registro;
$consulta_reporte_examenes = $bd->prepare("SELECT *, h.nombre AS nombre_h, h.apellido AS apellido_h  
                                            FROM registro c 
                                            INNER JOIN historias h ON c.cedula_h = h.cedula_historia
                                            INNER JOIN examenes e ON c.cod_examenes = e.cod_examenes
                                            WHERE cod_registro = :cod_registro");
$consulta_reporte_examenes->bindParam(':cod_registro', $cod_registro, PDO::PARAM_STR);
$consulta_reporte_examenes->execute();

$datos_reporte = $consulta_reporte_examenes->fetch(PDO::FETCH_OBJ);


if ($datos_reporte) {

     $pdf->SetFillColor(226 ,37 , 53); //colorFondo
     $pdf->SetTextColor(255, 255, 255); //colorTexto
     $pdf->SetDrawColor(0, 0, 0); //colorBorde
     $pdf->SetFont('Arial', 'B', 7);
     $pdf->Cell(38, 7, utf8_decode('NOMBRE'), 1, 0, 'C', 1);
     $pdf->Cell(38, 7, utf8_decode('APELLIDO'), 1, 0, 'C', 1);
     $pdf->Cell(38, 7, utf8_decode('CEDULA'), 1, 0, 'C', 1);
     $pdf->Cell(38, 7, utf8_decode('FECHA DEL EXAMEN'), 1, 0, 'C', 1);
     $pdf->Cell(38, 7, utf8_decode('NOMBRE DEL EXAMEN'), 1, 1, 'C', 1);
     


    



    /* TABLA */
     $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(38, 8, utf8_decode($datos_reporte->nombre_h), 1, 0, 'C', 0);
    $pdf->Cell(38, 8, utf8_decode($datos_reporte->apellido_h), 1, 0, 'C', 0);
    $pdf->Cell(38, 8, utf8_decode($datos_reporte->cedula_h), 1, 0, 'C', 0);
    $pdf->Cell(38, 8, utf8_decode($datos_reporte->fecha_r), 1, 0, 'C', 0);
    $pdf->Cell(38, 8, utf8_decode($datos_reporte->nombre_examen), 1, 1, 'C', 0);
    $pdf->Ln(10);

    $pdf->SetFillColor(226, 37, 53); // colorFondo
    $pdf->SetTextColor(255, 255, 255); // colorTexto
    $pdf->SetDrawColor(0, 0, 0); // colorBorde
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(190, 7, utf8_decode('OBSERVACION DEL EXAMEN'), 1, 1, 'C', 1);

    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetFont('Arial', '', 8);
    $pdf->MultiCell(190, 5, utf8_decode($datos_reporte->observacion_examen), 1, 'C',0);
    $pdf->Ln(10);
    
   
    $imagepath = 'usuarios/' . $datos_reporte->cedula_h . '-' . $datos_reporte->fecha_r . '-' . $datos_reporte->cod_examenes . '.jpeg';
    $image = 'usuarios/logo.png';

    if (file_exists($imagepath)) {
        $pdf->Image($imagepath,55.5, 120, 100, );
    } else {
        die('No se ha podido generar el reporte: la imagen no existe.');
    }
    
    ob_end_clean();
}

$pdf->Output();
