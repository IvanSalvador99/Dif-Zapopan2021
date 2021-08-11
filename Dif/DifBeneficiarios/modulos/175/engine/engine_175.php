<?php
    require_once("../../../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    echo "SESSION:</br>";
    var_dump($_SESSION);
    
    echo "</br></br>POST:</br>";
    var_dump($_POST);
    
    $query = "SELECT fecha FROM persona_escuela_padres WHERE idpersona = '" . $_POST['idpersona'] . "' ORDER BY fecha ASC LIMIT 1;";
    if ($result = mysql_query($query, $con)) {
        $rowfecha = mysql_fetch_array($result);
        $fechainicial = $rowfecha['fecha'];
    } else {
        echo "</br></br>Error en el query: " . $query . "\nError: " . mysql_error();
    }
    
    $query = "INSERT INTO $escpadres VALUES (null,
        '" . mysql_real_escape_string($_POST['idpersona']) . "',
        '" . mysql_real_escape_string(date("Y-m-d")) . "',
        '" . mysql_real_escape_string($_POST['tipovialidad']) . "',
        '" . mysql_real_escape_string($_POST['orientador']) . "',
        '" . mysql_real_escape_string($_POST['grupo']) . "',
        '" . mysql_real_escape_string($_POST['consecutivo']) . "',
        '" . mysql_real_escape_string($_POST['discapacidad']) . "',
        '" . mysql_real_escape_string($_POST['grupoetnico']) . "',
        '" . mysql_real_escape_string($_POST['asentamiento']) . "',
        '" . mysql_real_escape_string($_POST['tipoasentamiento']) . "',
        '" . mysql_real_escape_string($fechainicial) . "',
        '" . mysql_real_escape_string($_POST['tipoapoyo']) . "',
        '" . mysql_real_escape_string($_POST['estadonacimiento']) . "');";

    echo "</br></br>QUERY:</br>" . $query;
                            
    if (mysql_query($query, $con)) {
        
        $query = "INSERT INTO $servicios_otorgados VALUES (null,
                '" . $_POST['idpersona'] . "',
                '" . $_POST['fechaservicio'] . "',
                '" . $_POST['locacion'] . "',
                '" . $_POST['servicio'] . "', null)";
                
        echo "</br></br>QUERY 2:</br>" . $query;
                    
        if (mysql_query($query, $con)) {
            header("Location: ../../../beneficiariovista.php?id=" . $_POST['idpersona'] . "&action=1");
        } else {
            echo "</br></br>Error en el query: " . $query . "</br>Error: " . mysql_error();
        }
    } else {
        echo "</br></br>Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
?>  