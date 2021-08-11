<?php
include_once("menuincidencias.php");

if ($_SESSION['padron_admin_permisos'] != 7 && $_SESSION['padron_admin_permisos'] != 1) {
    echo "<script>window.location = 'incidencias.php'</script>";
}

 
?>
<script type="text/javascript">
    var selectconceptos = `<option value='-1' disabled selected>---Seleccione tipo---</option>` +
        <?php echo "` ".fillselectInci($incidencia_conceptos) ."`"?>;
    var tipoempleado = 0;
    var horasbimestrales = 0.0;
    var diaseconomicos = 0;
    $(document).ready(function() {

        if ('<?php echo isFechasAbiertas(); ?>' == "true") {
            $('.date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                language: 'es'
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

        $("#btnregistrar").on("click", function(e) {
            /*e.preventDefault();
            alert ("registrando");*/
        });

        $("#concepto").on("change", function(e) {
            e.preventDefault();
            $("#horasdiv").remove();
            $(this).parent().prop("class", "form-group has-feedback col-md-4");
            if ('<?php echo isFechasAbiertas(); ?>' == "true") {
                $('.date').datepicker("setStartDate", "");
                $('.date').datepicker("setEndDate", "");
            } else {
                $('.date').datepicker("setStartDate", "<?php echo date("Y-m-d", obtenerFechaInicio()); ?>");
                $('.date').datepicker("setEndDate", "<?php echo date("Y-m-d", strtotime("last day of next month")); ?>");
            }
            if (this.value == 4) {
                $(this).parent().prop("class", "form-group has-feedback col-md-2");
                var $output = "<label for='horas' class='control-label'>Horas:</label><select class='form-control input-sm' name='horas' id='horas' required><option value='-1' selected disabled>Seleccione un usuario</option></select><span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span><div class='help-block with-errors'></div>";
                $("#filauno").append($("<div>", {
                    id: "horasdiv",
                    "class": "form-group has-feedback col-md-2"
                }).html($output));
                $output = "<option value='-1' disabled selected>-Horas-</option>";
                for (var i = horasbimestrales; i >= 1; i -= 0.5) {
                    $output += "<option value='" + i + "'>" + i + "</option>";
                };
                $("#horas").html($output);
            } else if (this.value == 11) {
                $(this).parent().prop("class", "form-group has-feedback col-md-2");
                var $output = "<label for='horas' class='control-label'>Horas:</label><select class='form-control input-sm' name='horas' id='horas' required><option value='-1' disabled selected>-Horas-</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option><option value='6'>6</option><option value='7'>7</option><option value='8'>8</option></select><span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span><div class='help-block with-errors'></div>";
                $("#filauno").append($("<div>", {
                    id: "horasdiv",
                    "class": "form-group has-feedback col-md-2"
                }).html($output));
            } else if (this.value == 1) {
                if ('<?php echo isFechasAbiertas(); ?>' == "true") {
                    $('.date').datepicker("clearDates");
                    $('.date').datepicker("setStartDate", "");
                    $('.date').datepicker("setEndDate", "");
                } else {
                    $('.date').datepicker("clearDates");
                    $('.date').datepicker("setStartDate", "<?php echo (date("w", time()) == 1) ? "-3d" : "-1d"; ?>");
                    $('.date').datepicker("setEndDate", "<?php echo date("Y-m-d", strtotime("last day of next month")); ?>");
                }
            }
            $("#incidenciaform").validator("update");
        });

        $("#concepto").trigger("change");

        $("#idusuario").on("change", function(e) {
            $("#concepto").empty();
            $("#horasdiv").remove();
            $("#concepto").parent().prop("class", "form-group has-feedback col-md-4");
            $("#concepto").html(selectconceptos);
            $.post('ajax/ajax_getusuario.php', {
                "idusuario": this.value
            }, function(respuesta) {
                horasbimestrales = respuesta.horasindicato;
                diaseconomicos = respuesta.diaseconomicos;
                tipoempleado = respuesta.tipo;
                if (respuesta.tipo == 1) {
                    if (diaseconomicos > 0) {
                        alert("Dias economicos disponibles: " + diaseconomicos);
                    } else {
                        alert("Este usuario ya no cuenta con dias economicos");
                    }
                } else if (respuesta.tipo > 1) {
                    $("#concepto option[value='1']").attr("disabled", true);
                    $("#concepto option[value='4']").attr("disabled", true);
                } else {

                }
                if ($("#concepto").val() == 4) {
                    var $output = "<option value='-1' disabled selected>-Horas-</option>";
                    for (var i = horasbimestrales; i >= 1; i -= 0.5) {
                        $output += "<option value='" + i + "'>" + i + "</option>";
                    };
                    $("#horas").html($output);
                } else {
                    horasbimestrales = respuesta.horasindicato;
                }
            });
            $("#incidenciaform").validator("update");
            $("#incidenciaform").validator("validate");
        });
    });
</script>
<div class="row text-center">
    <h1>Registro de incidencia</h1>
</div>
<form method="post" action="engine/engine_incidenciaregistro.php" id="incidenciaform" data-toggle="validator">
    <div class="row">
        <div class="form-group has-feedback col-md-6">
            <label for="idusuario" class="control-label">Usuario:</label>
            <select class="form-control input-sm" name="idusuario" id="idusuario" required>
                <option value="-1" disabled selected>---Seleccione usuario---</option>
                <?php
                $idsecretaria = $_SESSION['padron_admin_id'];
                $query = "SELECT * from $incidencia_secretarias WHERE idsecretaria = $idsecretaria";
                if ($result = mysql_query($query, $con)) {
                    $row = mysql_fetch_assoc($result);
                    $validos = ($row['validos'] == "") ? "0" : $row['validos'];
                    $query = "SELECT * FROM $trabajadores WHERE id IN ($validos) OR id = $idsecretaria";
                    if ($result = mysql_query($query, $con)) {
                        $output = "";
                        while ($rowusuario = mysql_fetch_array($result)) {
                            $output .= "<option value='" . $rowusuario['id'] . "'>" . $rowusuario['nombre'] . " " . $rowusuario['apaterno'] . " " . $rowusuario['amaterno'] . "</option>";
                        }
                        echo $output;
                    } else {
                        echo "<option value='-1'>Error en el query: " . $query . "\nError: " . mysql_error() . "\n" . "</option>";
                    }
                } else {
                    echo "Error en el query: " . $query . "\nError: " . mysql_error() . "\n";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row" id="filauno">
        <div class="form-group has-feedback col-md-4">
            <label for="fecha" class="control-label">Fecha:</label>
            <div class="input-group date" data-provide="datepicker">
                <input type="text" class="form-control input-sm" name="fecha" id="fecha" data-required-error="Debe llenar este campo" required />
                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
            </div>
            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 50px"></span>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group has-feedback col-md-4">
            <label for="tipo" class="control-label">Tipo:</label>
            <select class="form-control input-sm" name="tipo" id="tipo" required>
                <option value="-1" disabled selected>---Seleccione tipo---</option>
                <?php echo fillselectInci($incidencia_tipos); ?>
            </select>
            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group has-feedback col-md-4">
            <label for="concepto" class="control-label">Concepto:</label>
            <select class="form-control input-sm" name="concepto" id="concepto" required>
                <option value="-1" disabled selected>---Seleccione concepto---</option>
                <?php echo fillSelectInci($incidencia_conceptos); ?>
            </select>
            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="row">
        <div class="form-group has-feedback col-md-12">
            <label for="descripcion" class="control-label">Descripcion:</label>
            <textarea class="form-control input-sm" name="descripcion" id="descripcion" rows="5" required></textarea>
            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="row text-center">
        <button type="submit" class="btn btn-primary" id="btnregistrar">Registrar</button>
    </div>
</form>
</div>
</body>