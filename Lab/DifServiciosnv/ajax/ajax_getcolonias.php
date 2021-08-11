<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    $jsondata = array();
    
    $query = "SELECT * FROM $colonias WHERE municipio = 'Zapopan' ORDER BY colonia";
    
    if ($result = mysql_query($query, $con)) {
        $output = "";
        $jsondata['mensaje'] = "Se encontraron " . mysql_num_rows($result) . " registros.";
        while ($row = mysql_fetch_array($result)) {
            $output .= "<option value='" . $row['id'] . "'>" . $row['colonia'] . "</option>";
        }
        $jsondata['html'] = $output;
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    }
?>