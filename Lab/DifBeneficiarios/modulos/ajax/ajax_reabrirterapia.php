<?php
    include("../../../funciones.php");
    iniciar_sesion(0);
    $con = conectar();
    $jsondata = array();
    
    if (!isset($_POST['idterapia'])) {
        $jsondata['exito'] = false;
        $jsondata['mensaje'] = "No esta especificado ID de la terapia.";
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    }
    
    $query = "UPDATE $tpsicologica SET cerrada = '0' WHERE id = '" . $_POST['idterapia'] . "'";
    
    if (mysql_query($query, $con)) {
        $query = "INSERT INTO $log_reapertura VALUES (null,
            '" . $_SESSION['padron_admin_id'] . "',
            '" . $_POST['idterapia'] . "',
            '" . date("Y-m-d H:i:s") . "')";
            
        if (mysql_query($query, $con)) {
            $jsondata['exito'] = true;
            $jsondata['mensaje'] = "Terapia reabierta correctamente";
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($jsondata, JSON_FORCE_OBJECT);
            exit();
        } else {
            $jsondata['exito'] = false;
            $jsondata['mensaje'] = "Error en el query" . $query;
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($jsondata, JSON_FORCE_OBJECT);
            exit();
        }
    } else {
        $jsondata['exito'] = false;
        $jsondata['mensaje'] = "Error en el query" . $query;
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    }
?>