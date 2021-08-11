<?php
    require_once("../../../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    $jsondata = array();
    
    if (isset($_POST['razon'])) {
        $razon = $_POST['razon'];
    } else {
        $jsondata['mensaje'] = "No enviaste nada";
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    }
    
    $output = "<option value='-1' selected disabled>---Seleccione tipo---</option>";
    if ($razon == 0) {
        $query = "SELECT * FROM $tipo_despensa WHERE padre = '1' OR id = '1' ORDER BY id";
    } else {
        $query = "SELECT * FROM $tipo_despensa WHERE padre != '1' AND id != '1' ORDER BY id";
    }
    $flag = false;
    $catalogo = array();
    if ($result = mysql_query($query, $con)) {
        $jsondata['mensaje'] = "Exito, se encontraron " . mysql_num_rows($result) . " tipos de despensa";
        while ($row = mysql_fetch_row($result)) {
            $catalogo[] = $row;
        }
        for ($i = 0; $i < count($catalogo); $i++) { 
            if ($catalogo[$i][2] == 0) {
                for ($j = 0; $j < count($catalogo); $j++) { 
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
                if ($flag) {
                    $output .= "</optgroup>";
                    $flag = false;
                } else {
                    $output .= "<option value = '" . $catalogo[$i][0] . "'>" . $catalogo[$i][1] . "</option>";
                }
            }
        }
        $jsondata['html'] = $output;
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    } else {
        $jsondata['html'] = "<option>Error en el query: $query</option>";
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
        exit();
    }
?>