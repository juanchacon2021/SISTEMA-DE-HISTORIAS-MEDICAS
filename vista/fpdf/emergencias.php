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
$cod_emergencia = isset($_GET['cod_emergencia']) ? $_GET['cod_emergencia'] : '';

if (empty($cod_emergencia)) {
    die('No se proporcionó una emergencia');
}
echo "Valor de cod_emergencia: " . $cod_emergencia;
$consulta_reporte_emergencia = $bd->prepare("SELECT *, h.nombre AS nombre_h, h.apellido AS apellido_h  
                                            FROM emergencias e 
                                            INNER JOIN historias h ON e.cedula_h = h.cedula_historia
                                            INNER JOIN personal p ON e.cedula_p = p.cedula_personal
                                            WHERE cod_emergencia = :cod_emergencia");
$consulta_reporte_emergencia->bindParam(':cod_emergencia', $cod_emergencia, PDO::PARAM_STR);
$consulta_reporte_emergencia->execute();

$datos_reporte = $consulta_reporte_emergencia->fetch(PDO::FETCH_OBJ);


if ($datos_reporte) {

     $pdf->SetFillColor(226 ,37 , 53); //colorFondo
     $pdf->SetTextColor(255, 255, 255); //colorTexto
     $pdf->SetDrawColor(0, 0, 0); //colorBorde
     $pdf->SetFont('Arial', 'B', 7);
     $pdf->Cell(38, 7, utf8_decode('NOMBRE'), 1, 0, 'C', 1);
     $pdf->Cell(38, 7, utf8_decode('APELLIDO'), 1, 0, 'C', 1);
     $pdf->Cell(38, 7, utf8_decode('CEDULA'), 1, 0, 'C', 1);
     $pdf->Cell(38, 7, utf8_decode('FECHA DE INGRESO'), 1, 0, 'C', 1);
     $pdf->Cell(38, 7, utf8_decode('HORA DE INGRESO'), 1, 1, 'C', 1);


    



    /* TABLA */
     $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(38, 8, utf8_decode($datos_reporte->nombre_h), 1, 0, 'C', 0);
    $pdf->Cell(38, 8, utf8_decode($datos_reporte->apellido_h), 1, 0, 'C', 0);
    $pdf->Cell(38, 8, utf8_decode($datos_reporte->cedula_h), 1, 0, 'C', 0);
    $pdf->Cell(38, 8, utf8_decode($datos_reporte->fechaingreso), 1, 0, 'C', 0);
    $pdf->Cell(38, 8, utf8_decode($datos_reporte->horaingreso), 1, 1, 'C', 0);
   
    $pdf->Ln(10);

    
    
    $pdf->SetFillColor(226, 37, 53); // colorFondo
    $pdf->SetTextColor(255, 255, 255); // colorTexto
    $pdf->SetDrawColor(0, 0, 0); // colorBorde
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(63, 7, utf8_decode('MOTIVO DE INGRESO'), 1, 0, 'C', 1);
    $pdf->Cell(63, 7, utf8_decode('DIAGNOSTICO'), 1, 0, 'C', 1);
    $pdf->Cell(63, 7, utf8_decode('TRATAMIENTOS'), 1, 1, 'C', 1);

    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetFont('Arial', '', 8);
    

    $pdf->Cell(63, 5, utf8_decode($datos_reporte->motingreso), 1, 0, 'C',0);
    $pdf->Cell(63, 5, utf8_decode($datos_reporte->diagnostico_e), 1, 0, 'C',0);
    $pdf->MultiCell(63, 5, utf8_decode($datos_reporte->tratamientos), 1,'C',0);

    $pdf->Ln(10);
    

    $pdf->SetFillColor(226, 37, 53); // colorFondo
    $pdf->SetTextColor(255, 255, 255); // colorTexto
    $pdf->SetDrawColor(0, 0, 0); // colorBorde
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(47, 7, utf8_decode('CARGO'), 1, 0, 'C', 1);
    $pdf->Cell(47, 7, utf8_decode('NOMBRE'), 1, 0, 'C', 1);
    $pdf->Cell(47, 7, utf8_decode('APELLIDO'), 1, 0, 'C', 1);
    $pdf->Cell(47, 7, utf8_decode('CEDULA'), 1, 1, 'C', 1);

    $pdf->SetTextColor(0, 0, 0); // colorTexto
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(47, 8, utf8_decode($datos_reporte->cargo), 1, 0, 'C', 0);
    $pdf->Cell(47, 8, utf8_decode($datos_reporte->nombre), 1, 0, 'C', 0);
    $pdf->Cell(47, 8, utf8_decode($datos_reporte->apellido), 1, 0, 'C', 0);
    $pdf->Cell(47, 8, utf8_decode($datos_reporte->cedula_p), 1, 1, 'C', 0);
    

    ob_end_clean();
    



   } else {
    die('No se a podido generar el reporte de la emergncia');
}

$pdf->Output();
