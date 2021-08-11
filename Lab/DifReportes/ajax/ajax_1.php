<?php
    require_once("../../funciones.php");
    $con = conectar();
?>
<script type="text/javascript">
    $("#checkboxgeneral").on("change", function(){
        if(this.checked) {
            $("#comunidad").prop("disabled", true);
        } else {
            $("#comunidad").prop("disabled", false);
        }
    });
</script>
<form method="post" target="_blank" action="modulos/1.php" id="generarform" data-toggle="validator">
    <div class="row text-center">
        <div class="form-group has-feedback col-md-12">
            <label for="comunidad" class="control-label">Comunidad:</label>
            <select class="form-control input-sm" name="comunidad" id="comunidad" required>
                <option value="-1" disabled selected>---Seleccione comunidad---</option>
                <?php echo fillSelect($comunidades); ?>
            </select>
            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
            <div class="help-block with-errors"></div>
            <div class="checkbox-inline">
                <label><input type="checkbox" id="checkboxgeneral" name="checkboxgeneral" value="1"/>Todas</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <label>Tipo de Despensa:</label><br />
            <div class="radio-inline">
                <label><input type="radio" name="radiotipo" value="1"/>Alimentaria</label>
            </div>
            <div class="radio-inline">
                <label><input type="radio" name="radiotipo" value="2"/>Programas</label>
            </div>
        </div>
    </div>
</form>