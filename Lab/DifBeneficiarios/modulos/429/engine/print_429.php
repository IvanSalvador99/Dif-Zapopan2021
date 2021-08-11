<?php
    require_once("../../../../funciones.php");
    require_once("../../../../../Tools/fpdf/fpdf.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    $rowpersona = null;
    $rowestudio = null;
    $rowservicio = null;
    $familiares = array();
    $avances = array();
    $apoyos = array();
    
    if (!isset($_GET['idservicio'])) {
        alerta_bota("No se envio el servidio que se quiere imprimir", 0);
        die;
    }
    
    $query = "SELECT * FROM $servicios_otorgados WHERE id = '" . $_GET['idservicio'] . "'";
    //echo "Query servicio:</br>" . $query;
    
    if ($result = mysql_query($query, $con)) {
        $rowservicio = mysql_fetch_assoc($result);
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
    
    /*echo "</br></br>Servicio:</br>";
    var_dump($rowservicio);*/
    
    $query = "SELECT * FROM $vw_persona WHERE id = '" . $rowservicio['idpersona'] . "'";
    //echo "Query servicio:</br>" . $query;
    
    if ($result = mysql_query($query, $con)) {
        $rowpersona = mysql_fetch_assoc($result);
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
    
    /*echo "</br></br>Persona:</br>";
    var_dump($rowpersona);*/
    
    $query = "SELECT * FROM $vw_estudio_sociofamiliar WHERE idservicio = '" . $_GET['idservicio'] . "'";
    //echo "Query estudio socioeconomico:</br>" . $query;
    
    if ($result = mysql_query($query, $con)) {
        $rowestudio = mysql_fetch_assoc($result);
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
    
    /*echo "</br></br>Estudio socioeconomico:</br>";
    var_dump($rowestudio);*/
    
    $query = "SELECT * FROM $estsocio_comp_familiar WHERE idservicio = '" . $_GET['idservicio'] . "'";
    //echo "</br></br>Query familiares:</br>" . $query;
    
    if ($result = mysql_query($query, $con)) {
        while ($rowfamiliar = mysql_fetch_assoc($result)) {
            $familiares[] = $rowfamiliar;
        }
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
    
    /*echo "</br></br>Familiares:</br>";
    var_dump($familiares);*/
    
    $query = "SELECT * FROM $estsocio_apoyos WHERE idservicio = '" . $_GET['idservicio'] . "' ORDER BY apoyofecha DESC";
    //echo "</br></br>Query apoyos:</br>" . $query;
    
    if ($result = mysql_query($query, $con)) {
        while ($rowapoyo = mysql_fetch_assoc($result)) {
            $apoyos[] = $rowapoyo;
        }
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
    
    /*echo "</br></br>Apoyos:</br>";
    var_dump($apoyos);*/
    
    $query = "SELECT * FROM $estsocio_avances WHERE idservicio = '" . $_GET['idservicio'] . "'";
    //echo "</br></br>Query avances:</br>" . $query;
    
    if ($result = mysql_query($query, $con)) {
        while ($rowavance = mysql_fetch_assoc($result)) {
            $avances[] = $rowavance;
        }
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
    
    $diagsplit = dividirString($rowestudio['diagsfamiliar'], 2500);
    $prv = explode(',', $rowestudio['probvul']);
    $prv2 = explode(',', $rowestudio['probvul2']);
    $prv3 = explode(',', $rowestudio['probvul3']);
    
    $diag = explode(',', $rowestudio['diagnostico']);
    $diag2 = explode(',', $rowestudio['diagnostico2']);
    $diag3 = explode(',', $rowestudio['diagnostico3']);
    
    /*echo "</br></br>Avances:</br>";
    var_dump($avances);*/
    
    $pdf = new FPDF("L", "mm", "Letter");
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(false, 0);
    $alto = $pdf->GetPageHeight();
    $ancho = $pdf->GetPageWidth();
    
    /* --- Primera hoja --- */
    
    $pdf->Rect(10, 10, 90, 69);
    $pdf->Rect(70, 10, 30, 35);
    $pdf->SetXY(15,50);
    $pdf->Image('../../../../../Tools/imagenes/LogoDIF.png', 15, 17, 23);
    $pdf->Image('../../../../../Tools/imagenes/logo_jalisco.png', 45, 12, 17);
    $pdf->Image('../../../../../Tools/imagenes/logo_dif_jalisco.png', 47, 30, 13);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(50, 4, iconv('UTF-8', 'windows-1252', 'Departamento de Trabajo Social'), 0, 2, "L");
    $pdf->SetFont("Arial", "B", 8);
    $pdf->SetX(20);
    $pdf->Cell(50, 4, iconv('UTF-8', 'windows-1252', 'Estudio Socio Familiar'), 0, 0, "L");
    $pdf->SetXY(80, 25);
    $pdf->Cell(15, 4, iconv('UTF-8', 'windows-1252', 'FOTO'), 0, 0, "L");
    $pdf->SetXY(15, 60);
    $pdf->SetFont("Arial", "", 7);
    $pdf->Cell(50, 4, iconv('UTF-8', 'windows-1252', 'Fecha de elaboración:'), 0, 0, "L");
    $pdf->SetX(55);
    $pdf->Cell(8, 4, iconv('UTF-8', 'windows-1252', date("d")), 0, 0, "L");
    $pdf->SetFont("Arial", "", 12);
    $pdf->Cell(6, 4, iconv('UTF-8', 'windows-1252', "/"), 0, 0, "L");
    $pdf->SetFont("Arial", "", 7);
    $pdf->Cell(8, 4, iconv('UTF-8', 'windows-1252', date("m")), 0, 0, "L");
    $pdf->SetFont("Arial", "", 12);
    $pdf->Cell(6, 4, iconv('UTF-8', 'windows-1252', "/"), 0, 0, "L");
    $pdf->SetFont("Arial", "", 7);
    $pdf->Cell(8, 4, iconv('UTF-8', 'windows-1252', date("Y")), 0, 0, "L");
    $pdf->SetXY(15, 65);
    $pdf->Cell(50, 4, iconv('UTF-8', 'windows-1252', 'Fecha de captura:'), 0, 0, "L");
    $pdf->SetX(55);
    $pdf->Cell(8, 4, iconv('UTF-8', 'windows-1252', date("d", strtotime($rowservicio['fecha']))), 0, 0, "L");
    $pdf->SetFont("Arial", "", 12);
    $pdf->Cell(6, 4, iconv('UTF-8', 'windows-1252', "/"), 0, 0, "L");
    $pdf->SetFont("Arial", "", 7);
    $pdf->Cell(8, 4, iconv('UTF-8', 'windows-1252', date("m", strtotime($rowservicio['fecha']))), 0, 0, "L");
    $pdf->SetFont("Arial", "", 12);
    $pdf->Cell(6, 4, iconv('UTF-8', 'windows-1252', "/"), 0, 0, "L");
    $pdf->SetFont("Arial", "", 7);
    $pdf->Cell(8, 4, iconv('UTF-8', 'windows-1252', date("Y", strtotime($rowservicio['fecha']))), 0, 0, "L");
    $pdf->SetXY(55, 70);
    $pdf->Cell(14, 4, iconv('UTF-8', 'windows-1252', 'Día'), 0, 0, "L");
    $pdf->Cell(14, 4, iconv('UTF-8', 'windows-1252', 'Mes'), 0, 0, "L");
    $pdf->Cell(14, 4, iconv('UTF-8', 'windows-1252', 'Año'), 0, 0, "L");
    $pdf->SetXY(100, 10);
    $pdf->SetFillColor(180);
    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', '1. Datos de identificación del beneficiario'), 1, 2, "C", TRUE);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - $pdf->GetX() - 10, 5);
    $prevX = $pdf->GetX();
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(37, 5, iconv('UTF-8', 'windows-1252', 'No. de expediente o registro'), 0, 0, "L");
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(43, 5, iconv('UTF-8', 'windows-1252', $rowestudio['registro']), 0, 0, "L");
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(17, 5, iconv('UTF-8', 'windows-1252', 'Referido por'), 0, 0, "L");
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', ucfirst(strtolower($rowestudio['procedencia']))), 0, 2, "L");
    $pdf->SetX($prevX);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - $pdf->GetX() - 10, 7);
    $pdf->Cell(35, 7, iconv('UTF-8', 'windows-1252', ucwords(strtolower($rowpersona['apaterno']))), 0, 0, "L");
    $pdf->Cell(35, 7, iconv('UTF-8', 'windows-1252', ucwords(strtolower($rowpersona['amaterno']))), 0, 0, "L");
    $pdf->Cell(60, 7, iconv('UTF-8', 'windows-1252', ucwords(strtolower($rowpersona['nombre']))), 0, 0, "L");
    $pdf->Cell(0, 7, iconv('UTF-8', 'windows-1252', $rowpersona['iddifzapopan']), 0, 2, "L");
    $pdf->SetX($prevX);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - $pdf->GetX() - 10, 5);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(35, 5, iconv('UTF-8', 'windows-1252', "Apellido paterno"), 0, 0, "L");
    $pdf->Cell(35, 5, iconv('UTF-8', 'windows-1252', "Apellido materno"), 0, 0, "L");
    $pdf->Cell(60, 5, iconv('UTF-8', 'windows-1252', "Nombre(s)"), 0, 0, "L");
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', "ID Dif Zapopan"), 0, 2, "L");
    $pdf->SetX($prevX);
    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', '1.1. Lugar de nacimiento'), 1, 2, "C", TRUE);
    $pdf->SetX($prevX);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - $pdf->GetX() - 10, 10);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(35, 5, iconv('UTF-8', 'windows-1252', "1.1.1 Fecha de nacimiento"), 0, 0, "L");
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(30, 5, iconv('UTF-8', 'windows-1252', invierte_fecha($rowpersona['fechanacimiento'])), 0, 0, "L");
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(22, 5, iconv('UTF-8', 'windows-1252', "1.1.2 Municipio"), 0, 0, "L");
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', $rowestudio['municipionac']), 0, 2, "L");
    $pdf->SetX($prevX);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(26, 5, iconv('UTF-8', 'windows-1252', "1.1.3 Nacionalidad"), 0, 0, "L");
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(54, 5, iconv('UTF-8', 'windows-1252', $rowestudio['pais']), 0, 0, "L");
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(22, 5, iconv('UTF-8', 'windows-1252', "1.1.4 Estado"), 0, 0, "L");
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', $rowestudio['estadonac']), 0, 2, "L");
    $pdf->SetX($prevX);
    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', '1.2. Lugar de residencia'), 1, 2, "C", TRUE);
    $pdf->SetX($prevX);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - $pdf->GetX() - 10, 5);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(21, 5, iconv('UTF-8', 'windows-1252', "1.2.1 Domicilio"), 0, 0, "L");
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(94, 5, iconv('UTF-8', 'windows-1252', ucfirst(strtolower($rowpersona['calle']))), 0, 0, "L");
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(21, 5, iconv('UTF-8', 'windows-1252', "Núm. ext. e int."), 0, 0, "L");
    $pdf->SetFont("Arial", "", 10);
    if ($rowpersona['numint'] == "" || $rowpersona['numint'] == 0) {
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', $rowpersona['numext']), 0, 2, "L");
    } else {
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', $rowpersona['numext'] . " - " . $rowpersona['numint']), 0, 2, "L");
    }
    $pdf->SetX($prevX);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - $pdf->GetX() - 10, 5);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(25, 5, iconv('UTF-8', 'windows-1252', "1.2.2 Cruza con"), 0, 0, "L");
    $pdf->SetFont("Arial", "", 10);
    if ($rowpersona['segundocruce'] == "") {
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', $rowpersona['primercruce']), 0, 2, "L");
    } else {
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', $rowpersona['primercruce'] . " y " . $rowpersona['segundocruce']), 0, 2, "L");
    }
    $pdf->SetX($prevX);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - $pdf->GetX() - 10, 5);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(18, 5, iconv('UTF-8', 'windows-1252', "1.2.3 Colonia"), 0, 0, "L");
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(72, 5, iconv('UTF-8', 'windows-1252', $rowpersona['colonia']), 0, 0, "L");
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', "1.2.4 Comunidad"), 0, 0, "L");
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(30, 5, iconv('UTF-8', 'windows-1252', $rowpersona['comunidad']), 0, 0, "L");
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', "1.2.5 C.P."), 0, 0, "L");
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', $rowpersona['codigopostal']), 0, 2, "L");
    $pdf->SetX($prevX);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - $pdf->GetX() - 10, 5);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', "1.2.6 Municipio"), 0, 0, "L");
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(40, 5, iconv('UTF-8', 'windows-1252', $rowpersona['municipio']), 0, 0, "L");
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(18, 5, iconv('UTF-8', 'windows-1252', "1.2.7 Estado"), 0, 0, "L");
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(40, 5, iconv('UTF-8', 'windows-1252', $rowpersona['estado']), 0, 0, "L");
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(32, 5, iconv('UTF-8', 'windows-1252', "1.2.8 Tiempo en el edo."), 0, 0, "L");
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', $rowestudio['tiempoestado']), 0, 2, "L");
    $pdf->SetX($prevX);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - $pdf->GetX() - 10, 5);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(25, 5, iconv('UTF-8', 'windows-1252', "1.2.9 Telefono"), 0, 0, "L");
    if ($rowpersona['escontacto'] == 0) {
        $pdf->SetFont("Arial", "", 10);
        $pdf->Cell(50, 5, iconv('UTF-8', 'windows-1252', ($rowpersona['telefono'] == 0) ? '' : $rowpersona['telefono']), 0, 0, "L");
    } else {
        $pdf->Cell(50, 5, iconv('UTF-8', 'windows-1252', ''), 0, 0, "L");
    }
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(25, 5, iconv('UTF-8', 'windows-1252', "1.2.10 Recados"), 0, 0, "L");
    if ($rowpersona['escontacto'] == 1) {
        $pdf->SetFont("Arial", "", 10);
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', ($rowpersona['telefono'] == 0) ? '' : $rowpersona['telefono']), 0, 2, "L");
    } else {
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', ''), 0, 2, "L");
    }
    $pdf->SetX($prevX);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - $pdf->GetX() - 10, 5);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(43, 5, iconv('UTF-8', 'windows-1252', "1.2.11 Programa que lo atiende"), 0, 0, "L");
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(67, 5, iconv('UTF-8', 'windows-1252', $rowestudio['programa']), 0, 0, "L");
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(25, 5, iconv('UTF-8', 'windows-1252', "1.2.12 Número"), 0, 0, "L");
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', $rowestudio['numeroprograma']), 0, 1, "L");
    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', '2. Servicio o apoyo solicitado'), 1, 1, "C", TRUE);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - $pdf->GetX() - 10, 10);
    $pdf->SetFont("Arial", "", 10);
    $prevY = $pdf->GetY();
    $pdf->MultiCell(0, 5, iconv('UTF-8', 'windows-1252', strtoupper($rowestudio['apoyosolicitado'])), 0, "C");
    if ($pdf->GetY() - $prevY < 10) {
        $pdf->SetY($pdf->GetY() + 5);
    }
    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(130, 4, iconv('UTF-8', 'windows-1252', '3. Composición familiar'), 1, 0, "C", TRUE);
    $pdf->Cell(36, 4, iconv('UTF-8', 'windows-1252', '4. Educación'), 1, 0, "C", TRUE);
    $pdf->Cell(0, 4, iconv('UTF-8', 'windows-1252', '5. Economía'), 1, 1, "C", TRUE);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(65, 7, iconv('UTF-8', 'windows-1252', "3.1 Nombre"), 1, 0, "C");
    $prevX = $pdf->GetX();
    $prevY = $pdf->GetY();
    $pdf->SetFont("Arial", "", 6);
    $pdf->MultiCell(15, 4, iconv('UTF-8', 'windows-1252', '3.2 Fecha de nacimiento dd/mm/aaaa'), 1, "C");
    $pdf->SetXY($prevX + 15, $prevY);
    $prevX = $pdf->GetX();
    $prevY = $pdf->GetY();
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 15, 7);
    $pdf->Cell(7.5, 3.5, iconv('UTF-8', 'windows-1252', "3.3 Sexo"), 0, 2, "L");
    $pdf->SetX($pdf->GetX() + 7.5);
    $pdf->Cell(7.5, 3.5, iconv('UTF-8', 'windows-1252', "Edad"), 0, 0, "R");
    $pdf->Line($prevX, $pdf->GetY() + 3.5, $pdf->GetX(), $prevY);
    $pdf->SetXY($prevX + 15, $prevY);
    $prevX = $pdf->GetX();
    $prevY = $pdf->GetY();
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 15, 12);
    $pdf->Cell(15, 2, iconv('UTF-8', 'windows-1252', ""), 0, 2, "L");
    $pdf->MultiCell(15, 4, iconv('UTF-8', 'windows-1252', '3.4 Estado civil'), 0, "C");
    $pdf->Cell(15, 2, iconv('UTF-8', 'windows-1252', ""), 0, 2, "L");
    $pdf->SetXY($prevX + 15, $prevY);
    $prevX = $pdf->GetX();
    $prevY = $pdf->GetY();
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 20, 12);
    $pdf->Cell(20, 12, iconv('UTF-8', 'windows-1252', '3.5 Parentesco'), 0, 0, "C");
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(36, 7, iconv('UTF-8', 'windows-1252', '4.1 Escolaridad'), 1, 0, "C", TRUE);
    $pdf->Cell(40, 12, iconv('UTF-8', 'windows-1252', '5.1 Ocupación'), 1, 0, "C", TRUE);
    $prevX = $pdf->GetX();
    $prevY = $pdf->GetY();
    $pdf->SetFont("Arial", "", 6);
    $pdf->MultiCell(15, 6, iconv('UTF-8', 'windows-1252', '5.2 Permanente'), 1, "C", TRUE);
    $pdf->SetXY($prevX + 15, $prevY);
    $pdf->SetFont("Arial", "", 8);
    $pdf->MultiCell(15, 6, iconv('UTF-8', 'windows-1252', '5.3 Eventual'), 1, "C", TRUE);
    $pdf->SetXY($prevX + 30, $prevY);
    $pdf->MultiCell(0, 6, iconv('UTF-8', 'windows-1252', '5.4 Ingreso mensual'), 1, "C", TRUE);
    $pdf->Sety($pdf->GetY() - 5);
    $pdf->SetFont("Arial", "", 6);
    $pdf->Cell(5, 5, iconv('UTF-8', 'windows-1252', 'No.'), 1, 0, "L");
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 60, 5);
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', 'A. paterno'), 0, 0, "L");
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', 'A. materno'), 0, 0, "L");
    $pdf->Cell(30, 5, iconv('UTF-8', 'windows-1252', 'Nombre'), 0, 0, "L");
    $pdf->SetX($pdf->GetX() + 15);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(7.5, 5, iconv('UTF-8', 'windows-1252', 'H'), 1, 0, "C");
    $pdf->Cell(7.5, 5, iconv('UTF-8', 'windows-1252', 'M'), 1, 0, "C");
    $pdf->SetX($pdf->GetX() + 35);
    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', '1'), 1, 0, "C");
    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', '2'), 1, 0, "C");
    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', '3'), 1, 0, "C");
    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', '4'), 1, 0, "C");
    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', '5'), 1, 0, "C");
    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', '6'), 1, 0, "C");
    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', '7'), 1, 0, "C");
    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', '8'), 1, 0, "C");
    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', '9'), 1, 1, "C");
    $pdf->Cell(5, 5, iconv('UTF-8', 'windows-1252', '1'), 1, 0, "C");
    $pdf->Cell(60, 5, iconv('UTF-8', 'windows-1252', ucwords(strtolower($rowpersona['apaterno'] . " " . $rowpersona['amaterno'] . " " . $rowpersona['nombre']))), 1, 0, "L");
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', invierte_fecha($rowpersona['fechanacimiento'])), 1, 0, "C");
    if ($rowpersona['sexo'] == 1) {
        $pdf->Cell(7.5, 5, iconv('UTF-8', 'windows-1252', $rowpersona['edad']), 1, 0, "C");
        $pdf->Cell(7.5, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    } else {
        $pdf->Cell(7.5, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(7.5, 5, iconv('UTF-8', 'windows-1252', $rowpersona['edad']), 1, 0, "C");
    }
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', ucfirst(strtolower($rowpersona['estado_civil']))), 1, 0, "C");
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    switch ($rowpersona['escolaridad']) {
        case 'ANALFABETA':
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            break;
        case 'AUTODIDACTA':
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            break;
        case 'PREESCOLAR':
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            break;
        case 'PRIMARIA INCOMPLETA':
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', '4'), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            break;
        case 'PRIMARIA':
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            break;
        case 'SECUNDARIA INCOMPLETA':
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', '2'), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            break;
        case 'SECUNDARIA':
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            break;
        case 'PREPARATORIA INCOMPLETA':
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', '4'), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            break;
        case 'PREPARATORIA':
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            break;
        case 'CARRERA TECNICA INCOMPLETA':
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', '4'), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            break;
        case 'CARRERA TECNICA':
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            break;
        case 'LICENCIATURA INCOMPLETA':
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', '4'), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            break;
        case 'LICENCIATURA':
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            break;
        case 'MAESTRIA' || 'DOCTORADO':
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
            break;
        default:
            break;
    }
    $pdf->Cell(40, 5, iconv('UTF-8', 'windows-1252', ucfirst(strtolower($rowpersona['ocupacion']))), 1, 0, "C");
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', "$" . ($rowestudio['ingresototal'] - $rowestudio['ingresofamiliar'] - $rowestudio['ingresootros'])), 1, 1, "R");
    
    $i = 0;
    foreach ($familiares as $familiar) {
        $pdf->Cell(5, 5, iconv('UTF-8', 'windows-1252', $i + 2), 1, 0, "C");
        $pdf->Cell(60, 5, iconv('UTF-8', 'windows-1252', ucwords(strtolower($familiar['nombre']))), 1, 0, "L");
        $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', invierte_fecha($familiar['fechanacimiento'])), 1, 0, "C");
        if ($familiar['sexo'] == 'Hombre') {
            $pdf->Cell(7.5, 5, iconv('UTF-8', 'windows-1252', $familiar['edad']), 1, 0, "C");
            $pdf->Cell(7.5, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        } else {
            $pdf->Cell(7.5, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(7.5, 5, iconv('UTF-8', 'windows-1252', $familiar['edad']), 1, 0, "C");
        }
        $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', ucfirst(strtolower($familiar['estadocivil']))), 1, 0, "C");
        $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', ucfirst(strtolower($familiar['parentesco']))), 1, 0, "C");
        switch (trim($familiar['escolaridad'])) {
            case 'Analfabeta':
                if ($familiar['grado'] == 'Terminado') {
                    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
                    
                } else {
                    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', $familiar['grado']), 1, 0, "C");
                }
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                break;
            case 'Autodidáctico':
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                if ($familiar['grado'] == 'Terminado') {
                    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
                    
                } else {
                    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', $familiar['grado']), 1, 0, "C");
                }
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                break;
            case 'Preescolar':
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                if ($familiar['grado'] == 'Terminado') {
                    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
                    
                } else {
                    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', $familiar['grado']), 1, 0, "C");
                }
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                break;
            case 'Primaria':
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                if ($familiar['grado'] == 'Terminado') {
                    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
                    
                } else {
                    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', $familiar['grado']), 1, 0, "C");
                }
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                break;
            case 'Secundaria':
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                if ($familiar['grado'] == 'Terminado') {
                    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
                    
                } else {
                    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', $familiar['grado']), 1, 0, "C");
                }
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                break;
            case 'Preparatoria':
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                if ($familiar['grado'] == 'Terminado') {
                    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
                    
                } else {
                    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', $familiar['grado']), 1, 0, "C");
                }
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                break;
            case 'Carrera Técnica':
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                if ($familiar['grado'] == 'Terminado') {
                    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
                    
                } else {
                    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', $familiar['grado']), 1, 0, "C");
                }
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                break;
            case 'Profesional':
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                if ($familiar['grado'] == 'Terminado') {
                    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
                    
                } else {
                    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', $familiar['grado']), 1, 0, "C");
                }
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                break;
            case 'Posgrado':
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
                if ($familiar['grado'] == 'Terminado') {
                    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
                    
                } else {
                    $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', $familiar['grado']), 1, 0, "C");
                }
                break;
            default:
                break;
        }
        $pdf->Cell(40, 5, iconv('UTF-8', 'windows-1252', ucfirst(strtolower($familiar['ocupacion']))), 1, 0, "C");
        if ($familiar['tipoingreso'] == 'Permanente') {
            $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
            $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        } elseif ($familiar['tipoingreso'] == 'Eventual') {
            $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
        } else {
            $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        }
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', "$" . $familiar['ingreso']), 1, 1, "R");
        
        $i++;
    }
    for ($j; $i < 14; $i++) { 
        $pdf->Cell(5, 5, iconv('UTF-8', 'windows-1252', $i + 2), 1, 0, "C");
        $pdf->Cell(60, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
        $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(7.5, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(7.5, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(4, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(40, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', '$'), 1, 1, "L");
    }
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 236, 8);
    $pdf->SetFont("Arial", "", 7);
    $pdf->Cell(206, 8, iconv('UTF-8', 'windows-1252', '1. Analfabeta, 2.Autodidáctico, 3. Preescolar, 4. Primaria, 5. Secundaria, 6. Preparatoria, 7. Carrera Técnica, 8. Profesional, 9. Posgrado'), 0, 0, "L");
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(30, 8, iconv('UTF-8', 'windows-1252', '5.5 Total'), 0, 0, "R");
    $pdf->Cell(0, 8, iconv('UTF-8', 'windows-1252', '$' . ($rowestudio['ingresototal'] - $rowestudio['ingresootros'])), 1, 1, "R");
    $pdf->SetFont("Arial", "", 7);
    $pdf->Ln(5);
    $pdf->Cell(236, 8, iconv('UTF-8', 'windows-1252', 'Fecha de actualización: 06 de Junio de 2019 V. 11 Código: DJ-TS-SG-RE-01'), 0, 0, "L");
    
    /* --- Segunda hoja --- */
    
    $pdf->AddPage();
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(110, 5, iconv('UTF-8', 'windows-1252', '6. Datos de identificación del beneficiario'), 1, 0, "C", TRUE);
    $pdf->Cell(50, 5, iconv('UTF-8', 'windows-1252', '7. Patrimonio'), 1, 0, "C", TRUE);
    $pdf->Cell(50, 5, iconv('UTF-8', 'windows-1252', '9. Total de Egresos'), 1, 0, "C", TRUE);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', '10. Balance de recursos'), 1, 1, "C", TRUE);
    $prevX = $pdf->GetX();
    $prevY = $pdf->GetY();
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 60, 80);
    $pdf->Cell(60, 5, iconv('UTF-8', 'windows-1252', '6.1 Condición'), 0, 1, "L");
    $pdf->SetX($pdf->GetX() + 2);
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Propia'), 0, 0, "L");
    if ($rowestudio['condicionvivienda'] == 1) {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
    }
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Renta'), 0, 0, "L");
    if ($rowestudio['condicionvivienda'] == 3) {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 1, "C");
    } else {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', ''), 1, 1, "L");
    }
    $pdf->SetX($pdf->GetX() + 2);
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'En pago'), 0, 0, "L");
    if ($rowestudio['condicionvivienda'] == 4) {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
    }
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Prestada'), 0, 0, "L");
    if ($rowestudio['condicionvivienda'] == 2) {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 1, "C");
    } else {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', ''), 1, 1, "L");
    }
    $pdf->SetX($pdf->GetX() + 2);
    $pdf->Cell(23, 5, iconv('UTF-8', 'windows-1252', 'Otro/¿Por quién?'), 0, 0, "L");
    $pdf->Cell(35, 5, iconv('UTF-8', 'windows-1252', $rowestudio['otrocondicion']), "B", 1, "C");
    $pdf->Cell(60, 5, iconv('UTF-8', 'windows-1252', '6.2 Servicios'), 0, 1, "L");
    $pdf->SetX($pdf->GetX() + 2);
    $pdf->Cell(17, 5, iconv('UTF-8', 'windows-1252', 'Agua'), 0, 0, "L");
    $pdf->Cell(41, 5, iconv('UTF-8', 'windows-1252', $rowestudio['tipoagua']), "B", 1, "C");
    $pdf->SetX($pdf->GetX() + 2);
    $pdf->Cell(17, 5, iconv('UTF-8', 'windows-1252', 'Desechos'), 0, 0, "L");
    $pdf->Cell(41, 5, iconv('UTF-8', 'windows-1252', $rowestudio['tipodesechos']), "B", 1, "C");
    $pdf->SetX($pdf->GetX() + 2);
    $pdf->Cell(17, 5, iconv('UTF-8', 'windows-1252', 'Electricidad'), 0, 0, "L");
    $pdf->Cell(41, 5, iconv('UTF-8', 'windows-1252', $rowestudio['tipoelectricidad']), "B", 1, "C");
    $pdf->Cell(60, 5, iconv('UTF-8', 'windows-1252', '6.3 Tipo de vivienda y distribución'), 0, 1, "L");
    $pdf->Cell(31, 5, iconv('UTF-8', 'windows-1252', ''), 0, 0, "L");
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Cocina'), 0, 0, "L");
    $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', $rowestudio['distcocina']), 1, 1, "C");
    $pdf->Cell(31, 5, iconv('UTF-8', 'windows-1252', ''), 0, 0, "L");
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Baño'), 0, 0, "L");
    $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', $rowestudio['distbano']), 1, 1, "C");
    $pdf->SetX($pdf->GetX() + 2);
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Casa'), 0, 0, "L");
    if ($rowestudio['tipovivienda'] == 1) {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
    }
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Dormitorios'), 0, 0, "L");
    $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', $rowestudio['distdormitorio']), 1, 1, "C");
    $pdf->SetX($pdf->GetX() + 2);
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Departamento'), 0, 0, "L");
    if ($rowestudio['tipovivienda'] == 2) {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
    }
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Sala'), 0, 0, "L");
    $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', $rowestudio['distsala']), 1, 1, "C");
    $pdf->SetX($pdf->GetX() + 2);
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Vecindad'), 0, 0, "L");
    if ($rowestudio['tipovivienda'] == 3) {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
    }
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Sala'), 0, 0, "L");
    $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', $rowestudio['distsala']), 1, 1, "C");
    $pdf->SetX($pdf->GetX() + 2);
    $tempX = $pdf->GetX();
    $tempY = $pdf->GetY();
    $pdf->Cell(7, 5, iconv('UTF-8', 'windows-1252', 'Otro'), 0, 0, "L");
    $pdf->SetFont("Arial", "", 7);
    $pdf->MultiCell(22, 5, iconv('UTF-8', 'windows-1252', $rowestudio['otrotipovivienda']), 0, "C");
    $pdf->SetXY($tempX + 29, $tempY);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(7, 5, iconv('UTF-8', 'windows-1252', 'Otro'), 0, 0, "L");
    $pdf->SetFont("Arial", "", 7);
    $pdf->MultiCell(22, 5, iconv('UTF-8', 'windows-1252', $rowestudio['otrodistribucion']), 0, "C");
    $pdf->SetXY($prevX + 60, $prevY);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 50, 80);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(50, 5, iconv('UTF-8', 'windows-1252', '6.4 Caracteristicas'), 0, 2, "L");
    $pdf->SetX($pdf->GetX() + 2);
    $pdf->Cell(10, 5, iconv('UTF-8', 'windows-1252', 'Piso'), 0, 0, "L");
    $pdf->Cell(38, 5, iconv('UTF-8', 'windows-1252', $rowestudio['tipopiso']), "B", 1, "L");
    $pdf->SetX($pdf->GetX() + 62);
    $pdf->Cell(10, 5, iconv('UTF-8', 'windows-1252', 'Muro'), 0, 0, "L");
    $pdf->Cell(38, 5, iconv('UTF-8', 'windows-1252', $rowestudio['tipomuro']), "B", 1, "L");
    $pdf->SetX($pdf->GetX() + 62);
    $pdf->Cell(10, 5, iconv('UTF-8', 'windows-1252', 'Techo'), 0, 0, "L");
    $pdf->Cell(38, 5, iconv('UTF-8', 'windows-1252', $rowestudio['tipotecho']), "B", 1, "L");
    $pdf->SetX($pdf->GetX() + 60);
    $pdf->Cell(50, 5, iconv('UTF-8', 'windows-1252', '6.5 Zona'), 0, 2, "L");
    $pdf->SetX($pdf->GetX() + 2);
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Urbana'), 0, 0, "L");
    if ($rowestudio['zonavivienda'] == 1) {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
    }
    $pdf->Cell(19, 5, iconv('UTF-8', 'windows-1252', 'Otro'), 0, 1, "L");
    $pdf->SetX($pdf->GetX() + 62);
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Sub-urbana'), 0, 0, "L");
    if ($rowestudio['zonavivienda'] == 2) {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
    }
    $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', ''), 1, 1, "L");
    $pdf->SetX($pdf->GetX() + 62);
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Rural'), 0, 0, "L");
    if ($rowestudio['zonavivienda'] == 3) {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 1, "C");
    } else {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', ''), 1, 1, "L");
    }
    $pdf->SetX($pdf->GetX() + 60);
    $pdf->Cell(50, 5, iconv('UTF-8', 'windows-1252', '6.6 Menaje de casa'), 0, 2, "L");
    $pdf->SetX($pdf->GetX() + 2);
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Equipado'), 0, 0, "L");
    if ($rowestudio['menajevivienda'] == 1) {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 1, "C");
    } else {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', ''), 1, 1, "L");
    }
    $pdf->SetX($pdf->GetX() + 62);
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Basico'), 0, 0, "L");
    if ($rowestudio['menajevivienda'] == 2) {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 1, "C");
    } else {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', ''), 1, 1, "L");
    }
    $pdf->SetX($pdf->GetX() + 62);
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Austero'), 0, 0, "L");
    if ($rowestudio['menajevivienda'] == 3) {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 1, "C");
    } else {
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', ''), 1, 1, "L");
    }
    $pdf->SetX($pdf->GetX() + 60);
    $pdf->Cell(50, 5, iconv('UTF-8', 'windows-1252', '6.7 Limpieza y organización'), 0, 2, "L");
    $pdf->SetFont("Arial", "", 7);
    $pdf->MultiCell(50, 5, iconv('UTF-8', 'windows-1252', $rowestudio['limporg']), 0, "C");
    $pdf->SetXY($prevX + 110, $prevY);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 50, 80);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(10, 5, iconv('UTF-8', 'windows-1252', '7.1 Tipo'), 0, 0, "L");
    $pdf->Cell(40, 5, iconv('UTF-8', 'windows-1252', 'Cantidad valuada'), 0, 1, "C");
    $pdf->SetX($pdf->GetX() + 110);
    if ($rowestudio['tipoinmueble'] == 1) {
        $pdf->Cell(12, 5, iconv('UTF-8', 'windows-1252', 'Casa'), 0, 0, "L");
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
        $pdf->Cell(1, 5, iconv('UTF-8', 'windows-1252', ''), 0, 0, "L");
        $pdf->Cell(28, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['valorinmueble']), "B", 1, "R");
    } else {
        $pdf->Cell(12, 5, iconv('UTF-8', 'windows-1252', 'Casa'), 0, 0, "L");
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
        $pdf->Cell(1, 5, iconv('UTF-8', 'windows-1252', ''), 0, 0, "L");
        $pdf->Cell(28, 5, iconv('UTF-8', 'windows-1252', '$'), "B", 1, "R");
    }
    $pdf->SetX($pdf->GetX() + 110);
    if ($rowestudio['tipoinmueble'] == 2) {
        $pdf->Cell(12, 5, iconv('UTF-8', 'windows-1252', 'Terreno'), 0, 0, "L");
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
        $pdf->Cell(1, 5, iconv('UTF-8', 'windows-1252', ''), 0, 0, "L");
        $pdf->Cell(28, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['valorinmueble']), "B", 1, "R");
    } else {
        $pdf->Cell(12, 5, iconv('UTF-8', 'windows-1252', 'Terreno'), 0, 0, "L");
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
        $pdf->Cell(1, 5, iconv('UTF-8', 'windows-1252', ''), 0, 0, "L");
        $pdf->Cell(28, 5, iconv('UTF-8', 'windows-1252', '$'), "B", 1, "R");
    }
    $pdf->SetX($pdf->GetX() + 110);
    if ($rowestudio['tipoinmueble'] == 3) {
        $pdf->Cell(12, 5, iconv('UTF-8', 'windows-1252', 'Otro'), 0, 0, "L");
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
        $pdf->Cell(1, 5, iconv('UTF-8', 'windows-1252', ''), 0, 0, "L");
        $pdf->Cell(28, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['valorinmueble']), "B", 1, "R");
    } else {
        $pdf->Cell(12, 5, iconv('UTF-8', 'windows-1252', 'Otro'), 0, 0, "L");
        $pdf->Cell(9, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
        $pdf->Cell(1, 5, iconv('UTF-8', 'windows-1252', ''), 0, 0, "L");
        $pdf->Cell(28, 5, iconv('UTF-8', 'windows-1252', '$'), "B", 1, "R");
    }
    $pdf->Ln(5);
    $pdf->SetX($pdf->GetX() + 110);
    $pdf->Cell(50, 5, iconv('UTF-8', 'windows-1252', '7.2 Cuentas de ahorro e inversión'), 0, 2, "L");
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', 'Institución'), 0, 0, "L");
    $pdf->Cell(35, 5, iconv('UTF-8', 'windows-1252', $rowestudio['institucioncuenta']), "B", 1, "R");
    $pdf->SetX($pdf->GetX() + 110);
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', 'Cantidad'), 0, 0, "L");
    $pdf->Cell(35, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['valorcuenta']), "B", 1, "R");
    $pdf->SetX($pdf->GetX() + 110);
    $pdf->Cell(50, 5, iconv('UTF-8', 'windows-1252', '7.3 Vehiculos'), 0, 2, "L");
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', 'Marca'), 0, 0, "L");
    $pdf->Cell(35, 5, iconv('UTF-8', 'windows-1252', $rowestudio['marcavehiculo']), "B", 1, "C");
    $pdf->SetX($pdf->GetX() + 110);
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', 'Modelo'), 0, 0, "L");
    $pdf->Cell(35, 5, iconv('UTF-8', 'windows-1252', $rowestudio['modelovehiculo']), "B", 1, "C");
    $pdf->SetX($pdf->GetX() + 110);
    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(50, 5, iconv('UTF-8', 'windows-1252', '8. Creditos'), 1, 2, "C", TRUE);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', 'Empresa'), 0, 0, "L");
    $pdf->Cell(35, 5, iconv('UTF-8', 'windows-1252', $rowestudio['institucioncredito']), "B", 1, "C");
    $pdf->SetX($pdf->GetX() + 110);
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', 'Cantidad'), 0, 0, "L");
    $pdf->Cell(35, 5, iconv('UTF-8', 'windows-1252', $rowestudio['saldocredito']), "B", 1, "C");
    $pdf->SetX($pdf->GetX() + 110);
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', 'Empresa'), 0, 0, "L");
    $pdf->Cell(35, 5, iconv('UTF-8', 'windows-1252', ''), "B", 1, "C");
    $pdf->SetX($pdf->GetX() + 110);
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', 'Cantidad'), 0, 0, "L");
    $pdf->Cell(35, 5, iconv('UTF-8', 'windows-1252', ''), "B", 1, "C");
    $pdf->SetXY($prevX + 160, $prevY);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 50, 80);
    $pdf->Cell(50, 5, iconv('UTF-8', 'windows-1252', '9.1 Egresos mensuales'), 0, 2, "L");
    $pdf->Cell(17, 5, iconv('UTF-8', 'windows-1252', 'Alimentos'), 0, 0, "L");
    $pdf->Cell(33, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['egresoalimentos']), "B", 1, "R");
    $pdf->SetX($pdf->GetX() + 160);
    $pdf->Cell(17, 5, iconv('UTF-8', 'windows-1252', 'Vivienda'), 0, 0, "L");
    $pdf->Cell(33, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['egresovivienda']), "B", 1, "R");
    $pdf->SetX($pdf->GetX() + 160);
    $pdf->Cell(17, 5, iconv('UTF-8', 'windows-1252', 'Servicios'), 0, 0, "L");
    $pdf->Cell(33, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['egresoservicios']), "B", 1, "R");
    $pdf->SetX($pdf->GetX() + 160);
    $pdf->Cell(17, 5, iconv('UTF-8', 'windows-1252', 'Transporte'), 0, 0, "L");
    $pdf->Cell(33, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['egresotransporte']), "B", 1, "R");
    $pdf->SetX($pdf->GetX() + 160);
    $pdf->Cell(17, 5, iconv('UTF-8', 'windows-1252', 'Educación'), 0, 0, "L");
    $pdf->Cell(33, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['egresoeducacion']), "B", 1, "R");
    $pdf->SetX($pdf->GetX() + 160);
    $pdf->Cell(17, 5, iconv('UTF-8', 'windows-1252', 'Salud'), 0, 0, "L");
    $pdf->Cell(33, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['egresosalud']), "B", 1, "R");
    $pdf->SetX($pdf->GetX() + 160);
    $pdf->Cell(17, 5, iconv('UTF-8', 'windows-1252', 'Vestido'), 0, 0, "L");
    $pdf->Cell(33, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['egresovestido']), "B", 1, "R");
    $pdf->SetX($pdf->GetX() + 160);
    $pdf->Cell(17, 5, iconv('UTF-8', 'windows-1252', 'Recreación'), 0, 0, "L");
    $pdf->Cell(33, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['egresorecreacion']), "B", 1, "R");
    $pdf->SetX($pdf->GetX() + 160);
    $pdf->Cell(17, 5, iconv('UTF-8', 'windows-1252', 'Deudas'), 0, 0, "L");
    $pdf->Cell(33, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['egresodeudas']), "B", 1, "R");
    $pdf->SetX($pdf->GetX() + 160);
    $pdf->Cell(17, 5, iconv('UTF-8', 'windows-1252', 'Otros'), 0, 0, "L");
    $pdf->Cell(33, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['egresootros']), "B", 1, "R");
    $pdf->Ln(5);
    $pdf->SetX($pdf->GetX() + 160);
    $pdf->Cell(17, 5, iconv('UTF-8', 'windows-1252', '9.2 Total'), 0, 0, "L");
    $pdf->Cell(33, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['egresototal']), "B", 1, "R");
    $pdf->SetX($pdf->GetX() + 160);
    $pdf->Cell(50, 5, iconv('UTF-8', 'windows-1252', '9.3 Observaciones'), 0, 2, "L");
    $pdf->SetXY($prevX + 210, $prevY);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - $pdf->GetX() - 10, 80);
    $pdf->SetFont("Arial", "", 7);
    $pdf->Cell(27, 5, iconv('UTF-8', 'windows-1252', '10.1 Ingreso familiar'), 0, 0, "L");
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', '$' . ($rowestudio['ingresototal'] - $rowestudio['ingresootros'])), "B", 1, "R");
    $pdf->SetX($pdf->GetX() + 210);
    $pdf->Cell(27, 5, iconv('UTF-8', 'windows-1252', '10.2 Otros ingresos'), 0, 0, "L");
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['ingresootros']), "B", 1, "R");
    $pdf->SetX($pdf->GetX() + 210);
    $pdf->Cell(27, 5, iconv('UTF-8', 'windows-1252', '10.3 Total de ingresos'), 0, 0, "L");
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['ingresototal']), "B", 1, "R");
    $pdf->SetX($pdf->GetX() + 210);
    $pdf->Cell(27, 5, iconv('UTF-8', 'windows-1252', '10.4 Total de egresos'), 0, 0, "L");
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['egresototal']), "B", 1, "R");
    $pdf->SetX($pdf->GetX() + 210);
    $pdf->Cell(27, 5, iconv('UTF-8', 'windows-1252', '10.5 Diferencia'), 0, 0, "L");
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', '$' . $rowestudio['diferenciatotal']), "B", 1, "R");
    $pdf->SetX($pdf->GetX() + 210);
    $pdf->Cell(50, 5, iconv('UTF-8', 'windows-1252', '10.6 Observaciones'), 0, 2, "L");
    $pdf->MultiCell(50, 5, iconv('UTF-8', 'windows-1252', $rowestudio['obsbalance']), 0, "C");
    $pdf->SetXY($prevX, $prevY + 80);
    $pdf->Cell(100, 5, iconv('UTF-8', 'windows-1252', '11. Alimentación'), 1, 0, "C", TRUE);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', '12. Apoyos y servicios otorgados'), 1, 1, "C", TRUE);
    $pdf->SetFillColor(220);
    $prevX = $pdf->GetX();
    $prevY = $pdf->GetY();
    $pdf->MultiCell(25, 5, iconv('UTF-8', 'windows-1252', '11.1 Frecuencia alimentaria'), 1, "L", TRUE);
    $pdf->SetXY($prevX + 25, $prevY);
    $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'Diario'), 1, 0, "C", TRUE);
    $pdf->MultiCell(15, 5, iconv('UTF-8', 'windows-1252', 'Cada 3er día'), 1, "C", TRUE);
    $pdf->SetXY($prevX + 55, $prevY);
    $pdf->MultiCell(15, 5, iconv('UTF-8', 'windows-1252', 'Cada 8 días'), 1, "C", TRUE);
    $pdf->SetXY($prevX + 70, $prevY);
    $pdf->MultiCell(15, 5, iconv('UTF-8', 'windows-1252', 'Cada 15 días'), 1, "C", TRUE);
    $pdf->SetXY($prevX + 85, $prevY);
    $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'Nunca'), 1, 0, "C", TRUE);
    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(20, 10, iconv('UTF-8', 'windows-1252', 'FECHA'), 1, 0, "C", TRUE);
    $pdf->Cell(40, 10, iconv('UTF-8', 'windows-1252', 'INSTITUCIÓN'), 1, 0, "C", TRUE);
    $pdf->Cell(50, 10, iconv('UTF-8', 'windows-1252', 'APOYO Y/O SERVICIO'), 1, 0, "C", TRUE);
    $pdf->Cell(30, 10, iconv('UTF-8', 'windows-1252', 'PERIODO'), 1, 0, "C", TRUE);
    $pdf->Cell(0, 10, iconv('UTF-8', 'windows-1252', 'MONTO'), 1, 1, "C", TRUE);
    $prevX = $pdf->GetX();
    $prevY = $pdf->GetY();
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(25, 10, iconv('UTF-8', 'windows-1252', 'Frutas y verduras'), 1, 0, "L");
    if ($rowestudio['frutas'] == 1) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    }
    if ($rowestudio['frutas'] == 2) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    }
    if ($rowestudio['frutas'] == 3) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    }
    if ($rowestudio['frutas'] == 4) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    }
    if ($rowestudio['frutas'] == 5) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    }
    $prevX = $pdf->GetX();
    $prevY = $pdf->GetY();
    $pdf->Ln(10);
    $tempX = $pdf->GetX();
    $tempY = $pdf->GetY();
    $pdf->MultiCell(25, 5, iconv('UTF-8', 'windows-1252', 'Cereales y tubérculos'), 1, "L");
    $pdf->SetXY($tempX + 25, $tempY);
    if ($rowestudio['cereales'] == 1) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    }
    if ($rowestudio['cereales'] == 2) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    }
    if ($rowestudio['cereales'] == 3) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    }
    if ($rowestudio['cereales'] == 4) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    }
    if ($rowestudio['cereales'] == 5) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 1, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 1, "C");
    }
    $pdf->Cell(25, 10, iconv('UTF-8', 'windows-1252', 'Leguminosas'), 1, 0, "L");
    if ($rowestudio['leguminosas'] == 1) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    }
    if ($rowestudio['leguminosas'] == 2) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    }
    if ($rowestudio['leguminosas'] == 3) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    }
    if ($rowestudio['leguminosas'] == 4) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    }
    if ($rowestudio['leguminosas'] == 5) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 1, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 1, "C");
    }
    $tempX = $pdf->GetX();
    $tempY = $pdf->GetY();
    $pdf->MultiCell(25, 5, iconv('UTF-8', 'windows-1252', 'Alimentos de origen animal'), 1, "L");
    $pdf->SetXY($tempX + 25, $tempY);
    if ($rowestudio['animal'] == 1) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    }
    if ($rowestudio['animal'] == 2) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    }
    if ($rowestudio['animal'] == 3) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    }
    if ($rowestudio['animal'] == 4) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    }
    if ($rowestudio['animal'] == 5) {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', 'X'), 1, 1, "C");
    } else {
        $pdf->Cell(15, 10, iconv('UTF-8', 'windows-1252', ''), 1, 1, "C");
    }
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 100, 20);
    $pdf->Cell(100, 5, iconv('UTF-8', 'windows-1252', '11.2 Observaciones'), 0, 1, "L");
    $pdf->MultiCell(100, 5, iconv('UTF-8', 'windows-1252', $rowestudio['obsalim']), 0, "C");
    
    $apsize = sizeof($apoyos);
    for ($i = 0; $i < 6; $i++) { 
        if ($i < $apsize) {
            $pdf->SetXY($prevX, $prevY + $i * 10);
            $pdf->Cell(20, 10, iconv('UTF-8', 'windows-1252', $apoyos[$i]['apoyofecha']), 1, 0, "C");
            $tempX = $pdf->GetX();
            $tempY = $pdf->GetY();
            $pdf->Rect($pdf->GetX(), $pdf->GetY(), 40, 10);
            $pdf->MultiCell(40, 5, iconv('UTF-8', 'windows-1252', ucfirst(strtolower($apoyos[$i]['apoyoinstitucion']))), 0, "C");
            $pdf->SetXY($tempX + 40, $tempY);
            $pdf->Rect($pdf->GetX(), $pdf->GetY(), 50, 10);
            $pdf->MultiCell(50, 5, iconv('UTF-8', 'windows-1252', $apoyos[$i]['apoyopasado']), 0, "C");
            $pdf->SetXY($tempX + 90, $tempY);
            $pdf->Rect($pdf->GetX(), $pdf->GetY(), 30, 10);
            $pdf->MultiCell(30, 5, iconv('UTF-8', 'windows-1252', $apoyos[$i]['apoyoperiodo']), 0, "C");
            $pdf->SetXY($tempX + 120, $tempY);
            $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - $pdf->GetX() - 10, 10);
            $pdf->MultiCell(0, 5, iconv('UTF-8', 'windows-1252', $apoyos[$i]['apoyomonto']), 0, "C");
        } else {
            $pdf->SetXY($prevX, $prevY + $i * 10);
            $pdf->Cell(20, 10, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
            $tempX = $pdf->GetX();
            $tempY = $pdf->GetY();
            $pdf->Rect($pdf->GetX(), $pdf->GetY(), 40, 10);
            $pdf->MultiCell(40, 5, iconv('UTF-8', 'windows-1252', ''), 0, "C");
            $pdf->SetXY($tempX + 40, $tempY);
            $pdf->Rect($pdf->GetX(), $pdf->GetY(), 50, 10);
            $pdf->MultiCell(50, 5, iconv('UTF-8', 'windows-1252', ''), 0, "C");
            $pdf->SetXY($tempX + 90, $tempY);
            $pdf->Rect($pdf->GetX(), $pdf->GetY(), 30, 10);
            $pdf->MultiCell(30, 5, iconv('UTF-8', 'windows-1252', ''), 0, "C");
            $pdf->SetXY($tempX + 120, $tempY);
            $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - $pdf->GetX() - 10, 10);
            $pdf->MultiCell(0, 5, iconv('UTF-8', 'windows-1252', ''), 0, "C");
        } 
    }
    $pdf->Ln(10);
    $pdf->SetX(100);
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(17, 5, iconv('UTF-8', 'windows-1252', 'Usuario:'), 0, 0, "L");
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', ucwords(strtolower($rowpersona['apaterno'] . " " . $rowpersona['amaterno'] . " " . $rowpersona['nombre']))), "B", 1, "C");
    $pdf->SetX(117);
    $pdf->SetFont("Arial", "", 7);
    $pdf->MultiCell(0, 5, iconv('UTF-8', 'windows-1252', 'Manifiesto bajo protesta decir la verdad que autorizo el uso y manejo de mis datos personales, asi como los considerados como datos personales sensibles, en los terminos  del aviso de privacidad, en donde señala que serán resguardados conforme a la Ley en la materia, el cual se puede consultar en http://sistemadif.jalisco.gob.mx'), 0, "L");
    $pdf->Ln(5);
    $pdf->Cell(236, 8, iconv('UTF-8', 'windows-1252', 'Fecha de actualización: 06 de Junio de 2019 V. 11 Código: DJ-TS-SG-RE-01'), 0, 0, "L");
    
    /* --- Tercera hoja --- */
    
    $pdf->AddPage();
    $pdf->SetFont("Arial", "B", 10);
    $pdf->SetFillColor(180);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', '13. Salud'), 1, 1, "C", TRUE);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', '13.1 Institución'), 1, 1, "L");
    $pdf->Cell(27, 5, iconv('UTF-8', 'windows-1252', 'IMSS'), 1, 0, "L");
    if ($rowpersona['servicios_medicos'] == 'IMSS') {
        $pdf->Cell(6, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(6, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
    }
    $pdf->Cell(26, 5, iconv('UTF-8', 'windows-1252', 'ISSSTE'), 1, 0, "L");
    if ($rowpersona['servicios_medicos'] == 'ISSSTE') {
        $pdf->Cell(6, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(6, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
    }
    $pdf->Cell(26, 5, iconv('UTF-8', 'windows-1252', 'SSJ'), 1, 0, "L");
    if ($rowpersona['servicios_medicos'] == 'HOSPITALES CIVILES' || $rowpersona['servicios_medicos'] == 'CENTRO DE SALUD') {
        $pdf->Cell(6, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(6, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
    }
    $pdf->Cell(26, 5, iconv('UTF-8', 'windows-1252', 'DIF'), 1, 0, "L");
    $pdf->Cell(6, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
    $pdf->Cell(27, 5, iconv('UTF-8', 'windows-1252', 'Cruz Roja'), 1, 0, "L");
    $pdf->Cell(6, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
    $pdf->Cell(27, 5, iconv('UTF-8', 'windows-1252', 'Seguro popular'), 1, 0, "L");
    if ($rowpersona['servicios_medicos'] == 'SEGURO POPULAR') {
        $pdf->Cell(6, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(6, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
    }
    $pdf->Cell(26, 5, iconv('UTF-8', 'windows-1252', 'Servicio particular'), 1, 0, "L");
    if ($rowpersona['servicios_medicos'] == 'PARTICULAR') {
        $pdf->Cell(6, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(6, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
    }
    $pdf->Cell(26, 5, iconv('UTF-8', 'windows-1252', 'Servicio municipal'), 1, 0, "L");
    if ($rowpersona['servicios_medicos'] == 'SERVICIOS MEDICOS MUNICIPALES') {
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 1, "C");
    } else {
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', ''), 1, 1, "L");
    }
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - 20, 10);
    $pdf->Cell(27, 5, iconv('UTF-8', 'windows-1252', 'Medicina alternativa'), 1, 0, "L");
    if ($rowpersona['servicios_medicos'] == 'HOMEÓPATICA' || $rowpersona['servicios_medicos'] == 'CURANDERO' || $rowpersona['servicios_medicos'] == 'HERBOLARIA' || $rowpersona['servicios_medicos'] == 'MEDICINA ALTERNATIVA') {
        $pdf->Cell(6, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(6, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
    }
    $pdf->Cell(26, 5, iconv('UTF-8', 'windows-1252', 'Otro'), 1, 0, "L");
    if ($rowpersona['servicios_medicos'] == 'SEDENA' || $rowpersona['servicios_medicos'] == 'FARMACIAS SIMILARES' || $rowpersona['servicios_medicos'] == 'HOSPITAL GENERAL DE OCCIDENTE' || $rowpersona['servicios_medicos'] == 'AUTOMEDICACIÓN' || $rowpersona['servicios_medicos'] == 'OTRO' || $rowpersona['servicios_medicos'] == 'NINGUNO') {
        $pdf->Cell(6, 5, iconv('UTF-8', 'windows-1252', 'X'), 1, 0, "C");
    } else {
        $pdf->Cell(6, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "L");
    }
    $pdf->Cell(26, 10, iconv('UTF-8', 'windows-1252', '13.2 Observaciones'), 1, 0, "C");
    $prevY = $pdf->GetY();
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - $pdf->GetX() - 10, 10);
    $pdf->MultiCell(0, 5, iconv('UTF-8', 'windows-1252', $rowestudio['obssalud']), 0, "L");
    $pdf->Ln(0);
    $pdf->SetY($prevY + 10);
    $prevX = $pdf->GetX();
    $prevY = $pdf->GetY();
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 65, 15);
    $pdf->Cell(65, 2.5, iconv('UTF-8', 'windows-1252', ''), 0, 2, "C");
    $pdf->MultiCell(65, 5, iconv('UTF-8', 'windows-1252', '13.3 Enfermedades crónicas o discapacidades de la familia'), 0, "L");
    $pdf->Cell(65, 2.5, iconv('UTF-8', 'windows-1252', ''), 0, 2, "C");
    $pdf->SetXY($prevX + 65, $prevY);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - $pdf->GetX() - 10, 15);
    $pdf->MultiCell(0, 5, iconv('UTF-8', 'windows-1252', $rowestudio['enfermedades']), 0, "L");
    $pdf->Ln(0);
    $pdf->SetY($prevY + 15);
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', '14. Diagnóstico sociofamiliar'), 1, 1, "C", TRUE);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - 20, 90);
    $prevX = $pdf->GetX();
    $prevY = $pdf->GetY();
    $pdf->MultiCell(0, 5, iconv('UTF-8', 'windows-1252', $diagsplit[0]), 0, "L");
    $pdf->SetY($prevY + 90);
    $pdf->SetFont("Arial", "B", 10);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', '14.1 Conclusión'), 1, 1, "C", TRUE);
    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(130, 5, iconv('UTF-8', 'windows-1252', 'Problemática y/o vulnerabilidad'), 1, 0, "C", TRUE);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', 'Detonante del problema'), 1, 1, "C", TRUE);
    $pdf->SetFillColor(220);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(8, 5, iconv('UTF-8', 'windows-1252', 'No.'), 1, 0, "C", TRUE);
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', 'Código'), 1, 0, "C", TRUE);
    $pdf->Cell(107, 5, iconv('UTF-8', 'windows-1252', 'Descripción'), 1, 0, "C", TRUE);
    $pdf->Cell(8, 5, iconv('UTF-8', 'windows-1252', 'No.'), 1, 0, "C", TRUE);
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', 'Código'), 1, 0, "C", TRUE);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', 'Descripción'), 1, 1, "C", TRUE);
    $pdf->Cell(8, 5, iconv('UTF-8', 'windows-1252', '1'), 1, 0, "C");
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', $prv[0]), 1, 0, "C");
    $pdf->Cell(107, 5, iconv('UTF-8', 'windows-1252', ucfirst(strtolower($prv[1]))), 1, 0, "L");
    $pdf->Cell(8, 5, iconv('UTF-8', 'windows-1252', '1'), 1, 0, "C");
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', ucfirst(strtolower($rowestudio['detonante']))), 1, 1, "L");
    $pdf->Cell(8, 5, iconv('UTF-8', 'windows-1252', '2'), 1, 0, "C");
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', $prv2[0]), 1, 0, "C");
    $pdf->Cell(107, 5, iconv('UTF-8', 'windows-1252', ucfirst(strtolower($prv2[1]))), 1, 0, "L");
    $pdf->Cell(8, 5, iconv('UTF-8', 'windows-1252', '2'), 1, 0, "C");
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', ucfirst(strtolower($rowestudio['detonante2']))), 1, 1, "L");
    $pdf->Cell(8, 5, iconv('UTF-8', 'windows-1252', '3'), 1, 0, "C");
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', $prv3[0]), 1, 0, "C");
    $pdf->Cell(107, 5, iconv('UTF-8', 'windows-1252', ucfirst(strtolower($prv3[1]))), 1, 0, "L");
    $pdf->Cell(8, 5, iconv('UTF-8', 'windows-1252', '3'), 1, 0, "C");
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', ucfirst(strtolower($rowestudio['detonante3']))), 1, 1, "L");
    $pdf->SetFillColor(180);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', 'Diagnóstico'), 1, 1, "C", TRUE);
    $pdf->SetFillColor(220);
    $pdf->Cell(8, 5, iconv('UTF-8', 'windows-1252', 'No.'), 1, 0, "C", TRUE);
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', 'Código'), 1, 0, "C", TRUE);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', 'Descripción'), 1, 1, "C", TRUE);
    $pdf->Cell(8, 5, iconv('UTF-8', 'windows-1252', '1'), 1, 0, "C");
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', $diag[0]), 1, 0, "C");
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', ucfirst(strtolower($diag[1]))), 1, 1, "L");
    $pdf->Cell(8, 5, iconv('UTF-8', 'windows-1252', '2'), 1, 0, "C");
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', $diag2[0]), 1, 0, "C");
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', ucfirst(strtolower($diag2[1]))), 1, 1, "L");
    $pdf->Cell(8, 5, iconv('UTF-8', 'windows-1252', '3'), 1, 0, "C");
    $pdf->Cell(15, 5, iconv('UTF-8', 'windows-1252', $diag3[0]), 1, 0, "C");
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', ucfirst(strtolower($diag3[1]))), 1, 1, "L");
    $pdf->SetFont("Arial", "", 7);
    $pdf->Cell(236, 8, iconv('UTF-8', 'windows-1252', 'Fecha de actualización: 06 de Junio de 2019 V. 11 Código: DJ-TS-SG-RE-01'), 0, 0, "L");
    
    /* --- Cuarta hoja --- */
    
    $pdf->AddPage();
    $pdf->SetFont("Arial", "B", 10);
    $pdf->SetFillColor(180);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', '14. Diagnóstico (continuación)'), 1, 1, "C", TRUE);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - 20, $alto - 30);
    $pdf->MultiCell(0, 5, iconv('UTF-8', 'windows-1252', $diagsplit[1]), 0, "L");
    $pdf->SetY($alto - 15);
    $pdf->SetFont("Arial", "", 7);
    $pdf->Cell(236, 8, iconv('UTF-8', 'windows-1252', 'Fecha de actualización: 06 de Junio de 2019 V. 11 Código: DJ-TS-SG-RE-01'), 0, 0, "L");
    
    /* --- Quinta hoja --- */
    
    $pdf->AddPage();
    $pdf->SetFont("Arial", "B", 10);
    $pdf->SetFillColor(180);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', '15. Plan de intervención'), 1, 1, "C", TRUE);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - 20, 100);
    $prevY = $pdf->GetY();
    $pdf->MultiCell(0, 5, iconv('UTF-8', 'windows-1252', $rowestudio['planinter']), 0, "L");
    $pdf->SetY($prevY + 100);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', '16. Evaluación'), 1, 1, "C", TRUE);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), $ancho - 20, $alto - $pdf->GetY() - 15);
    $prevY = $pdf->GetY();
    $pdf->MultiCell(0, 5, iconv('UTF-8', 'windows-1252', $rowestudio['eval']), 0, "L");
    $pdf->SetY($alto - 15);
    $pdf->SetFont("Arial", "", 7);
    $pdf->Cell(236, 8, iconv('UTF-8', 'windows-1252', 'Fecha de actualización: 06 de Junio de 2019 V. 11 Código: DJ-TS-SG-RE-01'), 0, 0, "L");
    
    /* --- Sexta hoja --- */
    
    $pdf->AddPage();
    $pdf->SetFont("Arial", "B", 10);
    $pdf->SetFillColor(180);
    $pdf->Cell(0, 15, iconv('UTF-8', 'windows-1252', '17. Notas de seguimiento y/o evolución'), 1, 1, "C", TRUE);
    $pdf->Image('../../../../../Tools/imagenes/LogoDIF.png', 11, 11, 13);
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(40, 5, iconv('UTF-8', 'windows-1252', '17.2 Nombre del beneficiario'), 1, 0, "L", TRUE);
    $pdf->Cell(130, 5, iconv('UTF-8', 'windows-1252', ucwords(strtolower($rowpersona['apaterno'] . " " . $rowpersona['amaterno'] . " " . $rowpersona['nombre']))), 1, 0, "L");
    $pdf->Cell(40, 5, iconv('UTF-8', 'windows-1252', '17.3 Numero de expediente'), 1, 0, "L", TRUE);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', $rowestudio['registro']), 1, 1, "L");
    $pdf->SetFillColor(220);
    $pdf->Cell(40, 5, iconv('UTF-8', 'windows-1252', '17.1 Fecha'), 1, 0, "C", TRUE);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', '17.4 Descripción'), 1, 1, "C", TRUE);
    
    $i = 0;
    foreach ($avances as $avance) {
        $pdf->Cell(40, 5, iconv('UTF-8', 'windows-1252', invierte_fecha($avance['fechaavance'])), 1, 0, "C");
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', $avance['avance']), 1, 1, "L");
        $i++;
    }
    for ($i; $i < 24; $i++) {
        $pdf->Cell(40, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
        $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', ''), 1, 1, "L");
    }
    $prevX = $pdf->GetX();
    $prevY = $pdf->GetY();
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 130, 25);
    $pdf->Rect($pdf->GetX() + 130, $pdf->GetY(), $ancho - 150, 25);
    $pdf->SetY($prevY + 15);
    $pdf->Cell(130, 5, iconv('UTF-8', 'windows-1252', ucwords(strtolower($_SESSION['padron_admin_nombre']))), 0, 2, "C");
    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(130, 5, iconv('UTF-8', 'windows-1252', 'Nombre y firma del(la) trabajador(a) social'), 0, 0, "C");
    $pdf->SetFont("Arial", "", 8);
    $pdf->SetXY($prevX + 130, $prevY + 15);
    $pdf->Cell(130, 5, iconv('UTF-8', 'windows-1252', 'LTS. Yadira Noemí Pérez Villa'), 0, 2, "C");
    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(130, 5, iconv('UTF-8', 'windows-1252', 'Nombre y firma del(la) coordinador(a)'), 0, 1, "C");
    $pdf->SetFillColor(180);
    $pdf->Cell(0, 5, iconv('UTF-8', 'windows-1252', 'Situación del caso'), 1, 1, "C", TRUE);
    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 130, 15);
    $pdf->Rect($pdf->GetX() + 130, $pdf->GetY(), $ancho - 150, 15);
    $prevX = $pdf->GetX();
    $prevY = $pdf->GetY();
    $pdf->SetFont("Arial", "", 8);
    $pdf->Cell(35, 15, iconv('UTF-8', 'windows-1252', ''), 0, 0, "C");
    $pdf->Cell(60, 2.5, iconv('UTF-8', 'windows-1252', ''), 0, 2, "C");
    $pdf->Cell(20, 10, iconv('UTF-8', 'windows-1252', 'Abierto'), 1, 0, "C");
    $tempX = $pdf->GetX();
    $pdf->Cell(10, 5, iconv('UTF-8', 'windows-1252', 'Día'), 1, 0, "C");
    $pdf->Cell(10, 5, iconv('UTF-8', 'windows-1252', 'Mes'), 1, 0, "C");
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Año'), 1, 2, "C");
    $pdf->SetX($tempX);
    $pdf->Cell(10, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    $pdf->Cell(10, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', ''), 1, 2, "C");
    $pdf->SetXY($prevX + 130, $prevY);
    $pdf->Cell(35, 15, iconv('UTF-8', 'windows-1252', ''), 0, 0, "C");
    $pdf->Cell(60, 2.5, iconv('UTF-8', 'windows-1252', ''), 0, 2, "C");
    $pdf->Cell(20, 10, iconv('UTF-8', 'windows-1252', 'Cerrado'), 1, 0, "C");
    $tempX = $pdf->GetX();
    $pdf->Cell(10, 5, iconv('UTF-8', 'windows-1252', 'Día'), 1, 0, "C");
    $pdf->Cell(10, 5, iconv('UTF-8', 'windows-1252', 'Mes'), 1, 0, "C");
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', 'Año'), 1, 2, "C");
    $pdf->SetX($tempX);
    $pdf->Cell(10, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    $pdf->Cell(10, 5, iconv('UTF-8', 'windows-1252', ''), 1, 0, "C");
    $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', ''), 1, 2, "C");
    $pdf->Ln(0);
    $pdf->SetY($alto - 15);
    $pdf->SetFont("Arial", "", 7);
    $pdf->Cell(236, 8, iconv('UTF-8', 'windows-1252', 'Fecha de actualización: 06 de Junio de 2019 V. 11 Código: DJ-TS-SG-RE-01'), 0, 0, "L");
    
    $pdf->Output();
?>