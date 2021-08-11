<?php
    require_once("../../../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    $query = "SELECT apoyo FROM $apoyos_otorgados WHERE idservicio = '" . $_POST['idservicio'] . "'";
    if ($result = mysql_query($query, $con)) {
        $row_servicio = mysql_fetch_assoc($result);                
    } else {
        echo "</br>Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
    $query = "SELECT * FROM $vw_entrevista_inicial WHERE idservicio = '" . $_POST['idservicio'] . "'";
    if ($result = mysql_query($query, $con)) {
        $row_servicio = mysql_fetch_assoc($result);
        $cadena=0;
        if (!empty($row_servicio['apoyo'])) {
            $cadena = $row_servicio['apoyo'].",".$_POST['apoyo'];
        }else {
            $cadena = $_POST['apoyo'];
        }                     
    } else {
        echo "</br>Error en el query: " . $query . "</br>Error: " . mysql_error();
    }  

  if (!empty($_POST['apoyo'])) {    
    $apoyo = strpos($_POST['apoyo'], ",");
    $apoyo++; 
    $num = 0;    
        for ($i=0; $i < $apoyo ; $i++) { 
            $query = "INSERT INTO $apoyos_otorgados VALUES (null, '" . $_POST['idservicio'] . "', '" . substr($_POST['apoyo'], $num,2) . "')";            
            if (mysql_query($query, $con)) {                 
                $num += 3;
            } else {
                echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
            }              
        }   
        $query = "UPDATE $vw_entrevista_inicial SET apoyo = '$cadena' WHERE idservicio = '" . $_POST['idservicio'] . "'";
        if (mysql_query($query, $con)) {                             
        } else {
            echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
        } 
    header("Location: ../169editar.php?id=" . $_POST['idservicio'] . "&action=1");
  } else {
        header("Location: agregarservicio_169.php?id=" . $_POST['idservicio'] . "&action=1");
  }
  