<?php
    include("../../funciones.php");
    $con=conectar();
    iniciar_sesion(3);
    $jsondata = array();
    
    if (!isset($_POST['idusuario'])) {
        echo "No enviaste nada";
        exit();
    }
    
    $query = "SELECT * FROM $trabajadores where id='" . mysql_real_escape_string($_POST['idusuario']) . "'";
    
    if ($result = mysql_query($query)) {
        $row = mysql_fetch_array($result);
        $jsondata["usuario"] = $row['nombre'] . " " . $row['apaterno'] . " " . $row['amaterno'];
        
        $query = "SELECT idsecretaria, CONCAT_WS(' ', usuario.nombre, usuario.apaterno, usuario.amaterno) AS nombresecretaria
                    FROM $incidencia_secretarias
                    LEFT JOIN usuario ON usuario.id = idsecretaria 
                    WHERE validos LIKE '" . mysql_real_escape_string($_POST['idusuario']) . "' 
                            OR validos LIKE '" . mysql_real_escape_string($_POST['idusuario']) . ",%' 
                            OR validos LIKE '%," . mysql_real_escape_string($_POST['idusuario']) . "' 
                            OR validos LIKE '%," . mysql_real_escape_string($_POST['idusuario']) . ",%'";
                            
        //$jsondata['querysecretarias'] = $query;
        
        if ($result = mysql_query($query)) {
            $jsondata["cantsecretarias"] = mysql_num_rows($result);
            while ($row = mysql_fetch_row($result)) {
                $jsondata["secretarias"][] = $row;
            }
            
            $query = "SELECT idjefe, CONCAT_WS(' ', usuario.nombre, usuario.apaterno, usuario.amaterno) AS nombresecretaria 
                        FROM $incidencia_jefes
                        LEFT JOIN usuario ON usuario.id = idjefe
                        WHERE validos LIKE '" . mysql_real_escape_string($_POST['idusuario']) . "' 
                                OR validos LIKE '%," . mysql_real_escape_string($_POST['idusuario']) . "' 
                                OR validos LIKE '" . mysql_real_escape_string($_POST['idusuario']) . ",%' 
                                OR validos LIKE '%," . mysql_real_escape_string($_POST['idusuario']) . ",%';";
                                
            //$jsondata['queryjefes'] = $query;
            
            if ($result = mysql_query($query)) {
                $jsondata["cantjefes"] = mysql_num_rows($result);
                while ($row = mysql_fetch_row($result)) {
                    $jsondata["jefes"][] = $row;
                }
                
                header('Content-type: application/json; charset=utf-8');
                echo json_encode($jsondata, JSON_FORCE_OBJECT);
                exit();
            } else {
                echo "Error en el query: " . $query . ", Error: " . mysql_error();
            }
        } else {
            echo "Error en el query: " . $query . ", Error: " . mysql_error();
        }
    } else {
        echo "Error en el query: " . $query . ", Error: " . mysql_error();
    }
    
        
?>