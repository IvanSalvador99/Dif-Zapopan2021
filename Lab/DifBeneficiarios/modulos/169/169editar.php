<?php
    require_once("../../menupadron.php");
    if (isset($_GET['action']) == 1) {
        alerta_bota("Servicio agregado exitosamente!", 0);
    }
?>
<div class="text-center">
    <h2>Agregar servicios, Imprimir entrevista inicial y SICATS</h2>
    <br /><br />
    <?php 
    if (empty($_POST)) { ?>
        <a href="modulo169/agregarservicio_169.php?idservicio=<?=$_GET['id']?>" class="btn btn-primary" id="btnimprimir">Agregar Nuevos Servicios</a>
        <a href="engine/print_169.php?idservicio=<?=$_GET['id']?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary" id="btnimprimir">Imprimir entrevista</a>    
    <?php }else{ ?>
        <a href="modulo169/agregarservicio_169.php?idservicio=<?=$_POST['idservicio']?>" class="btn btn-primary" id="btnimprimir">Agregar Nuevos Servicios</a>
        <a href="engine/print_169.php?idservicio=<?=$_POST['idservicio']?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary" id="btnimprimir">Imprimir entrevista</a>    
    <?php } ?>    
</div>