<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/Dif Zapopan/Lab/nav.php");
    
    if ((!perteneceA($_SESSION['padron_admin_area'], 4) && !perteneceA($_SESSION['padron_admin_area'], 8) && !perteneceA($_SESSION['padron_admin_area'], 7)) && $_SESSION['padron_admin_permisos'] != 1) {
        alerta_bota("No perteneces al departamento asignado para esta aplicación", "../menu.php");
        //echo "<script>window.location = '../menu.php'</script>";
    }
?>
    <script type="text/javascript">
        $(document).ready(function() {
            //alert(window.location.pathname);
            var $output = "<ul class='nav navbar-nav' style='margin-top: 5px'><li><a href='/Lab/DifBeneficiarios/beneficiarios.php'>Beneficiarios</a></li>";
            if (<?=$_SESSION['padron_admin_permisos']?> <= 5) {
                $output += "<li><a href='/Lab/DifBeneficiarios/beneficiarioregistro.php'>Agregar Beneficiarios</a></li>";
            }
            $output += "</ul>";
            $("#bs-example-navbar-collapse-1").prepend($output);
        });
    </script>