<?php
    require_once("../nav.php");
?>
    <script type="text/javascript">
        $(document).ready(function() {
            var $output = "<ul class='nav navbar-nav' style='margin-top: 5px'><li><a href='incidencias.php'>Incidencias</a></li>";
            if (<?=$_SESSION['padron_admin_permisos']?> == 7 || <?=$_SESSION['padron_admin_permisos']?> == 1) {
                $output += "<li><a href='incidenciaregistro.php'>Registrar incidencia</a></li>";
            }
            $output += "</ul>";
            $("#bs-example-navbar-collapse-1").prepend($output);
        });
    </script>