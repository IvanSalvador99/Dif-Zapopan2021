<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    $jsondata = array();
    
    if (isset($_POST['departamento'])) {
        $departamento = $_POST['departamento'];
    } else {
        $jsondata['mensaje'] = "No enviaste nada";
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    }
    
    $query = "SELECT * FROM $servicios where iddepartamento = '$departamento'";
    if ($result = mysql_query($query)) {
        $output = "";
        $flag = false;
        $catalogo = array();
        $jsondata['mensaje'] = "Se encontraron " . mysql_num_rows($result) . " registros.";
        while ($row = mysql_fetch_row($result)) {
            $catalogo[] = $row;
        }
        for ($i = 0; $i < count($catalogo); $i++) { 
            if ($catalogo[$i][2] == 0) {
                for ($j = 0; $j < count($catalogo); $j++) {
                    if ($catalogo[$j][5] != 0) {
                        if ($catalogo[$j][2] == $catalogo[$i][0]) {
                            if (!$flag) {
                                $output .= "<optgroup label = '" . $catalogo[$i][1] . "'>";
                                $output .= "<option value = '" . $catalogo[$j][0] . "'>" . $catalogo[$j][1] . "</option>";
                                $flag = true;
                            } else {
                                $output .= "<option value = '" . $catalogo[$j][0] . "'>" . $catalogo[$j][1] . "</option>";
                            }
                        }
                    }
                }
                if ($flag) {
                    $output .= "</optgroup>";
                    $flag = false;
                } else {
                    if ($catalogo[$i][5] != 0) {
                        $output .= "<option value = '" . $catalogo[$i][0] . "'>" . $catalogo[$i][1] . "</option>";
                    }
                }
            }
        }
        $jsondata['html'] = $output;
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    } else {
        $jsondata['mensaje'] = "Error en el query: " . $query . ", Error: " . mysql_error();
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    }
?>