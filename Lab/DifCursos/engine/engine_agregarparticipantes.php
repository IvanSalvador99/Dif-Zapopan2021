<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    echo "SESSION:</br>";
    var_dump($_SESSION);
    
    echo "</br></br>POST:</br>";
    var_dump($_POST);
    
    $exito = FALSE;
    $partactual = array();
    $actuales = array();
    $query = "SELECT * FROM $asistentes WHERE idcurso = '" . $_POST['curso'] . "'";
    if ($result = mysql_query($query, $con)) {
        while ($rowpart = mysql_fetch_assoc($result)) {
            $partactual[] = $rowpart;
            $actuales[] = $rowpart['idasistente'];
        }
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
        exit();
    }
    
    echo "</br></br>Participantes actualess:</br>";
    var_dump($actuales);
    
    foreach ($_POST['participantes'] as $key => $value) {
        if (!in_array($value, $actuales)) {
            $query = "INSERT INTO $asistentes VALUES (null, '" . $_POST['curso'] . "', '" . $value . "')";
            if (mysql_query($query, $con) /*1==1*/) {
                echo "</br>Insertado idasistente: " . $value;
            } else {
                echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                exit();
            }
        }
    }
    
    foreach ($actuales as $key => $value) {
        if (!in_array($value, $_POST['participantes'])) {
            $query = "DELETE FROM $asistentes WHERE  id = '" . $partactual[$key]['id'] . "'";
            if (mysql_query($query) /*1 == 1*/) {
                echo "</br>Eliminado idasistente: " . $partactual[$key]['idasistente'];
            } else {
                echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                exit();
            }
        }
    }
    
    header("Location: ../cursos.php?action=2");
?>