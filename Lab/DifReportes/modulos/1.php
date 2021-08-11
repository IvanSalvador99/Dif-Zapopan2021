<?php
    require_once("../../funciones.php");
    require_once("../../../Tools/fpdf/fpdf.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    /*echo "POST:</br>";
    var_dump($_POST);*/
    
    if (!isset($_POST['comunidad']) && !isset($_POST['checkboxgeneral']) || !isset($_POST['radiotipo'])) {
        echo "</br></br>No introduciste los parametros minimos necesarios para generar un reporte";
        alerta_bota("No introduciste los parametros minimos necesarios para generar un reporte", 0);
        die;
    }
    
    class PDF extends FPDF {
        var $cantidad;
        var $comunidad;
        var $posY;
        var $posX;
        
        // Page header
        function Header() {
            // Logo
            $this->Image('../../imagenes/Ayuntamiento de Zapopan.png', 15, 10, 30);
            $this->Image('../../imagenes/LOGOTIPO_DIF Zapopan.png', 310, 15, 30);
            $this->SetFont('Arial', 'B', 12);
            $this->Ln(10);
            $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'PROGRAMA DE AYUDA ALIMENTARIA DIRECTA MUNICIPAL'), 0, 0, 'C');
            $this->Ln(5);
            $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'INSCRIPCIÓN DE SUJETOS VULNERABLES A BENEFICIAR CON DESPENSAS'), 0, 0, 'C');
            $this->Ln(5);
            $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'COORDINACIÓN DE NUTRICIÓN Y ASISTENCIA ALIMENTARIA / JEFATURA DE DEPARTAMENTO DE TRABAJO SOCIAL'), 0, 0, 'C');
            $this->Ln(10);
            $this->SetX(55);
            $this->Cell(30, 10, iconv('UTF-8', 'windows-1252', 'Localidad: '), 0, 0, 'L');
            $this->SetFont('Arial', '', 12);
            $this->Cell(50, 10, iconv('UTF-8', 'windows-1252', $this->comunidad), 0, 0, 'L');
            $this->Ln(6);
            $this->SetX(55);
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(30, 10, iconv('UTF-8', 'windows-1252', "Padrón: "), 0, 0, 'L');
            $this->SetFont('Arial', '', 12);
            $this->Cell(30, 10, iconv('UTF-8', 'windows-1252', "2021"), 0, 0, 'L');
            $this->Ln(6);
            $this->SetX(55);
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(30, 10, iconv('UTF-8', 'windows-1252', "Beneficiarios: "), 0, 0, 'L');
            $this->SetFont('Arial', '', 12);
            $this->Cell(30, 10, iconv('UTF-8', 'windows-1252', $this->cantidad), 0, 0, 'L');
            $this->Ln(10);
        }
        
        // Page footer
        function Footer() {
            // Position at 1.5 cm from bottom
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial', 'I', 8);
            // Page number
            $this->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'Página') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }
        
        function LoadData($result) {
            $data = array();
            $this->cantidad = mysql_num_rows($result);
            while ($row = mysql_fetch_row($result)) {
                $data[] = $row;
            }
            
            if (!isset($_POST['checkboxgeneral'])) {
                $this->comunidad = $data[0][11];
                //echo $this->comunidad;
            } else {
                $this->comunidad = "TODAS";
                //echo $this->comunidad;
            }
            return $data;
        }
        
        function ImprovedTable($headers, $datos) {
            // Column widths
            $w = array(10, 36, 60, 12, 12, 25, 74, 60, 45);
            
            // Data
            foreach ($datos as $rownum => $row) {
                if ($rownum % 10 == 0) {
                    $this->SetFont('Arial', 'B', 10);
                    for($i = 0; $i < count($headers); $i++){
                        if ($i == 5) {
                            $this->posX = $this->GetX();
                            $this->posY = $this->GetY();
                            $this->MultiCell($w[$i], 6, iconv('UTF-8', 'windows-1252', $headers[$i]), 1, 'C');
                            $this->SetXY($this->posX + $w[$i], $this->posY);
                        } else {
                            $this->Cell($w[$i], 12, iconv('UTF-8', 'windows-1252', $headers[$i]), 1, 0, 'C');
                        }
                    }
                    $this->Ln();
                    $this->SetFont('Arial', '', 8);
                }
                $this->Cell($w[0], 12, $rownum + 1, 1);
                $this->Cell($w[1], 12, iconv('UTF-8', 'windows-1252', $row[0]), 1);
                $this->posX = $this->GetX();
                $this->posY = $this->GetY();
                $this->MultiCell($w[2], 6, iconv('UTF-8', 'windows-1252', $row[1] . "\n" . $row[2]), 1);
                $this->SetXY($this->posX + $w[2], $this->posY);
                $this->Cell($w[3], 12, iconv('UTF-8', 'windows-1252', $row[3]), 1);
                $this->Cell($w[4], 12, iconv('UTF-8', 'windows-1252', $row[4]), 1);
                $this->Cell($w[5], 12, iconv('UTF-8', 'windows-1252', $row[5]), 1);
                $this->posX = $this->GetX();
                $this->MultiCell($w[6], 6, iconv('UTF-8', 'windows-1252', $row[6] . "\n" . strtoupper($row[7])), 1);
                $this->SetXY($this->posX + $w[6], $this->posY);
                $this->posX = $this->GetX();
                $this->MultiCell($w[7], 6, iconv('UTF-8', 'windows-1252', $row[8] . "\n" . $row[9]), 1);
                $this->SetXY($this->posX + $w[7], $this->posY);
                $this->MultiCell($w[8], 6, iconv('UTF-8', 'windows-1252', halfString ($row[10], "low") . "\n" . halfString ($row[10], "high")), 1);
                //$this->Ln();
            }
            // Closing line
            //$this->Cell(array_sum($w),0,'','T');
        }
    }
    
    $count = 0;
    $query = "SELECT * FROM $reporte_alimentaria_padron WHERE";
    
    if (!isset($_POST['checkboxgeneral'])) {
        if ($count == 0) {
            $query .= " idcomunidad = '" . $_POST['comunidad'] . "'";
            $count ++;
        } else {
            $query .= " AND idcomunidad = '" . $_POST['comunidad'] . "'";
        }
    }
    
    if ($_POST['radiotipo'] == 1) {
        if ($count == 0) {
            $query .= " tipo < '24'";
            $count ++;
        } else {
            $query .= " AND tipo < '24'";
        }
    } elseif ($_POST['radiotipo'] == 2) {
        if ($count == 0) {
            $query .= " tipo >= '24'";
            $count ++;
        } else {
            $query .= " AND tipo >= '24'";
        }
    }
    
    /*echo "</br></br>QUERY:</br>" . $0query;*/
    
    if ($result = mysql_query($query, $con)) {
        /*echo "</br></br>Numero de filas obtenidas:" . mysql_num_rows($result);*/     
        /*$num_fields = mysql_num_fields($result);
        $headers = array();
        for ($i = 0; $i < $num_fields; $i++) {
            $headers[] = mysql_field_name($result , $i);
        }
        
        $fp = fopen('php://output', 'w');
        if ($fp && $result && mysql_num_rows($result) > 0) {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="export_' . date("Y-m-d") . '.csv"');
            header('Pragma: no-cache');
            header('Expires: 0');
            fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
            fputcsv($fp, $headers);
            while ($row = mysql_fetch_row($result)) {
                fputcsv($fp, array_values($row));
            }
            die;
        }*/
        
        $headers = array('NUM', 'CURP', 'NOMBRE DEL BENEFICIARIO', 'SEXO', 'EDAD', 'FECHA DE NACIMIENTO', 'DOMICILIO', 'RESPONSABLE', 'VULNERABILIDAD');
        $pdf = new PDF("L", "mm", "Legal");
        $datos = $pdf->LoadData($result);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->ImprovedTable($headers, $datos);
        $pdf->Output();
        
    } else {
        echo "</br></br>Error en el QUERY: " . $query . "</br>Error: " . mysql_error();
    }
?>