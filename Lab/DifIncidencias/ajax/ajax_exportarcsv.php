<?php
    include("../../funciones.php");
    $con=conectar();
    iniciar_sesion(3);
    
    //var_dump($_POST);
    
    $count = 0;
    $query = "SELECT * FROM $vw_exportar_incidencias WHERE fecha >='" . mysql_real_escape_string($_POST['exportarinicio']) . "' AND fecha <= '" . mysql_real_escape_string($_POST['exportarfin']) . "'";
    if ($_POST['aprobadas'] == "on" || $_POST['finalizadas'] == "on" || $_POST['creadas']  == "on" || $_POST['canceladas']  == "on") {
        $query .= " AND estatus IN(";
        if ($_POST['aprobadas'] == "on"){
            if ($count <= 0) {
                $query .= "'APROBADA POR JEFE DE DEPARTAMENTO O DIRECTOR'";
                $count ++;
            } else {
                $query .= ", 'APROBADA POR JEFE DE DEPARTAMENTO O DIRECTOR'";
            }
        }
        if ($_POST['finalizadas'] == "on") {
            if ($count <= 0) {
                $query .= "'APROBADA POR NOMINAS'";
                $count ++;
            } else {
                $query .= ", 'APROBADA POR NOMINAS'";
            }
        }
        if ($_POST['creadas']  == "on") {
            if ($count <= 0) {
                $query .= "'CREADA'";
                $count ++;
            } else {
                $query .= ", 'CREADA'";
            }
        }
        if ($_POST['canceladas']  == "on") {
            if ($count <= 0) {
                $query .= "'CANCELADA'";
                $count ++;
            } else {
                $query .= ", 'CANCELADA'";
            }
        }
        $query .= ")";
    }
    
    /*echo "</br>";
    echo $query;*/
    
    $result = mysql_query($query, $con);
    if (!$result) die('Couldn\'t fetch records');
    $num_fields = mysql_num_fields($result);
    $headers = array();
    for ($i = 0; $i < $num_fields; $i++) {
        $headers[] = mysql_field_name($result , $i);
    }
    
    //echo "</br>";
    //var_dump($row = mysql_fetch_array($result));
    
    $fp = fopen('php://output', 'w');
    if ($fp && $result) {
        header('Content-Type: text/csv');
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
?>