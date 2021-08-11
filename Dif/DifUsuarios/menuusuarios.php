<?php
    require_once("../nav.php");
?>
    <script type="text/javascript">
        $(document).ready(function() {
            var $output = "<ul class='nav navbar-nav' style='margin-top: 5px'><li><a href='usuarios.php'>Usuarios</a></li>";
            if (<?=$_SESSION['padron_admin_permisos']?> <= 5) {
                $output += "<li><a href='usuarioregistro.php'>Agregar Usuario</a></li>";
            }
            $output += "</ul>";
            $("#bs-example-navbar-collapse-1").prepend($output);
        });
    </script>