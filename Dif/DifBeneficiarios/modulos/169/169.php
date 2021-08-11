<?php
    require_once("../../menupadron.php");
    //var_dump($_POST);
    
    if ((!perteneceA($_SESSION['padron_admin_area'], 8) && !perteneceA($_SESSION['padron_admin_area'], 7)) && $_SESSION['padron_admin_permisos'] != 1) {
        alerta_bota("No perteneces al departamento asignado para esta aplicación", "../../../menu.php");
        //echo "<script>window.location = '../menu.php'</script>";
    }
?>
        <script type="text/javascript">
            var numApoyos = 0;
            $(document).ready(function(){
                
                $("#estadonacimiento").change(function(e) {
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
                
                $(".caps").blur(function(e){
                    $(this).val($(this).val().toUpperCase());
                });
                
                $("#agregarapoyo").on("click", function(e) {
                    e.preventDefault();
                    
                    if (numApoyos <= 0) {
                        $("#rowapoyos").empty();
                    }
                    
                    var output = "<div class='form-group has-feedback col-md-6'>" + 
                                     "<label for='apoyo'>Apoyo otorgado:</label>" +
                                     "<select class='form-control input-sm' required>" +
                                         "<option value='-1' disabled selected>---Seleccione Apoyo---</option>" +
                                         "<?php echo fillSelect($apoyos); ?>" + 
                                     "</select>" +
                                     "<span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span>" +
                                     "<div class='help-block with-errors'></div>" +
                                 "</div>";
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
                        form.append($("<input type='hidden' name='fechaservicio'/>").val("<?=$_POST['fechaservicio']?>"));
                        form.get(0).submit();
                    }
                });
                
                $("#checkboxSICATS").on("change", function(){
                    if (this.checked) {
                        //alert ("Activado");
                        $("#divSICATS").show();
                        $("#de").prop("disabled", false);
                        $("#destino").prop("disabled", false);
                        $("#anexos").prop("disabled", false);
                        $("#evolucion").prop("disabled", false);
                        $("#observaciones").prop("disabled", false);
                        $("#numerocanalizacion").prop("disabled", false);
                        $("#origen").prop("required", true);
                        $("#destino").prop("required", true);
                        $("#evolucion").prop("required", true);
                        $("#observaciones").prop("required", true);
                        $("#numerocanalizacion").prop("required", true); 
                        $("#solicitud").prop("disabled", false);
                        $("#solicitud").prop("required", true);
                        $("#responsableO").prop("disabled", false);
                        $("#responsableD").prop("disabled", false); //
                    } else {
                        //alert ("Desactivado");
                        $("#divSICATS").hide();
                        $("#de option[value='-1']").prop("selected", true);
                        $("#de").prop("disabled", true);
                        $("#numerocanalizacion").prop("disabled", true);
                        $("#origen").empty();
                        $("#origen").prop("disabled", true);
                        $("#destino").prop("disabled", true);
                        $("#destino option[value='-1']").prop("selected", true);
                        $("#anexos").prop("disabled", true);
                        $("#evolucion").prop("disabled", true);
                        $("#observaciones").prop("disabled", true);
                        $("#origen").prop("required", false);
                        $("#destino").prop("required", false);
                        $("#evolucion").prop("required", false);
                        $("#observaciones").prop("required", false);
                        $("#anexos").val("");
                        $("#evolucion").val("");
                        $("#observaciones").val("");
                        $("#numerocanalizacion").val("");
                        $("#numerocanalizacion").prop("required", false);
                        $("#solicitud").val("");
                        $("#solicitud").prop("required", false);
                        $("#responsableO").prop("disabled", true);
                        $("#responsableO").val("");
                        $("#responsableD").prop("disabled", true);
                        $("#responsableD").val("");
                    }
                });
                
                $("#de").change(function() {
                    if ($("#de").val() == "1") {
                        $("#origen").prop("disabled", false);
                        $("#origen").html("<option value='-1' selected disabled>---Seleccione---</option>")
                        $("#origen").append("<?php echo fillSelectWithPadre($locacion); ?>");
                    } else {
                        $("#origen").prop("disabled", false);
                        $("#origen").html("<option value='0' selected>Dif Zapopan</option>");
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
                
                $("#checkboxSICATS").prop("checked", false);
                $("#checkboxSICATS").trigger("change");
            });
        </script>
        <div>
            <h2 class="text-center">Entrevista Inicial/Orientación</h2>
            <h4>Datos de generales:</h4>
            <form method="post" action="engine/engine_169.php" id="formregistroentrevista" data-toggle="validator">
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="programa" class="control-label">Servicio:<small> *</small></label>
                        <select class="form-control input-sm" name="programa" id="programa" required disabled>
                            <!--<option value="-1" disabled selected>---Seleccione servicio---</option>-->
                            <?php echo fillSelectDataWithPadre($servicios, $_POST['servicio']); ?>
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
                        <label for="probvul">Problematica y/o vulnerabilidad:</label>
                        <select class="form-control input-sm" name="probvul" id="probvul" required>
                            <option value="-1" disabled selected>---Seleccione problematica y/o vulnerabilidad---</option>
                            <?php echo fillSelect($problematicas); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-6">
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
                    <div class="form-group has-feedback col-md-12">
                        <label for="otroapoyo" class="control-label">Otro apoyo:</label>
                        <input type="text" class="form-control input-sm" id="otroapoyo" name="otroapoyo" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-fedback col-md-12">
                        <label for="descripcionconclusion" class="control-label">Conclusión:</label>
                        <textarea class="form-control input-sm" form="formregistroentrevista" rows="4" name="descripcionconclusion" id="descripcionconclusion" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row col-md-12">
                    <div class="checkbox"><label><input type="checkbox" name="checkboxSICATS" id="checkboxSICATS" />Llenar forma SICATS</label></div>
                </div>
                <div id="divSICATS">
                    <div class="row">
                        <div class="form-group has-feedback col-md-6">
                            <label for="numerocanalizacion" class="control-label">Numero de canalización:</label>
                            <input type="text" class="form-control input-sm" id="numerocanalizacion" name="numerocanalizacion" pattern="^[A-Za-z0-9 /-,.]{1,}$" data-pattern-error="Solo letras y numeros" required/>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback col-md-6">
                            <label for="de">Tipo de canalización:</label>
                            <select class="form-control input-sm" name="de" id="de">
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
                            <label for="origen">Origen:</label>
                            <select class="form-control input-sm" name="origen" id="origen" required disabled>
                            </select>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback col-md-6">
                            <label for="destino">Destino:</label>
                            <select class="form-control input-sm" name="destino" id="destino" required>
                                <option value="-1" disabled selected>---Seleccione tipo---</option>
                                <?php echo fillSelect($derivaciones); ?>
                            </select>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group has-feedback col-md-6">
                            <label for="responsableO" class="control-label">Responsable en Origen:</label>
                            <input type="text" class="form-control input-sm" id="responsableO" name="responsableO" pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros"/>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback col-md-6">
                            <label for="responsableD" class="control-label">Responsable en Destino:</label>
                            <input type="text" class="form-control input-sm" id="responsableD" name="responsableD" pattern="^[A-Za-zÀ-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros"/>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group has-feedback col-md-6">
                            <label for="anexos" class="control-label">Se anexa la siguiente documentación:</label>
                            <input type="text" class="form-control input-sm" id="anexos" name="anexos" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros"/>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group has-fedback col-md-12">
                            <label for="solicitud" class="control-label">Solicitud:</label>
                            <textarea class="form-control input-sm" form="formregistroentrevista" rows="2" name="solicitud" id="solicitud" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group has-fedback col-md-6">
                            <label for="evolucion" class="control-label">Evoluición del caso:</label>
                            <textarea class="form-control input-sm" form="formregistroentrevista" rows="4" name="evolucion" id="evolucion" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-fedback col-md-6">
                            <label for="observaciones" class="control-label">Observaciones y/o sugerencias:</label>
                            <textarea class="form-control input-sm" form="formregistroentrevista" rows="4" name="observaciones" id="observaciones" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group text-center col-md-12">
                        <button type="submit" class="btn btn-primary" id="btnsubmit">Registrar</button>
                    </div>
                </div>
            </form>
        </div>