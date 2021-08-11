<?php
    require_once("../../funciones.php");
    require_once("../../../Tools/fpdf/code128.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    $query = "SELECT * FROM $vw_persona WHERE id = '" . $_GET['id'] . "'";
    /*echo "</br></br>Query:</br>";
    echo ($query);*/
    
    if ($result = mysql_query($query, $con)){
        $row = mysql_fetch_array($result);
        /*echo "</br></br>Row:</br>";
        var_dump($row);*/
    } else {
        die (mysql_error());
    }
    
    $pdf = new PDF_Code128('P', 'mm', 'Letter');
            $pdf->AddPage();
            $pdf->SetAutoPageBreak(false, 0);
            $alto = $pdf->GetPageHeight();
            $ancho = $pdf->GetPageWidth();
            
            $pdf->Rect(5, 5, 98, 130);
            $pdf->Rect(10, 10, 25, 30);
            $pdf->Rect(10, 10, 25, 30);
            $pdf->SetXY(10,10);
            $pdf->Image('../../../Tools/imagenes/LogoDIF.png', 60, 10, 20);
            $pdf->SetFont("Arial", "", 16);
            $pdf->SetXY(55, 33);
            $pdf->Cell(30, 4, iconv('UTF-8', 'windows-1252', 'Dif Zapopan'), 0, 2, "C");
            $pdf->SetXY(55, 38);
            $pdf->SetFont("Arial", "", 10);
            $pdf->Cell(30, 5, iconv('UTF-8', 'windows-1252', 'Tarjeta de identificación de beneficiario'), 0, 0, "C");
            $pdf->SetFont("Arial", "B", 9);
            $pdf->SetXY(0, 45);
            $pdf->Rect(9, 45, 90, 30);
            $pdf->Cell(108, 5, iconv('UTF-8', 'windows-1252', 'Datos del beneficiario'), 0, 0, "C");
            $pdf->SetXY(10, 50);
            $pdf->Cell(16, 5, iconv('UTF-8', 'windows-1252', 'Nombre: '), 0, 0, "L");
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $row["nombre"]), 0, 0, "L");
            $pdf->SetXY(26, 54);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $row["apaterno"] . " " . $row["amaterno"]), 0, 0, "L");
            $pdf->SetFont("Arial", "B", 9);
            $pdf->SetXY(10, 58);
            $pdf->Cell(16, 5, iconv('UTF-8', 'windows-1252', 'CURP: '), 0, 0, "L");
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $row["curp"]), 0, 0, "L");
            $pdf->SetFont("Arial", "B", 9);
            $pdf->SetXY(10, 62);
            $pdf->Cell(16, 5, iconv('UTF-8', 'windows-1252', 'Domicilio: '), 0, 0, "L");
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $row["calle"] . " " . $row['numext'] . " - " . $row['numint']), 0, 0, "L");
            $pdf->SetXY(26, 66);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $row['colonia']), 0, 0, "L");
            $pdf->SetFont("Arial", "B", 9);
            $pdf->SetXY(10, 70);
            $pdf->Cell(16, 5, iconv('UTF-8', 'windows-1252', 'Telefono: '), 0, 0, "L");
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $row['telefono']), 0, 0, "L");
            $pdf->SetFont("Arial", "B", 9);
            $pdf->SetXY(55, 70);
            $pdf->Cell(14, 5, iconv('UTF-8', 'windows-1252', 'Celular: '), 0, 0, "L");
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $row['celular']), 0, 0, "L");
            $pdf->Rect(9, 78, 90, 18);
            $pdf->SetFont("Arial", "B", 9);
            $pdf->SetXY(0, 78);
            $pdf->Cell(108, 5, iconv('UTF-8', 'windows-1252', 'Datos del padre o apoderado legal'), 0, 0, "C");
            $pdf->SetXY(10, 83);
            $pdf->Cell(19, 5, iconv('UTF-8', 'windows-1252', 'Nombre: '), 0, 0, "L");
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $row["tutornombre"]), 0, 0, "L");
            $pdf->SetFont("Arial", "B", 9);
            $pdf->SetXY(10, 87);
            $pdf->Cell(19, 5, iconv('UTF-8', 'windows-1252', 'CURP: '), 0, 0, "L");
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $row["tutorcurp"]), 0, 0, "L");
            $pdf->SetXY(10, 91);
            $pdf->SetFont("Arial", "B", 9);
            $pdf->Cell(19, 5, iconv('UTF-8', 'windows-1252', 'Parentezco: '), 0, 0, "L");
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $row["parentezco"]), 0, 0, "L");
            $pdf->SetXY(10, 119);
            $pdf->Code128(14, 99, $row['iddifzapopan'], 80, 20);
            $pdf->SetFont("Arial", "", 7);
            $pdf->Cell(93, 5, iconv('UTF-8', 'windows-1252', $row['iddifzapopan']), 0, 0, "C");
            $pdf->Line(108, 3, 108, 277);
            $pdf->Rect(113, 5, 98, 130);
            $pdf->Rect(113, 5, 98, 10);
            $pdf->SetFont("Arial", "B", 12);
            $pdf->SetXY(113, 8);
            $pdf->Cell(98, 5, iconv('UTF-8', 'windows-1252', "Registro de servicios/citas"), 0, 0, "C");
            $pdf->Rect(113, 15, 22, 10);
            $pdf->SetXY(113, 18);
            $pdf->SetFont("Arial", "B", 9);
            $pdf->Cell(22, 5, iconv('UTF-8', 'windows-1252', "Fecha y hora"), 0, 0, "C");
            $pdf->Rect(135, 15, 53, 10);
            $pdf->Cell(53, 5, iconv('UTF-8', 'windows-1252', "Descripción del servicio"), 0, 0, "C");
            $pdf->Rect(188, 15, 23, 10);
            $pdf->Cell(23, 5, iconv('UTF-8', 'windows-1252', "No. de recibu"), 0, 0, "C");
            
            for ($i = 25; $i < 135; $i += 6.875) {
                $pdf->Rect(113, $i, 22, 6.875);
                $pdf->Rect(135, $i, 53, 6.875);
                $pdf->Rect(188, $i, 23, 6.875);
            }
            
            $pdf->Rect(5, 145, 98, 130);
            $pdf->Rect(5, 145, 98, 10);
            $pdf->SetFont("Arial", "B", 12);
            $pdf->SetXY(5, 148);
            $pdf->Cell(98, 5, iconv('UTF-8', 'windows-1252', "Registro de servicios/citas"), 0, 0, "C");
            $pdf->Rect(5, 155, 22, 10);
            $pdf->SetXY(5, 158);
            $pdf->SetFont("Arial", "B", 9);
            $pdf->Cell(22, 5, iconv('UTF-8', 'windows-1252', "Fecha y hora"), 0, 0, "C");
            $pdf->Rect(27, 155, 53, 10);
            $pdf->Cell(53, 5, iconv('UTF-8', 'windows-1252', "Descripción del servicio"), 0, 0, "C");
            $pdf->Rect(80, 155, 23, 10);
            $pdf->Cell(23, 5, iconv('UTF-8', 'windows-1252', "No. de recibo"), 0, 0, "C");
            
            for ($i = 165; $i < 275; $i += 6.875) {
                $pdf->Rect(5, $i, 22, 6.875);
                $pdf->Rect(27, $i, 53, 6.875);
                $pdf->Rect(80, $i, 23, 6.875);
            }
            
            $pdf->Rect(113, 145, 98, 130);
            $pdf->Rect(113, 145, 98, 10);
            $pdf->SetFont("Arial", "B", 12);
            $pdf->SetXY(113, 148);
            $pdf->Cell(98, 5, iconv('UTF-8', 'windows-1252', "Registro de servicios/citas"), 0, 0, "C");
            $pdf->Rect(113, 155, 22, 10);
            $pdf->SetXY(113, 158);
            $pdf->SetFont("Arial", "B", 9);
            $pdf->Cell(22, 5, iconv('UTF-8', 'windows-1252', "Fecha y hora"), 0, 0, "C");
            $pdf->Rect(135, 155, 53, 10);
            $pdf->Cell(53, 5, iconv('UTF-8', 'windows-1252', "Descripción del servicio"), 0, 0, "C");
            $pdf->Rect(188, 155, 23, 10);
            $pdf->Cell(23, 5, iconv('UTF-8', 'windows-1252', "No. de recibo"), 0, 0, "C");
            
            for ($i = 165; $i < 275; $i += 6.875) {
                $pdf->Rect(113, $i, 22, 6.875);
                $pdf->Rect(135, $i, 53, 6.875);
                $pdf->Rect(188, $i, 23, 6.875);
            }
            
            $pdf->Output();
?>