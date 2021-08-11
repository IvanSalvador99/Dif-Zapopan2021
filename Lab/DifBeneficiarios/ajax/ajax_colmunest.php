<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    $jsondata = array();
    
    if (isset($_POST['cp'])) {
        $codigo = $_POST['cp'];
    } else {
        $jsondata['mensaje'] = "No enviaste nada";
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    }
    
    $qry = "SELECT * FROM $colonias WHERE codigo = '$codigo'";
    if ($result = mysql_query($qry, $con)){
        if (mysql_num_rows($result) > 0){
            $jsondata["exito"] = true;
            $jsondata['mensaje'] = sprintf("Se encontraron %d colonia(s).", mysql_num_rows($result));
            while ($row = mysql_fetch_array($result)) {
                $jsondata['ids'][] = $row['id'];
                $jsondata['codigos'][] = $row['tipo'] . " " . $row['colonia'];
                $jsondata['estado'] = $row['estado'];
                $jsondata['municipio'] = $row['municipio'];
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