<?php
    include("../../funciones.php");
    $con=conectar();
    iniciar_sesion(3);
    $jsondata = array();
    
    if (!isset($_POST['idusuario']) || !isset($_POST['idsecre'])) {
        echo "No enviaste los datos necesarios";
        exit();
    }
    
    $query = "SELECT * FROM $incidencia_secretarias WHERE idsecretaria = '" . mysql_real_escape_string($_POST['idsecre']) . "'";
    
    if ($result = mysql_query($query, $con)) {
        $row = mysql_fetch_array($result);
        $validos = explode(',', $row['validos']);
        $validos = implode(",", array_values(array_diff($validos, [$_POST['idusuario']])));
        
        $query = "UPDATE $incidencia_secretarias SET validos='$validos' WHERE id='" . $row['id'] . "'";
        
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