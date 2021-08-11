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
                                $query = "UPDATE $incidencias SET historial='" . $rowincidencia['historial'] . "\n\n" . date("Y-m-d H:i:s") . " -- Incidencia rechazada por Jefe de departamento o director.' WHERE id ='" . $rowincidencia['id'] . "'";
                                if (mysql_query($query, $con)) {
                                    if (mysql_affected_rows() > 0) {
                                        echo "Success|Incidencia actualizada correctamente.";
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
                                echo "Fail|No tienes permiso para rechazar esta incidencia, pide a tu jefe que lo haga.";
                                exit();
                            }                            
                        } else {
                            echo "Fail|Error en el query: " + $query + "\n" . mysql_error();
                            exit();
                        }
                    } elseif ($rowincidencia['estatus'] == 2) {
                        if ($_SESSION['padron_admin_id'] == $admin_incidencias) {
                            $query = "UPDATE $incidencias SET historial='" . $rowincidencia['historial'] . "\n\n" . date("Y-m-d H:i:s") . " -- Incidencia rechazada por oficina de nominas.\nRazon: " . $_POST['razon'] . "', estatus='1', idaprobado='' WHERE id ='" . $rowincidencia['id'] . "'";
                            if (mysql_query($query, $con)) {
                                if (mysql_affected_rows() > 0) {
                                    $mailstatus = ""; //enviamail($rowusuario['email'], utf8_decode($rowusuario['nombre'] . " " . $rowusuario['apaterno'] . " " . $rowusuario['amaterno']), utf8_decode("Incidencia rechazada por oficina de nominas."), utf8_decode("Incidencia rechazada por oficina de nominas. Favor de editarla y pedir a su jefe que la apruebe de nuevo.</br></br>Razon del rechazo: " . $_POST['razon'] . "</br></br>Datos de la incidencia:</br>Id: " . $rowincidencia['id'] . "</br>Fecha: " . $rowincidencia['fecha'] . "</br>Descripción: " . $rowincidencia['descripcion']));
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