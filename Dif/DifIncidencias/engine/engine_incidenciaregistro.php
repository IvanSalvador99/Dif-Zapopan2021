<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    var_dump($_POST);
    
    $historial = date("Y-m-d H:i:s") . " -- Incidencia creada.\nDescripcion: " . $_POST['descripcion'];
    
    if (isset($_POST['horas']) && $_POST['horas'] >= 1) {
        $query = "INSERT INTO $incidencias VALUES (null,
                       '" . mysql_real_escape_string($_POST['idusuario']) . "',
                       '" . mysql_real_escape_string($_SESSION['padron_admin_id']) . "',
                       null,
                       '" . mysql_real_escape_string(date("Y-m-d H:i:s")) . "', 
                       '" . mysql_real_escape_string($_POST['fecha']) . "', 
                       '" . mysql_real_escape_string($_POST['tipo']) . "', 
                       '" . mysql_real_escape_string($_POST['concepto']) . "',
                       '" . mysql_real_escape_string($_POST['horas']) . "', 
                       '" . mysql_real_escape_string($_POST['descripcion']) . "',
                       '" . mysql_real_escape_string($historial) . "',
                       '1', '0');";
    } else {
        $query = "INSERT INTO $incidencias VALUES (null,
                       '" . mysql_real_escape_string($_POST['idusuario']) . "',
                       '" . mysql_real_escape_string($_SESSION['padron_admin_id']) . "',
                       null,
                       '" . mysql_real_escape_string(date("Y-m-d H:i:s")) . "', 
                       '" . mysql_real_escape_string($_POST['fecha']) . "', 
                       '" . mysql_real_escape_string($_POST['tipo']) . "', 
                       '" . mysql_real_escape_string($_POST['concepto']) . "',
                       null, 
                       '" . mysql_real_escape_string($_POST['descripcion']) . "',
                       '" . mysql_real_escape_string($historial) . "',
                       '1', '0');";
    }
    
    echo "</br></br>" . $query;
    
    if (mysql_query($query, $con)) {
        header("Location: ../incidencias.php?action=1");
    } else {
        echo "Error en el query: " . mysql_error();
    }
?>