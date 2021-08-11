<?php
    require_once("../../../funciones.php");
    $con=conectar();
    $jsondata = array();
    
    if (isset($_POST['estado'])) {
        $estado = $_POST['estado'];
    } else {
        $jsondata['mensaje'] = "No enviaste nada";
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    }
    
    $query = "SELECT DISTINCT municipio FROM $colonias WHERE estado = '$estado' ORDER BY municipio";
    if ($result = mysql_query($query, $con)){
        if (mysql_num_rows($result) > 0){
            $jsondata["exito"] = true;
            $jsondata['mensaje'] = sprintf("Se encontraron %d municipios.", mysql_num_rows($result));
            while ($row = mysql_fetch_array($result)) {
                $jsondata['municipio'][] = $row['municipio'];
            }
        } else {
            $jsondata['exito'] = false;
            $jsondata['mensaje'] = "No se encontró ningún resultado";
        }
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    } else {
        die(mysql_error());
    }
?>