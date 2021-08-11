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
<form method="post" target="_blank" action="modulos/5.php" id="generarform" data-toggle="validator">
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
    <div class="row">
        <div class="form-group has-feedback col-md-6">
            <label for="servicio" class="control-label">Servicio:</label>
            <select class="form-control input-sm" name="servicio" id="servicio" required>
                <option value="-1" disabled selected>---Seleccione servicio---</option>
                <?php
                $output = "";
                $query = "SELECT * from $servicios WHERE id IN ('171', '172', '173')";
                if ($result = mysql_query($query, $con)) {
                    while ($row = mysql_fetch_array($result)) {
                        $output .= "<option value='" . $row['id'] . "'>" . $row['servicio'] . "</option>";
                    }
                } else {
                    $output .= "<option disabled selected> Error en el query: " . $query . "</option>"; 
                }
                echo $output;
                ?>
            </select>
            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group has-feedback col-md-6">
            <label for="locacion" class="control-label">Ludoteca:</label>
            <select class="form-control input-sm" name="locacion" id="locacion" required>
                <option value="-1" disabled selected>---Seleccione ludoteca---</option>
                <?php
                $output = "";
                $query = "SELECT * from $locacion WHERE id IN ('69', '70', '73')";
                if ($result = mysql_query($query, $con)) {
                    while ($row = mysql_fetch_array($result)) {
                        $output .= "<option value='" . $row['id'] . "'>" . $row['locacion'] . "</option>";
                    }
                } else {
                    $output .= "<option value='-2' disabled selected> Error en el query: " . $query . "</option>"; 
                }
                echo $output;
                ?>
            </select>
            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="row">
        <div class="form-group has-feedback col-md-6">
            <label for="edadmin" class="control-label" style="margin-bottom: 0px">Edad inicial:</label>
            <input class="form-control input-sm" type="text" id="edadmin" name="edadmin" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group has-feedback col-md-6">
            <label for="edadmax" class="control-label" style="margin-bottom: 0px">Edad final:</label>
            <input class="form-control input-sm" type="text" id="edadmax" name="edadmax" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
            <div class="help-block with-errors"></div>
        </div>
    </div>
</form>