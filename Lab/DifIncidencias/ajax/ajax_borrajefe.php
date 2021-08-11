<?php
    include("../../funciones.php");
    $con=conectar();
    iniciar_sesion(3);
    $jsondata = array();
    
    if (!isset($_POST['idusuario']) || !isset($_POST['idjefe'])) {
        echo "No enviaste los datos necesarios";
        exit();
    }
    
    $query = "SELECT * FROM $incidencia_jefes WHERE idjefe = '" . mysql_real_escape_string($_POST['idjefe']) . "'";
    
    if ($result = mysql_query($query, $con)) {
        $row = mysql_fetch_array($result);
        $validos = explode(',', $row['validos']);
        $validos = implode(",", array_values(array_diff($validos, [$_POST['idusuario']])));
        
        $query = "UPDATE $incidencia_jefes SET validos='$validos' WHERE id='" . $row['id'] . "'";
        
        if ($result = mysql_query($query, $con)) {
            echo "succes";
            exit();
        } else {
            echo "Error en el query: " . $query . ", Error: " . mysql_error();
            exit();
        }
    } else {
        echo "Error en el query: " . $query . ", Error: " . mysql_error();
        exit();
    }
?>