<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/Dif/nav.php");
?>
    <script type="text/javascript">
        $(document).ready(function() {
            //alert(window.location.pathname);
            var $output = "<ul class='nav navbar-nav' style='margin-top: 5px'><li><a href='/Dif/DifBeneficiarios/beneficiarios.php'>Beneficiarios</a></li>";
            if (<?=$_SESSION['padron_admin_permisos']?> <= 5) {
                $output += "<li><a href='/Dif/DifBeneficiarios/beneficiarioregistro.php'>Agregar Beneficiarios</a></li>";
            }
            $output += "</ul>";
            $("#bs-example-navbar-collapse-1").prepend($output);
        });
    </script>