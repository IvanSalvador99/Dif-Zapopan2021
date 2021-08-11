<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    /*echo "POST:</br>";
    var_dump($_POST);*/
    
    $query = "INSERT INTO $cursos VALUES (null, 
                '" . mysql_real_escape_string($_POST['nombrecurso']) . "', 
                '" . mysql_real_escape_string($_SESSION['padron_admin_area']) . "', 
                '" . mysql_real_escape_string($_SESSION['padron_admin_id']) . "', 
                '" . mysql_real_escape_string($_POST['locacion']) . "', 
                '" . mysql_real_escape_string($_POST['duracioncurso']) . "', 
                '" . mysql_real_escape_string($_POST['unidadduracion']) . "', 
                '" . mysql_real_escape_string($_POST['fechainicio']) . "', 
                '" . mysql_real_escape_string($_POST['fechafin']) . "', 
                '" . mysql_real_escape_string(implode(", ", $_POST['regularidad'])) . "', 
                '00:" . mysql_real_escape_string($_POST['horainicio']) . "', 
                '" . mysql_real_escape_string($_POST['duracionsesion']) . "', 
                '" . mysql_real_escape_string($_POST['aula']) . "', 
                '" . mysql_real_escape_string($_POST['cupominimo']) . "', 
                '" . mysql_real_escape_string($_POST['cupomaximo']) . "', 
                '" . mysql_real_escape_string($_POST['edadminima']) . "', 
                '" . mysql_real_escape_string($_POST['edadmaxima']) . "', 
                'flag')";
                
    $hoy = date("Y-m-d");
                
    if ($_POST['fechainicio'] >= $hoy) {
        $query = str_replace('flag', '1', $query);
    } else {
        $query = str_replace('flag', '2', $query);
    }
          
    //echo "</br></br>Query:</br>" . $query;
    
    if (mysql_query($query, $con) /*1 == 1*/) {
        $lastid = mysql_insert_id($con);
        
        //echo "</br></br>Last id:</br>" . $lastid;
        
        $query = "INSERT INTO $docentes VALUES (null, 
                    '" . $lastid . "', 
                    '" . mysql_real_escape_string($_POST['iddocente']) . "', 
                    '" . mysql_real_escape_string($_POST['nombredocente']) . "', 
                    '" . mysql_real_escape_string($_POST['esquemacolaboracion']) . "', 
                    '" . mysql_real_escape_string($_POST['dependenciadocente']) . "', 
                    '" . mysql_real_escape_string($_POST['teldocente']) . "')";
                    
        //echo "</br></br>Query:</br>" . $query;
        
        if (mysql_query($query, $con)) {
            //echo "</br></br>Hola Carola";
            header("Location: ../cursos.php?action=1");
        } else {
            echo "Error en el query: " . mysql_error();
        }
    } else {
        echo "Error en el query: " . mysql_error();
    }
?>