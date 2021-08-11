<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    /*echo "POST:</br>";
    var_dump($_POST);*/
    
    
    if ($_POST['fechainicio'] != "" && $_POST['fechafin'] != "") {
        $query = "SELECT * FROM $reporte_escpadres_difjalisco WHERE fecha >='" . $_POST['fechainicio'] . "' AND fecha <='" . $_POST['fechafin'] . "'";
    } elseif ($_POST['fechainicio'] != "" && $_POST['fechafin'] == "") {
        $query = "SELECT * FROM $reporte_escpadres_difjalisco WHERE fecha >='" . $_POST['fechainicio'] . "'";
    } elseif ($_POST['fechainicio'] == "" && $_POST['fechafin'] != "") {
        $query = "SELECT * FROM $reporte_escpadres_difjalisco WHERE fecha <='" . $_POST['fechafin'] . "'";
    } else {
        $query = "SELECT * FROM $reporte_escpadres_difjalisco";
    }
    
    /*echo "</br></br>QUERY: " . $query;*/
    
    if ($result = mysql_query($query, $con)){
        $num_fields = mysql_num_fields($result);
        $headers = array();
        for ($i = 0; $i < $num_fields; $i++) {
            $headers[] = mysql_field_name($result , $i);
        }
        
        $fp = fopen('php://output', 'w');
        if ($fp && $result && mysql_num_rows($result) > 0) {
            header('Content-Encoding: UTF-8');
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="export_' . date("Y-m-d") . '.csv"');
            header('Pragma: no-cache');
            header('Expires: 0');
            fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
            fputcsv($fp, $headers);
            while ($row = mysql_fetch_row($result)) {
                fputcsv($fp, array_values($row));
            }
            die;
        }
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
?>