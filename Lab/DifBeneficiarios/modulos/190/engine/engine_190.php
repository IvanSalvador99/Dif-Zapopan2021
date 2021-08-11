<?php
    require_once("../../../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City'); //Un nuevo comentario random :)
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
    
    /*echo "</br></br>QUERY SERVICIOO:</br>" . $query;*/
    
    if (mysql_query($query, $con) /*1 == 1*/) {
        $lastid = mysql_insert_id();
        
        if (isset($_POST['reportanteanonimo']) && $_POST['reportanteanonimo'] == '1') {
            $query = "INSERT INTO $casoreportado VALUES (null,
                        '" . $_POST['idpersona'] . "',
                        '" . $lastid . "',
                        '" . mysql_real_escape_string($_POST['noexp']) . "',
                        '" . mysql_real_escape_string($_POST['nombreagresor']) . "',
                        '" . mysql_real_escape_string($_POST['apellidopagresor']) . "',
                        '" . mysql_real_escape_string($_POST['apellidomagresor']) . "',
                        '" . mysql_real_escape_string($_POST['edadagresor']) . "',
                        '" . mysql_real_escape_string($_POST['sexoagresor']) . "',
                        '" . mysql_real_escape_string($_POST['paisagresor']) . "',
                        '" . mysql_real_escape_string($_POST['nivelescolaragresor']) . "',
                        '" . mysql_real_escape_string($_POST['ocupacionagresor']) . "',
                        '" . mysql_real_escape_string($_POST['estadocivilagresor']) . "',
                        '" . mysql_real_escape_string($_POST['parentescoagresor']) . "',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '" . mysql_real_escape_string($_POST['procedenciareport']) . "',
                        '" . mysql_real_escape_string($_POST['narracionhechos']) . "',
                        '" . mysql_real_escape_string($_POST['carpetainv']) . "',
                        '" . mysql_real_escape_string($_POST['agencia']) . "',
                        '" . mysql_real_escape_string($_POST['delito']) . "',
                        '" . mysql_real_escape_string($_POST['juicioexp']) . "',
                        '" . mysql_real_escape_string($_POST['juzgado']) . "',
                        '" . mysql_real_escape_string($_POST['tipojuicio']) . "',
                        '" . mysql_real_escape_string($_POST['medidaurgente']) . "',
                        '" . mysql_real_escape_string($_POST['medidaespecial']) . "',
                        '1',
                        null,
                        null)";
        } else {
            $query = "INSERT INTO $casoreportado VALUES (null,
                        '" . $_POST['idpersona'] . "',
                        '" . $lastid . "',
                        '" . mysql_real_escape_string($_POST['noexp']) . "',
                        '" . mysql_real_escape_string($_POST['nombreagresor']) . "',
                        '" . mysql_real_escape_string($_POST['apellidopagresor']) . "',
                        '" . mysql_real_escape_string($_POST['apellidomagresor']) . "',
                        '" . mysql_real_escape_string($_POST['edadagresor']) . "',
                        '" . mysql_real_escape_string($_POST['sexoagresor']) . "',
                        '" . mysql_real_escape_string($_POST['paisagresor']) . "',
                        '" . mysql_real_escape_string($_POST['nivelescolaragresor']) . "',
                        '" . mysql_real_escape_string($_POST['ocupacionagresor']) . "',
                        '" . mysql_real_escape_string($_POST['estadocivilagresor']) . "',
                        '" . mysql_real_escape_string($_POST['parentescoagresor']) . "',
                        '" . mysql_real_escape_string($_POST['nombrereportante']) . "',
                        '" . mysql_real_escape_string($_POST['apellidopreportante']) . "',
                        '" . mysql_real_escape_string($_POST['apellidomreportante']) . "',
                        '" . mysql_real_escape_string($_POST['domicilioreportante']) . "',
                        '" . mysql_real_escape_string($_POST['telefonoreportante']) . "',
                        '" . mysql_real_escape_string($_POST['procedenciareport']) . "',
                        '" . mysql_real_escape_string($_POST['narracionhechos']) . "',
                        '" . mysql_real_escape_string($_POST['carpetainv']) . "',
                        '" . mysql_real_escape_string($_POST['agencia']) . "',
                        '" . mysql_real_escape_string($_POST['delito']) . "',
                        '" . mysql_real_escape_string($_POST['juicioexp']) . "',
                        '" . mysql_real_escape_string($_POST['juzgado']) . "',
                        '" . mysql_real_escape_string($_POST['tipojuicio']) . "',
                        '" . mysql_real_escape_string($_POST['medidaurgente']) . "',
                        '" . mysql_real_escape_string($_POST['medidaespecial']) . "',
                        '1',
                        null,
                        null)";
        }   
        /*echo "</br></br>QUERY CASO REPORTADO:</br>" . $query;*/
        
        if (mysql_query($query, $con) /*1 == 1*/) {
            $exito = FALSE;
            foreach ($_POST['derechosvulnerados'] as $derechovulnerado) {
                $query = "INSERT INTO $derechos_vulnerados VALUES (null, '" . $lastid . "', '" . mysql_real_escape_string($derechovulnerado) . "')";
                if (mysql_query($query, $con) /*1 == 1*/) {
                    $exito = TRUE;
                } else {
                    $exito = FALSE;
                    echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                    break;
                }
            }
            if ($exito) {
                $exito1 = FALSE;
                foreach ($_POST['tipomaltrato'] as $tipomaltrato) {
                    $query = "INSERT INTO $tipos_maltrato VALUES (null, '" . $lastid . "', '" . mysql_real_escape_string($tipomaltrato) . "')";
                    if (mysql_query($query, $con) /*1 == 1*/) {
                        $exito1 = TRUE;
                    } else {
                        $exito1 = FALSE;
                        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
                        break;
                    }
                }
                if ($exito1) {
                    header("Location: ../../../beneficiariovista.php?id=" . $_POST['idpersona'] . "&action=1");
                    //echo "</br></br>Hola Carola";
                }
            }
        } else {
            echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
        }
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
?>