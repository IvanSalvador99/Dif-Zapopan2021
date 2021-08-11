<?php
    require_once("../nav.php");
    
    if ((!perteneceA($_SESSION['padron_admin_area'], 4) && !perteneceA($_SESSION['padron_admin_area'], 8) && !perteneceA($_SESSION['padron_admin_area'], 7)) && $_SESSION['padron_admin_permisos'] != 1 && $_SESSION['padron_admin_id'] != 750) {
        alerta_bota("No perteneces al departamento asignado para esta aplicación", "../menu.php");
        //echo "<script>window.location = '../menu.php'</script>";
    }
?>
    <script type="text/javascript">
        $(document).ready(function() {
            //alert(window.location.pathname);
            var $output = "<ul class='nav navbar-nav' style='margin-top: 5px'><li><a href='serviciosnv.php'>Servicios no vinculantes</a></li>";
            if (<?=$_SESSION['padron_admin_permisos']?> <= 5) {
                $output += "<li><a href='serviciosnvregistro.php'>Registrar servicio no vinculante</a></li>";
            }
            $output += "</ul>";
            $("#bs-example-navbar-collapse-1").prepend($output);
        });
    </script>