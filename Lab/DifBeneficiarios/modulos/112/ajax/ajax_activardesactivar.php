<?php
    require_once("../../../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    if ($_POST['activo']) {
        $query = "UPDATE $persona_despensas_2021 SET activo = '0' WHERE idpersona = '" . $_POST['idpersona'] . "'";
        //$query = "UPDATE $persona_despensas_2020 SET activo = '0' WHERE idpersona = '" . $_POST['idpersona'] . "'"; //
        if (mysql_query($query, $con)) {
            Echo "Exito,desactivado";
        } else {
            Echo "Fallo,Error en el query: " . $query . " Error: " . mysql_error();
        }
    } else {
        $query = "UPDATE $persona_despensas_2021 SET activo = '1' WHERE idpersona = '" . $_POST['idpersona'] . "'";
        //$query = "UPDATE $persona_despensas_2020 SET activo = '1' WHERE idpersona = '" . $_POST['idpersona'] . "'";
        if (mysql_query($query, $con)) {
            Echo "Exito,activado";
        } else {
            Echo "Fallo,Error en el query: " . $query . " Error: " . mysql_error();
        }
    }
?>