<?php
    require_once("../../../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City'); //Un nuevo comentario random :)
    $con = conectar();
    
    $rowestudio = null;
    $famactual = array();
    $quitar = array();
    
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
    
    $query = "SELECT * FROM $estudio_socioeconomico WHERE idservicio = '" . $_POST['servicio'] . "'";
    
    if ($result = mysql_query($query, $con)) {
        $rowestudio = mysql_fetch_assoc($result);
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
    
    /*echo "</br></br>ESTUDIO:</br>";
    var_dump($rowestudio);*/
    
    $query = "UPDATE $estudio_socioeconomico SET 
                    `registro`='" . mysql_real_escape_string($_POST['registro']) . "', 
                    `procedencia`='" . mysql_real_escape_string($_POST['referidopor']) . "', 
                    `pais`='" . mysql_real_escape_string($_POST['paisnacimiento']) . "', 
                    `estadonac`='" . mysql_real_escape_string($_POST['estadonacimiento']) . "', 
                    `municipionac`='" . mysql_real_escape_string($_POST['municipionacimiento']) . "', 
                    `tiempoestado`='" . mysql_real_escape_string($_POST['tiempoestado']) . "', 
                    `programa`='" . mysql_real_escape_string($_POST['programa']) . "', 
                    `numeroprograma`='" . (($_POST['programa'] == 1) ? "1056" : "1057") . "', 
                    `apoyosolicitado`='" . mysql_real_escape_string($_POST['apoyosolicitado']) . "', 
                    `condicionvivienda`='" . mysql_real_escape_string($_POST['condicionvivienda']) . "', 
                    `tipovivienda`='" . mysql_real_escape_string($_POST['tipovivienda']) . "', 
                    `otrocondicion`='" . mysql_real_escape_string($_POST['otrocondicion']) . "', 
                    `otrotipovivienda`='" . mysql_real_escape_string($_POST['otrotipovivienda']) . "', 
                    `tipoagua`='" . mysql_real_escape_string($_POST['tipoagua']) . "', 
                    `tipodesechos`='" . mysql_real_escape_string($_POST['tipodesechos']) . "', 
                    `tipoelectricidad`='" . mysql_real_escape_string($_POST['tipoelectricidad']) . "', 
                    `distcocina`='" . mysql_real_escape_string($_POST['distcocina']) . "', 
                    `distbano`='" . mysql_real_escape_string($_POST['distbano']) . "', 
                    `distdormitorio`='" . mysql_real_escape_string($_POST['distdormitorio']) . "', 
                    `distsala`='" . mysql_real_escape_string($_POST['distsala']) . "', 
                    `distcomedor`='" . mysql_real_escape_string($_POST['distcomedor']) . "', 
                    `otrodistribucion`='" . mysql_real_escape_string($_POST['otrodistribucion']) . "', 
                    `tipopiso`='" . mysql_real_escape_string($_POST['tipopiso']) . "', 
                    `tipomuro`='" . mysql_real_escape_string($_POST['tipomuro']) . "', 
                    `tipotecho`='" . mysql_real_escape_string($_POST['tipotecho']) . "', 
                    `zonavivienda`='" . mysql_real_escape_string($_POST['zonavivienda']) . "', 
                    `menajevivienda`='" . mysql_real_escape_string($_POST['menajevivienda']) . "', 
                    `limporg`='" . mysql_real_escape_string($_POST['limporg']) . "', 
                    `tipoinmueble`='" . mysql_real_escape_string($_POST['tipoinmueble']) . "', 
                    `valorinmueble`='" . mysql_real_escape_string($_POST['valorinmueble']) . "', 
                    `institucioncuenta`='" . mysql_real_escape_string($_POST['institucioncuenta']) . "', 
                    `valorcuenta`='" . mysql_real_escape_string($_POST['valorcuenta']) . "', 
                    `marcavehiculo`='" . mysql_real_escape_string($_POST['marcavehiculo']) . "', 
                    `modelovehiculo`='" . mysql_real_escape_string($_POST['modelovehiculo']) . "', 
                    `institucioncredito`='" . mysql_real_escape_string($_POST['institucioncredito']) . "', 
                    `saldocredito`='" . mysql_real_escape_string($_POST['saldocredito']) . "', 
                    `egresoalimentos`='" . mysql_real_escape_string($_POST['egresoalimentos']) . "', 
                    `egresovivienda`='" . mysql_real_escape_string($_POST['egresovivienda']) . "', 
                    `egresoservicios`='" . mysql_real_escape_string($_POST['egresoservicios']) . "', 
                    `egresotransporte`='" . mysql_real_escape_string($_POST['egresotransporte']) . "', 
                    `egresoeducacion`='" . mysql_real_escape_string($_POST['egresoeducacion']) . "', 
                    `egresosalud`='" . mysql_real_escape_string($_POST['egresosalud']) . "', 
                    `egresovestido`='" . mysql_real_escape_string($_POST['egresovestido']) . "', 
                    `egresorecreacion`='" . mysql_real_escape_string($_POST['egresorecreacion']) . "', 
                    `egresodeudas`='" . mysql_real_escape_string($_POST['egresodeudas']) . "', 
                    `egresootros`='" . mysql_real_escape_string($_POST['egresootros']) . "', 
                    `egresototal`='" . mysql_real_escape_string($_POST['egresototal']) . "', 
                    `ingresofamiliar`='" . mysql_real_escape_string($_POST['ingresofamiliar']) . "', 
                    `ingresootros`='" . mysql_real_escape_string($_POST['ingresootros']) . "', 
                    `ingresototal`='" . mysql_real_escape_string($_POST['ingresototal']) . "', 
                    `diferenciatotal`='" . mysql_real_escape_string($_POST['diferenciatotal']) . "', 
                    `obsbalance`='" . mysql_real_escape_string($_POST['obsbalance']) . "', 
                    `frutas`='" . mysql_real_escape_string($_POST['frutas']) . "', 
                    `cereales`='" . mysql_real_escape_string($_POST['cereales']) . "', 
                    `leguminosas`='" . mysql_real_escape_string($_POST['leguminosas']) . "', 
                    `animal`='" . mysql_real_escape_string($_POST['animal']) . "', 
                    `obsalim`='" . mysql_real_escape_string($_POST['obsalim']) . "', 
                    `obssalud`='" . mysql_real_escape_string($_POST['obssalud']) . "', 
                    `enfermedades`='" . mysql_real_escape_string($_POST['enfermedades']) . "', 
                    `diagsfamiliar`='" . mysql_real_escape_string($_POST['diagsfamiliar']) . "', 
                    `probvul`='" . mysql_real_escape_string($_POST['probvul']) . "', 
                    `probvul2`='" . mysql_real_escape_string($_POST['probvul2']) . "', 
                    `probvul3`='" . mysql_real_escape_string($_POST['probvul3']) . "', 
                    `detonante`='" . mysql_real_escape_string($_POST['detonante']) . "', 
                    `detonante2`='" . mysql_real_escape_string($_POST['detonante2']) . "', 
                    `detonante3`='" . mysql_real_escape_string($_POST['detonante3']) . "', 
                    `diagnostico`='" . mysql_real_escape_string($_POST['diagnostico']) . "', 
                    `diagnostico2`='" . mysql_real_escape_string($_POST['diagnostico2']) . "', 
                    `diagnostico3`='" . mysql_real_escape_string($_POST['diagnostico3']) . "', 
                    `planinter`='" . mysql_real_escape_string($_POST['planinter']) . "', 
                    `eval`='" . mysql_real_escape_string($_POST['eval']) . "' WHERE `id`='" . $rowestudio['id'] . "'";
                    
    if (mysql_query($query, $con)) {
        
        $query = "SELECT * FROM $estsocio_comp_familiar WHERE idservicio = '" . $_POST['servicio'] . "'";
        if ($result = mysql_query($query, $con)) {
            while ($rowfam = mysql_fetch_assoc($result)) {
                $famactual[] = $rowfam;
            }
        } else {
            echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
        }
        
        /*echo "</br></br>FAMACTUAL:</br>";
        var_dump($famactual);*/
        
        $flag = FALSE;
        foreach ($famactual as $fam) {
            foreach ($familiares as $familiar) {
                if ($fam['id'] == $familiar->id) {
                    $flag = TRUE;
                }
            }
            if(!$flag) {
                $query = "DELETE FROM $estsocio_comp_familiar WHERE id = '" . $fam['id'] . "'";
                //echo "</br></br>QUERY:</br>" . $query;
                
                if (!mysql_query($query, $con)) {
                    echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                }
            }
            $flag = FALSE;
        }
        
        foreach ($familiares as $familiar) {
            if ($familiar->id == NULL) {
                $query = "INSERT INTO $estsocio_comp_familiar VALUES  (null,
                                    '" . mysql_real_escape_string($_POST['servicio']) . "', 
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
                                        
                //echo "</br></br>QUERY:</br>" . $query;
                
                if (!mysql_query($query, $con)) {
                    echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                }
            }
        }
        
        foreach ($avances as $avance) {
            if(!isset($avance->id)) {
                $query = "INSERT INTO $estsocio_avances VALUES (null, 
                                    '" . mysql_real_escape_string($_POST['servicio']) . "',
                                    '" . mysql_real_escape_string($avance->fechaavance) . "',
                                    '" . mysql_real_escape_string($avance->avance) . "')";
                                    
                //echo "</br></br>QUERY:</br>" . $query;
                
                if (!mysql_query($query, $con)) {
                    echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                }
            }
        }
        
        foreach ($apoyos as $apoyo) {
            if(!isset($apoyo->id)) {
                $query = "INSERT INTO $estsocio_apoyos VALUES (null, 
                                    '" . mysql_real_escape_string($_POST['servicio']) . "',
                                    '" . mysql_real_escape_string($apoyo->apoyoinstitucion) . "',
                                    '" . mysql_real_escape_string($apoyo->apoyopasado) . "',
                                    '" . mysql_real_escape_string($apoyo->apoyofecha) . "',
                                    '" . mysql_real_escape_string($apoyo->apoyoperiodo) . "',
                                    '" . mysql_real_escape_string($apoyo->apoyomonto) . "')";
                    
                //echo "</br></br>QUERY:</br>" . $query;
                
                if (!mysql_query($query, $con)) {
                    echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                }
            }
        }
        
        //echo "</br></br>Hola Carola";
        header("Location: ../../../beneficiariovista.php?id=" . $rowestudio['idpersona'] . "&action=7");
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
    
?>  