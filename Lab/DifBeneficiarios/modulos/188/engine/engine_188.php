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
            '" . $_SESSION['padron_admin_id'] . "',
            '" . $_POST['fechaservicio'] . "',
            '" . $_POST['locacion'] . "',
            '" . $_POST['servicio'] . "',
            '" . $_POST['idservicio'] . "', null)";
    
    //echo "</br></br>QUERY SERVICIO:</br>" . $query;
                            
    if (mysql_query($query, $con)/*1==1*/) {
        
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
                
        //echo "</br></br>QUERY TRABAJO SOCIAL:</br>" . $query;
                    
        if (mysql_query($query, $con)/*1==1*/) {
            $done = 0;
            
            $apoyos = explode(',', $_POST['apoyo']);
            
            /*echo "</br></br>Apoyos:</br>";
            var_dump($apoyos);*/
            
            foreach ($apoyos as $apoyo) {
                $query = "INSERT INTO $apoyos_otorgados VALUES (null, '" . $lastid . "', '" . $apoyo . "')";
                //echo "</br></br>QUERY APOYOS:</br>" . $query;
                
                if (mysql_query($query, $con)/*1==1*/) {
                    $done ++;
                } else {
                    echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                }
            }
            
            //echo "</br></br>DONE:</br>" . $done;
            
            if ($done == count($apoyos)) {
                $done = false;
                switch ($_POST['cantsicats']) {
                    case "0":
                        $done = true;
                        break;
                    case "1":
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
                                
                        //echo "</br></br>QUERY SICATS 1:</br>" . $query;
                        
                        if (mysql_query($query, $con)/*1==1*/) {
                            $done = true;
                        } else {
                            echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                        }
                        break;
                    case "2":
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
                                
                        //echo "</br></br>QUERY SICATS 1:</br>" . $query;
                        
                        if (mysql_query($query, $con)/*1==1*/) {
                            $query = "INSERT INTO $persona_sicats VALUES (null,
                                    '" . $_POST['idpersona'] . "',
                                    '" . mysql_real_escape_string($lastid) . "', 
                                    '" . mysql_real_escape_string($_POST['origen2']) . "', 
                                    '" . mysql_real_escape_string($_POST['destino2']) . "', 
                                    '" . mysql_real_escape_string($_POST['responsableO2']) . "', 
                                    '" . mysql_real_escape_string($_POST['responsableD2']) . "', 
                                    '" . mysql_real_escape_string($_POST['numerocanalizacion2']) . "', 
                                    '" . mysql_real_escape_string($_POST['registro']) . "', 
                                    '" . $_POST['fechaservicio'] . "', 
                                    '" . mysql_real_escape_string($_POST['diagnostico']) . "', 
                                    '" . mysql_real_escape_string($_POST['solicitud2']) . "', 
                                    '" . mysql_real_escape_string($_POST['evolucion2']) . "', 
                                    '" . mysql_real_escape_string($_POST['anexos2']) . "', 
                                    '" . mysql_real_escape_string($_POST['observaciones2']) . "',
                                    '" . $_SESSION['padron_admin_id'] . "', 
                                    '" . $_POST['servicio'] . "', 
                                    '6503')";
                                    
                            //echo "</br></br>QUERY SICATS 2:</br>" . $query;
                            
                            if (mysql_query($query, $con)/*1==1*/) {
                                $done = true;
                            } else {
                                echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                            }
                        } else {
                            echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                        }
                        break;
                    case "3":
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
                                
                        //echo "</br></br>QUERY SICATS 1:</br>" . $query;
                        
                        if (mysql_query($query, $con)/*1==1*/) {
                            $query = "INSERT INTO $persona_sicats VALUES (null,
                                    '" . $_POST['idpersona'] . "',
                                    '" . mysql_real_escape_string($lastid) . "', 
                                    '" . mysql_real_escape_string($_POST['origen2']) . "', 
                                    '" . mysql_real_escape_string($_POST['destino2']) . "', 
                                    '" . mysql_real_escape_string($_POST['responsableO2']) . "', 
                                    '" . mysql_real_escape_string($_POST['responsableD2']) . "', 
                                    '" . mysql_real_escape_string($_POST['numerocanalizacion2']) . "', 
                                    '" . mysql_real_escape_string($_POST['registro']) . "', 
                                    '" . $_POST['fechaservicio'] . "', 
                                    '" . mysql_real_escape_string($_POST['diagnostico']) . "', 
                                    '" . mysql_real_escape_string($_POST['solicitud2']) . "', 
                                    '" . mysql_real_escape_string($_POST['evolucion2']) . "', 
                                    '" . mysql_real_escape_string($_POST['anexos2']) . "', 
                                    '" . mysql_real_escape_string($_POST['observaciones2']) . "',
                                    '" . $_SESSION['padron_admin_id'] . "', 
                                    '" . $_POST['servicio'] . "', 
                                    '6503')";
                                    
                            //echo "</br></br>QUERY SICATS 2:</br>" . $query;
                            
                            if (mysql_query($query, $con)/*1==1*/) {
                                $query = "INSERT INTO $persona_sicats VALUES (null,
                                        '" . $_POST['idpersona'] . "',
                                        '" . mysql_real_escape_string($lastid) . "', 
                                        '" . mysql_real_escape_string($_POST['origen3']) . "', 
                                        '" . mysql_real_escape_string($_POST['destino3']) . "', 
                                        '" . mysql_real_escape_string($_POST['responsableO3']) . "', 
                                        '" . mysql_real_escape_string($_POST['responsableD3']) . "', 
                                        '" . mysql_real_escape_string($_POST['numerocanalizacion3']) . "', 
                                        '" . mysql_real_escape_string($_POST['registro']) . "', 
                                        '" . $_POST['fechaservicio'] . "', 
                                        '" . mysql_real_escape_string($_POST['diagnostico']) . "', 
                                        '" . mysql_real_escape_string($_POST['solicitud3']) . "', 
                                        '" . mysql_real_escape_string($_POST['evolucion3']) . "', 
                                        '" . mysql_real_escape_string($_POST['anexos3']) . "', 
                                        '" . mysql_real_escape_string($_POST['observaciones3']) . "',
                                        '" . $_SESSION['padron_admin_id'] . "', 
                                        '" . $_POST['servicio'] . "', 
                                        '6503')";
                                        
                                //echo "</br></br>QUERY SICATS 3:</br>" . $query;
                                
                                if (mysql_query($query, $con)/*1==1*/) {
                                    $done = true;
                                } else {
                                    echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                                }
                            } else {
                                echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                            }
                        } else {
                            echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                        }
                        break; 
                }
                if ($done) {
                    header("Location: ../../../beneficiariovista.php?id=" . $_POST['idpersona'] . "&action=1");
                    //echo "</br></br>Hola Carola";
                } else {
                    echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                }
            }
        } else {
            echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
        }
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
?>