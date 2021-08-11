<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    //var_dump($_POST);
    
    if ($_POST['password'] == "") {
        if ($_POST['idusuario'] == $_SESSION['padron_admin_id']) {
            $query = "UPDATE $trabajadores SET username='" . mysql_real_escape_string($_POST['nombreusuario']) . "',
                             email='" . mysql_real_escape_string($_POST['email']) . "',
                             nombre='" . mysql_real_escape_string($_POST['nombre']) . "', 
                             apaterno='" . mysql_real_escape_string($_POST['apellidop']) . "', 
                             amaterno='" . mysql_real_escape_string($_POST['apellidom']) . "' WHERE id='" . mysql_real_escape_string($_POST['idusuario']) . "'";
        } else {
            $query = "UPDATE $trabajadores SET username='" . mysql_real_escape_string($_POST['nombreusuario']) . "',
                             email='" . mysql_real_escape_string($_POST['email']) . "', 
                             departamento='" . mysql_real_escape_string($_POST['departamento']) . "', 
                             permiso='" . mysql_real_escape_string($_POST['permiso']) . "', 
                             nombre='" . mysql_real_escape_string($_POST['nombre']) . "', 
                             apaterno='" . mysql_real_escape_string($_POST['apellidop']) . "', 
                             amaterno='" . mysql_real_escape_string($_POST['apellidom']) . "',
                             tipo='" . mysql_real_escape_string($_POST['tipo']) . "', 
                             numeroempleado='" . mysql_real_escape_string($_POST['numeroempleado']) . "' WHERE id='" . mysql_real_escape_string($_POST['idusuario']) . "'";
        }
    } else {
        if ($_POST['idusuario'] == $_SESSION['padron_admin_id']) {
            $query = "UPDATE $trabajadores SET username='" . mysql_real_escape_string($_POST['nombreusuario']) . "',
                             email='" . mysql_real_escape_string($_POST['email']) . "', 
                             password='" . mysql_real_escape_string(md5($_POST['password'])) . "',
                             nombre='" . mysql_real_escape_string($_POST['nombre']) . "', 
                             apaterno='" . mysql_real_escape_string($_POST['apellidop']) . "', 
                             amaterno='" . mysql_real_escape_string($_POST['apellidom']) . "' WHERE id='" . mysql_real_escape_string($_POST['idusuario']) . "'";
        } else {
            $query = "UPDATE $trabajadores SET username='" . mysql_real_escape_string($_POST['nombreusuario']) . "',
                             email='" . mysql_real_escape_string($_POST['email']) . "', 
                             password='" . mysql_real_escape_string(md5($_POST['password'])) . "', 
                             departamento='" . mysql_real_escape_string($_POST['departamento']) . "', 
                             permiso='" . mysql_real_escape_string($_POST['permiso']) . "', 
                             nombre='" . mysql_real_escape_string($_POST['nombre']) . "', 
                             apaterno='" . mysql_real_escape_string($_POST['apellidop']) . "', 
                             amaterno='" . mysql_real_escape_string($_POST['apellidom']) . "',
                             tipo='" . mysql_real_escape_string($_POST['tipo']) . "', 
                             numeroempleado='" . mysql_real_escape_string($_POST['numeroempleado']) . "' WHERE id='" . mysql_real_escape_string($_POST['idusuario']) . "'";
        }
    }
    
    /*echo "</br></br>" . $query;*/
    
    if (mysql_query($query, $con)) {
        header("Location: ../usuarios.php?action=2");
    } else {
        echo "Error en el query: " . mysql_error();
    }
?>