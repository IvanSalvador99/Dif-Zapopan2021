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
        $query = "UPDATE $acompanamiento SET 
                    `desaparecido_curp` = '" . mysql_real_escape_string($_POST['curp']) . "', 
                    `desaparecido_nombre` = '" . mysql_real_escape_string($_POST['nombre']) . "', 
                    `desaparecido_apaterno` = '" . mysql_real_escape_string($_POST['apaterno']) . "', 
                    `desaparecido_amaterno` = '" . mysql_real_escape_string($_POST['amaterno']) . "', 
                    `desaparecido_fechanac` = '" . mysql_real_escape_string($_POST['fechanacimiento']) . "', 
                    `desaparecido_sexo` = '" . mysql_real_escape_string($_POST['sexo']) . "', 
                    `desaparecido_estcivil` = '" . mysql_real_escape_string($_POST['estadocivil']) . "', 
                    `fecha_desaparicion` = '" . mysql_real_escape_string($_POST['fechadesaparicion']) . "', 
                    `hay_denuncia` = '" . mysql_real_escape_string($_POST['haydenuncia']) . "', 
                    `documento` = '" . mysql_real_escape_string($_POST['docdir']) . "', 
                    `comparte_datos` = '" . mysql_real_escape_string($_POST['compartir']) . "', 
                    `localizado` = '" . mysql_real_escape_string($_POST['localizado']) . "' WHERE (`id` = '" . mysql_real_escape_string($_POST['oldid']) . "');";

        echo "</br></br>QUERY Desaparecido:</br>" . $query;

        if (mysql_query($query, $con)/*1==1*/) {
            //header("Location: ../../../beneficiariovista.php?id=" . $_POST['idpersona'] . "&action=1");
            echo "</br></br>Hola Carola";
        } else {
            ?>
            <script type="text/javascript">
                alert ("Error en el query: " + <?= $query ?>);
                window.history.back();
            </script>
            <?php
        }
    } else {
        if ($_FILES['documento']['type'] != 'image/png' && $_FILES['documento']['type'] != 'image/jpeg' && $_FILES['documento']['type'] != 'image/bmp' && $_FILES['documento']['type'] != 'application/pdf') {
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
            $dbname = $_POST['docdir'];
            $uploadpath = $_SERVER['DOCUMENT_ROOT'] . $dbname;
            echo "</br></br>DB Name:</br>" . $dbname;
            echo "</br></br>Upload Path:</br>" . $uploadpath;
            
            if (move_uploaded_file($_FILES['documento']['tmp_name'], $uploadpath)) {
            } else {
                echo "</br></br>" . "El archivo " . $target_file . " tuvo un error al cargarse.";
            }
            
            $query = "UPDATE $acompanamiento SET 
                        `desaparecido_curp` = '" . mysql_real_escape_string($_POST['curp']) . "', 
                        `desaparecido_nombre` = '" . mysql_real_escape_string($_POST['nombre']) . "', 
                        `desaparecido_apaterno` = '" . mysql_real_escape_string($_POST['apaterno']) . "', 
                        `desaparecido_amaterno` = '" . mysql_real_escape_string($_POST['amaterno']) . "', 
                        `desaparecido_fechanac` = '" . mysql_real_escape_string($_POST['fechanacimiento']) . "', 
                        `desaparecido_sexo` = '" . mysql_real_escape_string($_POST['sexo']) . "', 
                        `desaparecido_estcivil` = '" . mysql_real_escape_string($_POST['estadocivil']) . "', 
                        `fecha_desaparicion` = '" . mysql_real_escape_string($_POST['fechadesaparicion']) . "', 
                        `hay_denuncia` = '" . mysql_real_escape_string($_POST['haydenuncia']) . "', 
                        `documento` = '" . $dbname . "', 
                        `comparte_datos` = '" . mysql_real_escape_string($_POST['compartir']) . "', 
                        `localizado` = '" . mysql_real_escape_string($_POST['localizado']) . "' WHERE (`id` = '" . mysql_real_escape_string($_POST['oldid']) . "');";

            echo "</br></br>QUERY Desaparecido:</br>" . $query;

            if (mysql_query($query, $con)/*1==1*/) {
                //header("Location: ../../../beneficiariovista.php?id=" . $_POST['idpersona'] . "&action=1");
                echo "</br></br>Hola Carola";
            } else {
                ?>
                <script type="text/javascript">
                    alert ("Error en el query: " + <?= $query ?>);
                    window.history.back();
                </script>
                <?php
            }
        }
    } 
          
?>