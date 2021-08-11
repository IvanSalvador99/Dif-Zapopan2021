<?php
    include_once("menuincidencias.php");
    
    if ($_SESSION['padron_admin_permisos'] != 7 && $_SESSION['padron_admin_permisos'] != 1) {
        echo "<script>window.location = 'incidencias.php'</script>";
    }
    
    $query = "SELECT * FROM $incidencias WHERE id = '" . $_POST['id'] . "'";
    if ($result = mysql_query($query, $con)) {
        $rowincidencia = mysql_fetch_array($result);
        
        //var_dump($rowincidencia);
    } else {
        die (mysql_error());
    }
    
    $query = "SELECT * FROM $trabajadores WHERE id = '" . $rowincidencia['idusuario'] . "'";
    if ($result = mysql_query($query, $con)) {
        $rowusuario = mysql_fetch_array($result);
        
        //var_dump($rowusuario);
    } else {
        die (mysql_error());
    }
    ?>
        <script type="text/javascript">
            $(document).ready(function(){
                if ('<?php echo isFechasAbiertas(); ?>' == "true") {
                    $('.date').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                        language: 'es',
                        startDate: '-3y',
                        endDate: '+3y'
                    });
                } else {
                    $('.date').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                        language: 'es',
                        startDate: '<?php echo date("Y-m-d", obtenerFechaInicio()); ?>',
                        endDate: '<?php echo date("Y-m-d", strtotime("last day of next month")); ?>'
                    });
                }
                
                $("#concepto").on("change", function (e){
                    e.preventDefault();
                    $("#horasdiv").remove();
                    if (this.value == 4) {
                        $(this).parent().prop("class", "form-group has-feedback col-md-2");
                        var $output = "<label for='horas' class='control-label'>Horas:</label><select class='form-control input-sm' name='horas' id='horas' required></select><span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span><div class='help-block with-errors'></div>";
                        $("#filauno").append($("<div>", {id: "horasdiv", "class": "form-group has-feedback col-md-2"}).html($output));
                        $.post('ajax/ajax_getusuario.php',{"idusuario": <?=$rowincidencia['idusuario']?>}, function(respuesta){
                            $output = "<option value='-1' disabled selected>-Horas-</option>";
                            for (var i = respuesta.horasindicato; i >= 1; i -= 0.5) {
                                if (i == <?php echo (($rowincidencia['horas'] == NULL) ? 0 : $rowincidencia['horas']); ?>) {
                                    $output += "<option value='" + i + "' selected>" + i + "</option>";
                                } else {
                                    $output += "<option value='" + i + "'>" + i + "</option>";
                                }
                                
                            };
                            $("#horas").html($output);
                        });
                    } else if (this.value == 11) {
                        $(this).parent().prop("class", "form-group has-feedback col-md-2");
                        var $output = "<label for='horas' class='control-label'>Horas:</label><select class='form-control input-sm' name='horas' id='horas' required><option value='-1' disabled selected>-Horas-</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option><option value='6'>6</option><option value='7'>7</option><option value='8'>8</option></select><span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span><div class='help-block with-errors'></div>";
                        $("#filauno").append($("<div>", {id: "horasdiv", "class": "form-group has-feedback col-md-2"}).html($output));
                    } else {
                        $(this).parent().prop("class", "form-group has-feedback col-md-4");
                    }
                    $("#incidenciaform").validator("update");
                });
                
                $("#concepto").trigger("change");
                
                if (<?= $rowusuario['tipo'] ?> > 1) {
                    $("#concepto option[value='1']").attr("disabled", true);
                    $("#concepto option[value='4']").attr("disabled", true);
                }
            });
        </script>
        <div class="row text-center">
            <h1>Actualización de incidencia</h1>
        </div>
        <form method="post" action="engine/engine_incidenciaeditar.php" id="incidenciaform" data-toggle="validator">
            <div class="row" id="filauno">
                <div class="form-group has-feedback col-md-4">
                    <label for="fecha" class="control-label">Fecha:</label>
                    <div class="input-group date" data-provide="datepicker">
                        <input type="text" class="form-control input-sm" name="fecha" id="fecha" value="<?=$rowincidencia['fecha']?>" data-required-error="Debe llenar este campo" required/>
                        <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                    </div>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 50px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="tipo" class="control-label">Tipo:</label>
                    <select class="form-control input-sm" name="tipo" id="tipo" required>
                        <?php echo fillSelectData($incidencia_tipos, $rowincidencia['tipo']); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="concepto" class="control-label">Concepto:</label>
                    <select class="form-control input-sm" name="concepto" id="concepto" required>
                    <?php echo fillSelectData($incidencia_conceptos, $rowincidencia['concepto']); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-12">
                    <label for="descripcion" class="control-label">Descripcion:</label>
                    <textarea class="form-control input-sm" name="descripcion" id="descripcion" rows="5" required><?=$rowincidencia['descripcion']?></textarea>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row text-center">
                <input type="hidden" name="id" value="<?=$_POST['id']?>" />
                <input type="hidden" name="prehistorial" value="<?=$rowincidencia['historial']?>" />
                <button type="submit" class="btn btn-primary" id="btnregistrar">Actualizar</button>
            </div>
        </form>
    </div>
</body>