<?php
    include("../../funciones.php");
    $con = conectar();
?>
<script type="text/javascript">
    $(document).ready(function(){
        $('.date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            language: 'es'
        });
    });
</script>
<form method="post" target="_blank" action="modulos/8.php" id="generarform" data-toggle="validator">
    <h2 class="text-center">INTERVALO DE FECHAS</h2>
    <br />
    <div class="row">
        <div class="form-group has-feedback col-md-6">
            <label for="fechainicio" class="control-label">Fecha de inicio:</label>
            <div class="input-group date" data-provide="datepicker">
                <input type="text" class="form-control input-sm" name="fechainicio" id="fechainicio" data-required-error="Debe llenar este campo" required/>
                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
            </div>
            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 50px"></span>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group has-feedback col-md-6">
            <label for="fechafin" class="control-label">Fecha de termino:</label>
            <div class="input-group date" data-provide="datepicker">
                <input type="text" class="form-control input-sm" name="fechafin" id="fechafin" data-required-error="Debe llenar este campo" required/>
                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
            </div>
            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 50px"></span>
            <div class="help-block with-errors"></div>
        </div>
    </div>
</form>