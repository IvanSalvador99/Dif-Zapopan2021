<?php
    require_once("../../menupadron.php");
    
    /*if ((!perteneceA($_SESSION['padron_admin_area'], 8) && !perteneceA($_SESSION['padron_admin_area'], 7)) && $_SESSION['padron_admin_permisos'] != 1) {
        alerta_bota("No perteneces al departamento asignado para esta aplicación", "../../../menu.php");
        //echo "<script>window.location = '../menu.php'</script>";
    }*/
?>
<div class="text-center">
    <h2>Imprimir entrevista inicial y SICATS</h2>
    <br /><br />
    <a href="engine/print_169.php?idservicio=<?=$_POST['idservicio']?>" target="_blank" class="btn btn-primary" id="btnimprimir">Imprimir entrevista</a>
</div>

