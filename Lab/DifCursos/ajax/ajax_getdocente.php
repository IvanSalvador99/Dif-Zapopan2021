<?php
    include("../../funciones.php");
    $con=conectar();
    iniciar_sesion(0);
    $output = array();
    
    if (!isset($_POST['docente'])) {
        echo "No enviaste nada";
        exit();
    }
    
    $query = "SELECT * FROM $trabajadores WHERE id = '" . $_POST['docente'] . "'";
    
    if ($result = mysql_query($query, $con)) {
        if (mysql_num_rows($result) > 0) {
            $output['exito'] = TRUE;
            $output['persona'] = mysql_fetch_array($result);
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($output, JSON_FORCE_OBJECT);
            exit();
        } else {
            $output['exito'] = FALSE;
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($output, JSON_FORCE_OBJECT);
            exit();
        }
        
    } else {
        echo "Error en el query: " . $query . ", Error: " . mysql_error();
    }
?>
