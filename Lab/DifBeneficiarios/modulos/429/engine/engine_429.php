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
    
    $avances = json_decode($_POST['avances']);
    
    /*echo "</br></br>AVANCES:</br>";
    var_dump($avances);*/
    
    $apoyos = json_decode($_POST['apoyos']);
    
    /*echo "</br></br>APOYOS:</br>";
    var_dump($apoyos);*/
    
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
        
        //echo "</br></br>LAST ID:</br>" . $lastid;
        
        $query = "INSERT INTO $estudio_socioeconomico VALUES (null,
                        '" . $_POST['idpersona'] . "', 
                        '" . mysql_real_escape_string($lastid) . "', 
                        '" . mysql_real_escape_string($_POST['registro']) . "', 
                        '" . mysql_real_escape_string($_POST['referidopor']) . "',
                        '" . mysql_real_escape_string($_POST['paisnacimiento']) . "',
                        '" . mysql_real_escape_string($_POST['estadonacimiento']) . "',
                        '" . mysql_real_escape_string($_POST['municipionacimiento']) . "',
                        '" . mysql_real_escape_string($_POST['tiempoestado']) . "', 
                        '" . mysql_real_escape_string($_POST['programa']) . "', 
                        '" . (($_POST['programa'] == 1) ? "1056" : "1057") . "',
                        '" . mysql_real_escape_string($_POST['apoyosolicitado']) . "', 
                        '" . mysql_real_escape_string($_POST['condicionvivienda']) . "', 
                        '" . mysql_real_escape_string($_POST['tipovivienda']) . "', 
                        '" . mysql_real_escape_string($_POST['otrocondicion']) . "', 
                        '" . mysql_real_escape_string($_POST['otrotipovivienda']) . "', 
                        '" . mysql_real_escape_string($_POST['tipoagua']) . "', 
                        '" . mysql_real_escape_string($_POST['tipodesechos']) . "', 
                        '" . mysql_real_escape_string($_POST['tipoelectricidad']) . "', 
                        '" . mysql_real_escape_string($_POST['distcocina']) . "', 
                        '" . mysql_real_escape_string($_POST['distbano']) . "', 
                        '" . mysql_real_escape_string($_POST['distdormitorio']) . "', 
                        '" . mysql_real_escape_string($_POST['distsala']) . "', 
                        '" . mysql_real_escape_string($_POST['distcomedor']) . "', 
                        '" . mysql_real_escape_string($_POST['otrodistribucion']) . "', 
                        '" . mysql_real_escape_string($_POST['tipopiso']) . "', 
                        '" . mysql_real_escape_string($_POST['tipomuro']) . "', 
                        '" . mysql_real_escape_string($_POST['tipotecho']) . "', 
                        '" . mysql_real_escape_string($_POST['zonavivienda']) . "', 
                        '" . mysql_real_escape_string($_POST['menajevivienda']) . "', 
                        '" . mysql_real_escape_string($_POST['limporg']) . "', 
                        '" . mysql_real_escape_string($_POST['tipoinmueble']) . "', 
                        '" . mysql_real_escape_string($_POST['valorinmueble']) . "', 
                        '" . mysql_real_escape_string($_POST['institucioncuenta']) . "', 
                        '" . mysql_real_escape_string($_POST['valorcuenta']) . "', 
                        '" . mysql_real_escape_string($_POST['marcavehiculo']) . "', 
                        '" . mysql_real_escape_string($_POST['modelovehiculo']) . "', 
                        '" . mysql_real_escape_string($_POST['institucioncredito']) . "', 
                        '" . mysql_real_escape_string($_POST['saldocredito']) . "', 
                        '" . mysql_real_escape_string($_POST['egresoalimentos']) . "', 
                        '" . mysql_real_escape_string($_POST['egresovivienda']) . "', 
                        '" . mysql_real_escape_string($_POST['egresoservicios']) . "', 
                        '" . mysql_real_escape_string($_POST['egresotransporte']) . "', 
                        '" . mysql_real_escape_string($_POST['egresoeducacion']) . "', 
                        '" . mysql_real_escape_string($_POST['egresosalud']) . "', 
                        '" . mysql_real_escape_string($_POST['egresovestido']) . "', 
                        '" . mysql_real_escape_string($_POST['egresorecreacion']) . "', 
                        '" . mysql_real_escape_string($_POST['egresodeudas']) . "', 
                        '" . mysql_real_escape_string($_POST['egresootros']) . "', 
                        '" . mysql_real_escape_string($_POST['egresototal']) . "', 
                        '" . mysql_real_escape_string($_POST['ingresofamiliar']) . "', 
                        '" . mysql_real_escape_string($_POST['ingresootros']) . "', 
                        '" . mysql_real_escape_string($_POST['ingresototal']) . "', 
                        '" . mysql_real_escape_string($_POST['diferenciatotal']) . "', 
                        '" . mysql_real_escape_string($_POST['obsbalance']) . "', 
                        '" . mysql_real_escape_string($_POST['frutas']) . "', 
                        '" . mysql_real_escape_string($_POST['cereales']) . "', 
                        '" . mysql_real_escape_string($_POST['leguminosas']) . "', 
                        '" . mysql_real_escape_string($_POST['animal']) . "', 
                        '" . mysql_real_escape_string($_POST['obsalim']) . "', 
                        '" . mysql_real_escape_string($_POST['obssalud']) . "', 
                        '" . mysql_real_escape_string($_POST['enfermedades']) . "', 
                        '" . mysql_real_escape_string($_POST['diagsfamiliar']) . "', 
                        '" . mysql_real_escape_string($_POST['probvul']) . "', 
                        '" . mysql_real_escape_string($_POST['probvul2']) . "', 
                        '" . mysql_real_escape_string($_POST['probvul3']) . "', 
                        '" . mysql_real_escape_string($_POST['detonante']) . "', 
                        '" . mysql_real_escape_string($_POST['detonante2']) . "', 
                        '" . mysql_real_escape_string($_POST['detonante3']) . "', 
                        '" . mysql_real_escape_string($_POST['diagnostico']) . "', 
                        '" . mysql_real_escape_string($_POST['diagnostico2']) . "', 
                        '" . mysql_real_escape_string($_POST['diagnostico3']) . "', 
                        '" . mysql_real_escape_string($_POST['planinter']) . "', 
                        '" . mysql_real_escape_string($_POST['eval']) . "')";
                        
        //echo "</br></br>QUERY ESTUDIO:</br>" . $query;
        
        if (mysql_query($query, $con)/*1==1*/) {
            $exito = TRUE;
            if (!empty($familiares)) {
                foreach ($familiares as $familiar) {
                    $query = "INSERT INTO $estsocio_comp_familiar VALUES  (null,
                                    '" . mysql_real_escape_string($lastid) . "', 
                                    '" . mysql_real_escape_string($familiar->idpariente) . "',
                                    '" . mysql_real_escape_string($familiar->nombre) . "',
                                    '" . mysql_real_escape_string($familiar->fechanacimiento) . "',
                                    '" . mysql_real_escape_string($familiar->curp) . "',
                                    '" . mysql_real_escape_string($familiar->edad) . "',
                                    '" . mysql_real_escape_string($familiar->sexo) . "',
                                    '" . mysql_real_escape_string($familiar->estadocivil) . "',
                                    '" . mysql_real_escape_string($familiar->ocupacion) . "',
                                    '" . mysql_real_escape_string($familiar->parentesco) . "',
                                    '" . mysql_real_escape_string($familiar->tipoingreso) . "',
                                    '" . mysql_real_escape_string($familiar->ingreso) . "',
                                    '" . mysql_real_escape_string($familiar->grado) . "',
                                    '" . mysql_real_escape_string($familiar->escolaridad) . "')";
                    
                    //echo "</br></br>QUERY FAMILIAR:</br>" . $query;
                    
                    if (mysql_query($query, $con) /*1 == 1*/) {
                        $exito = TRUE;
                    } else {
                        $exito = FALSE;
                        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                        exit();
                    }
                }
            }
            if (!empty($apoyos)) {
                foreach ($apoyos as $apoyo) {
                    $query = "INSERT INTO $estsocio_apoyos VALUES (null, 
                                    '" . mysql_real_escape_string($lastid) . "',
                                    '" . mysql_real_escape_string($apoyo->apoyoinstitucion) . "',
                                    '" . mysql_real_escape_string($apoyo->apoyopasado) . "',
                                    '" . mysql_real_escape_string($apoyo->apoyofecha) . "',
                                    '" . mysql_real_escape_string($apoyo->apoyoperiodo) . "',
                                    '" . mysql_real_escape_string($apoyo->apoyomonto) . "')";
                    
                    //echo "</br></br>QUERY APOYO:</br>" . $query;
                    
                    if (mysql_query($query, $con) /*1 == 1*/) {
                        $exito = TRUE;
                    } else {
                        $exito = FALSE;
                        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                        exit();
                    }
                }
            }
            if (!empty($avances)) {
                foreach ($avances as $avance) {
                    $query = "INSERT INTO $estsocio_avances VALUES (null, 
                                    '" . mysql_real_escape_string($lastid) . "',
                                    '" . mysql_real_escape_string($avance->fechaavance) . "',
                                    '" . mysql_real_escape_string($avance->avance) . "')";
                                    
                    //echo "</br></br>QUERY AVANCE:</br>" . $query;
                    
                    if (mysql_query($query, $con) /*1 == 1*/) {
                        $exito = TRUE;
                    } else {
                        $exito = FALSE;
                        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                        exit();
                    }
                }
            }
            if ($exito) {
                header("Location: ../../../beneficiariovista.php?id=" . $_POST['idpersona'] . "&action=1");
                //echo "</br></br>Hola Carola.";
            }
        } else {
            echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
        }
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
?>