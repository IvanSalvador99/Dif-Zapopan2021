<?php
require_once("../../funciones.php");

iniciar_sesion(0);
date_default_timezone_set('America/Mexico_City');
$con = conectar();

//var_dump($_POST);

$jefes = array();
$query = "SELECT * FROM $trabajadores WHERE id = '" . $_POST['idusuario'] . "'"; // $rowusuario['id']
if ($result = mysql_query($query, $con)) {
    $rowusuario = mysql_fetch_array($result);
    $query = "SELECT idjefe FROM $incidencia_jefes WHERE validos LIKE '%," . $rowusuario['id'] . ",%' OR '%" . $rowusuario['id'] . ",%' OR '," . $rowusuario['id'] . "%'";
    if ($result = mysql_query($query, $con)) {
        while ($row = mysql_fetch_row($result)) {
            $jefes[] = $row;
        }
    } else {
        echo "Error en el query: " . $query . ", Error: " . mysql_error();
        die();
    }
} else {
    echo "Error en el query: " . $query . ", Error: " . mysql_error();
    die();
}

if (($_POST['concepto'] == 4 || $_POST['concepto'] == 1) && $rowusuario['tipo'] > 1) {
    header("Location: ../incidencias.php?action=3");
    exit();
}

$historial = date("Y-m-d H:i:s") . " -- Incidencia creada.\nDescripcion: " . utf8_decode($_POST['descripcion']);


if ((isFechasAbiertas() == "false" && $_POST['fecha'] >= date('Y-m-d', obtenerFechaInicio())) || (isFechasAbiertas() == "true")) {
    if (isset($_POST['horas']) && $_POST['horas'] >= 1) {
        $query = "INSERT INTO $incidencias VALUES (null,
                           '" . mysql_real_escape_string($_POST['idusuario']) . "',
                           '" . mysql_real_escape_string($_SESSION['padron_admin_id']) . "',
                           null, null,
                           '" . mysql_real_escape_string(date("Y-m-d H:i:s")) . "', 
                           '" . mysql_real_escape_string($_POST['fecha']) . "', 
                           '" . mysql_real_escape_string($_POST['tipo']) . "', 
                           '" . mysql_real_escape_string($_POST['concepto']) . "',
                           '" . mysql_real_escape_string($_POST['horas']) . "', 
                           '" . mysql_real_escape_string(utf8_decode($_POST['descripcion'])) . "',
                           '" . mysql_real_escape_string($historial) . "',
                           '1', '0');";
    } else {
        $query = "INSERT INTO $incidencias VALUES (null,
                           '" . mysql_real_escape_string($_POST['idusuario']) . "',
                           '" . mysql_real_escape_string($_SESSION['padron_admin_id']) . "',
                           null, null,
                           '" . mysql_real_escape_string(date("Y-m-d H:i:s")) . "', 
                           '" . mysql_real_escape_string($_POST['fecha']) . "', 
                           '" . mysql_real_escape_string($_POST['tipo']) . "', 
                           '" . mysql_real_escape_string($_POST['concepto']) . "',
                           null, 
                           '" . mysql_real_escape_string(utf8_decode($_POST['descripcion'])) . "',
                           '" . mysql_real_escape_string($historial) . "',
                           '1', '0');";
    }

    if (mysql_query($query, $con)) {
        $lastid = mysql_insert_id();
        echo enviamail($rowusuario['email'], $rowusuario['apaterno'] . " " . $rowusuario['amaterno'] . " " . $rowusuario['nombre'], "Incidencia registrada", "Buen dia, la incidencia con id " . $lastid . " del dia " . $_POST['fecha'] . " con la siguiente descripcion: " . $_POST['descripcion'] . " ha sido registrada correctamente.");
        //var_dump($jefes);
        foreach ($jefes as $jefe) {
            //echo $jefe;
            $query = "SELECT * FROM $trabajadores WHERE id = '" . $jefe[0] . "'";
            //echo $query;
            if ($result = mysql_query($query, $con)) {
                $rowjefe = mysql_fetch_array($result);
                //var_dump($rowjefe);
                echo enviamail($rowjefe['email'], $rowjefe['apaterno'] . " " . $rowjefe['amaterno'] . " " . $rowjefe['nombre'], "Incidencia creada y en espera de ser aprobada/rechazada", "Buen dia, la incidencia con id " . $lastid . " del dia " . $_POST['fecha'] . " del usuario " . $rowusuario['apaterno'] . " " . $rowusuario['amaterno'] . " " . $rowusuario['nombre'] . " con la siguiente descripcion: " . $_POST['descripcion'] . " se registro y espera su autorización.");
            } else {
                echo "Error en el query: " . mysql_error();
                die();
            }
        }
        header("Location: ../incidencias.php?action=1");
        exit();
    } else {
        echo "Error en el query: " . mysql_error();
        die();
    }
} else {
    header("Location: ../incidencias.php?action=5");
    exit();
}


//echo "</br></br>" . $query;
