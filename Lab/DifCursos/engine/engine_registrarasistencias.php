<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    echo "SESSION:</br>";
    var_dump($_SESSION);
    
    echo "</br></br>POST:</br>";
    var_dump($_POST);
    
    if ($_POST['fecha'] == "" || $_POST['curso'] == "" || $_POST['asistencias'] == "") {
        header("Location: ../cursos.php?action=5");
        exit();
    }
    
    $query = "SELECT * FROM $asistencias WHERE idcurso = '" . mysql_real_escape_string($_POST['curso']) . "' and fecha = '" . mysql_real_escape_string($_POST['fecha']) . "'";
    echo "</br></br>QUERY:</br>" . $query;
    
    if ($result = mysql_query($query, $con)) {
        if (mysql_num_rows($result) > 0) {
            header("Location: ../cursos.php?action=4");
            exit();
        }
    } else {
        echo "</br></br>Error en el query: " . $query . "</br>Error: " . mysql_error();
        exit();
    }
    
    $asis = json_decode($_POST['asistencias']);
    
    echo "</br></br>ASISTENCIAS:</br>";
    var_dump($asis);
    
    foreach ($asis as $as) {
        if ($as->asistencia == "a") {
            $as->asistencia = 1;
        } elseif ($as->asistencia == "f") {
            $as->asistencia = 2;
        } elseif ($as->asistencia == "j") {
            $as->asistencia = 3;
        } else {
            $as->asistencia = 2;
        }
        
        $query = "INSERT INTO $asistencias VALUES (null, 
                    '" . mysql_real_escape_string($_POST['curso']) . "', 
                    '" . mysql_real_escape_string($as->id) . "', 
                    '" . mysql_real_escape_string($_POST['fecha']) . "', 
                    '" . mysql_real_escape_string($as->asistencia) ."')";
                    
        echo "</br></br>QUERY:</br>" . $query;
        
        if (mysql_query($query, $con)) {
            $cant ++;
        } else {
            echo "</br></br>Error en el query: " . $query . "</br>Error: " . mysql_error();
            exit();
        }
    }
    header("Location: ../cursos.php?action=3");
    exit();
?>