<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    $query = "INSERT INTO $trabajadores VALUES (null,
                                              '".mysql_real_escape_string($_POST["nombreusuario"])."',
                                              '".mysql_real_escape_string($_POST["email"])."',
                                              '".mysql_real_escape_string(md5($_POST["password"]))."',
                                              '".mysql_real_escape_string($_POST["departamento"])."',
                                              '".mysql_real_escape_string($_POST["permiso"])."',
                                              '".mysql_real_escape_string($_POST["nombre"])."',
                                              '".mysql_real_escape_string($_POST["apellidop"])."',
                                              '".mysql_real_escape_string($_POST["apellidom"])."',
                                              '".mysql_real_escape_string($_POST["numeroempleado"])."',
                                              '".mysql_real_escape_string($_POST["tipo"])."',
                                              '".mysql_real_escape_string(($_POST["tipo"] == 1) ? "5" : "0")."',
                                              '".mysql_real_escape_string(($_POST["tipo"] == 1) ? "3" : "0")."')";
                                              
    if (mysql_query($query, $con)) {
        header("Location: ../usuarios.php?action=1");
    } else {
        echo "Error en el query: " . mysql_error();
    }
                                              
?>