<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    var_dump($_POST);
    
    $query = "INSERT INTO $servicios_otorgados VALUES (null,
                '" . $_POST['idpersona'] . "',
                '" . $_SESSION['padron_admin_id'] . "',
                '" . $_POST['fechaservicio'] . "',
                '" . $_POST['locacion'] . "',
                '" . $_POST['servicio'] . "', null)";
                
    if (mysql_query($query, $con)) {
        //echo "<br/><br/>Entrada creada correctamente con el id: " . $lastid . " iddifzapopan: " . $iddifzapopan;
        header("Location: ../beneficiariovista.php?id=" . $_POST['idpersona'] . "&action=1");
    } else {
        echo "Error en el query: " . mysql_error();
    }
?>