<?php
require_once("../../funciones.php");

iniciar_sesion(0);
date_default_timezone_set('America/Mexico_City');
$con = conectar();
$jsondata = array();
$tomorrow = strtotime("tomorrow");
$today = date('l dS \o\f F Y h:i:s A', time());

$query = "UPDATE $variables_fijas SET valor = '" . $tomorrow . "' WHERE id='1'";
$query2 = "INSERT INTO $hist_activar_captura_ext (id_usuario, valor_ingresado, fecha) VALUES ($_SESSION[$session_id], '" . $tomorrow . "', '" . $today . "');";
if (mysql_query($query2, $con)) {
    if (mysql_query($query, $con)) {
        $jsondata['mensaje'] = "Registro extemporaneo activado hasta finalizar el dia.";
    } else {
        $jsondata['mensaje'] = "Error en el query: $query, Error: " . mysql_error() . ", contactar a Sistemas.";
    }
} else {
    $jsondata['mensaje'] = "Error en el query: $query2, Error: " . mysql_error() . ", contactar a Sistemas.";
}

header('Content-type: application/json; charset=utf-8');
echo json_encode($jsondata, JSON_FORCE_OBJECT);
