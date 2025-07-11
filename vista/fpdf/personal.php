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
        $this->SetTextColor(226, 37, 53);
        $this->Cell(45); // mover a la derecha
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(100, 10, utf8_decode("Datos del Personal"), 0, 1, 'C', 0);
        $this->Ln(7);

        /* CAMPOS DE LA TABLA */
        //color
        $this->SetFillColor(226, 37, 53); //colorFondo
        $this->SetTextColor(255, 255, 255); //colorTexto
        $this->SetDrawColor(0, 0, 0); //colorBorde
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(20, 5, utf8_decode('CÉDULA'), 1, 0, 'C', 1);
        $this->Cell(30, 5, utf8_decode('NOMBRE'), 1, 0, 'C', 1);
        $this->Cell(30, 5, utf8_decode('APELLIDO'), 1, 0, 'C', 1);
        $this->Cell(55, 5, utf8_decode('CORREO'), 1, 0, 'C', 1);
        $this->Cell(22, 5, utf8_decode('CARGO'), 1, 0, 'C', 1);
        $this->Ln(5);
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

// Conexión a la base de datos
function conexion() {
    try {
        return new PDO('mysql:host=localhost;dbname=sgm;charset=utf8mb4', 'root', '');
    } catch (PDOException $e) {
        die('Error de conexión: ' . $e->getMessage());
    }
}

$pdf = new PDF();
$pdf->AddPage(); 
$pdf->AliasNbPages();

$pdf->SetFont('Arial', '', 9);
$pdf->SetDrawColor(163, 163, 163); // colorBorde

$bd = conexion();

try {
    // Consulta para obtener el personal
    $consulta_personal = $bd->query("SELECT * FROM personal");
    $personas = $consulta_personal->fetchAll(PDO::FETCH_OBJ);

    if ($personas) {
        foreach ($personas as $persona) {
            $pdf->Cell(20, 5, utf8_decode($persona->cedula_personal), 1, 0, 'C', 0);
            $pdf->Cell(30, 5, utf8_decode($persona->nombre), 1, 0, 'C', 0);
            $pdf->Cell(30, 5, utf8_decode($persona->apellido), 1, 0, 'C', 0);
            $pdf->Cell(55, 5, utf8_decode($persona->correo), 1, 0, 'C', 0);
            $pdf->Cell(22, 5, utf8_decode($persona->cargo), 1, 0, 'C', 0);
            $pdf->Ln(5); 
        }
    } else {
        $pdf->Cell(0, 10, utf8_decode('No se encontraron registros de personal'), 0, 1);
    }
} catch (PDOException $e) {
    $pdf->Cell(0, 10, utf8_decode('Error al consultar la base de datos: ' . $e->getMessage()), 0, 1);
}

$pdf->Output();