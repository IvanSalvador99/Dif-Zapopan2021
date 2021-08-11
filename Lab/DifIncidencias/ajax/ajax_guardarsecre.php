<?php
    include("../../funciones.php");
    $con=conectar();
    iniciar_sesion(3);
    $jsondata = array();
    
    if (!isset($_POST['idusuarios']) || !isset($_POST['idsecre'])) {
        echo "No enviaste los datos necesarios";
        exit();
    }
    
    $query = "SELECT * FROM $incidencia_secretarias WHERE idsecretaria = '" . mysql_real_escape_string($_POST['idsecre']) . "'";
    
    if ($result = mysql_query($query, $con)) {
        $row = mysql_fetch_array($result);
        $usuarios = implode(",", $_POST['idusuarios']);
        
        if (mysql_num_rows($result) < 1) {
            $query = "INSERT INTO $incidencia_secretarias VALUES (null, '" . mysql_real_escape_string($_POST['idsecre']) . "', '" . mysql_real_escape_string($usuarios) . "')";
            
            if ($result = mysql_query($query, $con)) {
                echo "succes";
                exit();
            } else {
                echo "Error en el query: " . $query . ", Error: " . mysql_error();
                exit();
            }
        } else {
            $query = "UPDATE $incidencia_secretarias SET validos='" . mysql_real_escape_string($usuarios) . "' WHERE id='" . $row['id'] . "'";
            
            if ($result = mysql_query($query, $con)) {
                echo "succes";
                exit();
            } else {
                echo "Error en el query: " . $query . ", Error: " . mysql_error();
                exit();
            }
        }  
    } else {
        echo "Error en el query: " . $query . ", Error: " . mysql_error();
        exit();
    }
?>