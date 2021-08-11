<?php
    require_once("../../../../funciones.php");
    
    iniciar_sesion(0);
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    echo "SESSION:</br>";
    var_dump($_SESSION);
    
    echo "</br></br>POST:</br>";
    var_dump($_POST);
    
    echo "</br></br>FILES:</br>";
    var_dump($_FILES);
    
    array_walk_recursive($_POST, function(&$val){
        $val = trim($val);
    });
    
    if (empty($_POST) || empty($_FILES)) {
        ?>
        <script type="text/javascript">
            alert ("No ha subido información o el archivo esta vacio");
            window.history.back();
        </script>
        <?php
    }
    
    if ($_FILES['documento']['error'] == 4) {
        ?>
        <script type="text/javascript">
            alert ("El archivo no se ha subido correctamente");
            window.history.back();
        </script>
        <?php
    } elseif ($_FILES['documento']['type'] != 'image/png' && $_FILES['documento']['type'] != 'image/jpeg' && $_FILES['documento']['type'] != 'image/bmp' && $_FILES['documento']['type'] != 'application/pdf') {
        ?>
        <script type="text/javascript">
            alert ("El archivo no esta en un formato permitido: PNG, JPG, BMP o PDF");
            window.history.back();
        </script>
        <?php
    } elseif ($_FILES['documento']['size'] > 5000000) {
        ?>
        <script type="text/javascript">
            alert ("El archivo excede el peso maximo de 5MB");
            window.history.back();
        </script>
        <?php
    } else {
        $query = "INSERT INTO $servicios_otorgados VALUES (null,
            '" . $_POST['idpersona'] . "',
            '" . $_SESSION['padron_admin_id'] . "',
            '" . $_POST['fechaservicio'] . "',
            '" . $_POST['locacion'] . "',
            '" . $_POST['servicio'] . "',
            '" . $_POST['idservicio'] . "', null)";
    
        echo "</br></br>QUERY SERVICIO:</br>" . $query;
        
        if (mysql_query($query, $con)/*1==1*/) {
            $lastid = mysql_insert_id();
            
            $dbname = "/Lab/DifBeneficiarios/modulos/481/docs/doc" . $lastid . "." . strtolower(pathinfo($_FILES['documento']['name'], PATHINFO_EXTENSION));
            $uploadpath = $_SERVER['DOCUMENT_ROOT'] . $dbname;
            echo "</br></br>DB Name:</br>" . $dbname;
            echo "</br></br>Upload Path:</br>" . $uploadpath;
            
            if (move_uploaded_file($_FILES['documento']['tmp_name'], $uploadpath)) {
            } else {
                echo "</br></br>" . "El archivo " . $target_file . " tuvo un error al cargarse.";
            }
            
            $query = "INSERT INTO $acompanamiento VALUES (null,
                                                          '" . $_POST['idpersona'] . "', 
                                                          '" . $lastid . "', 
                                                          '" . mysql_real_escape_string($_POST['curp']) . "', 
                                                          '" . mysql_real_escape_string($_POST['nombre']) . "', 
                                                          '" . mysql_real_escape_string($_POST['apaterno']) . "', 
                                                          '" . mysql_real_escape_string($_POST['amaterno']) . "', 
                                                          '" . mysql_real_escape_string($_POST['fechanacimiento']) . "', 
                                                          '" . mysql_real_escape_string($_POST['sexo']) . "', 
                                                          '" . mysql_real_escape_string($_POST['estadocivil']) . "', 
                                                          '" . mysql_real_escape_string($_POST['fechadesaparicion']) . "', 
                                                          '" . mysql_real_escape_string($_POST['haydenuncia']) . "', 
                                                          '" . $dbname . "',
                                                          '" . mysql_real_escape_string($_POST['compartir']) . "',
                                                          '" . mysql_real_escape_string($_POST['localizado']) . "');";
            
            echo "</br></br>QUERY Desaparecido:</br>" . $query;
            
            if (mysql_query($query, $con)/*1==1*/) {
                header("Location: ../../../beneficiariovista.php?id=" . $_POST['idpersona'] . "&action=1");
                //echo "</br></br>Hola Carola";
            } else {
                ?>
                <script type="text/javascript">
                    alert ("Error en el query: " + <?= $query ?>);
                    window.history.back();
                </script>
                <?php
            }
        } else {
            ?>
            <script type="text/javascript">
                alert ("Error en el query: " + <?= $query ?>);
                window.history.back();
            </script>
            <?php
        }
    }  
?>
