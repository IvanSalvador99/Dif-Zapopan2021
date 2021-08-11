<?php
    require_once("../../../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City'); //Un nuevo comentario random :)
    $con = conectar();
    
    /*echo "SESSION:</br>";
    var_dump($_SESSION);
    
    echo "</br></br>POST:</br>";
    var_dump($_POST);*/
    
    $familiares = json_decode($_POST['familiares']);
    
    /*echo "</br></br>FAMILIARES:</br>";
    var_dump($familiares);*/
    
    $query = "INSERT INTO $servicios_otorgados VALUES (null,
            '" . $_POST['idpersona'] . "',
            '" . $_SESSION['padron_admin_id'] . "',
            '" . $_POST['fechaservicio'] . "',
            '" . $_POST['locacion'] . "',
            '" . $_POST['servicio'] . "',
            '" . $_POST['idservicio'] . "', null)";
    
    //echo "</br></br>QUERY SERVICIO:</br>" . $query;

    if (mysql_query($query, $con) /*1 == 1*/) {
        $lastid = mysql_insert_id();
        
        $query = "INSERT INTO $tpsicologica VALUES (null,
                    '" . $_POST['idpersona'] ."',
                    '" . $lastid . "',
                    '" . mysql_real_escape_string($_POST['noexp']) . "',
                    '" . mysql_real_escape_string($_POST['tipoterapia']) . "',
                    '" . mysql_real_escape_string($_POST['tiposistemafamiliar']) . "',
                    '" . mysql_real_escape_string($_POST['etapaciclovitalfamiliar']) . "',
                    0)";
                                                    
        //echo "</br></br>QUERY TERAPIA PSICOLOGICA:</br>" . $query;
        
        if (mysql_query($query, $con) /*1 == 1*/) {
            $exito = FALSE;
            if (!empty($familiares)) {
                foreach ($familiares as $familiar) {
                    $query = "INSERT INTO $comp_familiar VALUES (null,
                                '" . $lastid . "',
                                '" . mysql_real_escape_string($familiar->id) . "',
                                '" . mysql_real_escape_string($familiar->parentesco) . "',
                                '" . mysql_real_escape_string($familiar->nombre) . "',
                                '" . mysql_real_escape_string($familiar->fechanacimiento) . "',
                                '" . mysql_real_escape_string($familiar->curp) . "',
                                '" . mysql_real_escape_string($familiar->edad) . "',
                                '" . mysql_real_escape_string($familiar->estadocivil) . "',
                                '" . mysql_real_escape_string($familiar->escolaridad) . "',
                                '" . mysql_real_escape_string($familiar->ocupacion) . "')";
                                                                    
                    //echo "</br></br>QUERY FAMILIARES:</br>" . $query;
                    
                    if (mysql_query($query, $con) /*1 == 1*/) {
                        $exito = TRUE;
                    } else {
                        $exito = FALSE;
                        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                        break;
                    }
                }
            } else {
                $exito = TRUE;
            }
            if ($exito) {
                header("Location: ../../../beneficiariovista.php?id=" . $_POST['idpersona'] . "&action=1");
                //echo "</br></br>Hola Carola";
            }
        } else {
            echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
        }
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
?>