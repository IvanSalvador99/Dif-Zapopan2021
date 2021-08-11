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
              persona_servicios_otorgados.locacion AS idlocacion,
              persona_cat_locacion.locacion,
              COUNT(*) AS totalservicios,
              COUNT(DISTINCT persona_servicios_otorgados.idpersona) AS totalpersonas
              FROM persona_servicios_otorgados
              LEFT JOIN persona_cat_locacion ON persona_cat_locacion.id = persona_servicios_otorgados.locacion";
    
    
    if ($_POST['fechainicio'] != "" && $_POST['fechafin'] != "") { // GROUP BY locacion ORDER BY total DESC
        $query .= " WHERE fecha >= '" . $_POST['fechainicio'] . "' AND fecha <='" . $_POST['fechafin'] . "' GROUP BY locacion ORDER BY idlocacion";
    } elseif ($_POST['fechainicio'] != "" && $_POST['fechafin'] == "") {
        $query .= " WHERE fecha >= '" . $_POST['fechainicio'] . "' GROUP BY locacion ORDER BY idlocacion";
    } elseif ($_POST['fechainicio'] == "" && $_POST['fechafin'] != "") {
        $query .= " WHERE fecha <= '" . $_POST['fechafin'] . "' GROUP BY locacion ORDER BY idlocacion";
    } else {
        $query .= " GROUP BY locacion ORDER BY idlocacion";
    }
    
    //echo "</br></br>QUERY: " . $query;
    
    if ($result = mysql_query($query, $con)) {
            
        echo "\xEF\xBB\xBF";
        $fp = fopen('php://output', 'w');
        if ($fp) {
            header('Content-Encoding: UTF-8');
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="export_' . date("Y-m-d") . '.csv"');
            header("Content-Transfer-Encoding: UTF-8");
            header('Pragma: no-cache');
            header('Expires: 0');
            fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
            /*fputcsv($fp, $headers);
            while ($row = mysql_fetch_row($result)) {
                fputcsv($fp, array_values($row));
            }
            die;*/
        }
        
        while ($rowtotal = mysql_fetch_assoc($result)) {
            
            fputs($fp, "Locación,Total de servicios, Total de personas" . PHP_EOL);
            fputs($fp, $rowtotal['locacion']. "," . $rowtotal['totalservicios'] . "," . $rowtotal['totalpersonas']);
            fputs($fp, PHP_EOL);
            fputs($fp, PHP_EOL);
            
            $query = "SELECT
                      persona_cat_servicio.servicio AS servicionombre,
                      COUNT(*) AS total
                      FROM persona_servicios_otorgados
                      LEFT JOIN persona_cat_servicio ON persona_cat_servicio.id = persona_servicios_otorgados.servicio_otorgado";
                      
            if ($_POST['fechainicio'] != "" && $_POST['fechafin'] != "") { // GROUP BY locacion ORDER BY total DESC
                $query .= " WHERE locacion = '" . $rowtotal['idlocacion'] . "' AND fecha >= '" . $_POST['fechainicio'] . "' AND fecha <='" . $_POST['fechafin'] . "' GROUP BY servicionombre ORDER BY total DESC";
            } elseif ($_POST['fechainicio'] != "" && $_POST['fechafin'] == "") {
                $query .= " WHERE locacion = '" . $rowtotal['idlocacion'] . "' AND fecha >= '" . $_POST['fechainicio'] . "' GROUP BY servicionombre ORDER BY total DESC";
            } elseif ($_POST['fechainicio'] == "" && $_POST['fechafin'] != "") {
                $query .= " WHERE locacion = '" . $rowtotal['idlocacion'] . "' AND fecha <= '" . $_POST['fechafin'] . "' GROUP BY servicionombre ORDER BY total DESC";
            } else {
                $query .= " WHERE locacion = '" . $rowtotal['idlocacion'] . "' GROUP BY servicionombre ORDER BY total DESC";
            }    
            
            //echo "</br></br>QUERY: " . $query;
            
            if ($result1 = mysql_query($query, $con)) {
                fputs($fp, "Tipo de servicio, Cantidad de servicios" . PHP_EOL);
                while ($rowservicios = mysql_fetch_assoc($result1)) {
                    fputs($fp, $rowservicios['servicionombre'] . "," . $rowservicios['total'] . PHP_EOL);
                }
                fputs($fp, PHP_EOL);
                fputs($fp, PHP_EOL);
            } else {
                echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
            }   
        }

        fclose($fp);
        die;
        
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
?>