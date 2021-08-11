<?php
    require_once("../nav.php");
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