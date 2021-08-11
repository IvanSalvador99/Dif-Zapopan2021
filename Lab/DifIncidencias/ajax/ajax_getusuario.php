<?php
    include("../../funciones.php");
    $con=conectar();
    iniciar_sesion(3);
    $jsondata = array();
    
    if (!isset($_POST['idusuario'])) {
        echo "No enviaste nada";
        exit();
    }
    
    $query = "SELECT * FROM $trabajadores WHERE id = '" . mysql_real_escape_string($_POST['idusuario']) . "'";
    
    if ($result = mysql_query($query, $con)) {
        $row = mysql_fetch_array($result);
        $jsondata['horasindicato'] = $row['horasindicato'];
        $jsondata['diaseconomicos'] = $row['diaseconomicos'];
        $jsondata['tipo'] = $row['tipo'];
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    } else {
        echo "Error en el query: " . $query . ", Error: " . mysql_error();
    }
?>