<?php
require_once("config.php");
require_once("funciones.php");
session_start();
$con = conectar();
$opcion=$_POST["opcion"];

switch ($opcion) 
{
    case 1:
        $usuario=$_POST["usuario"];
        echo mysql_este("SELECT foto FROM $trabajadores WHERE username ='$usuario'","foto",$con);
    break;

    case 2:
    $usuario=$_POST["usuario"];
    $pass=$_POST["pass"];

    $ip=$_SERVER['REMOTE_ADDR'];
    $datos=$_SERVER['HTTP_USER_AGENT'];
    
    date_default_timezone_set('America/Mexico_City');

    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $hora_inicio = date("H:i:s", strtotime("-1 hour"));

    $seguridad = "select * from logsesiones where usuario='".$usuario."' and fecha='".$fecha."' and hora > '".$hora_inicio."'";
    $seguridad = mysql_query($seguridad,$con);
    $seguridad = mysql_num_rows($seguridad);

    if($seguridad < 4)
    {
      $consultaUsuario="SELECT * FROM $trabajadores WHERE username = '$usuario'";
      $resultUsuario = mysql_query($consultaUsuario, $con) or die(mysql_error());
    
      if($rowUsuario = mysql_fetch_array($resultUsuario))
      {
        if($rowUsuario["password"] == md5($pass))
        {
            echo 1;

            $_SESSION[$session_id] = $rowUsuario["id"];
            $_SESSION[$session_usuario] = $rowUsuario["username"];
            $_SESSION[$session_nombre] = trim($rowUsuario["nombre"]) . " " . trim($rowUsuario["apaterno"]) . " " . trim($rowUsuario["amaterno"]);
            $_SESSION[$session_activo] = "activa";
            $_SESSION[$session_area] = $rowUsuario["departamento"];
            $_SESSION[$session_permisos] = $rowUsuario["permiso"];
            $_SESSION[$session_timeout] = 1200;
        }
        else
        {
          $inserta_mal = "insert into logsesiones (usuario,agent,ip,hora,fecha) values ('$usuario','$datos','$ip','$hora','$fecha')";
          //$inserta_mal=mysql_query($inserta_mal,$con);
          echo 2;
        }
      }
      else
      {
        $inserta_mal = "insert into logsesiones (usuario, agent, ip, hora, fecha) values ('$usuario', '$datos', '$ip', '$hora', '$fecha')";
        //$inserta_mal = mysql_query($inserta_mal, $con);
        echo 3;
      }

    }
    else
    {
      echo 4;
    }
    break;
}
?>