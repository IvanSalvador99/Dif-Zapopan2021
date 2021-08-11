<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    var_dump($_POST);
    
    $query = "UPDATE $personas SET 
                `perfilatencion`='" . mysql_real_escape_string($_POST['perfilatencion']) . "',
                `problematicadpna`='" . mysql_real_escape_string($_POST['problematicadpna']) . "',
                `curp`='" . mysql_real_escape_string($_POST['curp']) . "',
                `nombre`='" . mysql_real_escape_string($_POST['nombre']) . "',
                `apaterno`='" . mysql_real_escape_string($_POST['apellidop']) . "',
                `amaterno`='" . mysql_real_escape_string($_POST['apellidom']) . "',
                `fechanacimiento`='" . mysql_real_escape_string($_POST['fechanacimiento']) . "',
                `sexo`='" . mysql_real_escape_string($_POST['sexo']) . "',
                `estadocivil`='" . mysql_real_escape_string($_POST['estadocivil']) . "', 
                `ocupacion`='" . mysql_real_escape_string($_POST['ocupacion']) . "', 
                `escolaridad`='" . mysql_real_escape_string($_POST['nivelescolar']) . "', 
                `lenguamaterna`='" . mysql_real_escape_string($_POST['lenguamaterna']) . "', 
                `lenguasecundaria`='" . mysql_real_escape_string($_POST['lenguasecundaria']) . "', 
                `serviciosmedicos`='" . mysql_real_escape_string($_POST['serviciosmedicos']) . "', 
                `enfermedad`='" . mysql_real_escape_string($_POST['enfermedadprevia']) . "', 
                `calle`='" . mysql_real_escape_string($_POST['calle']) . "', 
                `numext`='" . mysql_real_escape_string($_POST['numext']) . "', 
                `numint`='" . mysql_real_escape_string($_POST['numint']) . "', 
                `primercruce`='" . mysql_real_escape_string($_POST['primercruce']) . "', 
                `segundocruce`='" . mysql_real_escape_string($_POST['segundocruce']) . "', 
                `codigopostal`='" . mysql_real_escape_string($_POST['cp']) . "', 
                `colonia`='" . mysql_real_escape_string($_POST['colonia']) . "', 
                `municipio`='" . mysql_real_escape_string($_POST['municipio']) . "', 
                `estado`='" . mysql_real_escape_string($_POST['estado']) . "', 
                `telefono`='" . mysql_real_escape_string($_POST['telefono']) . "', 
                `celular`='" . mysql_real_escape_string($_POST['celular']) . "', 
                `escontacto`='" . mysql_real_escape_string((isset($_POST['escontacto']) && $_POST['escontacto'] == "on") ? 1 : 0) . "', 
                `email`='" . mysql_real_escape_string($_POST['email']) . "', 
                `tutorcurp`='" . mysql_real_escape_string($_POST['curptutor']) . "', 
                `parentesco`='" . mysql_real_escape_string($_POST['parentesco']) . "', 
                `tutornombre`='" . mysql_real_escape_string($_POST['nombretutor']) . "', 
                `tutorapaterno`='" . mysql_real_escape_string($_POST['apellidoptutor']) . "', 
                `tutoramaterno`='" . mysql_real_escape_string($_POST['apellidomtutor']) . "', 
                `tutorsexo`='" . mysql_real_escape_string($_POST['sexotutor']) . "', 
                `tutorfechanacimiento`='" . mysql_real_escape_string($_POST['tutorfechanacimiento']) . "', 
                `vivienda`='" . mysql_real_escape_string($_POST['vivienda']) . "' WHERE `id`='" . $_POST['idbeneficiario'] . "'";
                
    //echo "<br/><br/>" . $query;
    
    if (mysql_query($query, $con)) {
        echo "<br/><br/> Entrada actualizada correctamente.";
        header("Location: ../beneficiarios.php?action=2");
    } else {
        echo "<br/><br/> Erreor en el query:" . mysql_error();
    }
?>