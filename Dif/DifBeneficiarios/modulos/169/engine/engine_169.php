<?php
    require_once("../../../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    /*echo "SESSION:</br>";
    var_dump($_SESSION);
    
    echo "</br></br>POST:</br>";
    var_dump($_POST);*/
    
    $query = "INSERT INTO $servicios_otorgados VALUES (null,
            '" . $_POST['idpersona'] . "',
            '" . $_POST['fechaservicio'] . "',
            '" . $_POST['locacion'] . "',
            '" . $_POST['servicio'] . "', null)";
    
    //echo "</br></br>QUERY:</br>" . $query;
                            
    if (mysql_query($query, $con)) {
        
        $lastid = mysql_insert_id();
        
        $query = "INSERT INTO $personas_ventanilla_unica VALUES (null, '" . $_POST['idpersona'] . "',
            '" . mysql_real_escape_string($lastid) . "',
            '" . mysql_real_escape_string($_POST['procedencia']) . "',
            '" . mysql_real_escape_string($_POST['registro']) . "',
            '" . mysql_real_escape_string($_POST['estadonacimiento']) . "',
            '" . mysql_real_escape_string($_POST['municipionacimiento']) . "',
            '" . mysql_real_escape_string($_POST['paisnacimiento']) . "',
            '" . mysql_real_escape_string($_POST['integrantes']) . "',
            '" . mysql_real_escape_string($_POST['pagocasa']) . "',
            '" . mysql_real_escape_string($_POST['poblacionatendida']) . "',
            '" . mysql_real_escape_string($_POST['integrantesactivos']) . "',
            '" . mysql_real_escape_string($_POST['ingresofamiliar']) . "',
            '" . mysql_real_escape_string($_POST['integrantesenfermos']) . "',
            '" . mysql_real_escape_string($_POST['deudas']) . "',
            '" . mysql_real_escape_string($_POST['probvul']) . "',
            '" . mysql_real_escape_string($_POST['detonante']) . "',
            '" . mysql_real_escape_string($_POST['diagnostico']) . "',
            '" . mysql_real_escape_string($_POST['apoyo']) . "',
            '" . mysql_real_escape_string($_POST['otroapoyo']) . "',
            '" . mysql_real_escape_string($_POST['canalizadoa']) . "',
            '" . mysql_real_escape_string($_POST['apoyosolicitado']) . "',
            '" . mysql_real_escape_string($_POST['descripcioncaso']) . "',
            '" . mysql_real_escape_string($_POST['descripcionconclusion']) . "')";
                
        //echo "</br></br>QUERY 2:</br>" . $query;
                    
        if (mysql_query($query, $con)) {
            
            if ($_POST['checkboxSICATS'] == "on") {
                
                $query = "INSERT INTO $persona_sicats VALUES (null,
                        '" . $_POST['idpersona'] . "',
                        '" . mysql_real_escape_string($lastid) . "', 
                        '" . mysql_real_escape_string($_POST['origen']) . "', 
                        '" . mysql_real_escape_string($_POST['destino']) . "', 
                        '" . mysql_real_escape_string($_POST['responsableO']) . "', 
                        '" . mysql_real_escape_string($_POST['responsableD']) . "', 
                        '" . mysql_real_escape_string($_POST['numerocanalizacion']) . "', 
                        '" . mysql_real_escape_string($_POST['registro']) . "', 
                        '" . $_POST['fechaservicio'] . "', 
                        '" . mysql_real_escape_string($_POST['diagnostico']) . "', 
                        '" . mysql_real_escape_string($_POST['solicitud']) . "', 
                        '" . mysql_real_escape_string($_POST['evolucion']) . "', 
                        '" . mysql_real_escape_string($_POST['anexos']) . "', 
                        '" . mysql_real_escape_string($_POST['observaciones']) . "',
                        '" . $_SESSION['padron_admin_id'] . "', 
                        '" . $_POST['servicio'] . "', 
                        '6503')";
                        
                //echo "</br></br>QUERY 3:</br>" . $query;
                        
                if (mysql_query($query, $con)) {
                    header("Location: ../../../beneficiariovista.php?id=" . $_POST['idpersona'] . "&action=1");
                } else {
                    echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                }
            } else {
                header("Location: ../../../beneficiariovista.php?id=" . $_POST['idpersona'] . "&action=1");
            }
        } else {
            echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
        }
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
?>