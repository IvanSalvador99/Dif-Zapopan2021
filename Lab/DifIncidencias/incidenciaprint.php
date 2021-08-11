<?php
    include_once '../../Tools/fpdf/fpdf.php';
    include_once '../funciones.php';
    session_start();
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    $query = "SELECT * FROM $vw_incidencias WHERE id='" . $_POST['id'] . "'";
    if ($result = mysql_query($query, $con)) {
        $rowincidencia = mysql_fetch_array($result);
        $query = "SELECT * FROM $trabajadores WHERE id='" . $rowincidencia['idusuario'] . "'";
        if ($result = mysql_query($query, $con)) {
            $rowusuario = mysql_fetch_array($result);
        } else {
            echo "Error en query: " . $query . "\nError: " . mysql_error();
        }
    } else {
        echo "Error en query: " . $query . "\nError: " . mysql_error();
    }
    
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Image('../imagenes/LogoDIF.jpg', 20, 10, 30);
    $pdf->Cell(80);
    $pdf->Cell(30, 20, utf8_decode('Formato de incidencias'));
    $pdf->ln(15);
    $pdf->SetX(76);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(30, 10, utf8_decode('Departamento de Desarrollo de Capital Humano'));
    $pdf->ln(25);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(50, 10, utf8_decode('Departamento:'));
    $pdf->SetX(135);
    $pdf->Cell(50, 10, utf8_decode('Fecha:'));
    $pdf->ln(8);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', '', 12);
    
    $query = "SELECT * FROM $departamentos WHERE id='" . $rowusuario['departamento'] . "'";
    if ($result = mysql_query($query, $con)) {
        $rowdepartamento = mysql_fetch_array($result);
    } else {
        echo "Error en query: " . $query . "\nError: " . mysql_error();
    }
    
    $pdf->Cell(50, 10, utf8_decode($rowdepartamento['departamento']));
    $pdf->SetX(135);
    $pdf->Cell(50, 10, utf8_decode(date("Y-m-d")));
    
    $pdf->ln(25);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(50, 10, utf8_decode('ID de incicencia:'));
    $pdf->SetX(70);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 10, utf8_decode($rowincidencia['id']));
    $pdf->ln(8);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(50, 10, utf8_decode('Numero de empleado:'));
    $pdf->SetX(70);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 10, utf8_decode($rowusuario['numeroempleado']));
    $pdf->ln(8);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(50, 10, utf8_decode('Nombre:'));
    $pdf->SetX(70);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 10, utf8_decode($rowusuario['apaterno'] . " " . $rowusuario['amaterno'] . " " . $rowusuario['nombre']));
    $pdf->ln(8);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(50, 10, utf8_decode('Fecha:'));
    $pdf->SetX(70);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 10, utf8_decode($rowincidencia['fecha']));
    $pdf->ln(8);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(50, 10, utf8_decode('Tipo:'));
    $pdf->SetX(70);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 10, ucfirst(strtolower(utf8_decode($rowincidencia['tipo']))));
    $pdf->ln(8);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(50, 10, utf8_decode('Concepto:'));
    $pdf->SetX(70);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 10, ucfirst(strtolower(utf8_decode($rowincidencia['concepto']))));
    $pdf->ln(15);
    $pdf->SetX(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(50, 10, utf8_decode('Descripción:'));
    $pdf->SetX(70);
    $pdf->SetFont('Arial', '', 10); // 
    $pdf->MultiCell(0, 7, utf8_decode($rowincidencia['descripcion']), 0, 'J');
    
    $pdf->Output();
?>