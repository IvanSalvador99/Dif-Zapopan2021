<?php
    include("../../../funciones.php");
    iniciar_sesion(0);
    $con = conectar();
    $jsondata = array();
    $temp = array();
    //echo (var_dump($_FILES) . "\n\n" . var_dump($_POST));
    
    if (!isset($_POST['tiposesion']) || !isset($_POST['idterapia'])) {
        $jsondata['exito'] = false;
        $jsondata['mensaje'] = "No esta especificado el tipo de sesión o el ID de la terapia.";
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    }
    if (!empty($_POST['fecha'])) {
        switch ($_POST['tiposesion']) {
            case '1':
                $isimageincluded = TRUE;
                
                if ($_FILES['genograma']['type'][0] == "image/png") {
                    $filename = "Genograma" . $_POST['idterapia'] . ".png";
                } elseif ($_FILES['genograma']['type'][0] == "image/jpeg") {
                    $filename = "Genograma" . $_POST['idterapia'] . ".jpg";
                } elseif ($_FILES['genograma']['type'][0] == "image/bmp") {
                    $filename = "Genograma" . $_POST['idterapia'] . ".bmp";
                } else {
                    $jsondata['exito'] = false;
                    $jsondata['mensaje'] = "Tu archivo no es de imagen JPG o PNG, no se puede registrar la sesión.";
                    $isimageincluded = FALSE;
                }
                
                $uploadpath = $_SERVER['DOCUMENT_ROOT'] . "/Tools/imagenes/Genogramas/" . $filename;
                
                if (!move_uploaded_file($_FILES['genograma']['tmp_name'][0], $uploadpath)) {
                    $jsondata['exito'] = false;
                    $jsondata['mensaje'] = "Error al crear agregar la imagen de genograma a la base de datos.";
                    $isimageincluded = FALSE;
                }
                if (!empty($_POST['motivoconsulta'])&&!empty($_POST['solucionesintentadas'])&&!empty($_POST['hipotesisrelacional'])&&!empty($_POST['objetivosterapeuticos'])&&!empty($_POST['observaciones'])) {
                    $query = "INSERT INTO $sesiones VALUES (null,
                            '" . $_POST['idterapia'] . "',
                            '" . $_POST['tiposesion'] . "',
                            '" . $_SESSION['padron_admin_id'] . "',
                            '" . $_POST['fecha']." ".date("H:i:s") . "',
                            '" . mysql_real_escape_string($_POST['motivoconsulta']) . "',
                            '" . mysql_real_escape_string($_POST['solucionesintentadas']) . "',
                            '" . (($isimageincluded) ? "/Tools/imagenes/Genogramas/" . $filename : "") . "',
                            '" . mysql_real_escape_string($_POST['hipotesisrelacional']) . "',
                            '" . mysql_real_escape_string($_POST['objetivosterapeuticos']) . "',
                            '" . mysql_real_escape_string($_POST['observaciones']) . "',
                            null,
                            null,
                            null,
                            null,
                            null,
                            null,
                            null)";
                
                if (mysql_query($query, $con)) {
                    $jsondata['exito'] = true;
                    $jsondata['mensaje'] = "Sesión de primera entrevista creada correctamente";
                }
                } else {
                    $jsondata['exito'] = false;
                    $jsondata['mensaje'] = "No agregaste correctamente los datos en PRIMERA ENTREVISTA";
                    header('Content-type: application/json; charset=utf-8');
                    echo json_encode($jsondata, JSON_FORCE_OBJECT);
                    exit();
                }
                
                
                break;
            case '2':
                if (!empty($_POST['avances'])&&!empty($_POST['dificultades'])&&!empty($_POST['notassesion'])&&!empty($_POST['tareas'])) {
                    $query = "INSERT INTO $sesiones VALUES (null,
                            '" . $_POST['idterapia'] . "',
                            '" . $_POST['tiposesion'] . "',
                            '" . $_SESSION['padron_admin_id'] . "',
                            '" . $_POST['fecha']." ".date("H:i:s") . "',
                            null,
                            null,
                            null,
                            null,
                            null,
                            null,
                            '" . mysql_real_escape_string($_POST['avances']) . "',
                            '" . mysql_real_escape_string($_POST['dificultades']) . "',
                            '" . mysql_real_escape_string($_POST['notassesion']) . "',
                            '" . mysql_real_escape_string($_POST['tareas']) . "',
                            null,
                            null,
                            null)";
                            
                if (mysql_query($query, $con)) {
                    $jsondata['exito'] = true;
                    $jsondata['mensaje'] = "Sesión regular creada correctamente";
                }
                } else {
                    $jsondata['exito'] = false;
                    $jsondata['mensaje'] = "No agregaste correctamente los datos en SESION SUBSECUENTE";
                    header('Content-type: application/json; charset=utf-8');
                    echo json_encode($jsondata, JSON_FORCE_OBJECT);
                    exit();
                }                                
                break;
            case '3':
                if (!empty($_POST['justinasistencia'])) {
                    $query = "INSERT INTO $sesiones VALUES (null,
                            '" . $_POST['idterapia'] . "',
                            '" . $_POST['tiposesion'] . "',
                            '" . $_SESSION['padron_admin_id'] . "',
                            '" . $_POST['fecha']." ".date("H:i:s") . "',
                            null, 
                            null,
                            null,
                            null,
                            null,
                            null,
                            null,
                            null,
                            null,
                            null,
                            '" . mysql_real_escape_string($_POST['justinasistencia']) . "',
                            null,
                            null)";
                            
                if (mysql_query($query, $con)) {
                    $jsondata['exito'] = true;
                    $jsondata['mensaje'] = "Inasistencia a sesión creada correctamente";
                }
                } else {
                    $jsondata['exito'] = false;
                    $jsondata['mensaje'] = "No agregaste correctamente los datos en INASISTENCIA";
                    header('Content-type: application/json; charset=utf-8');
                    echo json_encode($jsondata, JSON_FORCE_OBJECT);
                    exit();
                }                                
                break;
            case '4':
                if (!empty($_POST['evfinal'])&&!empty($_POST['motivoconclusion'])) {
                    $query = "INSERT INTO $sesiones VALUES (null,
                            '" . $_POST['idterapia'] . "',
                            '" . $_POST['tiposesion'] . "',
                            '" . $_SESSION['padron_admin_id'] . "',
                            '" . $_POST['fecha']." ".date("H:i:s") . "',
                            null,
                            null,
                            null,
                            null,
                            null,
                            null,
                            null,
                            null,
                            null,
                            null,
                            null,
                            '" . mysql_real_escape_string($_POST['evfinal']) . "',
                            '" . mysql_real_escape_string($_POST['motivoconclusion']) . "')";
                            
                if (mysql_query($query, $con)) {
                    $query = "UPDATE $tpsicologica SET cerrada = '1' WHERE id = '" . $_POST['idtpsicologica'] . "'";
                    if (mysql_query($query, $con)) {
                        $jsondata['exito'] = true;
                        $jsondata['mensaje'] = "Sesión de conclusión creada correctamente";
                    }
                }
                } else {
                    $jsondata['exito'] = false;
                    $jsondata['mensaje'] = "No agregaste correctamente los datos en CONCLUSIVA";
                    header('Content-type: application/json; charset=utf-8');
                    echo json_encode($jsondata, JSON_FORCE_OBJECT);
                    exit();
                }                                
                break;
            case '5':
                $isimageincluded = TRUE;
                
                if ($_FILES['genograma']['type'][0] == "image/png") {
                    $filename = "Genograma" . $_POST['idterapia'] . ".png";
                } elseif ($_FILES['genograma']['type'][0] == "image/jpeg") {
                    $filename = "Genograma" . $_POST['idterapia'] . ".jpg";
                } elseif ($_FILES['genograma']['type'][0] == "image/bmp") {
                    $filename = "Genograma" . $_POST['idterapia'] . ".bmp";
                } else {
                    $jsondata['exito'] = false;
                    $jsondata['mensaje'] = "Tu archivo no es de imagen JPG o PNG, no se puede registrar la sesión.";
                    $isimageincluded = FALSE;
                }
                
                $uploadpath = $_SERVER['DOCUMENT_ROOT'] . "/Tools/imagenes/Genogramas/" . $filename;
                
                if (!move_uploaded_file($_FILES['genograma']['tmp_name'][0], $uploadpath)) {
                    $jsondata['exito'] = false;
                    $jsondata['mensaje'] = "Error al crear agregar la imagen de genograma a la base de datos.";
                    $isimageincluded = FALSE;
                }
                
                // UPDATE `padron_unico`.`tpsicologica_sesiones` SET `motivoconsulta`='asdfasd', `solucionesintentadas`='asdfasd', `genograma`='4245', `hipotesisrel`='dfgsdfg', `objterapeuticos`='asdfasd', `observaciones`='asdfasdf' WHERE `id`='3567';
                if ($isimageincluded) {
                    $query = "UPDATE $sesiones SET `motivoconsulta`='" . mysql_real_escape_string($_POST['motivoconsulta']) . "',
                                               `solucionesintentadas`='" . mysql_real_escape_string($_POST['solucionesintentadas']) . "',
                                               `genograma`='/Tools/imagenes/Genogramas/" . $filename . "', 
                                               `hipotesisrel`='" . mysql_real_escape_string($_POST['hipotesisrelacional']) . "', 
                                               `objterapeuticos`='" . mysql_real_escape_string($_POST['objetivosterapeuticos']) . "', 
                                               `observaciones`='" . mysql_real_escape_string($_POST['observaciones']) . "' WHERE `id`='" . $_POST['idsesion'] . "'";
                } else {
                    $query = "UPDATE $sesiones SET `motivoconsulta`='" . mysql_real_escape_string($_POST['motivoconsulta']) . "',
                                               `solucionesintentadas`='" . mysql_real_escape_string($_POST['solucionesintentadas']) . "',
                                               `hipotesisrel`='" . mysql_real_escape_string($_POST['hipotesisrelacional']) . "', 
                                               `objterapeuticos`='" . mysql_real_escape_string($_POST['objetivosterapeuticos']) . "', 
                                               `observaciones`='" . mysql_real_escape_string($_POST['observaciones']) . "' WHERE `id`='" . $_POST['idsesion'] . "'";
                }         
                
                           
    
                if (mysql_query($query, $con)) {
                    $jsondata['exito'] = true;
                    $jsondata['mensaje'] = "Sesión de primera entrevista editada correctamente";
                }
                break;
            default:
                
                break;
        }
    
        if ($jsondata['exito']) {
            $query = "SELECT * FROM $vw_tpsicologica_sesiones WHERE idtpsicologica = '" . $_POST['idterapia'] . "'";
            if($result = mysql_query($query, $con)) {
                while ($row = mysql_fetch_assoc($result)) {
                    $temp[] = $row;
                }
            }
        }
        
        $jsondata['sesiones'] = $temp;
        
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    } else {
        $jsondata['exito'] = false;
        $jsondata['mensaje'] = "No agregaste la fecha y/o datos correctamente";
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    }
    
     
?>

