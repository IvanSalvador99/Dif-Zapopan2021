<?php
    include("../../funciones.php");
    $con=conectar();
    iniciar_sesion(3);
    $jsondata = array();
    
    if (!isset($_POST['idincidencia'])) {
        echo "No enviaste nada";
        exit();
    }
    
    $query = "SELECT * FROM $incidencias WHERE id = '" . $_POST['idincidencia'] . "'";
    if ($result = mysql_query($query, $con)){
        if (mysql_num_rows($result) > 0){
            $rowincidencia = mysql_fetch_array($result);
            $query = "SELECT * FROM $trabajadores WHERE id = '" . $rowincidencia['idusuario'] . "'";
            if ($result = mysql_query($query, $con)) {
                if (mysql_num_rows($result) > 0) {
                    $rowusuario = mysql_fetch_array($result);
                    $idjefe = $_SESSION['padron_admin_id'];
                    $permitido = false;
                    if ($rowincidencia['estatus'] == 1) {
                        $query = "SELECT * FROM $incidencia_jefes WHERE idjefe = $idjefe";
                        if ($result = mysql_query($query, $con)) {
                            $rowvalidos = mysql_fetch_assoc($result);
                            $validos = explode(",", $rowvalidos['validos']);
                            foreach ($validos as $valido) {
                                if ($rowusuario['id'] == $valido) {
                                    $permitido = true;
                                }
                            }
                            if ($permitido && $rowusuario['id'] != $_SESSION['padron_admin_id']) {
                                $query = "UPDATE $incidencias SET historial='" . mysql_real_escape_string($rowincidencia['historial']) . "\n\n" . date("Y-m-d H:i:s") . " -- Incidencia aprobada por Jefe de departamento o director.', estatus='2', idaprobado='" . $_SESSION['padron_admin_id'] . "' WHERE id ='" . $rowincidencia['id'] . "'";
                                if (mysql_query($query, $con)) {
                                    if (mysql_affected_rows() > 0) {
                                        echo "Success|Incidencia actualizada correctamente.";
                                        exit();
                                    } else {
                                        echo "Fail|Ninguna incidencia fue cambiada.";
                                        exit();
                                    }
                                } else {
                                    echo "Fail|Error en el query: " . $query . "\nError: " . mysql_error();
                                    exit();
                                }
                            } else {
                                echo "Fail|No tienes permiso para aceptar esta incidencia.";
                                exit();
                            }
                        } else {
                            echo "Fail|Error en el query: " . $query . "\nError: " . mysql_error();
                            exit();
                        }
                    } elseif ($rowincidencia['estatus'] == 2) {
                        if ($_SESSION['padron_admin_id'] == $admin_incidencias || $_SESSION['padron_admin_permisos'] == 1) {
                            if ($rowincidencia['concepto'] == 4) {
                                $restante = $rowusuario['horasindicato'] - $rowincidencia['horas'];
                                if ($restante >= 0) {
                                    $query = "UPDATE $trabajadores SET horasindicato = '$restante' WHERE id = '" . $rowincidencia['idusuario'] . "'";
                                    if (mysql_query($query, $con)) {
                                        $query = "UPDATE $incidencias SET historial='" . mysql_real_escape_string($rowincidencia['historial']) . "\n\n" . date("Y-m-d H:i:s") . " -- Incidencia aprobada por oficina de nominas.', estatus='3' WHERE id ='" . $rowincidencia['id'] . "'";
                                        if (mysql_query($query, $con)) {
                                            if (mysql_affected_rows() > 0) {
                                                $mailstatus = ""; //enviamail($rowusuario['email'], utf8_decode($rowusuario['nombre'] . " " . $rowusuario['apaterno'] . " " . $rowusuario['amaterno']), utf8_decode("Incidencia aprobada por oficina de nominas."), utf8_decode("Incidencia aprobada por oficina de nominas.</br></br>Datos de la incidencia:</br>Id: " . $rowincidencia['id'] . "</br>Fecha: " . $rowincidencia['fecha'] . "</br>Descripción: " . $rowincidencia['descripcion'] . "</br></br>Si necesita comprobante escrito acuda a la oficina de nominas."));
                                                echo "Success|" . $mailstatus;
                                                exit();
                                            } else {
                                                echo "Fail|Ninguna incidencia fue cambiada.";
                                                exit();
                                            }
                                        } else {
                                            echo "Fail|Error en el query: " . $query . "\n" . mysql_error();
                                            exit();
                                        }
                                    } else {
                                        echo "Fail|Error en el query: " . $query . "\n" . mysql_error();
                                        exit();
                                    }
                                } else {
                                    echo "Fail|El usuario no cuenta con horas suficientes";
                                    exit();
                                }
                            } else {
                                $query = "UPDATE $incidencias SET historial='" . mysql_real_escape_string($rowincidencia['historial']) . "\n\n" . date("Y-m-d H:i:s") . " -- Incidencia aprobada por oficina de nominas.', estatus='3' WHERE id ='" . $rowincidencia['id'] . "'";
                                if (mysql_query($query, $con)) {
                                    if (mysql_affected_rows() > 0) {
                                        $mailstatus = ""; //enviamail($rowusuario['email'], utf8_decode($rowusuario['nombre'] . " " . $rowusuario['apaterno'] . " " . $rowusuario['amaterno']), utf8_decode("Incidencia aprobada por oficina de nominas."), utf8_decode("Incidencia aprobada por oficina de nominas.</br></br>Datos de la incidencia:</br>Id: " . $rowincidencia['id'] . "</br>Fecha: " . $rowincidencia['fecha'] . "</br>Descripción: " . $rowincidencia['descripcion'] . "</br></br>Si necesita comprobante escrito acuda a la oficina de nominas."));
                                        echo "Success|" . $mailstatus;
                                        exit();
                                    } else {
                                        echo "Fail|Ninguna incidencia fue cambiada.";
                                        exit();
                                    }
                                } else {
                                    echo "Fail|Error en el query: " . $query . "\n" . mysql_error();
                                    exit();
                                }
                            }
                        } else {
                            echo "Fail|No tienes permiso para aceptar esta incidencia, esta siendo revisada por la oficina de nominas.";
                            exit();
                        }
                    } else {
                        echo "Fail|Incidencia terminada y procesada.";
                        exit();
                    }
                } else {
                    echo "Fail|No existe el usuario que registro dicha incidencia.";
                    exit();
                }
            } else {
                echo "Fail|Error en el query: " . $query . "\n" . mysql_error();
                exit();
            }
        } else {
            echo "Fail|No existe la incidencia.";
            exit();
        }
    } else {
        echo "Fail|Error en el query: " . $query . "\n" . mysql_error();
        exit();
    }
?>