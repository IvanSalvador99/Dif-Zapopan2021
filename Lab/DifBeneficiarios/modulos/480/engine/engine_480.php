<?php
    require_once("../../../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City'); //Un nuevo comentario random :)
    $con = conectar();
    
    /*echo "SESSION:</br>";
    var_dump($_SESSION);
    
    echo "</br></br>POST:</br>";
    var_dump($_POST);*/
    
    /*echo "</br></br>FAMILIARES:</br>";
    var_dump($familiares);*/
    
    $query = "INSERT INTO $servicios_otorgados VALUES (null,
            '" . $_POST['idpersona'] . "',
            '" . $_SESSION['padron_admin_id'] . "',
            '" . $_POST['fechaservicio'] . "',
            '" . $_POST['locacion'] . "',
            '" . $_POST['servicio'] . "',
            '" . $_POST['idservicio'] . "', null)";    
            if (mysql_query($query, $con) /*1 == 1*/) {                              
                $lastid = mysql_insert_id();
            } else {
                echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
            }    
        
        $query = "INSERT INTO $atenjuridica VALUES (null,
                    '" . $_POST['idpersona'] ."',
                    '" . $lastid . "',
                    '" . mysql_real_escape_string($_POST['noexp']) . "',
                    '" . mysql_real_escape_string($_POST['tipodeli']) . "',
                    '" . mysql_real_escape_string($_POST['problematicaplanteada']) . "',
                    '" . mysql_real_escape_string($_POST['sugerenciajuridica']) . "',
                    0)";

    if (mysql_query($query, $con) /*1 == 1*/) {
        header("Location: ../../../beneficiariovista.php?id=" . $_POST['idpersona'] . "&action=1");
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
?>