<?php
    include("../../funciones.php");
    $con=conectar();
    iniciar_sesion(3);
    $jsondata = array();
    
    if (!isset($_POST['idusuario'])) {
        echo "No enviaste nada";
        exit();
    }
    
    $jsondata['output'] = "";
    $coincide = FALSE;
    
    $query = "SELECT id, CONCAT_WS(' ', nombre, apaterno, amaterno) AS nombre FROM $trabajadores";
    
    if ($resultusuarios = mysql_query($query, $con)) {
        $query = "SELECT validos FROM $incidencia_secretarias WHERE idsecretaria = '" . mysql_real_escape_string($_POST['idusuario']) . "'";
        
        if ($resultvalidos = mysql_query($query, $con)) {
            $rowvalidos = mysql_fetch_assoc($resultvalidos);
            $validos = explode(",", $rowvalidos['validos']);
            
            while ($rowusuario = mysql_fetch_assoc($resultusuarios)) {
                if ($rowusuario['id'] == $_POST['idusuario']) {
                    $jsondata['usuario'] = $rowusuario['nombre'];
                }
                
                if (in_array($rowusuario['id'], $validos)) {
                    $jsondata['output'] .= "<option value='" . $rowusuario['id'] . "' selected>" . $rowusuario['nombre'] . "</option>";
                } else {
                    $jsondata['output'] .= "<option value='" . $rowusuario['id'] . "'>" . $rowusuario['nombre'] . "</option>";
                }
                
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
    
?>