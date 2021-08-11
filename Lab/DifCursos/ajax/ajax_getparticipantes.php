<?php
    include("../../funciones.php");
    $con = conectar();
    $jsondata = array();
    
    if (isset($_POST['idsdif'])) {
        $idsdif = $_POST['idsdif'];
    } else {
        $jsondata['mensaje'] = "No enviaste nada";
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
    
    $temps = explode(",", $idsdif);
    $idsdif = implode("','", $temps);
    $idsdif = "'" . $idsdif . "'";
    
    $query = "SELECT * FROM $vw_persona WHERE iddifzapopan IN (" . $idsdif . ")";
        
    if ($result = mysql_query($query, $con)) {
        $jsondata['numero'] = mysql_num_rows($result);
        while ($rowpersona = mysql_fetch_assoc($result)) {
            $jsondata['personas'][] = $rowpersona;
        }
        
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    } else {
        $jsondata['mensaje'] = "Error en el query: " . $query . "</br>Error: " . mysql_error();
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
?>