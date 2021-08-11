<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    /*echo "POST:</br>";
    var_dump($_POST);*/
    
    $query = "SELECT * FROM $reporte_servicios_general WHERE servicio_otorgado IN ('171', '172', '173')";
    
    if ($_POST['fechainicio'] != "") {
        $query .= " AND fechaservicio >= '" . $_POST['fechainicio'] . "'";
    } 
    if ($_POST['fechafin'] != "") {
        $query .= " AND fechaservicio <= '" . $_POST['fechafin'] . "'";
    }
    if ($_POST['servicio'] != "") {
        $query .= " AND servicio_otorgado = '" . $_POST['servicio'] . "'";
    }
    if ($_POST['locacion'] != "") {
        $query .= " AND locacion = '" . $_POST['locacion'] . "'";
    }
    if ($_POST['edadmin'] != "") {
        $fechaedad = date("Y-m-d", strtotime("-" . abs($_POST['edadmin']) . " year"));
        $query .= " AND fechanacimiento <= '" . $fechaedad . "'";
    }
    if ($_POST['edadmax'] != "") {
        $fechaedad = date("Y-m-d", strtotime("-" . abs($_POST['edadmax']) . " year"));
        $query .= " AND fechanacimiento >= '" . $fechaedad . "'";
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
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
        die;
    }
?>