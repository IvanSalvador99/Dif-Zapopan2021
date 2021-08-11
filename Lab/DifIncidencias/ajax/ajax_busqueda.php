<?php
require_once("../../funciones.php");

iniciar_sesion(0);
date_default_timezone_set('America/Mexico_City');
$con = conectar();


if ($_SESSION['padron_admin_permisos'] == 1 || esAdminIncidencias($_SESSION['padron_admin_id'])) {
    $query = "SELECT * FROM vw_incidencias WHERE (CONCAT_WS(' ',nombre,apaterno,amaterno) LIKE '%" . $_POST['texto'] . "%') and 
    fechacaptura >= '" . date("Y-m-d", strtotime("-3 months")) . "' or 
    CONCAT_WS(' ',apaterno,amaterno,nombre) LIKE '" . $_POST['texto'] . "' and
    fechacaptura >= '" . date("Y-m-d", strtotime("-3 months")) . "'  or 
    id LIKE '%" . $_POST['texto'] . "%' and fechacaptura >= '" . date("Y-m-d", strtotime("-3 months")) . "' or
    idusuario LIKE '%" . $_POST['texto'] . "%' and fechacaptura >= '" . date("Y-m-d", strtotime("-3 months")) . "' or
    numeroempleado LIKE '%" . $_POST['texto'] . "%' and fechacaptura >= '" . date("Y-m-d", strtotime("-3 months")) . "' LIMIT 0, 50;";
    if ($result = mysql_query($query, $con)) {
        $output = "";
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_array($result)) {
                $output .= "<tr id='" . $row['id'] . "'><td style='vertical-align: middle'>" . $row['id'] . "</td>";
                $output .= "<td style='vertical-align: middle'>" . $row['fechacaptura'] . "</td>";
                $output .= "<td style='vertical-align: middle'>" . $row['nombre'] . " " . $row['apaterno'] . " " . $row['amaterno'] . "</td>";
                $output .= "<td style='vertical-align: middle'>" . $row['fecha'] . "</td>";
                $output .= "<td style='vertical-align: middle'>" . $row['tipo'] . "</td>";
                $output .= "<td style='vertical-align: middle'>" . $row['concepto'] . "</td>";
                if ($row['horas'] == 0) {
                    $output .= "<td style='vertical-align: middle'>" . "NA" . "</td>";
                } else {
                    $output .= "<td style='vertical-align: middle'>" . $row['horas'] . "</td>";
                }
                $output .= "<td style='vertical-align: middle'>" . $row['estatus'] . "</td>";

                if ($_SESSION['padron_admin_permisos'] == 1) {
                    $output .= "<td style='vertical-align: middle'><a onclick='aceptarclick(" . $row['id'] . ", event)' class='btn btn-success' style='width: 100%;'><span class='glyphicon glyphicon-ok'></span> Autorizar</a>
                                            <a onclick='rechazarclick(" . $row['id'] . ", event)' class='btn btn-danger' style='width: 100%;'><span class='glyphicon glyphicon-remove'></span> Rechazar</a>
                                            <a onclick='editarclick(" . $row['id'] . ", event)' id='editar" . $row['id'] . "' class='btn btn-warning' style='width: 100%;'><span class='glyphicon glyphicon-edit'></span> Editar</a>
                                            <a onclick='cancelarclick(" . $row['id'] . ", event)' class='btn btn-primary' style='width: 100%;'><span class='glyphicon glyphicon-trash'></span> Cancelar</a>
                                            <a onclick='imprimirclick(" . $row['id'] . ", event)' class='btn btn-default' style='width: 100%;'><span class='glyphicon glyphicon-print'></span> Imprimir</a></td>";
                } else {
                    $output .= "<td style='vertical-align: middle'><a onclick='aceptarclick(" . $row['id'] . ", event)' class='btn btn-success' style='width: 100%;'><span class='glyphicon glyphicon-ok'></span> Autorizar</a>
                                            <a onclick='rechazarclick(" . $row['id'] . ", event)' class='btn btn-danger' style='width: 100%;'><span class='glyphicon glyphicon-remove'></span> Rechazar</a>
                                            <a onclick='cancelarclick(" . $row['id'] . ", event)' class='btn btn-primary' style='width: 100%;'><span class='glyphicon glyphicon-trash'></span> Cancelar</a>
                                            <a onclick='imprimirclick(" . $row['id'] . ", event)' class='btn btn-default' style='width: 100%;'><span class='glyphicon glyphicon-print'></span> Imprimir</a></td>";
                }
            }
            $output .= "</tr>";
            echo $output;
        } else {
            $output .= "<td>No hay elementos</td>";
            echo $output;
        }
    } else {
        echo (mysql_error());
    }
} elseif ($_SESSION['padron_admin_permisos'] >= 2 && $_SESSION['padron_admin_permisos'] <= 4) {
    $idjefe = $_SESSION['padron_admin_id'];
    $query = "SELECT * from $incidencia_jefes WHERE idjefe = $idjefe";
    if ($result = mysql_query($query, $con)) {
        $row = mysql_fetch_assoc($result);
        $validos = ($row['validos'] == "") ? "0" : $row['validos'];
        //echo $validos . "\n";
        $query = "SELECT * FROM $vw_incidencias WHERE (CONCAT_WS(' ',nombre,apaterno,amaterno) LIKE '%" . $_POST['texto'] . "%') and  
        (idusuario IN ($validos) OR idusuario = $idjefe) and 
            fechacaptura >= '" . date("Y-m-d", strtotime("-3 months")) . "' or 
            CONCAT_WS(' ',apaterno,amaterno,nombre) LIKE '" . $_POST['texto'] . "' and  
        (idusuario IN ($validos) OR idusuario = $idjefe) and
            fechacaptura >= '" . date("Y-m-d", strtotime("-3 months")) . "'  or 
            id LIKE '%" . $_POST['texto'] . "%' and  (idusuario IN ($validos) OR idusuario = $idjefe) and fechacaptura >= '" . date("Y-m-d", strtotime("-3 months")) . "' or
            idusuario LIKE '%" . $_POST['texto'] . "%' and fechacaptura >= '" . date("Y-m-d", strtotime("-3 months")) . "' or
            numeroempleado LIKE '%" . $_POST['texto'] . "%' and  (idusuario IN ($validos) OR idusuario = $idjefe) and fechacaptura >= '" . date("Y-m-d", strtotime("-3 months")) . "' LIMIT 0, 50";
        //$query = "SELECT * FROM $vw_incidencias WHERE (idusuario IN ($validos) OR idusuario = $idjefe) AND fechacaptura >= '" . date("Y-m-d", strtotime("-2 months")) . "'LIMIT 0, 50";
        //echo $query . "\n";
        if ($result = mysql_query($query, $con)) {
            $output = "";
            if (mysql_num_rows($result) > 0) {
                while ($row = mysql_fetch_array($result)) {
                    $output .= "<tr id='" . $row['id'] . "'><td style='vertical-align: middle'>" . $row['id'] . "</td>";
                    $output .= "<td style='vertical-align: middle'>" . $row['fechacaptura'] . "</td>";
                    $output .= "<td style='vertical-align: middle'>" . $row['nombre'] . " " . $row['apaterno'] . " " . $row['amaterno'] . "</td>";
                    $output .= "<td style='vertical-align: middle'>" . $row['fecha'] . "</td>";
                    $output .= "<td style='vertical-align: middle'>" . $row['tipo'] . "</td>";
                    $output .= "<td style='vertical-align: middle'>" . $row['concepto'] . "</td>";
                    if ($row['horas'] == 0) {
                        $output .= "<td style='vertical-align: middle'>" . "NA" . "</td>";
                    } else {
                        $output .= "<td style='vertical-align: middle'>" . $row['horas'] . "</td>";
                    }
                    $output .= "<td style='vertical-align: middle'>" . $row['estatus'] . "</td>";
                    if ($row['idusuario'] != $_SESSION['padron_admin_id']) {
                        if ($row['idestatus'] == 2 && $row['idconcepto'] == 10) {
                            $output .= "<td style='vertical-align: middle'><a onclick='aceptarclick(" . $row['id'] . ", event)' class='btn btn-success' style='width: 100%;'><span class='glyphicon glyphicon-ok'></span> Autorizar</a>
                                                    <a onclick='rechazarclick(" . $row['id'] . ", event)' class='btn btn-danger' style='width: 100%;'><span class='glyphicon glyphicon-remove'></span> Rechazar</a>
                                                    <a onclick='imprimirclick(" . $row['id'] . ", event)' class='btn btn-default' style='width: 100%;'><span class='glyphicon glyphicon-print'></span> Imprimir</a></td>";
                        } else {
                            $output .= "<td style='vertical-align: middle'><a onclick='aceptarclick(" . $row['id'] . ", event)' class='btn btn-success' style='width: 100%;'><span class='glyphicon glyphicon-ok'></span> Autorizar</a>
                                                    <a onclick='rechazarclick(" . $row['id'] . ", event)' class='btn btn-danger' style='width: 100%;'><span class='glyphicon glyphicon-remove'></span> Rechazar</a></td>";
                        }
                    } else {
                        $output .= "<td style='vertical-align: middle'></td>";
                    }
                }
                $output .= "</tr>";
                echo $output;
            } else {
                $output .= "<td>No hay elementos</td>";
                echo $output;
            }
        } else {
            echo "Error en el query: " . $query . "\nError: " . mysql_error() . "\n";
        }
    } else {
        echo "Error en el query: " . $query . "\nError: " . mysql_error() . "\n";
    }
} elseif ($_SESSION['padron_admin_permisos'] == 7) {
    $idsecretaria = $_SESSION['padron_admin_id'];
    $query = "SELECT * from $incidencia_secretarias WHERE idsecretaria = $idsecretaria";
    if ($result = mysql_query($query, $con)) {
        $row = mysql_fetch_assoc($result);
        $validos = ($row['validos'] == "") ? "0" : $row['validos'];
        //echo $validos . "\n";
        $query = "SELECT * FROM $vw_incidencias WHERE (idusuario IN ($validos) OR idusuario = $idsecretaria) AND fechacaptura >= '" . date("Y-m-d", strtotime("-2 months")) . "' LIMIT 0, 50";
        //echo $query . "\n";
        if ($result = mysql_query($query, $con)) {
            $output = "";
            if (mysql_num_rows($result) > 0) {
                while ($row = mysql_fetch_array($result)) {
                    $output .= "<tr id='" . $row['id'] . "'><td style='vertical-align: middle'>" . $row['id'] . "</td>";
                    $output .= "<td style='vertical-align: middle'>" . $row['fechacaptura'] . "</td>";
                    $output .= "<td style='vertical-align: middle'>" . $row['nombre'] . " " . $row['apaterno'] . " " . $row['amaterno'] . "</td>";
                    $output .= "<td style='vertical-align: middle'>" . $row['fecha'] . "</td>";
                    $output .= "<td style='vertical-align: middle'>" . $row['tipo'] . "</td>";
                    $output .= "<td style='vertical-align: middle'>" . $row['concepto'] . "</td>";
                    if ($row['horas'] == 0) {
                        $output .= "<td style='vertical-align: middle'>" . "NA" . "</td>";
                    } else {
                        $output .= "<td style='vertical-align: middle'>" . $row['horas'] . "</td>";
                    }
                    $output .= "<td style='vertical-align: middle'>" . $row['estatus'] . "</td>";
                    if ($row['idestatus'] == 1) {
                        $output .= "<td style='vertical-align: middle'><a onclick='editarclick(" . $row['id'] . ", event)' id='editar" . $row['id'] . "' class='btn btn-primary' style='width: 100%;'><span class='glyphicon glyphicon-edit'></span> Editar</a>
                                                <a onclick='cancelarclick(" . $row['id'] . ", event)' class='btn btn-primary' style='width: 100%;'><span class='glyphicon glyphicon-trash'></span> Cancelar</a></td>";
                    } elseif ($row['idestatus'] == 2 && $row['idconcepto'] == 10) {
                        $output .= "<td style='vertical-align: middle'><a onclick='imprimirclick(" . $row['id'] . ", event)' class='btn btn-default' style='width: 100%;'><span class='glyphicon glyphicon-print'></span> Imprimir</a></td>";
                    } else {
                        $output .= "<td></td>";
                    }
                }
                $output .= "</tr>";
                echo $output;
            } else {
                $output .= "<td>No hay elementos</td>";
                echo $output;
            }
        } else {
            echo "Error en el query: " . $query . "\nError: " . mysql_error() . "\n";
        }
    } else {
        echo "Error en el query: " . $query . "\nError: " . mysql_error() . "\n";
    }
} else {
    $query = "SELECT * FROM $vw_incidencias WHERE idusuario = '" . $_SESSION['padron_admin_id'] . "' AND fechacaptura >= '" . date("Y-m-d", strtotime("-2 months")) . "' LIMIT 0, 50";
    if ($result = mysql_query($query, $con)) {
        $output = "";
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_array($result)) {
                $output .= "<tr id='" . $row['id'] . "'><td style='vertical-align: middle'>" . $row['id'] . "</td>";
                $output .= "<td style='vertical-align: middle'>" . $row['fechacaptura'] . "</td>";
                $output .= "<td style='vertical-align: middle'>" . $row['nombre'] . " " . $row['apaterno'] . " " . $row['amaterno'] . "</td>";
                $output .= "<td style='vertical-align: middle'>" . $row['fecha'] . "</td>";
                $output .= "<td style='vertical-align: middle'>" . $row['tipo'] . "</td>";
                $output .= "<td style='vertical-align: middle'>" . $row['concepto'] . "</td>";
                if ($row['horas'] == 0) {
                    $output .= "<td style='vertical-align: middle'>" . "NA" . "</td>";
                } else {
                    $output .= "<td style='vertical-align: middle'>" . $row['horas'] . "</td>";
                }
                $output .= "<td style='vertical-align: middle'>" . $row['estatus'] . "</td><td></td>";
            }
            $output .= "</tr>";
            echo $output;
        } else {
            $output .= "<td>No hay elementos</td>";
            echo $output;
        }
    } else {
        echo (mysql_error());
    }
}
?>