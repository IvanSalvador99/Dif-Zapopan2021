<?php
    require_once("../../funciones.php");
    require_once("../../../Tools/fpdf/code128.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    //var_dump($_POST);
    $query = "INSERT INTO $personas VALUES (null, 'random',
                  '".mysql_real_escape_string(date("Y-m-d H:i:s"))."',
                  '".mysql_real_escape_string($_SESSION['padron_admin_area'])."',
                  '".mysql_real_escape_string($_POST["perfilatencion"])."',
                  '".mysql_real_escape_string($_POST["problematicadpna"])."',
                  '".mysql_real_escape_string(($_POST['curp'] == "") ? null : $_POST['curp'])."',
                  '".mysql_real_escape_string($_POST["nombre"])."',
                  '".mysql_real_escape_string($_POST["apellidop"])."',
                  '".mysql_real_escape_string($_POST["apellidom"])."',
                  '".mysql_real_escape_string($_POST["fechanacimiento"])."',
                  '".mysql_real_escape_string($_POST["sexo"])."',
                  '".mysql_real_escape_string($_POST["estadocivil"])."',
                  '".mysql_real_escape_string($_POST["ocupacion"])."',
                  '".mysql_real_escape_string($_POST["nivelescolar"])."',
                  '".mysql_real_escape_string($_POST["lenguamaterna"])."',
                  '".mysql_real_escape_string(($_POST['lenguasecundaria'] == -1) ? null : $_POST['lenguasecundaria'])."',
                  '".mysql_real_escape_string($_POST["serviciosmedicos"])."',
                  '".mysql_real_escape_string(($_POST['enfermedadprevia'] == -1) ? null : $_POST['enfermedadprevia'])."',
                  '".mysql_real_escape_string($_POST["calle"])."',
                  '".mysql_real_escape_string(($_POST['numext'] == "") ? null : $_POST['numext'])."',
                  '".mysql_real_escape_string(($_POST['numint'] == "") ? null : $_POST['numint'])."',
                  '".mysql_real_escape_string(($_POST['primercruce'] == "") ? null : $_POST['primercruce'])."',
                  '".mysql_real_escape_string(($_POST['segundocruce'] == "") ? null : $_POST['segundocruce'])."',
                  '".mysql_real_escape_string($_POST["cp"])."',
                  '".mysql_real_escape_string($_POST["colonia"])."',
                  '".mysql_real_escape_string($_POST["municipio"])."',
                  '".mysql_real_escape_string($_POST["estado"])."', 
                  '".mysql_real_escape_string(($_POST['telefono'] == "") ? null : $_POST['telefono'])."',
                  '".mysql_real_escape_string(($_POST['celular'] == "") ? null : $_POST['celular'])."',
                  '".mysql_real_escape_string((isset($_POST['escontacto']) && $_POST['escontacto'] == "on")? 1 : 0)."',
                  '".mysql_real_escape_string(($_POST['email'] == "") ? null : $_POST['email'])."',
                  '".mysql_real_escape_string(null)."',
                  '".mysql_real_escape_string(($_POST['curptutor'] == "") ? null : $_POST['curptutor'])."',
                  '".mysql_real_escape_string(($_POST['parentesco'] == -1) ? null : $_POST['parentesco'])."',
                  '".mysql_real_escape_string(($_POST['nombretutor'] == "") ? null : $_POST['nombretutor'])."',
                  '".mysql_real_escape_string(($_POST['apellidoptutor'] == "") ? null : $_POST['apellidoptutor'])."',
                  '".mysql_real_escape_string(($_POST['apellidomtutor'] == "") ? null : $_POST['apellidomtutor'])."',
                  '".mysql_real_escape_string(($_POST['sexotutor'] == -1) ? null : $_POST['sexotutor'])."',
                  '".mysql_real_escape_string(($_POST['tutorfechanacimiento'] == "") ? null : $_POST['tutorfechanacimiento'])."',
                  '".mysql_real_escape_string($_POST["vivienda"])."',
                  '".mysql_real_escape_string($_SESSION['padron_admin_id'])."',
                  '".mysql_real_escape_string("1")."')";
                                              
    //echo "<br/><br/>" . $query;
    
    if (mysql_query($query, $con)) {
        $lastid = mysql_insert_id();
        $iddifzapopan = "DIFZAP" . date("Y") . str_pad($lastid, 6, "0", STR_PAD_LEFT);
        $query = "UPDATE $personas SET iddifzapopan = '$iddifzapopan' WHERE id = '$lastid'";
        if (mysql_query($query, $con)) {
            //echo "</br></br>Hola Carola";
            //echo "<br/><br/>Entrada creada correctamente con el id: " . $lastid . " iddifzapopan: " . $iddifzapopan;
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
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $_POST["nombre"] . " " . $_POST["apellidop"] . " " . $_POST["apellidom"]), 0, 0, "L");
            $pdf->SetXY(26, 54);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $_POST["apellidop"] . " " . $_POST["apellidom"]), 0, 0, "L");
            $pdf->SetFont("Arial", "B", 9);
            $pdf->SetXY(10, 58);
            $pdf->Cell(16, 5, iconv('UTF-8', 'windows-1252', 'CURP: '), 0, 0, "L");
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $_POST["curp"]), 0, 0, "L");
            $pdf->SetFont("Arial", "B", 9);
            $pdf->SetXY(10, 62);
            $pdf->Cell(16, 5, iconv('UTF-8', 'windows-1252', 'Domicilio: '), 0, 0, "L");
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $_POST["calle"] . " " . $_POST['numext'] . " - " . $_POST['numint']), 0, 0, "L");
            $pdf->SetXY(26, 66);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $_POST['coloniatexto']), 0, 0, "L");
            $pdf->SetFont("Arial", "B", 9);
            $pdf->SetXY(10, 70);
            $pdf->Cell(16, 5, iconv('UTF-8', 'windows-1252', 'Telefono: '), 0, 0, "L");
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $_POST['telefono']), 0, 0, "L");
            $pdf->SetFont("Arial", "B", 9);
            $pdf->SetXY(55, 70);
            $pdf->Cell(14, 5, iconv('UTF-8', 'windows-1252', 'Celular: '), 0, 0, "L");
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $_POST['celular']), 0, 0, "L");
            $pdf->Rect(9, 78, 90, 18);
            $pdf->SetFont("Arial", "B", 9);
            $pdf->SetXY(0, 78);
            $pdf->Cell(108, 5, iconv('UTF-8', 'windows-1252', 'Datos del padre o apoderado legal'), 0, 0, "C");
            $pdf->SetXY(10, 83);
            $pdf->Cell(19, 5, iconv('UTF-8', 'windows-1252', 'Nombre: '), 0, 0, "L");
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $_POST["nombretutor"] . " " . $_POST["apellidoptutor"] . " " . $_POST["apellidomtutor"]), 0, 0, "L");
            $pdf->SetFont("Arial", "B", 9);
            $pdf->SetXY(10, 87);
            $pdf->Cell(19, 5, iconv('UTF-8', 'windows-1252', 'CURP: '), 0, 0, "L");
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $_POST["curptutor"]), 0, 0, "L");
            $pdf->SetXY(10, 91);
            $pdf->SetFont("Arial", "B", 9);
            $pdf->Cell(19, 5, iconv('UTF-8', 'windows-1252', 'Parentezco: '), 0, 0, "L");
            $pdf->SetFont("Arial", "", 9);
            $pdf->Cell(20, 5, iconv('UTF-8', 'windows-1252', $_POST["parentezcotexto"]), 0, 0, "L");
            $pdf->SetXY(10, 119);
            $pdf->Code128(14, 99, $iddifzapopan, 80, 20);
            $pdf->SetFont("Arial", "", 7);
            $pdf->Cell(93, 5, iconv('UTF-8', 'windows-1252', $iddifzapopan), 0, 0, "C");
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
            //header("Location: ../beneficiarios.php?action=1");
        } else {
            echo "Error en el query: " . mysql_error();
        }
    } else {
        echo "Error en el query: " . mysql_error();
    }                                       
?>