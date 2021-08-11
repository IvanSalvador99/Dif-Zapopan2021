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
        
        $("#locacion").on("change", function(){
            if (this.value == "72") {
                $.post('ajax/ajax_getcolonias.php',{"locacion": this.value }, function(respuesta){
                /*alert(respuesta.mensaje);
                alert(respuesta.html);*/
                var output = '<div class="form-group has-feedback col-md-12"><label for="colonia" class="control-label">Colonia:</label><select class="form-control input-sm" name="colonia" id="colonia" required><option value="-1" disabled selected>---Seleccione colonia---</option>';
                output += respuesta.html;
                output+= '</select><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span><div class="help-block with-errors"></div></div>';
                $("#rowcolonia").html(output);
            });
            } else {
                $("#rowcolonia").html("");
            }
        });
    });
</script>
<form method="post" target="_blank" action="modulos/6.php" id="generarform" data-toggle="validator">
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
                $query = "SELECT * from $serviciosnv_servicios WHERE id IN ('2', '3', '4', '5', '6')";
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
            <label for="locacion" class="control-label">Locación:</label>
            <select class="form-control input-sm" name="locacion" id="locacion" required>
                <option value="-1" disabled selected>---Seleccione locación---</option>
                <?php echo fillSelectLocaciones($locacion); ?>
            </select>
            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="row" id="rowcolonia"></div>
</form>