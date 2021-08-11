<?php 
    include("../../funciones.php");
    $con=conectar();
    iniciar_sesion(3);
    $jsondata = array();
    
    if (!isset($_POST['idincidencia'])) {
        echo "No enviaste nada";
        exit();
    }
    
    $query = "SELECT * FROM $incidencias WHERE id = '" . mysql_real_escape_string($_POST['idincidencia']) . "'";
    if ($result = mysql_query($query, $con)){
        if (mysql_num_rows($result) > 0){
            $rowincidencia = mysql_fetch_array($result);
            $query = "SELECT * FROM $trabajadores WHERE id = '" . $rowincidencia['idusuario'] . "'";
            if ($result = mysql_query($query, $con)) {
                if (mysql_num_rows($result) > 0) {
                    $rowusuario = mysql_fetch_array($result);
                    $idsecretaria = $_SESSION['padron_admin_id'];
                    $permitido = false;
                    if ($rowincidencia['estatus'] == 1) {
                        $query = "SELECT * FROM $incidencia_secretarias WHERE idsecretaria = $idsecretaria";
                        if ($result = mysql_query($query, $con)) {
                            $rowvalidos = mysql_fetch_assoc($result);
                            $validos = explode(",", $rowvalidos['validos']);
                            foreach ($validos as $valido) {
                                if ($rowusuario['id'] == $valido) {
                                    $permitido = true;
                                }
                            }
                            
                            if (($permitido && $_SESSION['padron_admin_permisos'] == 7) || $_SESSION['padron_admin_permisos'] == 1) {
                                $query = "UPDATE $incidencias SET historial='" . $rowincidencia['historial']. "\n\n" . date("Y-m-d H:i:s") . utf8_decode(" -- Incidencia cancelada a petición de usuario por motivo:  " .$_POST['razon']).".', estatus='4', idaprobado='" . $_SESSION['padron_admin_id'] . "' WHERE id ='" . $rowincidencia['id'] . "'";
                                if (mysql_query($query, $con)) {
                                    if (mysql_affected_rows() > 0) {
                                        $mailstatus = enviamail($rowusuario['email'], utf8_decode($rowusuario['nombre'] . " " . $rowusuario['apaterno'] . " " . $rowusuario['amaterno']), utf8_decode("Incidencia cancelada por su secretario(a) designado(a)."), utf8_decode("Incidencia cancelada por su secretario(a) desginado(a) . Contacte a su secretario(a) para obtener mas información</br></br>Datos de la incidencia:</br>Id: " . $rowincidencia['id'] . "</br>Fecha: " . $rowincidencia['fecha'] . "</br>Descripción: " . $rowincidencia['descripcion']));
                                        echo "Success|" . $mailstatus;
                                        exit();
                                    } else {
                                        echo "Fail|Ninguna incidencia fue cambiada.";
                                        exit();
                                    }
                                } else {
                                    echo "Fail|Error en el query: " + $query + "\n" . mysql_error();
                                    exit();
                                }
                            } else {
                                echo "Fail|No tienes permiso para cancelar esta incidencia, pide a tu jefe que lo haga.";
                                exit();
                            }
                        } else {
                            echo "Fail|Error en el query: " + $query + "\n" . mysql_error();
                            exit();
                        }
                    } elseif ($rowincidencia['estatus'] == 2) {
                        if (esAdminIncidencias($_SESSION['padron_admin_id']) || $_SESSION['padron_admin_permisos'] == 1) {
                            if ($rowincidencia['concepto'] == 4) {
                                $restante = $rowusuario['horasindicato'] + $rowincidencia['horas'];
                                $query = "UPDATE $trabajadores SET horasindicato = '$restante' WHERE id = '" . $rowincidencia['idusuario'] . "'";
                                if (mysql_query($query, $con)) {
                                    $query = "UPDATE $incidencias SET historial='" . $rowincidencia['historial']. "\n\n" . date("Y-m-d H:i:s") . " -- Incidencia cancelada por oficina de nominas.\nRazon: " . $_POST['razon'] . "', estatus='4', idnominas = '" . $_SESSION['padron_admin_id'] . "' WHERE id ='" . $rowincidencia['id'] . "'";
                                    if (mysql_query($query, $con)) {
                                        if (mysql_affected_rows() > 0) {
                                            //echo "Success|Incidencia actualizada correctamente.";
                                            $mailstatus = enviamail($rowusuario['email'], utf8_decode($rowusuario['nombre'] . " " . $rowusuario['apaterno'] . " " . $rowusuario['amaterno']), utf8_decode("Incidencia cancelada por oficina de nominas."), utf8_decode("Incidencia cancelada por oficina de nominas. Contacte a su oficina de nominas para obtener mas información</br></br>Datos de la incidencia:</br>Id: " . $rowincidencia['id'] . "</br>Fecha: " . $rowincidencia['fecha'] . "</br>Descripción: " . $rowincidencia['descripcion']));
                                            echo "Success|" . $mailstatus;
                                            exit();
                                        } else {
                                            echo "Fail|Ninguna incidencia fue cambiada.";
                                            exit();
                                        }
                                    } else {
                                        echo "Fail|Error en el query: " + $query + "\n" . mysql_error();
                                        exit();
                                    }
                                } else {
                                    echo "Fail|Error en el query: " . $query . "\nError: " . mysql_error();
                                    exit();
                                }
                            } elseif ($rowincidencia['concepto'] == 1) {
                                $restante = $rowusuario['diaseconomicos'] + 1;
                                $query = "UPDATE $trabajadores SET diaseconomicos = '$restante' WHERE id = '" . $rowincidencia['idusuario'] . "'";
                                if (mysql_query($query, $con)) {
                                    $query = "UPDATE $incidencias SET historial='" . $rowincidencia['historial']. "\n\n" . date("Y-m-d H:i:s") . " -- Incidencia cancelada por oficina de nominas.\nRazon: " . $_POST['razon'] . " Se agrega un día económico, Habia: " . $rowusuario['diaseconomicos'] . ", Restantes: $restante', estatus='4', idnominas = '" . $_SESSION['padron_admin_id'] . "' WHERE id ='" . $rowincidencia['id'] . "'";
                                    if (mysql_query($query, $con)) {
                                        if (mysql_affected_rows() > 0) {
                                            //echo "Success|Incidencia actualizada correctamente.";
                                            $mailstatus = enviamail($rowusuario['email'], utf8_decode($rowusuario['nombre'] . " " . $rowusuario['apaterno'] . " " . $rowusuario['amaterno']), utf8_decode("Incidencia cancelada por oficina de nominas."), utf8_decode("Incidencia cancelada por oficina de nominas. Contacte a su oficina de nominas para obtener mas información</br></br>Datos de la incidencia:</br>Id: " . $rowincidencia['id'] . "</br>Fecha: " . $rowincidencia['fecha'] . "</br>Descripción: " . $rowincidencia['descripcion']));
                                            echo "Success|" . $mailstatus;
                                            exit();
                                        } else {
                                            echo "Fail|Ninguna incidencia fue cambiada.";
                                            exit();
                                        }
                                    } else {
                                        echo "Fail|Error en el query: " + $query + "\n" . mysql_error();
                                        exit();
                                    }
                                } else {
                                    echo "Fail|Error en el query: " . $query . "\nError: " . mysql_error();
                                    exit();
                                }
                            } else {
                                $query = "UPDATE $incidencias SET historial='" . $rowincidencia['historial']. "\n\n" . date("Y-m-d H:i:s") . " -- Incidencia cancelada por oficina de nominas.\nRazon: " . $_POST['razon'] . "', estatus='4', idnominas = '" . $_SESSION['padron_admin_id'] . "' WHERE id ='" . $rowincidencia['id'] . "'";
                                if (mysql_query($query, $con)) {
                                    if (mysql_affected_rows() > 0) {
                                        //echo "Success|Incidencia actualizada correctamente.";
                                        $mailstatus = enviamail($rowusuario['email'], utf8_decode($rowusuario['nombre'] . " " . $rowusuario['apaterno'] . " " . $rowusuario['amaterno']), utf8_decode("Incidencia cancelada por oficina de nominas."), utf8_decode("Incidencia cancelada por oficina de nominas. Contacte a su oficina de nominas para obtener mas información</br></br>Datos de la incidencia:</br>Id: " . $rowincidencia['id'] . "</br>Fecha: " . $rowincidencia['fecha'] . "</br>Descripción: " . $rowincidencia['descripcion']));
                                        echo "Success|" . $mailstatus;
                                        exit();
                                    } else {
                                        echo "Fail|Ninguna incidencia fue cambiada.";
                                        exit();
                                    }
                                } else {
                                    echo "Fail|Error en el query: " + $query + "\n" . mysql_error();
                                    exit();
                                }
                            }
                        } else {
                            echo "Fail|No tienes permiso para cancelar esta incidencia, esta siendo revisada por la oficina de nominas.";
                            exit();
                        }
                    } else {
                        if (esAdminIncidencias($_SESSION['padron_admin_id']) || $_SESSION['padron_admin_permisos'] == 1) {
                            if ($rowincidencia['concepto'] == 4) {
                                $restante = $rowusuario['horasindicato'] + $rowincidencia['horas'];
                                $query = "UPDATE $trabajadores SET horasindicato = '$restante' WHERE id = '" . $rowincidencia['idusuario'] . "'";
                                if (mysql_query($query, $con)) {
                                    $query = "UPDATE $incidencias SET historial='" . $rowincidencia['historial']. "\n\n" . date("Y-m-d H:i:s") . " -- Incidencia cancelada por oficina de nominas.\nRazon: " . $_POST['razon'] . "', estatus='4', idnominas = '" . $_SESSION['padron_admin_id'] . "' WHERE id ='" . $rowincidencia['id'] . "'";
                                    if (mysql_query($query, $con)) {
                                        if (mysql_affected_rows() > 0) {
                                            //echo "Success|Incidencia actualizada correctamente.";
                                            $mailstatus = enviamail($rowusuario['email'], utf8_decode($rowusuario['nombre'] . " " . $rowusuario['apaterno'] . " " . $rowusuario['amaterno']), utf8_decode("Incidencia cancelada por oficina de nominas."), utf8_decode("Incidencia cancelada por oficina de nominas. Contacte a su oficina de nominas para obtener mas información</br></br>Datos de la incidencia:</br>Id: " . $rowincidencia['id'] . "</br>Fecha: " . $rowincidencia['fecha'] . "</br>Descripción: " . $rowincidencia['descripcion']));
                                            echo "Success|" . $mailstatus;
                                            exit();
                                        } else {
                                            echo "Fail|Ninguna incidencia fue cambiada.";
                                            exit();
                                        }
                                    } else {
                                        echo "Fail|Error en el query: " + $query + "\n" . mysql_error();
                                        exit();
                                    }
                                } else {
                                    echo "Fail|Error en el query: " . $query . "\nError: " . mysql_error();
                                    exit();
                                }
                            } elseif ($rowincidencia['concepto'] == 1) {
                                $restante = $rowusuario['diaseconomicos'] + 1;
                                $query = "UPDATE $trabajadores SET diaseconomicos = '$restante' WHERE id = '" . $rowincidencia['idusuario'] . "'";
                                if (mysql_query($query, $con)) {
                                    $query = "UPDATE $incidencias SET historial='" . $rowincidencia['historial']. "\n\n" . date("Y-m-d H:i:s") . " -- Incidencia cancelada por oficina de nominas.\nRazon: " . $_POST['razon'] . "', estatus='4', idnominas = '" . $_SESSION['padron_admin_id'] . "' WHERE id ='" . $rowincidencia['id'] . "'";
                                    if (mysql_query($query, $con)) {
                                        if (mysql_affected_rows() > 0) {
                                            //echo "Success|Incidencia actualizada correctamente.";
                                            $mailstatus = enviamail($rowusuario['email'], utf8_decode($rowusuario['nombre'] . " " . $rowusuario['apaterno'] . " " . $rowusuario['amaterno']), utf8_decode("Incidencia cancelada por oficina de nominas."), utf8_decode("Incidencia cancelada por oficina de nominas. Contacte a su oficina de nominas para obtener mas información</br></br>Datos de la incidencia:</br>Id: " . $rowincidencia['id'] . "</br>Fecha: " . $rowincidencia['fecha'] . "</br>Descripción: " . $rowincidencia['descripcion']));
                                            echo "Success|" . $mailstatus;
                                            exit();
                                        } else {
                                            echo "Fail|Ninguna incidencia fue cambiada.";
                                            exit();
                                        }
                                    } else {
                                        echo "Fail|Error en el query: " + $query + "\n" . mysql_error();
                                        exit();
                                    }
                                } else {
                                    echo "Fail|Error en el query: " . $query . "\nError: " . mysql_error();
                                    exit();
                                }
                            } else {
                                $query = "UPDATE $incidencias SET historial='" . $rowincidencia['historial']. "\n\n" . date("Y-m-d H:i:s") . " -- Incidencia cancelada por oficina de nominas.\nRazon: " . $_POST['razon'] . "', estatus='4', idnominas = '" . $_SESSION['padron_admin_id'] . "' WHERE id ='" . $rowincidencia['id'] . "'";
                                if (mysql_query($query, $con)) {
                                    if (mysql_affected_rows() > 0) {
                                        //echo "Success|Incidencia actualizada correctamente.";
                                        $mailstatus = enviamail($rowusuario['email'], utf8_decode($rowusuario['nombre'] . " " . $rowusuario['apaterno'] . " " . $rowusuario['amaterno']), utf8_decode("Incidencia cancelada por oficina de nominas."), utf8_decode("Incidencia cancelada por oficina de nominas. Contacte a su oficina de nominas para obtener mas información</br></br>Datos de la incidencia:</br>Id: " . $rowincidencia['id'] . "</br>Fecha: " . $rowincidencia['fecha'] . "</br>Descripción: " . $rowincidencia['descripcion']));
                                        echo "Success|" . $mailstatus;
                                        exit();
                                    } else {
                                        echo "Fail|Ninguna incidencia fue cambiada.";
                                        exit();
                                    }
                                } else {
                                    echo "Fail|Error en el query: " + $query + "\n" . mysql_error();
                                    exit();
                                }
                            }
                        } else {
                            echo "Fail|No tienes permiso para cancelar esta incidencia, esta siendo revisada por la oficina de nominas.";
                            exit();
                        }
                        echo "Fail|Incidencia terminada y procesada.";
                        exit();
                    }
                } else {
                    echo "Fail|No existe el usuario que registro dicha incidencia.";
                    exit();
                }
            } else {
                echo "Fail|Error en el query: " + $query + "\n" . mysql_error();
                exit();
            }
        } else {
            echo "Fail|No existe la incidencia.";
            exit();
        }
    } else {
        echo "Fail|Error en el query: " + $query + "\n" . mysql_error();
        exit();
    }
?>