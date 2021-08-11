<?php
    require_once("../../../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    echo "SESSION:</br>";
    var_dump($_SESSION);
    
    echo "</br></br>POST:</br>";
    var_dump($_POST);
    
    $query = "SELECT * FROM $tipo_despensa WHERE id = '" . $_POST['tipodespensa'] . "'";
    echo "</br></br>QUERY: " . $query;
    if ($result = mysql_query($query, $con)) {
        $rowtipodespensa = mysql_fetch_array($result);
    } else {
        echo "</br></br>Error en el query: " . mysql_error();
    }
    
    if ($_POST['razondespensa'] == 0) {
        if ($_POST['registrado'] == 1 && $_POST['activo'] == 1) {
            header("Location: ../../../beneficiariovista.php?id=" . $_POST['idpersona'] . "&action=6");
        } else {
            $query = "INSERT INTO $servicios_otorgados VALUES (null,
                    '" . $_POST['idpersona'] . "',
                    '" . $_POST['fechaservicio'] . "',
                    '" . $_POST['locacion'] . "',
                    '" . $_POST['servicio'] . "', 
                    '" . $rowtipodespensa['tipo_despensa'] . "')";
                    
            echo "</br></br>QUERY: " . $query;
            
            if (mysql_query($query, $con)) {
                header("Location: ../../../beneficiariovista.php?id=" . $_POST['idpersona'] . "&action=1");
            } else {
                echo "</br></br>Error en el query: " . mysql_error();
            }
        }   
    } else {
        if (isset($_POST['registrado']) && $_POST['registrado'] == 1) {
            $query = "UPDATE $persona_depsensas SET
                    `estadonacimiento`='" . $_POST['estadonacimiento'] . "',
                    `municipionacimiento`='" . $_POST['municipionacimiento'] . "',
                    `ingresos`='" . $_POST['ingresos'] . "',
                    `grupoprioritario`='" . $_POST['poblacionatendida'] . "',
                    `comunidad`='" . $_POST['comunidad'] . "',
                    `tipodespenda`='" . $_POST['tipodespensa'] . "' WHERE `idpersona`='" . $_POST['idpersona'] . "'";
                    
            echo "</br></br>QUERY: " . $query;
            
            if (mysql_query($query, $con)) {
                header("Location: ../../../beneficiariovista.php?id=" . $_POST['idpersona'] . "&action=5");
            } else {
                echo "</br></br>Error en el query: " . mysql_error();
            }
        } else {
            $query = "INSERT INTO $persona_depsensas VALUES (null, 
                    '" . $_POST['idpersona'] . "',
                    '" . $_POST['estadonacimiento'] . "', 
                    '" . $_POST['municipionacimiento'] . "', 
                    '" . $_POST['ingresos'] . "', 
                    '" . $_POST['poblacionatendida'] . "',
                    '" . $_POST['comunidad'] . "',
                    '" . $_POST['tipodespensa'] . "', 
                    '1')";
                    
            echo "</br></br>QUERY: " . $query;
            
            if (mysql_query($query, $con)) {
                header("Location: ../../../beneficiariovista.php?id=" . $_POST['idpersona'] . "&action=2");
            } else {
                echo "</br></br>Error en el query: " . mysql_error();
            }
        }
            
    }
?>