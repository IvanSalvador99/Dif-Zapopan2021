<?php
    require_once("../../menupadron.php");
    //var_dump($_POST);
    
    $query = "SELECT vivienda, servicios_medicos FROM $vw_persona WHERE id = '" . $_POST['idpersona'] . "'";
    
    if ($result = mysql_query($query, $con)) {
        $row_vivseg = mysql_fetch_assoc($result);
    } else {
        echo "</br>Error en el query: " . $query . "</br>Error: " . mysql_error();
    }
?>
        <script type="text/javascript">
            var sicats1 = '<div class="alert alert-success" id="sicats1"><div class="row"><div class="form-group has-feedback col-md-6"><label for="numerocanalizacion" class="control-label">Numero de canalización:</label><input type="text" class="form-control input-sm" id="numerocanalizacion" name="numerocanalizacion" pattern="^[A-Za-z0-9 /-,.]{1,}$" data-pattern-error="Solo letras y numeros" required/><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback col-md-6"><label for="de">Tipo de canalización:</label><select class="form-control input-sm" name="de" id="de"><option value="-1" disabled selected>---Seleccione tipo---</option><option value="1">Interna</option><option value="2">Externa</option></select><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span><div class="help-block with-errors"></div></div></div><div class="row"><div class="form-group has-feedback col-md-6"><label for="origen">Origen:</label><select class="form-control input-sm" name="origen" id="origen" required disabled></select><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback col-md-6"><label for="destino">Destino:</label><select class="form-control input-sm" name="destino" id="destino" required><option value="-1" disabled selected>---Seleccione tipo---</option><?php echo fillSelect($derivaciones); ?></select><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span><div class="help-block with-errors"></div></div></div><div class="row"><div class="form-group has-feedback col-md-6"><label for="responsableO" class="control-label">Responsable en Origen:</label><input type="text" class="form-control input-sm" id="responsableO" name="responsableO" pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros"/><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback col-md-6"><label for="responsableD" class="control-label">Responsable en Destino:</label><input type="text" class="form-control input-sm" id="responsableD" name="responsableD" pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros"/><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div></div><div class="row"><div class="form-group has-feedback col-md-6"><label for="anexos" class="control-label">Se anexa la siguiente documentación:</label><input type="text" class="form-control input-sm" id="anexos" name="anexos" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros"/><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div></div><div class="row"><div class="form-group has-fedback col-md-12"><label for="solicitud" class="control-label">Solicitud:</label><textarea class="form-control input-sm" form="formregistroentrevista" rows="2" name="solicitud" id="solicitud" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div></div><div class="row"><div class="form-group has-fedback col-md-6"><label for="evolucion" class="control-label">Evolución del caso:</label><textarea class="form-control input-sm" form="formregistroentrevista" rows="4" name="evolucion" id="evolucion" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div><div class="form-group has-fedback col-md-6"><label for="observaciones" class="control-label">Observaciones y/o sugerencias:</label><textarea class="form-control input-sm" form="formregistroentrevista" rows="4" name="observaciones" id="observaciones" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div></div></div>';
            var sicats2 = '<div class="alert alert-info" id="sicats2"><div class="row"><div class="form-group has-feedback col-md-6"><label for="numerocanalizacion2" class="control-label">Numero de canalización:</label><input type="text" class="form-control input-sm" id="numerocanalizacion2" name="numerocanalizacion2" pattern="^[A-Za-z0-9 /-,.]{1,}$" data-pattern-error="Solo letras y numeros" required/><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback col-md-6"><label for="de2">Tipo de canalización:</label><select class="form-control input-sm" name="de2" id="de2"><option value="-1" disabled selected>---Seleccione tipo---</option><option value="1">Interna</option><option value="2">Externa</option></select><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span><div class="help-block with-errors"></div></div></div><div class="row"><div class="form-group has-feedback col-md-6"><label for="origen2">Origen:</label><select class="form-control input-sm" name="origen2" id="origen2" required disabled></select><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback col-md-6"><label for="destino2">Destino:</label><select class="form-control input-sm" name="destino2" id="destino2" required><option value="-1" disabled selected>---Seleccione tipo---</option><?php echo fillSelect($derivaciones); ?></select><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span><div class="help-block with-errors"></div></div></div><div class="row"><div class="form-group has-feedback col-md-6"><label for="responsableO2" class="control-label">Responsable en Origen:</label><input type="text" class="form-control input-sm" id="responsableO2" name="responsableO2" pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros"/><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback col-md-6"><label for="responsableD2" class="control-label">Responsable en Destino:</label><input type="text" class="form-control input-sm" id="responsableD2" name="responsableD2" pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros"/><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div></div><div class="row"><div class="form-group has-feedback col-md-6"><label for="anexos2" class="control-label">Se anexa la siguiente documentación:</label><input type="text" class="form-control input-sm" id="anexos2" name="anexos2" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros"/><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div></div><div class="row"><div class="form-group has-fedback col-md-12"><label for="solicitud2" class="control-label">Solicitud:</label><textarea class="form-control input-sm" form="formregistroentrevista" rows="2" name="solicitud2" id="solicitud2" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div></div><div class="row"><div class="form-group has-fedback col-md-6"><label for="evolucion2" class="control-label">Evolución del caso:</label><textarea class="form-control input-sm" form="formregistroentrevista" rows="4" name="evolucion2" id="evolucion2" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div><div class="form-group has-fedback col-md-6"><label for="observaciones2" class="control-label">Observaciones y/o sugerencias:</label><textarea class="form-control input-sm" form="formregistroentrevista" rows="4" name="observaciones2" id="observaciones2" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div></div></div>';
            var sicats3 = '<div class="alert alert-warning" id="sicats3"><div class="row"><div class="form-group has-feedback col-md-6"><label for="numerocanalizacion3" class="control-label">Numero de canalización:</label><input type="text" class="form-control input-sm" id="numerocanalizacion3" name="numerocanalizacion3" pattern="^[A-Za-z0-9 /-,.]{1,}$" data-pattern-error="Solo letras y numeros" required/><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback col-md-6"><label for="de3">Tipo de canalización:</label><select class="form-control input-sm" name="de3" id="de3"><option value="-1" disabled selected>---Seleccione tipo---</option><option value="1">Interna</option><option value="2">Externa</option></select><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span><div class="help-block with-errors"></div></div></div><div class="row"><div class="form-group has-feedback col-md-6"><label for="origen3">Origen:</label><select class="form-control input-sm" name="origen3" id="origen3" required disabled></select><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback col-md-6"><label for="destino3">Destino:</label><select class="form-control input-sm" name="destino3" id="destino3" required><option value="-1" disabled selected>---Seleccione tipo---</option><?php echo fillSelect($derivaciones); ?></select><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span><div class="help-block with-errors"></div></div></div><div class="row"><div class="form-group has-feedback col-md-6"><label for="responsableO3" class="control-label">Responsable en Origen:</label><input type="text" class="form-control input-sm" id="responsableO3" name="responsableO3" pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros"/><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback col-md-6"><label for="responsableD3" class="control-label">Responsable en Destino:</label><input type="text" class="form-control input-sm" id="responsableD3" name="responsableD3" pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros"/><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div></div><div class="row"><div class="form-group has-feedback col-md-6"><label for="anexos3" class="control-label">Se anexa la siguiente documentación:</label><input type="text" class="form-control input-sm" id="anexos3" name="anexos3" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros"/><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div></div><div class="row"><div class="form-group has-fedback col-md-12"><label for="solicitud3" class="control-label">Solicitud:</label><textarea class="form-control input-sm" form="formregistroentrevista" rows="2" name="solicitud3" id="solicitud3" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div></div><div class="row"><div class="form-group has-fedback col-md-6"><label for="evolucion3" class="control-label">Evolución del caso:</label><textarea class="form-control input-sm" form="formregistroentrevista" rows="4" name="evolucion3" id="evolucion3" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div><div class="form-group has-fedback col-md-6"><label for="observaciones3" class="control-label">Observaciones y/o sugerencias:</label><textarea class="form-control input-sm" form="formregistroentrevista" rows="4" name="observaciones3" id="observaciones3" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span><div class="help-block with-errors"></div></div></div></div>';
            var numApoyos = 0;
            
            $("body").on("change", "#de", function(e) {
                if ($("#de").val() == "1") {
                    $("#origen").prop("disabled", false);
                    $("#origen").html("<option value='-1' selected disabled>---Seleccione---</option>")
                    $("#origen").append('<?php echo fillSelectWithPadre($locacion); ?>');
                } else {
                    $("#origen").prop("disabled", false);
                    $("#origen").html("<option value='0' selected>Dif Zapopan</option>");
                }
            });
            
            $("body").on("change", "#de2", function(e) {
                if ($("#de2").val() == "1") {
                    $("#origen2").prop("disabled", false);
                    $("#origen2").html("<option value='-1' selected disabled>---Seleccione---</option>")
                    $("#origen2").append('<?php echo fillSelectWithPadre($locacion); ?>');
                } else {
                    $("#origen2").prop("disabled", false);
                    $("#origen2").html("<option value='0' selected>Dif Zapopan</option>");
                }
            });
            
            $("body").on("change", "#de3", function(e) {
                if ($("#de3").val() == "1") {
                    $("#origen3").prop("disabled", false);
                    $("#origen3").html("<option value='-1' selected disabled>---Seleccione---</option>")
                    $("#origen3").append('<?php echo fillSelectWithPadre($locacion); ?>');
                } else {
                    $("#origen3").prop("disabled", false);
                    $("#origen3").html("<option value='0' selected>Dif Zapopan</option>");
                }
            });
            
            $(document).ready(function(){
                
                $("#estadonacimiento").on("change", function(e) {
                    //alert("Hola");
                    $("#municipionacimiento").val("Obteniendo municipios...");
                    $.post('../ajax/ajax_municipionacimiento.php',{"estado": this.value }, function(respuesta){
                        //alert(respuesta.mensaje);
                        var output = "<option disabled selected>---Seleccione un municipio---</option>";
                        $.each(respuesta.municipio, function(index, value) {
                            output += "<option>" + value + "</option>";
                        });
                        $("#municipionacimiento").html(output);
                        $("#municipionacimiento").prop("disabled", false);
                    });
                });
                
                $(".caps").on("blur", function(e){
                    $(this).val($(this).val().toUpperCase());
                });
                
                $("#agregarapoyo").on("click", function(e) {
                    e.preventDefault();
                    
                    if (numApoyos <= 0) {
                        $("#rowapoyos").empty();
                    }
                    
                    var output = '<div class="form-group has-feedback col-md-6"><label for="apoyo">Apoyo otorgado:</label><select class="form-control input-sm" required><option value="-1" disabled selected>---Seleccione Apoyo---</option><?php echo fillSelect($apoyos); ?></select><span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span><div class="help-block with-errors"></div></div>';
                    $("#rowapoyos").append(output);
                    numApoyos ++;
                });
                
                $("#eliminarapoyo").on("click", function(e) {
                    e.preventDefault();
                    if (numApoyos > 0) {
                        $("#rowapoyos").children().last().remove();
                        numApoyos --;
                    }
                    
                    if (numApoyos <= 0) {
                        $("#rowapoyos").html("<h3>Ningun apoyo agregado</h3>");
                    }
                });
                
                $("#descripcioncaso").on("change keyup paste selectionchange propertychange", function() {
                    var maxlength = $(this).attr('maxlength');
                    var newlines = ($(this).val().match(/\n/g) || []).length;
                    
                    if ($(this).val().length + newlines > maxlength) {
                        alert("Ha superado el numero de caracteres permitidos (2000)");
                        $(this).val($(this).val().substring(0, maxlength - newlines));
                    }
                });
                
                $("#cantsicats").on("change", function(e) {
                    //alert (this.value);
                    switch (this.value) {
                        case '0':
                            $("#divSICATS").empty();
                            break;
                        case '1':
                            $("#divSICATS").empty();
                            $("#divSICATS").append(sicats1);
                            break;
                        case '2':
                            $("#divSICATS").empty();
                            $("#divSICATS").append(sicats1);
                            $("#divSICATS").append("<hr>");
                            $("#divSICATS").append(sicats2);
                            break;
                        case '3':
                            $("#divSICATS").empty();
                            $("#divSICATS").append(sicats1);
                            $("#divSICATS").append("<hr>");
                            $("#divSICATS").append(sicats2);
                            $("#divSICATS").append("<hr>");
                            $("#divSICATS").append(sicats3);
                            break;
                    }
                });
                
                $("#btnsubmit").on("click", function(e) {
                    e.preventDefault();
                    if(($(this).attr("class")).includes("disabled")) {
                        alert ("Boton desabilitado favor de llenar la forma correctamente");
                    } else {
                        var output = "";
                        var first = false;
                        var form = $("#formregistroentrevista");
                        jQuery.each($("#rowapoyos select"), function() {
                            if (!first) {
                                output += "" + $(this).val();
                                first = true;
                            } else {
                                output += "," + $(this).val(); 
                            }
                        });
                        
                        form.append($("<input type='hidden' name='apoyo'/>").val(output));
                        form.append($("<input type='hidden' name='idpersona'/>").val(<?=$_POST['idpersona']?>));
                        form.append($("<input type='hidden' name='servicio'/>").val(<?=$_POST['servicio']?>));
                        form.append($("<input type='hidden' name='locacion'/>").val(<?=$_POST['locacion']?>));
                        form.append($("<input type='hidden' name='idservicio'/>").val(<?php echo "`".$_POST['idservicio']."`" ?>));
                        form.append($("<input type='hidden' name='fechaservicio'/>").val("<?=$_POST['fechaservicio']?>"));
                        form.get(0).submit();
                    }
                });
                
                $("#cantsicats").trigger("change");
            });
        </script>
        <div>
            <h2 class="text-center">Entrevista Inicial/Orientación</h2><br />
            <h4>Datos de generales:</h4>
            <div class="row">
                <div class="col-md-6">
                    <h4>Condición de vivienda: <?= $row_vivseg['vivienda'] ?></h4>
                </div>
                <div class="col-md-6">
                    <h4>Institución o lugar de servicios de salud: <?= $row_vivseg['servicios_medicos'] ?></h4>
                </div>
            </div>
            <form method="post" action="engine/engine_169.php" id="formregistroentrevista" data-toggle="validator">
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="programa" class="control-label">Programa:<small> *</small></label>
                        <select class="form-control input-sm" name="programa" id="programa" required>
                            <option value="-1" disabled selected>---Seleccione programa---</option>
                            <option value="1">Caso urgente</option>
                            <option value="2">Fortalecimiento sociofamiliar</option>
                            <option value="3">Unidad de Atencion a Victimas de Violencia (UAVV)</option>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group col-md-4 has-feedback">
                        <label for="procedencia" class="control-label">Procedencia:<small> *</small></label>
                        <select class="form-control input-sm" name="procedencia" id="procedencia" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelect($procedencia);?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group col-md-4 has-feedback">
                        <label for="registro">Registro:</label>
                        <input class="form-control input-sm caps" type="text" id="registro" name="registro"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="paisnacimiento" class="control-label">País:<small> *</small></label>
                        <select class="form-control input-sm" name="paisnacimiento" id="paisnacimiento" required>
                            <option value="-1" disabled selected>---Seleccione un país---</option>
                            <?php echo fillSelectData($paises, 144); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="estadonacimiento" class="control-label">Estado de nacimiento:<small> *</small></label>
                        <select class="form-control input-sm" name="estadonacimiento" id="estadonacimiento">
                            <option value="-1" disabled selected>---Seleccione un estado---</option>
                            <?php
                                $query = "SELECT DISTINCT estado FROM $colonias ORDER BY estado";
                                if ($result = mysql_query($query, $con)) {
                                    while ($rowestado = mysql_fetch_array($result)) {
                                        echo "<option>" . $rowestado['estado'] . "</option>";
                                    }
                                }
                            ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="municipionacimiento" class="control-label">Municipio de nacimiento:<small> *</small></label>
                        <select class="form-control input-sm" name="municipionacimiento" id="municipionacimiento" disabled></select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <h4>Datos familiares</h4>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="integrantes" class="control-label">Integrantes<small> *</small></label>
                        <input type="text" class="form-control input-sm" id="integrantes" name="integrantes" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="integrantesactivos" class="control-label">Integrantes economicamente activos:<small> *</small></label>
                        <input type="text" class="form-control input-sm" id="integrantesactivos" name="integrantesactivos" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="pagocasa" class="control-label">Pago renta (si aplica):</label>
                        <input type="text" class="form-control input-sm" id="pagocasa" name="pagocasa" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="poblacionatendida">Grupo de vulnerabilidad:</label>
                        <select class="form-control input-sm" name="poblacionatendida" id="poblacionatendida" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelect($grupo_prioritario); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="integrantesenfermos" class="control-label">Integrantes enfermos y/o discapacitados:</label>
                        <input type="text" class="form-control input-sm" id="integrantesenfermos" name="integrantesenfermos" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="enfermedad">Enfermedad o discapacidad:</label>
                        <select class="form-control input-sm" name="enfermedad" id="enfermedad">
                            <?php echo fillSelectData($enfermedades, 78); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-6">
                        <label for="ingresofamiliar" class="control-label">Ingreso familiar mensual aproximado:</label>
                        <input type="text" class="form-control input-sm" id="ingresofamiliar" name="ingresofamiliar" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-6">
                        <label for="deudas" class="control-label">Monto mensual de deuda aproximado:</label>
                        <input type="text" class="form-control input-sm" id="deudas" name="deudas" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-12">
                        <label for="apoyosolicitado" class="control-label">Apoyo solicitado:</label>
                        <input type="text" class="form-control input-sm" id="apoyosolicitado" name="apoyosolicitado" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-fedback col-md-12">
                        <label for="descripcioncaso" class="control-label">Diagnostico social inicial:</label>
                        <textarea class="form-control input-sm" form="formregistroentrevista" rows="4" name="descripcioncaso" id="descripcioncaso" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-6">
                        <label for="tipodelito">Tipo de Delito:</label>
                        <select class="form-control input-sm" name="tipodelito" id="tipodelito" required>
                            <option value="-1" disabled selected>---Seleccione el tipo de delito---</option>
                            <?php echo fillSelect($tipodelito); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                        </div>
                    <div class="form-group has-feedback col-md-6">
                        <label for="probvul">Problematica y/o vulnerabilidad:</label>
                        <select class="form-control input-sm" name="probvul" id="probvul" required>
                            <option value="-1" disabled selected>---Seleccione problematica y/o vulnerabilidad---</option>
                            <?php echo fillSelect($problematicas); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-12">
                        <label for="detonante">Detonante:</label>
                        <select class="form-control input-sm" name="detonante" id="detonante" required>
                            <option value="-1" disabled selected>---Seleccione Detonante---</option>
                            <?php echo fillSelect($detonantes); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-6">
                        <label for="diagnostico">Diagnostico:</label>
                        <select class="form-control input-sm" name="diagnostico" id="diagnostico" required>
                            <option value="-1" disabled selected>---Seleccione Diagnostico---</option>
                            <?php echo fillSelect($diagnostico); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-6">
                        <label for="canalizadoa">Canalización:</label>
                        <select class="form-control input-sm" name="canalizadoa" id="canalizadoa" required>
                            <option value="-1" disabled selected>---Seleccione canalización---</option>
                            <?php echo fillSelect($derivaciones); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row text-center">
                    <h4 class="text-center">Apoyos y/o servicios otorgados:</h4>
                    <button id="agregarapoyo" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span></button>
                    <button id="eliminarapoyo" class="btn btn-danger"><span class="glyphicon glyphicon-minus"></span></button>
                </div>
                <div class="col-md-12 text-center" id="rowapoyos" style="border: 1px solid #999999; border-radius: 5px; margin-top: 10px; margin-bottom: 15px;">
                    <h3>Ningun apoyo agregado</h3>
                </div>
                <div class="row">
                    <div class="form-group has-fedback col-md-12">
                        <label for="descripcionconclusion" class="control-label">Conclusión:</label>
                        <textarea class="form-control input-sm" form="formregistroentrevista" rows="4" name="descripcionconclusion" id="descripcionconclusion" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="cantsicats">Canalizaciones SICATS:</label>
                        <select class="form-control input-sm" name="cantsicats" id="cantsicats">
                            <option value="0" selected>---Ninguna---</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>                        
                        </select>
                    </div>
                </div>
                <div id="divSICATS">
                    
                </div>
                <div class="row">
                    <div class="form-group text-center col-md-12">
                        <button type="submit" class="btn btn-primary" id="btnsubmit">Registrar</button>
                    </div>
                </div>
            </form>
        </div>
        
    <!--
    <div class="alert alert-warning" id="sicats3">
        <div class="row">
            <div class="form-group has-feedback col-md-6">
                <label for="numerocanalizacion3" class="control-label">Numero de canalización:</label>
                <input type="text" class="form-control input-sm" id="numerocanalizacion3" name="numerocanalizacion3" pattern="^[A-Za-z0-9 /-,.]{1,}$" data-pattern-error="Solo letras y numeros" required/>
                <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback col-md-6">
                <label for="de3">Tipo de canalización:</label>
                <select class="form-control input-sm" name="de3" id="de3">
                    <option value="-1" disabled selected>---Seleccione tipo---</option>
                    <option value="1">Interna</option>
                    <option value="2">Externa</option>
                </select>
                <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                <div class="help-block with-errors"></div>
            </div>
        </div>
        <div class="row">
            <div class="form-group has-feedback col-md-6">
                <label for="origen3">Origen:</label>
                <select class="form-control input-sm" name="origen3" id="origen3" required disabled>
                </select>
                <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback col-md-6">
                <label for="destino3">Destino:</label>
                <select class="form-control input-sm" name="destino3" id="destino3" required>
                    <option value="-1" disabled selected>---Seleccione tipo---</option>
                    <?php echo fillSelect($derivaciones); ?>
                </select>
                <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                <div class="help-block with-errors"></div>
            </div>
        </div>
        <div class="row">
            <div class="form-group has-feedback col-md-6">
                <label for="responsableO3" class="control-label">Responsable en Origen:</label>
                <input type="text" class="form-control input-sm" id="responsableO3" name="responsableO3" pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros"/>
                <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-feedback col-md-6">
                <label for="responsableD3" class="control-label">Responsable en Destino:</label>
                <input type="text" class="form-control input-sm" id="responsableD3" name="responsableD3" pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros"/>
                <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                <div class="help-block with-errors"></div>
            </div>
        </div>
        <div class="row">
            <div class="form-group has-feedback col-md-6">
                <label for="anexos3" class="control-label">Se anexa la siguiente documentación:</label>
                <input type="text" class="form-control input-sm" id="anexos3" name="anexos3" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros"/>
                <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                <div class="help-block with-errors"></div>
            </div>
        </div>
        <div class="row">
            <div class="form-group has-fedback col-md-12">
                <label for="solicitud3" class="control-label">Solicitud:</label>
                <textarea class="form-control input-sm" form="formregistroentrevista" rows="2" name="solicitud3" id="solicitud3" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea>
                <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                <div class="help-block with-errors"></div>
            </div>
        </div>
        <div class="row">
            <div class="form-group has-fedback col-md-6">
                <label for="evolucion3" class="control-label">Evoluición del caso:</label>
                <textarea class="form-control input-sm" form="formregistroentrevista" rows="4" name="evolucion3" id="evolucion3" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea>
                <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                <div class="help-block with-errors"></div>
            </div>
            <div class="form-group has-fedback col-md-6">
                <label for="observaciones3" class="control-label">Observaciones y/o sugerencias:</label>
                <textarea class="form-control input-sm" form="formregistroentrevista" rows="4" name="observaciones3" id="observaciones3" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea>
                <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                <div class="help-block with-errors"></div>
            </div>
        </div>
    </div> -->