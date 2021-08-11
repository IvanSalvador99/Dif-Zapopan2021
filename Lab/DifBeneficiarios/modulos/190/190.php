<?php
    require_once("../../menupadron.php");
    //var_dump($_POST);
?>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#narracionhechos").on("change keyup paste selectionchange propertychange", function() {
                    var maxlength = $(this).attr('maxlength');
                    var newlines = ($(this).val().match(/\n/g) || []).length;
                    
                    if ($(this).val().length + newlines > maxlength) {
                        alert("Ha superado el numero de caracteres permitidos (2000)");
                        $(this).val($(this).val().substring(0, maxlength - newlines));
                    }
                });
                
                $("#btnsubmit").on("click", function(e) {
                    e.preventDefault();
                    if(($(this).attr("class")).includes("disabled")) {
                        alert ("Boton desabilitado favor de llenar la forma correctamente");
                    } else {
                        var form = $("#formregistrocasorep");
                        
                        form.append($("<input type='hidden' name='idpersona'/>").val(<?=$_POST['idpersona']?>));
                        form.append($("<input type='hidden' name='servicio'/>").val(<?=$_POST['servicio']?>));
                        form.append($("<input type='hidden' name='locacion'/>").val(<?=$_POST['locacion']?>));
                        form.append($("<input type='hidden' name='fechaservicio'/>").val("<?=$_POST['fechaservicio']?>"));
                        form.append($("<input type='hidden' name='idservicio'/>").val(<?php echo "`".$_POST['idservicio']."`" ?>));
                        form.get(0).submit();
                    }
                });
                
                $(".caps").on("blur", function(e){
                    $(this).val($(this).val().toUpperCase());
                });
                
                if ($("#reportanteanonimo").prop("checked") == true) {
                    $("#divreportante").hide();
                    $("#reportanteanonimo").trigger("change");
                } else {
                    $("#divreportante").show();
                    $("#reportanteanonimo").trigger("change");
                }
                
                $("#reportanteanonimo").change(function() {
                    if(this.checked) {
                        $("#divreportante").hide();
                        $("#nombrereportante").prop("required", false);
                        $("#apellidopreportante").prop("required", false);
                        $("#domicilioreportante").prop("required", false);
                        $("#telefonoreportante").prop("required", false);
                        $("#nombrereportante").prop("disabled", true);
                        $("#apellidopreportante").prop("disabled", true);
                        $("#apellidomreportante").prop("disabled", true);
                        $("#domicilioreportante").prop("disabled", true);
                        $("#telefonoreportante").prop("disabled", true);
                    } else {
                        $("#divreportante").show();
                        $("#nombrereportante").prop("required", true);
                        $("#apellidopreportante").prop("required", true);
                        $("#domicilioreportante").prop("required", true);
                        $("#telefonoreportante").prop("required", true);
                        $("#nombrereportante").prop("disabled", false);
                        $("#apellidopreportante").prop("disabled", false);
                        $("#apellidomreportante").prop("disabled", false);
                        $("#domicilioreportante").prop("disabled", false);
                        $("#telefonoreportante").prop("disabled", false);
                    }
                });
            });
        </script>
        <div>
            <h2 class="text-center">Registro de Caso Reportado</h2><br />
            <h4>Favor de llenar los siguientes datos</h4>
            <form method="post" action="engine/engine_190.php" id="formregistrocasorep" data-toggle="validator">
                <div class="row">
                    <div class="form-group col-md-4 has-feedback">
                        <label for="noexp">Numero de Expediente:<small> *</small></label>
                        <input class="form-control input-sm caps" type="text" id="noexp" name="noexp" pattern="^(UCEN|UPAU|UCCC|UFSA|USLU|ULAG|UAVF|CTAA)\/[0-9]{4}\/[0-9]{4}$" data-pattern-error="El formato es incorrecto" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <h4>Tipo de maltrato:</h4>
                <div class="row">
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="tipomaltrato1" name="tipomaltrato[]" value="1"/> 1 - Físico.</label>                        
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="tipomaltrato2" name="tipomaltrato[]" value="2"/> 2 - Psicológico.</label>
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="tipomaltrato3" name="tipomaltrato[]" value="3"/> 3 - Omisión de cuidados.</label>
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="tipomaltrato4" name="tipomaltrato[]" value="4"/> 4 - Abandono.</label>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="tipomaltrato5" name="tipomaltrato[]" value="5"/> 5 - Abuso sexual.</label>                        
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="tipomaltrato6" name="tipomaltrato[]" value="6"/> 6 - Explotación laboral.</label>
                    </div>
                </div><br /><br />
                <h4>Derechos vulnerados:</h4>
                <div class="row">
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados1" name="derechosvulnerados[]" value="1"/> 1 - Derecho a la vida, a la supervivencia y al desarrollo.</label>                        
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados2" name="derechosvulnerados[]" value="2"/> 2 - Derecho de prioridad.</label>
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados3" name="derechosvulnerados[]" value="3"/> 3 - Derecho a la identidad.</label>
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados4" name="derechosvulnerados[]" value="4"/> 4 - Derecho a vivir en familia.</label>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados5" name="derechosvulnerados[]" value="5"/> 5 - Derecho a la igualdad sustantiva.</label>                        
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados6" name="derechosvulnerados[]" value="6"/> 6 - Derecho a no ser discriminado.</label>
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados7" name="derechosvulnerados[]" value="7"/> 7 - Derecho a vivir en condiciones de bienestar y a un sano desarrollo integral.</label>
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados8" name="derechosvulnerados[]" value="8"/> 8 - Derecho a una vida libre de violencia y a la integridad personal.</label>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados9" name="derechosvulnerados[]" value="9"/> 9 - Derecho a la protección de la salud y a la seguridad social.</label>                        
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados10" name="derechosvulnerados[]" value="10"/> 10 - Derecho a la inclusión de niñas, niños y adolescentes con discapacidad.</label>
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados11" name="derechosvulnerados[]" value="11"/> 11 - Derecho a la educación.</label>
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados12" name="derechosvulnerados[]" value="12"/> 12 - Derecho al descanso y al esparcimiento.</label>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados13" name="derechosvulnerados[]" value="13"/> 13 - Derecho a la libertad de convicciones éticas, pensamiento, conciencia, religion y cultura.</label>
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados14" name="derechosvulnerados[]" value="14"/> 14 - Derecho a la libertad de expresión y de acceso a la información.</label>
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados15" name="derechosvulnerados[]" value="15"/> 15 - Derecho de participación.</label>
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados16" name="derechosvulnerados[]" value="16"/> 16 - Derecho de asociación y reunión.</label>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados17" name="derechosvulnerados[]" value="17"/> 17 - Derecho a la intimidad.</label>
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados18" name="derechosvulnerados[]" value="18"/> 18 - Derecho a la seguridad jurídica y al debido proceso.</label>
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados19" name="derechosvulnerados[]" value="19"/> 19 - Derecho de niñas, niños y adolescentes migrantes.</label>
                    </div>
                    <div class="form-group col-md-3 checkbox">
                        <label><input type="checkbox" id="derechosvulnerados20" name="derechosvulnerados[]" value="20"/> 20 - Derecho de acceso a las tecnologías de la información y comunicación.</label>
                    </div>
                </div>
                <h4>Datos generales del posible agresor:</h4>
                <div class="row">
                    <div class="form-group has-feedback col-md-4 caps">
                        <label for="nombreagresor" class="control-label">Nombre(s):</label>
                        <input class="form-control input-sm caps" type="text" id="nombreagresor" name="nombreagresor" pattern="^[A-Za-zñÑ .]{1,}$" data-pattern-error="Solo letras (sin acentos)" data-required-error="Debe llenar este campo"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4 caps">
                        <label for="apellidopagresor" class="control-label">Apellido Paterno:</label>
                        <input class="form-control input-sm caps" type="text" id="apellidopagresor" name="apellidopagresor" pattern="^[A-Za-zñÑ ]{1,}$" data-pattern-error="Solo letras (sin acentos)" data-required-error="Debe llenar este campo"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4 caps">
                        <label for="apellidomagresor" class="control-label">Apellido Materno:</label>
                        <input class="form-control input-sm caps" type="text" id="apellidomagresor" name="apellidomagresor" pattern="^[A-Za-zñÑ ]{1,}$" data-pattern-error="Solo letras (sin acentos)"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="edadagresor" class="control-label">Edad:</label>
                        <input type="text" class="form-control input-sm" id="edadagresor" name="edadagresor" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="sexoagresor" class="control-label">Sexo:</label>
                        <select class="form-control input-sm" name="sexoagresor" id="sexoagresor">
                            <option value="-1" disabled selected>---Seleccione sexo---</option>
                            <?php echo fillSelect($sexo); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="paisagresor" class="control-label">Nacionalidad:</label>
                        <select class="form-control input-sm" name="paisagresor" id="paisagresor">
                            <option value="-1" disabled selected>---Seleccione un país---</option>
                            <?php echo fillSelectData($paises, 144); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="nivelescolaragresor" class="control-label">Nivel escolar:</label>
                        <select class="form-control input-sm" name="nivelescolaragresor" id="nivelescolaragresor">
                            <option value="-1" disabled selected>---Seleccione nivel escolar---</option>
                            <?php echo fillSelect($escolaridad); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="ocupacionagresor" class="control-label">Ocupaci&oacute;n:</label>
                        <select class="form-control input-sm" name="ocupacionagresor" id="ocupacionagresor">
                            <option value="-1" disabled selected>---Seleccione ocupaci&oacute;n---</option>
                            <?php echo fillSelect($ocupacion); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="estadocivilagresor" class="control-label">Estado civil:</label>
                        <select class="form-control input-sm" name="estadocivilagresor" id="estadocivilagresor">
                            <option value="-1" disabled selected>---Seleccione estado civil---</option>
                            <?php echo fillSelect($estado_civil); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="parentescoagresor">Parentesco:</label>
                        <select class="form-control input-sm" name="parentescoagresor" id="parentescoagresor">
                            <option value="-1" disabled selected>---Seleccione el parentesco---</option>
                            <?php echo fillSelect($parentesco); ?>
                        </select>
                    </div>
                </div>
                <h4>Datos generales del reportante:</h4><label><input type="checkbox" id="reportanteanonimo" name="reportanteanonimo" value="1"/>  ¿Reportante anonimo?</label>
                <br /><br />
                <div id="divreportante">
                    <div class="row">
                        <div class="form-group has-feedback col-md-4 caps">
                            <label for="nombrereportante" class="control-label">Nombre(s):<small> *</small></label>
                            <input class="form-control input-sm caps" type="text" id="nombrereportante" name="nombrereportante" pattern="^[A-Za-zñÑ .]{1,}$" data-pattern-error="Solo letras (sin acentos)" data-required-error="Debe llenar este campo"/>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback col-md-4 caps">
                            <label for="apellidopreportante" class="control-label">Apellido Paterno:<small> *</small></label>
                            <input class="form-control input-sm caps" type="text" id="apellidopreportante" name="apellidopreportante" pattern="^[A-Za-zñÑ ]{1,}$" data-pattern-error="Solo letras (sin acentos)" data-required-error="Debe llenar este campo"/>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback col-md-4 caps">
                            <label for="apellidomreportante" class="control-label">Apellido Materno:</label>
                            <input class="form-control input-sm caps" type="text" id="apellidomreportante" name="apellidomreportante" pattern="^[A-Za-zñÑ ]{1,}$" data-pattern-error="Solo letras (sin acentos)"/>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group has-feedback col-md-8">
                            <label for="domicilioreportante" class="control-label">Domicilio:<small> *</small></label>
                            <input class="form-control input-sm" type="text" id="domicilioreportante" name="domicilioreportante" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento), numeros, coma y punto" data-required-error="Debe llenar este campo"/>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback col-md-4">
                            <label for="telefonoreportante" class="control-label">Telefono:<small> *(10 digitos)</small></label>
                            <input class="form-control input-sm" type="text" id="telefonoreportante" name="telefonoreportante" pattern="^[0-9]{10}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo"/>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px;"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="form-group col-md-4 has-feedback">
                        <label for="procedenciareporte" class="control-label">Procedencia del reporte:<small> *</small></label>
                        <select class="form-control input-sm" name="procedenciareport" id="procedenciareport" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelect($procedencia_reporte);?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <h4>Narración de hechos:</h4>
                <div class="row">
                    <div class="form-group has-fedback col-md-12">
                        <textarea class="form-control input-sm" form="formregistrocasorep" rows="4" name="narracionhechos" id="narracionhechos" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <h4>Acciones legales realizadas</h4>
                <h5>Denuncia</h5>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="carpetainv" class="control-label">Carpeta de investigación:</label>
                        <input class="form-control input-sm" type="text" id="carpetainv" name="carpetainv" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento), numeros, coma y punto" data-required-error="Debe llenar este campo"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="agencia" class="control-label">Agencia:</label>
                        <input class="form-control input-sm" type="text" id="agencia" name="agencia" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento), numeros, coma y punto" data-required-error="Debe llenar este campo"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="procedenciareporte" class="control-label">Delito:</label>
                        <select class="form-control input-sm" name="delito" id="delito">
                            <option value="-1" selected>---Seleccione---</option>
                            <?php echo fillSelect($delito);?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <h5>Juicio</h5>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="juicioexp" class="control-label">Numero de expediente:</label>
                        <input class="form-control input-sm" type="text" id="juicioexp" name="juicioexp" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento), numeros, coma y punto" data-required-error="Debe llenar este campo"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="juzgado" class="control-label">Juzgado:</label>
                        <input class="form-control input-sm" type="text" id="juzgado" name="juzgado" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento), numeros, coma y punto" data-required-error="Debe llenar este campo"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group col-md-4 has-feedback">
                        <label for="tipojuicio" class="control-label">Tipo de juicio:</label>
                        <select class="form-control input-sm" name="tipojuicio" id="tipojuicio">
                            <option value="-1" selected>---Seleccione---</option>
                            <?php echo fillSelect($tipo_juicio);?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <h4>Medida de protección</h4>
                <div class="row">
                    <div class="form-group col-md-6 has-feedback">
                        <label for="medidaurgente" class="control-label">Urgente:</label>
                        <select class="form-control input-sm" name="medidaurgente" id="medidaurgente">
                            <option value="-1" selected>---Seleccione---</option>
                            <?php echo fillSelect($derechos_nna);?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group col-md-6 has-feedback">
                        <label for="medidaespecial" class="control-label">Especial:</label>
                        <select class="form-control input-sm" name="medidaespecial" id="medidaespecial">
                            <option value="-1" selected>---Seleccione---</option>
                            <?php echo fillSelect($derechos_nna);?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group text-center col-md-12">
                        <button type="submit" class="btn btn-primary" id="btnsubmit">Registrar</button>
                    </div>
                </div>
            </form>
        </div>