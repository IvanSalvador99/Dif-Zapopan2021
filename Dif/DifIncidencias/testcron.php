<?php
    /*require_once "../funciones.php";
    $con = conectar();*/
    
    date_default_timezone_set('America/Mexico_City');
    
    $content = "";
    $fp = fopen("/opt/bitnami/apache2/htdocs/Lab/DifIncidencias/testcron.txt","a");
    
    $con = mysqli_connect("localhost", "root", "bitnami", "padron_unico");
    if (mysqli_connect_errno()) {
        $content .= "Error al conectarse a la base de datos: " . mysqli_error($con) . "\n";
        fwrite($fp,$content);
        fclose($fp);
        exit();
    }
    
    /*$query = "SELECT * FROM usuario WHERE id = '1'";
    
    if ($result = mysqli_query($con, $query)) {
        $row = mysqli_fetch_array($result);
    } else {
        $content .= "Error en el query: " . $query . "\nError: " . mysqli_error($con) . "\n\n";
        fwrite($fp,$content);
        fclose($fp);
        exit();
    }
    
    $horas = $row['horasindicato'] + 1;*/
    
    $query = "UPDATE usuario SET horasindicato = '3' WHERE tipo = '1'";
    
    if (!mysqli_query($con, $query)) {
        $content .= "Error en el query: " . $query . "\nError: " . mysqli_error($con) . "\n\n";
        fwrite($fp,$content);
        fclose($fp);
        exit();
    } else {
        $content .= "Actualizado correctamente el dia " . date("d/m/Y") . " a las " . date("H:i:s") . "\n";
        fwrite($fp,$content);
        fclose($fp);
        exit();
    }
?>
