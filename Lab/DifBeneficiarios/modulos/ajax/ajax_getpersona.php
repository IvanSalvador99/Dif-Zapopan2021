<?php
    include("../../../funciones.php");
    $con=conectar();
    $jsondata = array();
    
    if (isset($_POST['iddif'])) {
        $iddif = $_POST['iddif'];
    } else {
        $jsondata['mensaje'] = "No enviaste nada";
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
    
    $query = "SELECT * FROM $vw_persona WHERE iddifzapopan = '" . mysql_real_escape_string($iddif) . "'";
    
    if ($result = mysql_query($query, $con)) {
        $row = mysql_fetch_array($result);
        $jsondata['idpariente'] = $row['id'];
        $jsondata['nombre'] = $row['nombre'] . " " . $row['apaterno'] . " " . $row['amaterno'];
        $jsondata['fechanacimiento'] = $row['fechanacimiento'];
        $jsondata['curp'] = $row['curp'];
        $jsondata['edad'] = $row['edad'];
        $jsondata['sexo'] = $row['sexo'];
        $jsondata['estadocivil'] = $row['estado_civil'];
        $jsondata['ocupacion'] = $row['ocupacion'];
        
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    } else {
        $jsondata['mensaje'] = mysql_error();
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    }
?>