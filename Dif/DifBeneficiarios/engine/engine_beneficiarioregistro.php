<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    var_dump($_POST);
    $query = "INSERT INTO $personas VALUES (null, 'random',
                  '".mysql_real_escape_string(date("Y-m-d H:i:s"))."',
                  '".mysql_real_escape_string($_SESSION['padron_admin_area'])."',
                  '".mysql_real_escape_string($_POST["perfilatencion"])."',
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
                                              
    echo "<br/><br/>" . $query;
    
    if (mysql_query($query, $con)) {
        $lastid = mysql_insert_id();
        $iddifzapopan = "DIFZAP" . date("Y") . str_pad($lastid, 6, "0", STR_PAD_LEFT);
        $query = "UPDATE $personas SET iddifzapopan = '$iddifzapopan' WHERE id = '$lastid'";
        if (mysql_query($query, $con)) {
            //echo "<br/><br/>Entrada creada correctamente con el id: " . $lastid . " iddifzapopan: " . $iddifzapopan;
            header("Location: ../beneficiarios.php?action=1");      
        } else {
            echo "Error en el query: " . mysql_error();
        }
    } else {
        echo "Error en el query: " . mysql_error();
    }                                       
?>