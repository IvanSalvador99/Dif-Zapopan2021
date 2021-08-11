<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    //var_dump($_POST);
    
    $historial = $_POST['prehistorial'] . "\n\n" . date("Y-m-d H:i:s") . " -- Incidencia editada por: " . $_SESSION['padron_admin_nombre'];
    echo $descripcion;
    
    $query = "SELECT * FROM $incidencias WHERE id = '" . $_POST['id'] . "'";
    
    if ($result = mysql_query($query, $con)) {
        $row = mysql_fetch_array($result);
        if ($row['estatus'] > 1) {
            header("Location: ../incidencias.php?action=4");
            exit();
        }
    }
    
    $jefes = array();
    $query = "SELECT * FROM $trabajadores WHERE id = '" . $row['idusuario'] . "'";
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
        echo enviamail($rowusuario['email'], $rowusuario['apaterno'] . " " . $rowusuario['amaterno'] . " " . $rowusuario['nombre'], "Incidencia editada", "Buen dia, la incidencia con id " . $_POST['id'] . " ha sido editada correctamente.");
        //var_dump($jefes);
        foreach ($jefes as $jefe) {
            //echo $jefe;
            $query = "SELECT * FROM $trabajadores WHERE id = '" . $jefe[0] . "'";
            //echo $query;
            if ($result = mysql_query($query, $con)) {
                $rowjefe = mysql_fetch_array($result);
                //var_dump($rowjefe);
                echo enviamail($rowjefe['email'], $rowjefe['apaterno'] . " " . $rowjefe['amaterno'] . " " . $rowjefe['nombre'], "Incidencia editada y en espera de ser aprobada/rechazada", "Buen dia, la incidencia con id " . $_POST['id'] . " fue editada y espera su autorización.");
            } else {
                echo "Error en el query: " . mysql_error();
                die();
            }
        }
        header("Location: ../incidencias.php?action=2");
        exit();
    } else {
        echo "Error en el query: " . mysql_error();
    }
?>