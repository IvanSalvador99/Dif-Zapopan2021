<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    var_dump($_POST);
    
    $historial = $_POST['prehistorial'] . "\n\n" . date("Y-m-d H:i:s") . " -- Incidencia editada por: " . $_SESSION['padron_admin_nombre'];
    echo $descripcion;
    
    $query = "SELECT estatus FROM $incidencias WHERE id = '" . $_POST['id'] . "'";
    
    if ($result = mysql_query($query, $con)) {
        $row = mysql_fetch_array($result);
        if ($row['estatus'] > 1) {
            alerta_bota("La incidencia ya ha sido cerrada", "../incidencias.php");
            exit();
        }
    }
    
    if (isset($_POST['horas']) && $_POST['horas'] >= 1) {
        $query = "UPDATE $incidencias SET fecha = '" . mysql_real_escape_string($_POST['fecha']) . "', 
                      tipo='" . mysql_real_escape_string($_POST['tipo']) . "',
                      concepto='" . mysql_real_escape_string($_POST['concepto']) . "',
                      historial='" . $historial . "',
                      horas='" . mysql_real_escape_string($_POST['horas']) . "',
                      descripcion='" . mysql_real_escape_string($_POST['descripcion']) . "' WHERE id='" . $_POST['id'] . "'";                                                      
    } else {
        $query = "UPDATE $incidencias SET fecha='" . mysql_real_escape_string($_POST['fecha']) . "', 
                      tipo='" . mysql_real_escape_string($_POST['tipo']) . "',
                      concepto='" . mysql_real_escape_string($_POST['concepto']) . "',
                      historial='" . $historial . "',
                      horas='0',
                      descripcion='" . mysql_real_escape_string($_POST['descripcion']) . "' WHERE id='" . $_POST['id'] . "'";
    }
                                          
    echo "<br/><br/>" . $query . "<br/>";
    
    if (mysql_query($query, $con)) {
        header("Location: ../incidencias.php?action=2");
    } else {
        echo "Error en el query: " . mysql_error();
    }
?>