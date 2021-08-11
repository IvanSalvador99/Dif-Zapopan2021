<?php
    include("../../funciones.php");
    $con=conectar();
    $jsondata = array();
    
    if (isset($_POST['idincidencia'])) {
        $idincidencia = mysql_real_escape_string($_POST['idincidencia']);
    } else {
        $jsondata['exito'] = false;
        $jsondata['mensaje'] = "No enviaste nada";
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    }
    
    $qry = "SELECT * FROM $vw_incidencias WHERE id = '$idincidencia'";
    if ($result = mysql_query($qry, $con)){
        if (mysql_num_rows($result) > 0){
            $row = mysql_fetch_array($result);
            $jsondata['exito'] = true;
            $jsondata['mensaje'] = $row['descripcion'];
            $jsondata['historial'] = $row['historial'];
            $jsondata['tipo'] = $row['tipo'];
            $jsondata['concepto'] = $row['concepto'];
            $jsondata['nombreusuario'] = $row['nombre'] . " " . $row['apaterno'] . " " . $row['amaterno'];
            $jsondata['nombrecapturista'] = $row['nombrecapturista'] . " " . $row['apaternocapturista'] . " " . $row['amaternocapturista'];
            $jsondata['nombreautorizador'] = $row['nombreautorizador'] . " " . $row['apaternoautorizador'] . " " . $row['amaternoautorizador'];
            $jsondata['numeroempleado'] = $row['numeroempleado'];
            $jsondata['tipoempleado'] = $row['tipoempleado'];
            $jsondata['departamento'] = $row['departamento'];
        } else {
            $jsondata['exito'] = false;
            $jsondata['mensaje'] = "No se encontró ningún resultado";
        }
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    } else {
        $jsondata['exito'] = false;
        $jsondata['mensaje'] = "Error en el query: " + $qry + "\nError: " + mysql_error();
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    }
?>