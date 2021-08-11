<?php
require_once("../../funciones.php");

iniciar_sesion(0);
date_default_timezone_set('America/Mexico_City');
$con = conectar();


$query = "SELECT iddifzapopan, fechacaptura, curp, nombre, apaterno, amaterno, fechanacimiento, sexo, calle, numext, 
numint, codigopostal, colonia, municipio, estado, estatus, id FROM $vw_personas WHERE (CONCAT_WS(' ',nombre,apaterno,amaterno) LIKE '%" . $_POST['texto'] . "%'
or CONCAT_WS(' ',apaterno,amaterno,nombre) LIKE '%" . $_POST['texto'] . "%'
or iddifzapopan LIKE '%" . $_POST['texto'] . "%')
order by apaterno asc, amaterno asc, nombre asc
limit 0, 10;";


$finalstr = "";
if ($result = mysql_query($query, $con)) {
    while ($row = mysql_fetch_array($result)) {
        if ($row['estatus'] == "Activo") {
            $output = "<tr class='success' id='" . $row['id'] . "'>";
        } else {
            $output = "<tr class='danger' id='" . $row['id'] . "'>";
        }
        $output .= "<td style='vertical-align: middle'>" . $row['iddifzapopan'] . "</td>
                    <td style='vertical-align: middle'>" . $row['fechacaptura'] . "</td>
                    <td style='vertical-align: middle'>" . $row['curp'] . "</td>
                    <td style='vertical-align: middle'>" . $row['nombre'] . " " . $row['apaterno'] . " " . $row['amaterno'] . "</td>
                    <td style='vertical-align: middle'>" . $row['fechanacimiento'] . "</td>
                    <td style='vertical-align: middle'>" . $row['sexo'] . "</td>
                    <td style='vertical-align: middle'>" . $row['calle'] . " " . $row['numext'] . "numint, " . $row['codigopostal'] . ", " . $row['colonia'] . ", " . $row['municipio'] . ", " . $row['estado'] . "</td>";
        if ($row['numint'] == "0") {
            $output = str_replace("numint", "", $output);
        } else {
            $output = str_replace("numint", "-" . $row['numint'], $output);
        }
        if ($_SESSION['padron_admin_permisos'] <= 5) {
            $output .= "<td>botoncito
                                                    <a onclick='editarclick(" . $row['id'] . ", event);' class='btn btn-primary' style='width: 100%;'><span class='glyphicon glyphicon-edit'></span> Editar</a>
                                                </td></tr>";
            if ($row['estatus'] == "Activo") {
                $output = str_replace("botoncito", "<a onclick='cambiarestatus(" . $row['id'] . ", event);' class='btn btn-danger' style='width: 100%;'><span class='glyphicon glyphicon-ban-circle'></span> Deshabilitar</a>", $output);
            } else {
                $output = str_replace("botoncito", "<a onclick='cambiarestatus(" . $row['id'] . ", event);' class='btn btn-success' style='width: 100%;'><span class='glyphicon glyphicon-ok-circle'></span> Habilitar</a>", $output);
            }
        } else {
            $output .= "<td style='vertical-align: middle'>Sin Permisos</td></tr>";
        }
        $finalstr .= $output;
    }
} else {
    echo "</br></br>ERROR:</br>";
    echo (mysql_error());
}
echo $finalstr;
