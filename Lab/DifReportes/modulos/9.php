<?php
    require_once("../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    /*echo "POST:</br>";
    var_dump($_POST);*/
    
    if (!in_array($_SESSION['padron_admin_id'], $reportes_planeacion)) {
        echo "No tienes los permisos para generar este reporte";
        die;
    }
    
    $query = "SELECT
              $servicios.servicio AS servicio,
              $departamentos.departamento,
              count(*) AS total,
              count(CASE WHEN timestampdiff(YEAR, $personas.fechanacimiento, curdate()) < 18 AND $personas.sexo = 1 THEN 1 END) AS menoreshombres,
              count(CASE WHEN timestampdiff(YEAR, $personas.fechanacimiento, curdate()) < 18 AND $personas.sexo = 2 THEN 1 END) AS menoresmujeres,
              count(CASE WHEN timestampdiff(YEAR, $personas.fechanacimiento, curdate()) > 17 AND timestampdiff(YEAR, $personas.fechanacimiento, curdate()) < 65 AND $personas.sexo = 1 THEN 1 END) AS adultoshombres,
              count(CASE WHEN timestampdiff(YEAR, $personas.fechanacimiento, curdate()) > 17 AND timestampdiff(YEAR, $personas.fechanacimiento, curdate()) < 65 AND $personas.sexo = 2 THEN 1 END) AS adultosmujeres,
              count(CASE WHEN timestampdiff(YEAR, $personas.fechanacimiento, curdate()) > 64 AND $personas.sexo = 1 THEN 1 END) AS adultosmayoreshombres,
              count(CASE WHEN timestampdiff(YEAR, $personas.fechanacimiento, curdate()) > 64 AND $personas.sexo = 2 THEN 1 END) AS adultosmayoresmujeres
              FROM $servicios_otorgados 
              LEFT JOIN $servicios ON $servicios.id = $servicios_otorgados.servicio_otorgado
              LEFT JOIN $personas ON $personas.id = $servicios_otorgados.idpersona
              LEFT JOIN $departamentos ON $departamentos.id = $servicios.iddepartamento";
              
    if ($_POST['fechainicio'] != "" && $_POST['fechafin'] != "") { //GROUP BY servicio ORDER BY total DESC";
        $query .= " WHERE fecha >= '" . $_POST['fechainicio'] . "' AND fecha <= '" . $_POST['fechafin'] . "' GROUP BY servicio ORDER BY total DESC";
    } elseif ($_POST['fechainicio'] != "" && $_POST['fechafin'] == "") {
        $query .= " WHERE fecha >= '" . $_POST['fechainicio'] . "' GROUP BY servicio ORDER BY total DESC";
    } elseif ($_POST['fechainicio'] == "" && $_POST['fechafin'] != "") {
        $query .= " WHERE fecha <= '" . $_POST['fechafin'] . "' GROUP BY servicio ORDER BY total DESC";
    } else {
        $query .= " GROUP BY servicio ORDER BY total DESC";
    }
    
    //echo "</br></br>QUERY: " . $query;
    
    if ($result = mysql_query($query, $con)){
        /*echo "</br></br>";
        var_dump($result);*/
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
            fputs($fp, $bom = ( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
            fputcsv($fp, $headers);
            while ($row = mysql_fetch_row($result)) {
                fputcsv($fp, array_values($row));
            }
            die;
        }
    } else {
        echo " Error en el query: " . $query . "</br> Error: " . mysql_error();
    }
?>