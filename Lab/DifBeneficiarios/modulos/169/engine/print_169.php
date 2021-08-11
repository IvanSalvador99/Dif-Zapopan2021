<?php
    require_once("../../../../funciones.php");
    require_once("../../../../../Tools/fpdf/fpdf.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    echo $_GET['idservicio'];
    if (!isset($_GET['idservicio'])) {
        alerta_bota("No se envio el servidio que se quiere imprimir", 0);
        die;
    }
    
    $rowsicats = array();
    $grupoetario = "";
    
    //echo $_GET['idservicio'];
    
    $query = "SELECT * FROM $reporte_servicios_general WHERE idservicio='" . $_GET['idservicio'] . "';";
    if ($result = mysql_query($query, $con)) {
        $rowservicio = mysql_fetch_array($result);
        /*echo "</br></br>";
        var_dump($rowservicio);*/
        
        $query = "SELECT * FROM $vw_persona WHERE id='" . $rowservicio['idpersona'] . "';"; // 
        if($result = mysql_query($query, $con)) {
            $rowpersona = mysql_fetch_array($result);
            /*echo "</br></br>";
            var_dump($rowpersona);*/
            
            $query = "SELECT * FROM $vw_entrevista_inicial WHERE idservicio = '" . $_GET['idservicio'] . "';";
            if ($result = mysql_query($query, $con)) {
                $rowventanilla = mysql_fetch_array($result);
                /*echo "</br></br>";
                var_dump($rowventanilla);*/
                
                $query = "SELECT * FROM $vw_sicats WHERE idservicio = '" . $_GET['idservicio'] . "';";
                if ($result = mysql_query($query, $con)) {
                    while ($sicats = mysql_fetch_array($result)){
                        $rowsicats[] = $sicats; 
                    }
                    /*echo "</br></br>";
                    var_dump($rowsicats);*/
                } else {
                    echo "</br>Error en el query: " . $query . "</br>Error: " . mysql_error();
                }
            } else {
                echo "</br>Error en el query: " . $query . "</br>Error: " . mysql_error();
            }
        } else {
            echo "</br>Error en el query: " . $query . "</br>Error: " . mysql_error();
        }
    } else {
        echo "</br>Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
    
    $pdf = new FPDF("P", "mm", "Letter");
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(false, 0);
    $alto = $pdf->GetPageHeight();
    $ancho = $pdf->GetPageWidth();
    $pdf->Image('../../../../../Tools/imagenes/LogoDIF.png', 10, 4, 20);
    $pdf->Image('../../../../../Tools/imagenes/bienestar.png', 163, 4, 35);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'Entrevista - Orientacíon'), 0, 0, "C");
    $pdf->Ln();
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'Departamento de Trabajo Social'), 0, 0, "C");
    $pdf->Ln();
    $pdf->SetX($ancho * 0.7);
    $pdf->SetFillColor(180);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'Fecha dd/mm/aaaa'), 1, 2, "L", true);
    $pdf->Cell(15, 4, iconv('UTF-8', 'windows-1252', dameFecha($rowservicio['fechaservicio'], "d")), 1, 0, "C");
    $pdf->Cell(15, 4, iconv('UTF-8', 'windows-1252', dameFecha($rowservicio['fechaservicio'], "m")), 1, 0, "C");
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', dameFecha($rowservicio['fechaservicio'], "y")), 1, 1, "C");
    $pdf->Ln(1);
    $oldX = $pdf->GetX();
    $oldY = $pdf->GetY();
    $pdf->SetLineWidth(0.5);
    $pdf->Cell(0, 8, "", 1, 2, "L", false);
    $pdf->SetLineWidth();
    $pdf->SetXY($oldX, $oldY);
    $pdf->Cell(40, 4, iconv('UTF-8', 'windows-1252', 'Servicio y/o programa'), 1, 0, "L", true);
    $pdf->Cell(100, 4, iconv('UTF-8', 'windows-1252', $rowservicio['servicio']), 1, 0, "L", false);
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Registro'), 1, 0, "L", true);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['registro']), 1, 1, "L", false);
    $pdf->Cell(30, 4, iconv('UTF-8', 'windows-1252', 'CURP/ID DIF'), 1, 0, "L", true);
    $pdf->Cell(35, 4, iconv('UTF-8', 'windows-1252', $rowpersona['curp']), 1, 0, "L", false);
    $pdf->Cell(22, 4, iconv('UTF-8', 'windows-1252', 'Procedencia'), 1, 0, "L", true);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['procedencia']), 1, 1, "L", false);
    $pdf->Ln(1);
    $oldX = $pdf->GetX();
    $oldY = $pdf->GetY();
    $pdf->SetLineWidth(0.5);
    $pdf->Cell(0, 42, "", 1, 2, "L", false);
    $pdf->SetXY($oldX, $oldY);
    $pdf->SetLineWidth();
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'DATOS GENERALES'), 1, 1, "L", true);
    $pdf->Cell(20, 7, iconv('UTF-8', 'windows-1252', 'Nombre'), 1, 0, "L", true);
    $pdf->Cell(35, 4, iconv('UTF-8', 'windows-1252', $rowpersona['apaterno']), 1, 0, "L", false);
    $pdf->Cell(35, 4, iconv('UTF-8', 'windows-1252', $rowpersona['amaterno']), 1, 0, "L", false);
    $pdf->Cell(50, 4, iconv('UTF-8', 'windows-1252', $rowpersona['nombre']), 1, 0, "L", false);
    $pdf->Cell(30, 7, iconv('UTF-8', 'windows-1252', 'Fecha de Nac.'), 1, 0, "L", true);
    $pdf->Cell(0, 7, iconv('UTF-8', 'windows-1252', dameFecha($rowpersona['fechanacimiento'], "all")), 1, 1, "L", false);
    $pdf->SetXY(30, $oldY + 8);
    $pdf->SetFont("Arial", "", 6);
    $pdf->Cell(35, 3, iconv('UTF-8', 'windows-1252', 'Apellido paterno'), 1, 0, "L", true);
    $pdf->Cell(35, 3, iconv('UTF-8', 'windows-1252', 'Apellido materno'), 1, 0, "L", true);
    $pdf->Cell(50, 3, iconv('UTF-8', 'windows-1252', 'Nombre(s)'), 1, 1, "L", true);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Sexo'), 1, 0, "L", true);
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', $rowpersona['sexo']), 1, 0, "L", false);
    $pdf->Cell(15, 4, iconv('UTF-8', 'windows-1252', 'Edad'), 1, 0, "L", true);
    $pdf->Cell(25, 4, iconv('UTF-8', 'windows-1252', $rowpersona['edad']), 1, 0, "L", false);
    $pdf->Cell(25, 4, iconv('UTF-8', 'windows-1252', 'Estado Civil'), 1, 0, "L", true);
    $pdf->Cell(35, 4, iconv('UTF-8', 'windows-1252', $rowpersona['estado_civil']), 1, 0, "L", false);
    $pdf->Cell(30, 4, iconv('UTF-8', 'windows-1252', 'Grupo etario'), 1, 0, "L", true);
    if ($rowpersona['edad'] >= 0 && $rowpersona['edad'] < 18) {
        $grupoetario = "NNA";
    } elseif ($rowpersona['edad'] < 65) {
        $grupoetario = "Adulto";
    } else {
        $grupoetario = "Adulto mayor";
    }
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $grupoetario), 1, 1, "L", false);
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Ocupación'), 1, 0, "L", true);
    $pdf->Cell(85, 4, iconv('UTF-8', 'windows-1252', $rowpersona['ocupacion']), 1, 0, "L", false);
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Escolaridad'), 1, 0, "L", true);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowpersona['escolaridad']), 1, 1, "L", false);
    $pdf->Cell(20, 8, iconv('UTF-8', 'windows-1252', 'Domicilio'), 1, 0, "L", true);
    $pdf->Cell(35, 4, iconv('UTF-8', 'windows-1252', 'Calle y No. (ext e int)'), 1, 0, "L", true);
    if ($rowpersona['numint'] != 0 || $rowpersona['numint'] != "") {
        $pdf->Cell(85, 4, iconv('UTF-8', 'windows-1252', $rowpersona['calle'] . " " . $rowpersona['numext'] . "-" . $rowpersona['numint']), 1, 0, "L", false);
    } else {
        $pdf->Cell(85, 4, iconv('UTF-8', 'windows-1252', $rowpersona['calle'] . " " . $rowpersona['numext']), 1, 0, "L", false);
    }
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'C.P.'), 1, 0, "L", true);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowpersona['codigopostal']), 1, 1, "L", false);
    $pdf->SetX(30);
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Cruza con'), 1, 0, "L", true);
    $pdf->Cell(70, 4, iconv('UTF-8', 'windows-1252', $rowpersona['primercruce'] . ", " . $rowpersona['segundocruce']), 1, 0, "L", false);
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Colonia'), 1, 0, "L", true);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowservicio['colonia']), 1, 1, "L", false);
    $pdf->Cell(45, 4, iconv('UTF-8', 'windows-1252', 'Mpio. y edo. de nac.'), 1, 0, "L", true);
    $pdf->Cell(55, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['municipionacimiento'] . ", " . $rowventanilla['estadonacimiento']), 1, 0, "L", false);
    $pdf->Cell(45, 4, iconv('UTF-8', 'windows-1252', 'Mpio. y edo. de residencia'), 1, 0, "L", true);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowpersona['municipio'] . ", " . $rowpersona['estado']), 1, 1, "L", false);
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Pais'), 1, 0, "L", true);
    $pdf->Cell(40, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['pais']), 1, 0, "L", false);
    $pdf->Cell(35, 4, iconv('UTF-8', 'windows-1252', 'Telefono Particular'), 1, 0, "L", true);
    $pdf->Cell(37, 4, iconv('UTF-8', 'windows-1252', $rowpersona['telefono']), 1, 0, "L", false);
    $pdf->Cell(35, 4, iconv('UTF-8', 'windows-1252', 'Telefono Celular'), 1, 0, "L", true);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowpersona['celular']), 1, 1, "L", false);
    $pdf->SetFont("Arial", "", 6);
    $pdf->Cell(0, 3, "Datos del tutor o apoderado legal", 1, 1, "L", true);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Nombre'), 1, 0, "L", true);
    $pdf->Cell(75, 4, iconv('UTF-8', 'windows-1252', $rowpersona['tutornombre']), 1, 0, "L", false);
    $pdf->Cell(25, 4, iconv('UTF-8', 'windows-1252', 'Fecha de Nac.'), 1, 0, "L", true);
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', $rowpersona['tutorfechanacimiento']), 1, 0, "L", false);
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Parentezco'), 1, 0, "L", true);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowpersona['parentesco']), 1, 1, "L", false);
    $pdf->Ln(1);
    
    $oldX = $pdf->GetX();
    $oldY = $pdf->GetY();
    $pdf->SetLineWidth(0.5);
    $pdf->Cell(0, 8, "", 1, 2, "L", false);
    $pdf->SetXY($oldX, $oldY);
    $pdf->SetLineWidth();
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'APOYO Y/O SERVICIO SOLICITADO'), 1, 1, "C", true);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['apoyosolicitado']), 1, 1, "C", false);
    $pdf->Ln(1);
    
    $oldX = $pdf->GetX();
    $oldY = $pdf->GetY();
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'DIAGNÓSTICO SOCIAL INICIAL'), 1, 1, "L", true);
    $pdf->Cell(80, 4, iconv('UTF-8', 'windows-1252', 'Composición familiar y economica'), 1, 0, "L", true);
    $pdf->Cell(40, 4, iconv('UTF-8', 'windows-1252', 'Vivienda - Condición'), 1, 0, "L", true);
    $pdf->Cell(30, 4, iconv('UTF-8', 'windows-1252', 'Pago por mes'), 1, 0, "L", true);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'Poblacion atendida'), 1, 1, "L", true);
    $pdf->Cell(60, 4, iconv('UTF-8', 'windows-1252', 'Integrantes'), 1, 0, "L", true);
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['integrantes']), 1, 0, "C", false);
    $pdf->Cell(40, 4, iconv('UTF-8', 'windows-1252', $rowpersona['vivienda']), 1, 0, "C", false);
    $pdf->Cell(30, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['pagocasa']), 1, 0, "C", false);
    $tempY = $pdf->GetY();
    $pdf->MultiCell(0, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['poblacionatendida']), 0, "J", false);
    $pdf->SetY($pdf->GetY() - ($pdf->GetY() - $tempY - 4));
    $pdf->Cell(60, 4, iconv('UTF-8', 'windows-1252', 'Integrantes económicamente activos'), 1, 0, "L", true);
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['integrantesactivos']), 1, 0, "C", false);
    $pdf->Cell(30, 4, iconv('UTF-8', 'windows-1252', 'Otra (especifique)'), 1, 0, "L", true);
    $pdf->Cell(40, 4, iconv('UTF-8', 'windows-1252', ""), 1, 1, "L", false);
    $pdf->Cell(60, 4, iconv('UTF-8', 'windows-1252', 'Ingreso mensual familiar aproximado'), 1, 0, "L", true);
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['ingresofamiliar']), 1, 0, "C", false);
    $pdf->Cell(90, 4, iconv('UTF-8', 'windows-1252', 'Integrantes con enfermedad cronica y/o discapacidad'), 1, 0, "L", true);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['integrantesenfermos']), 1, 1, "C", false);
    $pdf->Cell(60, 4, iconv('UTF-8', 'windows-1252', 'Deudas (pago mensual aproximado)'), 1, 0, "L", true);
    $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['deudas']), 1, 0, "C", false);
    $pdf->Cell(73, 4, iconv('UTF-8', 'windows-1252', '¿Dónde atienden sus necesidades de salud?'), 1, 0, "L", true);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowpersona['servicios_medicos']), 1, 1, "L", false);
    $pdf->MultiCell(0, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['descripcioncaso']), 0, "J", false);
    $multiCellHeight = $pdf->GetY() - $oldY;
    $pdf->SetXY($oldX, $oldY);
    $pdf->SetLineWidth(0.5);
    $pdf->Cell(0, $multiCellHeight, "", 1, 2, "L", false);
    $pdf->SetLineWidth();
    $pdf->SetXY($oldX, $oldY + $multiCellHeight + 1);
    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'CONCLUSIONES'), 1, 1, "C", true);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(95, 4, iconv('UTF-8', 'windows-1252', 'Problematica y/o vulnerabilidad'), 1, 0, "L", true);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'Detonante del problema'), 1, 1, "L", true);
    $oldX = $pdf->GetX();
    $oldY = $pdf->GetY();
    $pdf->MultiCell(95, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['problematica']), 0, "J", false);
    $pdf->SetXY($oldX + 95, $oldY);
    $pdf->MultiCell(0, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['detonante']), 1, "J", false);
    $multiCellHeight = $pdf->GetY() - $oldY;
    $pdf->SetXY($oldX, $oldY);
    $pdf->Cell(95, $multiCellHeight, "", 1, 1, "L", false);
    $pdf->Cell(150, 4, iconv('UTF-8', 'windows-1252', 'Diagnóstico'), 1, 0, "L", true);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'Clasificación del programa'), 1, 1, "L", true);
    $oldX = $pdf->GetX();
    $oldY = $pdf->GetY();
    $pdf->MultiCell(150, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['diagnostico']), 1, "J", false);
    $multiCellHeight = $pdf->GetY() - $oldY;
    $pdf->SetXY($oldX + 150, $oldY);
    $pdf->Cell(0, $multiCellHeight, iconv('UTF-8', 'windows-1252', $rowventanilla['programa']), 1, 1, "L", false);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'Apoyo(s) y/o servicio(s) otorgado(s)'), 1, 1, "C", true);
    
    $query = "SELECT GROUP_CONCAT($apoyos.apoyosservicios SEPARATOR ',') AS apoyos
                FROM $personas_ventanilla_unica
                INNER JOIN $apoyos ON FIND_IN_SET($apoyos.id, $personas_ventanilla_unica.apoyo) > 0
                WHERE $personas_ventanilla_unica.idservicio = '" . $_GET['idservicio'] . "'";
    
    if ($result = mysql_query($query, $con)) {
        $rowapoyos = mysql_fetch_array($result);
    } else {
        echo "</br>Error en el query: " . $query . "</br>Error: " . mysql_error();
    }      
    $apoyos = explode(",", $rowapoyos['apoyos']);
    $count = 0;
    if (count($apoyos) % 2 != 0){
        $apoyos[count($apoyos)] = "";
    }
    foreach ($apoyos as $key => $value) {
        if ($count == 0) {
            $pdf->Cell(10, 4, iconv('UTF-8', 'windows-1252', $key + 1), 1, 0, "L", true);
            $pdf->Cell(85, 4, iconv('UTF-8', 'windows-1252', $value), 1, 0, "L", false);
            $count++;
        } elseif ($count == 1) {
            $pdf->Cell(10, 4, iconv('UTF-8', 'windows-1252', $key + 1), 1, 0, "L", true);
            $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $value), 1, 1, "L", false);
            $count = 0;
        }
    }
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'Otros apoyos'), 1, 1, "C", true);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['otroapoyo']), 1, 1, "C", false);
    $pdf ->Ln(1);
    $pdf->SetFont("Arial", "", 6);
    $pdf->MultiCell(0, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['descripcionconclusion']), 0, "J", false);
    $pdf->Ln(1);
    $pdf->Cell(150, 4, iconv('UTF-8', 'windows-1252', 'Área o institución a la que fue canalizado(a)'), 1, 0, "C", true);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'Numero de canalización'), 1, 1, "C", true);
    $pdf->Cell(150, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['canalizadoa']), 1, 0, "C", false);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowsicats[0]['numero_canalizacion']), 1, 1, "C", false);
    $pdf->Ln(1);
    $pdf->SetY(260);
    $pdf->Cell(95, 4, iconv('UTF-8', 'windows-1252', "TS. " . $rowservicio['capturistaservicio']), 0, 0, "C", false);
    $pdf->Cell(95, 4, iconv('UTF-8', 'windows-1252', $rowpersona['nombre'] . " " . $rowpersona['apaterno'] . " " . $rowpersona['amaterno']), 0, 1, "C", false);
    $pdf->Cell(95, 4, iconv('UTF-8', 'windows-1252', 'NOMBRE Y FIRMA DEL TRABAJADOR SOCIAL'), 0, 0, "C", false);
    $pdf->SetX(110);
    $pdf->SetFont("Arial", "", 6);
    $pdf->MultiCell(0, 3, iconv('UTF-8', 'windows-1252', 'Manifiesto bajo protesta decir la verdad respecto a proporcionar mis datos personales, así como mi aceptación del aviso de privacidad, en donde señala que serán resguardados conforme a la Ley establecida. http://sistemadif.jalisco.gob.mx'), 0, "J", false);
    $pdf->Line(10,264,100,264);
    $pdf->Line(110,264,205,264);
    
    for ($i = 0; $i < count($rowsicats); $i++) { 
        $pdf->AddPage();
        $pdf->Image('../../../../imagenes/LOGOTIPO_DIF Zapopan.png', 10, 4, 17);
        $pdf->Image('../../../../../Tools/imagenes/bienestar.png', 150, 10, 35);
        $pdf->Image('../../../../../Tools/imagenes/sicats.png', 190, 4, 15);
        $pdf->Cell(180, 4, iconv('UTF-8', 'windows-1252', 'Sistema Interinstitucional de Canalización a Trabajo Social'), 0, 1, "C");
        $pdf->Cell(180, 4, iconv('UTF-8', 'windows-1252', 'Sistema DIF Zapopan, Jefatura de Trabajo Social'), 0, 0, "C");
        $pdf->Ln(10);
        $pdf->Cell(10, 4, iconv('UTF-8', 'windows-1252', 'De:'), 0, 0, "R");
        $oldY = $pdf->GetY();
        if ($rowsicats[$i]['origen'] == NULL) {
            $pdf->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', 'DIF ZAPOPAN'), 0, "C", false);
        } else {
            $pdf->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', $rowsicats[$i]['origen']), 0, "C", false);
        }
        $multiCellHeight = $pdf->GetY() - $oldY;
        $pdf->SetXY(110, $oldY);
        $pdf->Cell(80, $multiCellHeight, iconv('UTF-8', 'windows-1252', $rowsicats[$i]['encargado_origen']), 0, 1, "C");
        $pdf->Line(20, $pdf->GetY(), 100, $pdf->GetY());
        $pdf->Line(110, $pdf->GetY(), 190, $pdf->GetY());
        $pdf->SetXY(20, $pdf->GetY()-0.5);
        $pdf->Cell(80, 4, iconv('UTF-8', 'windows-1252', 'Nombre de la institución'), 0, 0, "C");
        $pdf->SetX(110);
        $pdf->Cell(80, 4, iconv('UTF-8', 'windows-1252', 'Responsable de Trabajo Social'), 0, 1, "C");
        $pdf->Ln(3);
        $pdf->Cell(10, 4, iconv('UTF-8', 'windows-1252', 'Para:'), 0, 0, "R");
        $oldY = $pdf->GetY();
        $pdf->MultiCell(80, 4, iconv('UTF-8', 'windows-1252', $rowsicats[$i]['destino']), 0, "C", false);
        $multiCellHeight = $pdf->GetY() - $oldY;
        $pdf->SetXY(110, $oldY);
        $pdf->Cell(80, $multiCellHeight, iconv('UTF-8', 'windows-1252', $rowsicats[$i]['encargado_destino']), 0, 1, "C");
        $pdf->Line(20, $pdf->GetY(), 100, $pdf->GetY());
        $pdf->Line(110, $pdf->GetY(), 190, $pdf->GetY());
        $pdf->SetXY(20, $pdf->GetY() -0.5);
        $pdf->Cell(80, 4, iconv('UTF-8', 'windows-1252', 'Nombre de la institución'), 0, 0, "C");
        $pdf->SetX(110);
        $pdf->Cell(80, 4, iconv('UTF-8', 'windows-1252', 'Responsable de Trabajo Social'), 0, 1, "C");
        $pdf->Ln(1);
        $pdf->Cell(35, 4, iconv('UTF-8', 'windows-1252', 'N° de canalización'), 1, 0, "L", true);
        $pdf->Cell(40, 4, iconv('UTF-8', 'windows-1252', $rowsicats[$i]['numero_canalizacion']), 1, 0, "L", false);
        $pdf->Cell(30, 4, iconv('UTF-8', 'windows-1252', 'N° de registro'), 1, 0, "L", true);
        $pdf->Cell(40, 4, iconv('UTF-8', 'windows-1252', $rowsicats[$i]['numero_registro']), 1, 0, "L", false);
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Fecha'), 1, 0, "L", true);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowsicats[$i]['fecha']), 1, 1, "L", false);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'DATOS GENERALES'), 1, 1, "L", true);
        $oldY = $pdf->GetY();
        $pdf->Cell(30, 4, iconv('UTF-8', 'windows-1252', 'Nombre'), 1, 0, "L", true);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowpersona['apaterno'] . " " . $rowpersona['amaterno'] . " " . $rowpersona['nombre']), 1, 1, "L", false);
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Sexo'), 1, 0, "L", true);
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', $rowpersona['sexo']), 1, 0, "L", false);
        $pdf->Cell(30, 4, iconv('UTF-8', 'windows-1252', 'Fecha de Nac.'), 1, 0, "L", true);
        $pdf->Cell(30, 4, iconv('UTF-8', 'windows-1252', $rowpersona['fechanacimiento']), 1, 0, "L", false);
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Edad'), 1, 0, "L", true);
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', $rowpersona['edad']), 1, 0, "L", false);
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Estado Civil'), 1, 0, "L", true);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowpersona['estado_civil']), 1, 1, "L", false);
        $pdf->Cell(20, 8, iconv('UTF-8', 'windows-1252', 'Domicilio'), 1, 0, "L", true);
        $pdf->SetX(30);
        $pdf->Cell(35, 4, iconv('UTF-8', 'windows-1252', 'Calle y No. (ext e int)'), 1, 0, "L", true);
        if ($rowpersona['numint'] != 0 || $rowpersona['numint'] != "") {
            $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowpersona['calle'] . " " . $rowpersona['numext'] . "-" . $rowpersona['numint']), 1, 1, "L", false);
        } else {
            $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowpersona['calle'] . " " . $rowpersona['numext']), 1, 1, "L", false);
        }
        $pdf->SetX(30);
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Cruza con'), 1, 0, "L", true);
        $pdf->Cell(75, 4, iconv('UTF-8', 'windows-1252', $rowpersona['primercruce'] . ", " . $rowpersona['segundocruce']), 1, 0, "L", false);
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Colonia'), 1, 0, "L", true);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowservicio['colonia']), 1, 1, "L", false);
        $pdf->Cell(25, 4, iconv('UTF-8', 'windows-1252', 'Municipio'), 1, 0, "L", true);
        $pdf->Cell(40, 4, iconv('UTF-8', 'windows-1252', $rowpersona['municipio']), 1, 0, "L", false);
        $pdf->Cell(25, 4, iconv('UTF-8', 'windows-1252', 'Estado'), 1, 0, "L", true);
        $pdf->Cell(40, 4, iconv('UTF-8', 'windows-1252', $rowpersona['estado']), 1, 0, "L", false);
        $pdf->Cell(25, 4, iconv('UTF-8', 'windows-1252', 'Pais'), 1, 0, "L", true);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['pais']), 1, 1, "L", false);
        $pdf->Cell(40, 4, iconv('UTF-8', 'windows-1252', 'Telefono Particular'), 1, 0, "L", true);
        $pdf->Cell(60, 4, iconv('UTF-8', 'windows-1252', $rowpersona['telefono']), 1, 0, "L", false);
        $pdf->Cell(40, 4, iconv('UTF-8', 'windows-1252', 'Telefono Celular'), 1, 0, "L", true);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowpersona['celular']), 1, 1, "L", false);
        $pdf->SetFont("Arial", "", 6);
        $pdf->Cell(0, 3, "Datos del tutor o apoderado legal", 1, 1, "L", true);
        $pdf->SetFont("Arial", "", 8);
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Nombre'), 1, 0, "L", true);
        $pdf->Cell(75, 4, iconv('UTF-8', 'windows-1252', $rowpersona['tutornombre']), 1, 0, "L", false);
        $pdf->Cell(25, 4, iconv('UTF-8', 'windows-1252', 'Fecha de Nac.'), 1, 0, "L", true);
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', $rowpersona['tutorfechanacimiento']), 1, 0, "L", false);
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Parentezco'), 1, 0, "L", true);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowpersona['parentesco']), 1, 1, "L", false);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'DIAGNÓSTICO SOCIAL PRELIMINAR'), 1, 1, "L", true);
        $pdf->MultiCell(0, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['descripcioncaso']), 1, "J", false);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'SOLICITUD'), 1, 1, "C", true);
        $pdf->SetFont("Arial", "B", 8);
        $pdf->MultiCell(0, 4, iconv('UTF-8', 'windows-1252', $rowsicats[$i]['solicitud']), 1, "J", false);
        $pdf->SetFont("Arial", "", 8);
        $pdf->Cell(30, 4, iconv('UTF-8', 'windows-1252', 'Requiere de:'), 1, 0, "L", true);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', ''), 1, 1, "L", false);
        $pdf->Cell(30, 4, iconv('UTF-8', 'windows-1252', 'Durante:'), 1, 0, "L", true);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', ''), 1, 1, "L", false);
        $pdf->Cell(40, 4, iconv('UTF-8', 'windows-1252', 'El costo total es de:'), 1, 0, "L", true);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', ''), 1, 1, "L", false);
        $pdf->Cell(45, 4, iconv('UTF-8', 'windows-1252', 'Solicitamos su apoyo de:'), 1, 0, "L", true);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', ''), 1, 1, "L", false);
        $pdf->Cell(40, 8, iconv('UTF-8', 'windows-1252', 'El resto se cubrira:'), 1, 0, "L", true);
        $pdf->Cell(5, 4, iconv('UTF-8', 'windows-1252', 'A)'), 1, 0, "L", true);
        $pdf->Cell(65, 4, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L", false);
        $pdf->Cell(5, 4, iconv('UTF-8', 'windows-1252', 'B)'), 1, 0, "L", true);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', ''), 1, 1, "L", false);
        $pdf->SetX(50);
        $pdf->Cell(5, 4, iconv('UTF-8', 'windows-1252', 'C)'), 1, 0, "L", true);
        $pdf->Cell(65, 4, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L", false);
        $pdf->Cell(5, 4, iconv('UTF-8', 'windows-1252', 'D)'), 1, 0, "L", true);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', ''), 1, 1, "L", false);
        $oldY = $pdf->GetY();
        $pdf->MultiCell(50, 4, iconv('UTF-8', 'windows-1252', 'El pago se realizará (Razón Social):'), 1, "J", true);
        $multiCellHeight = $pdf->GetY() - $oldY;
        $pdf->SetXY(60, $oldY);
        $pdf->Cell(60, $multiCellHeight, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L", false);
        $pdf->Cell(35, $multiCellHeight, iconv('UTF-8', 'windows-1252', 'Nombre del contacto:'), 1, 0, "L", true);
        $pdf->Cell(0, $multiCellHeight, iconv('UTF-8', 'windows-1252', ''), 1, 1, "L", false);
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Domicilio:'), 1, 0, "L", true);
        $pdf->Cell(90, 4, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L", false);
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Telefono:'), 1, 0, "L", true);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', ''), 1, 1, "L", false);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'EVOLUCIÓN DEL CASO'), 1, 1, "C", true);
        $pdf->MultiCell(0, 4, iconv('UTF-8', 'windows-1252', $rowsicats[$i]['evolucion_caso']), 1, "J", false);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'SE ANEXA LA SIGUIENTE DOCUMENTACIÓN'), 1, 1, "C", true);
        $pdf->MultiCell(0, 4, iconv('UTF-8', 'windows-1252', $rowsicats[$i]['anexos']), 1, "J", false);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', 'OBSERVACIONES Y/O SUGERENCIAS'), 1, 1, "C", true);
        $pdf->MultiCell(0, 4, iconv('UTF-8', 'windows-1252', $rowsicats[$i]['observaciones_sugerencias']), 1, "J", false);
        $pdf->Cell(40, 4, iconv('UTF-8', 'windows-1252', 'Responsable del caso:'), 1, 0, "L", true);
        $pdf->Cell(70, 4, iconv('UTF-8', 'windows-1252', "TS. " . $rowsicats[$i]['responsable']), 1, 0, "L", false);
        $pdf->Cell(40, 4, iconv('UTF-8', 'windows-1252', 'Al programa de:'), 1, 0, "L", true);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowventanilla['programa']), 1, 1, "L", false);
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Telefono:'), 1, 0, "L", true);
        $pdf->Cell(70, 4, iconv('UTF-8', 'windows-1252', '38-36-34-44'), 1, 0, "L", false);
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Extensión:'), 1, 0, "L", true);
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', $rowsicats[$i]['extension']), 1, 0, "L", false);
        $pdf->Cell(20, 4, iconv('UTF-8', 'windows-1252', 'Horario:'), 1, 0, "L", true);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', '08:30 a 14:30hrs.'), 1, 1, "L", false);
        $pdf->SetFont("Arial", "", 6);
        $pdf->Cell(49.5, 3, iconv('UTF-8', 'windows-1252', 'NOTA: Este documento oficial tiene una vigencia de '), 0, 0, "L", false);
        $pdf->SetFont("Arial", "BIU", 6);
        $pdf->Cell(18, 3, iconv('UTF-8', 'windows-1252', '30 días naturales'), 0, 0, "L", false);
        $pdf->SetFont("Arial", "", 6);
        $pdf->Cell(0, 3, iconv('UTF-8', 'windows-1252', ' a partir de la fecha de expedición, por lo que no sera válido ante otras dependencias en fechas posteriores.'), 0, 1, "L", false);
        $pdf->SetFont("Arial", "B", 6.5);
        $pdf->Cell(0, 3, iconv('UTF-8', 'windows-1252', 'La petición del apoyo solicitado será valorada por el área receptora de acuerdo a sus lineamientos institucionales.'), 0, 1, "L", false);
        $pdf->SetY(250);
        $pdf->SetFont("Arial", "", 8);
        $pdf->Cell(90, 4, iconv('UTF-8', 'windows-1252', 'Atentamente'), 0, 0, "C", false);
        $pdf->Ln(10);
        $pdf->Cell(90, 4, iconv('UTF-8', 'windows-1252', 'LTS. Yadira Noemi Perez Villa'), 0, 0, "C", false);
        $pdf->SetX(120);
        $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', $rowpersona['apaterno'] . " " . $rowpersona['amaterno'] . " " . $rowpersona['nombre']), 0, 1, "C", false);
        $oldY = $pdf->GetY();
        $pdf->Cell(90, 4, iconv('UTF-8', 'windows-1252', 'Jefe o responsable de Trabajo Social'), 0, 0, "C", false);
        $pdf->SetX(120);
        $pdf->SetFont("Arial", "", 6);
        $pdf->MultiCell(0, 3, iconv('UTF-8', 'windows-1252', 'Manifiesto bajo protesta decir la verdad respecto a proporcionar mis datos personales, así como mi aceptación del aviso de privacidad, en donde señala que serán resguardados conforme a la Ley establecida. http://sistemadif.jalisco.gob.mx'), 0, "J", false);
        $pdf->Line(10, $oldY, 100, $oldY);
        $pdf->Line(120, $oldY, 205, $oldY);
    }
    $pdf->Output();
?>