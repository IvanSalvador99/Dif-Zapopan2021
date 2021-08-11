<?php
    require_once("menupadron.php"); //hola
    
    alerta_bota("<h1>Estas por capturar un beneficiario nuevo.</br></br> ¿Ya revisaste que este no exista en el sistema?</h1>", 0);
?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('.date').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    language: 'es',
                    endDate: '0d'
                });
                
                if ($("#haytutor").prop("checked") == true) {
                    $("#divtutor").show();
                    $("#haytutor").trigger("change");
                } else {
                    $("#divtutor").hide();
                    $("#haytutor").trigger("change");
                }
                
                $("#haytutor").change(function() {
                    if(this.checked) {
                        $("#divtutor").show();
                        $("#parentesco").prop("required", true);
                        $("#nombretutor").prop("required", true);
                        $("#apellidoptutor").prop("required", true);
                        $("#sexotutor").prop("required", true);
                        $("#tutorfechanacimiento").prop("required", true);
                    } else {
                        $("#divtutor").hide();
                        $("#parentesco").prop("required", false);
                        $("#nombretutor").prop("required", false);
                        $("#apellidoptutor").prop("required", false);
                        $("#sexotutor").prop("required", false);
                        $("#tutorfechanacimiento").prop("required", false);
                        $("#curptutor").val("");
                        $("#parentesco").val(-1);
                        $("#nombretutor").val("");
                        $("#apellidoptutor").val("");
                        $("#apellidomtutor").val("");
                        $("#sexotutor").val(-1);
                        $("#tutorfechanacimiento").val("");
                    }
                });
                
                $("#sinnumero").change(function(){
                    if (this.checked) {
                        $("#numext").prop("disabled", true);
                        $("#numext").prop("required", false);
                        $("#numext").val("");
                    } else {
                        $("#numext").prop("disabled", false);
                        $("#numext").prop("required", true);
                        $("#numext").val("");
                    }
                });
                
                $("#cp").blur(function(e){
                    //alert("Hola");
                    var output = "<option disabled selected>---Obteniendo colonia(s)...</option>";
                    $("#estado").val("Obteniendo estado...");
                    $("#municipio").val("Obteniendo municipio...");
                    $("#colonia").html(output);
                    $.post('ajax/ajax_colmunest.php',{"cp": this.value }, function(respuesta){
                        //alert(respuesta.mensaje);
                        var output = "<option disabled selected>---Seleccione una colonia---</option>";
                        $("#estado").val(respuesta.estado);
                        $("#municipio").val(respuesta.municipio);
                        $.each(respuesta.codigos, function(index, value) {
                            output += "<option value='" + respuesta.ids[index] + "'>" + value + "</option>";
                        });
                        $("#colonia").html(output);
                        $("#colonia").prop("disabled", false);
                    });
                });
                
                $(".caps").blur(function(e){
                    $(this).val($(this).val().toUpperCase());
                });
                
                $('#formregistro').submit(function(eventObj) {
                    $(this).append('<input type="hidden" name="coloniatexto" value="' + $("#colonia option:selected").html() + '" /> ');
                    $(this).append('<input type="hidden" name="parentezcotexto" value="' + $("#parentesco option:selected").html() + '" /> ');
                    return true;
                });
            });
        </script>
        <form method="post" action="engine/engine_beneficiarioregistro.php" id="formregistro" data-toggle="validator">
            <h2 class="text-center">Registro</h2>
            <br />
            <h4>Datos de referencia para DIF Zapopan</h4>
            <div class="row">
                <div class="form-group has-feedback col-md-4">
                    <label for="perfilatencion" class="control-label">Perfil de atenc&oacute;n:<small> *</small></label>
                    <select class="form-control input-sm" name="perfilatencion" id="perfilatencion" required>
                        <option value="-1" disabled selected>---Seleccione perfil de atenci&oacute;n---</option>
                        <?php echo fillSelect($perfil_atencion); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="problematicadpna" class="control-label">Problematica DPNNA (solo para casos DPNNA):</label>
                    <select class="form-control input-sm" name="problematicadpna" id="problematicadpna">
                        <option value="-1" disabled selected>---Seleccione problematica---</option>
                        <?php echo fillSelect($problematicadpna); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <h4>Datos generales:</h4>
            <div class="row">
                <div class="form-group has-feedback col-md-4">
                    <label for="curp" class="control-label">CURP:</label>
                    <input class="form-control input-sm caps" type="text" id="curp" name="curp" data-pattern-error="No es un formato valido de CURP" pattern="^[A-Za-z]{1}[AEIOUXaeioux]{1}[A-Za-z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HMhm]{1}(AS|as|BC|bc|BS|bs|CC|cc|CS|cs|CH|ch|CL|cl|CM|cm|DF|df|DG|dg|GT|gt|GR|gr|HG|hg|JC|jc|MC|mc|MN|mn|MS|ms|NT|nt|NL|nl|OC|oc|PL|pl|QT|qt|QR|qr|SP|sp|SL|sl|SR|sr|TC|tc|TS|ts|TL|tl|VZ|vz|YN|yn|ZS|zs|NE|ne)[B-DF-HJ-NP-TV-Zb-df-hj-np-tv-z]{3}[0-9A-Za-z]{1}[0-9]{1}$"/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-4">
                    <label for="nombre" class="control-label">Nombre(s):<small> *</small></label>
                    <input class="form-control input-sm caps" type="text" id="nombre" name="nombre" pattern="^[A-Za-zñÑ .]{1,}$" data-pattern-error="Solo letras (sin acentos)" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="apellidop" class="control-label">Apellido Paterno:<small> *</small></label>
                    <input class="form-control input-sm caps" type="text" id="apellidop" name="apellidop" pattern="^[A-Za-zñÑ ]{1,}$" data-pattern-error="Solo letras (sin acentos)" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="apellidom" class="control-label">Apellido Materno:</label>
                    <input class="form-control input-sm caps" type="text" id="apellidom" name="apellidom" pattern="^[A-Za-zñÑ ]{1,}$" data-pattern-error="Solo letras (sin acentos)"/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-4">
                    <label for="fechanacimiento" class="control-label">Fecha de nacimiento:<small> *</small></label>
                    <div class="input-group date" data-provide="datepicker">
                        <input type="text" class="form-control" name="fechanacimiento" id="fechanacimiento" data-required-error="Debe llenar este campo" required/>
                        <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                    </div>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 50px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="sexo" class="control-label">Sexo:<small> *</small></label>
                    <select class="form-control input-sm" name="sexo" id="sexo" required>
                        <option value="-1" disabled selected>---Seleccione sexo---</option>
                        <?php echo fillSelect($sexo); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="estadocivil" class="control-label">Estado civil:<small> *</small></label>
                    <select class="form-control input-sm" name="estadocivil" id="estadocivil" required>
                        <option value="-1" disabled selected>---Seleccione estado civil---</option>
                        <?php echo fillSelect($estado_civil); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-4">
                    <label for="ocupacion" class="control-label">Ocupaci&oacute;n:<small> *</small></label>
                    <select class="form-control input-sm" name="ocupacion" id="ocupacion" required>
                        <option value="-1" disabled selected>---Seleccione ocupaci&oacute;n---</option>
                        <?php echo fillSelect($ocupacion); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="nivelescolar" class="control-label">Nivel escolar:<small></small></label>
                    <select class="form-control input-sm" name="nivelescolar" id="nivelescolar" required>
                        <option value="-1" disabled selected>---Seleccione nivel escolar---</option>
                        <?php echo fillSelect($escolaridad); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-4">
                    <label for="lenguamaterna" class="control-label">Lengua materna:<small> *</small></label>
                    <select class="form-control input-sm" name="lenguamaterna" id="lenguamaterna" required>
                        <option value="-1" disabled selected>---Seleccione un lenguaje---</option>
                        <?php echo fillSelect($idioma); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="lenguasecundaria" class="control-label">Lengua secundaria:</label>
                    <select class="form-control input-sm" name="lenguasecundaria" id="lenguasecundaria">
                        <option value="-1" disabled selected>---Seleccione un lenguaje---</option>
                        <?php echo fillSelectData($idioma, 25); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="enfermedadprevia" class="control-label">Enfermedad cronica o discapacidad:</label>
                    <select class="form-control input-sm" name="enfermedadprevia" id="enfermedadprevia">
                        <option value="-1" disabled selected>---Seleccione enfermedad o discapacidad---</option>
                        <?php echo fillSelect($enfermedades); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-6">
                    <label for="serviciosmedicos">Institución de servicios medicos:</label>
                    <select class="form-control input-sm" name="serviciosmedicos" id="serviciosmedicos" required>
                        <option value="-1" disabled selected>---Seleccione institución---</option>
                        <?php echo fillSelect($servicios_medicos); ?>
                    </select>
                </div>
            </div>
            <h4>Datos de contacto:</h4>
            <div class="row">
                <div class="form-group has-feedback col-md-6">
                    <label for="calle" class="control-label">Calle:<small> *</small></label>
                    <input class="form-control input-sm" type="text" id="calle" name="calle" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento), coma y punto" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-3">
                    <label for="numext" class="control-label" style="margin-bottom: 0px">Numero exterior:<small>  <label>¿Sin numero? <input type="checkbox" name="sinnumero" id="sinnumero"/></label></small></label>
                    <input class="form-control input-sm" type="text" id="numext" name="numext" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-3">
                    <label for="numint" class="control-label">Numero interior:</label>
                    <input class="form-control input-sm caps" type="text" id="numint" name="numint" pattern="^[0-9A-za-z]{1,}$" data-pattern-error="Solo numeros y letras" data-required-error="Debe llenar este campo"/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-4">
                    <label for="primercruce" class="control-label">Calle de interseccion:</label>
                    <input class="form-control input-sm" type="text" id="primercruce" name="primercruce" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento), coma y punto" data-required-error="Debe llenar este campo"/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="segundocruce" class="control-label">Calle de interseccion:</label>
                    <input class="form-control input-sm" type="text" id="segundocruce" name="segundocruce" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento), coma y punto" data-required-error="Debe llenar este campo"/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="vivienda">Tipo de vivienda:<small> *</small></label>
                    <select class="form-control input-sm" name="vivienda" id="vivienda" required>
                        <option value="-1" disabled selected>---Seleccione el tipo de vivienda---</option>
                        <?php echo fillSelect($vivienda); ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <p style="margin: 0; padding: 0; margin-left: 16px"><small>Al escribir el codigo postal automaticamente aparecera el estado, municipio y una lista de colonias para elegir</small></p>
                <div class="form-group has-feedback col-md-3">
                    <label for="cp" class="control-label">Codigo postal:<small> *</small></label>
                    <input class="form-control input-sm" type="text" id="cp" name="cp" pattern="^[0-9]{4,5}$" data-pattern-error="Solo 4 o 5 numeros" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group">
                    <a data-toggle="modal" data-target="#mymodal" class="btn btn-default" style="margin-top: 23px">Buscar CP</a>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-4">
                    <label for="estado" class="control-label">Estado:<small> *</small></label>
                    <input class="form-control input-sm" type="text" id="estado" name="estado" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras" data-required-error="Debe llenar este campo" required readonly/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="municipio" class="control-label">Municipio:<small> *</small></label>
                    <input class="form-control input-sm" type="text" id="municipio" name="municipio" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras" data-required-error="Debe llenar este campo" required readonly/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="colonia" class="control-label">Colonia:<small> *</small></label>
                    <select class="form-control input-sm" name="colonia" id="colonia" required disabled></select>                        
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-4">
                    <label for="telefono" class="control-label">Telefono:<small> (10 digitos) <label style="margin: 0;">¿Es de contacto? <input type="checkbox" name="escontacto" id="escontacto" style="margin: 0;"/></label></small></label>
                    <input class="form-control input-sm" type="text" id="telefono" name="telefono" pattern="^[0-9]{10}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo"/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px;"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="celular" class="control-label">Celular:<small> 10 digitos</small></label>
                    <input class="form-control input-sm" type="text" id="celular" name="celular" pattern="^[0-9]{10}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo"/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="email" class="control-label">E-mail:</label>
                    <input class="form-control input-sm" type="text" id="email" name="email" pattern="^[a-zA-Z0-9_]+(?:\.[A-Za-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?!([a-zA-Z0-9]*\.[a-zA-Z0-9]*\.[a-zA-Z0-9]*\.))(?:[A-Za-z0-9](?:[a-zA-Z0-9-]*[A-Za-z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$" data-pattern-error="No es un email valido" data-required-error="Debe llenar este campo"/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="checkbox col-md-12">
                    <label><input type="checkbox" name="haytutor" id="haytutor"/>¿Registra tutor?</label>
                </div>
            </div>
            <div id="divtutor">
                <h4>Datos del tutor/apoderado:</h4>
                <div class="row">
                    <p style="margin: 0; padding: 0; margin-left: 16px"><small></small></p>
                    <div class="form-group has-feedback col-md-4">
                        <label for="curptutor" class="control-label">Curp:</label>
                        <input class="form-control input-sm caps" type="text" id="curptutor" name="curptutor" data-pattern-error="No es un formato valido de CURP" pattern="^[A-Za-z]{1}[AEIOUaeiou]{1}[A-Za-z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HMhm]{1}(AS|as|BC|bc|BS|bs|CC|cc|CS|cs|CH|ch|CL|cl|CM|cm|DF|df|DG|dg|GT|gt|GR|gr|HG|hg|JC|jc|MC|mc|MN|mn|MS|ms|NT|nt|NL|nl|OC|oc|PL|pl|QT|qt|QR|qr|SP|sp|SL|sl|SR|sr|TC|tc|TS|ts|TL|tl|VZ|vz|YN|yn|ZS|zs|NE|ne)[B-DF-HJ-NP-TV-Zb-df-hj-np-tv-z]{3}[0-9A-Za-z]{1}[0-9]{1}$"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="parentesco">Parentesco:<small> *</small></label>
                        <select class="form-control input-sm" name="parentesco" id="parentesco">
                            <option value="-1" disabled selected>---Seleccione el parentesco---</option>
                            <?php echo fillSelect($parentesco); ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="nombretutor" class="control-label">Nombre(s):<small> *</small></label>
                        <input class="form-control input-sm caps" type="text" id="nombretutor" name="nombretutor" pattern="^[A-Za-zñÑ .]{1,}$" data-pattern-error="Solo letras (sin acento)" data-required-error="Debe llenar este campo"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="apellidoptutor" class="control-label">Apellido Paterno:<small> *</small></label>
                        <input class="form-control input-sm caps" type="text" id="apellidoptutor" name="apellidoptutor" pattern="^[A-Za-zñÑ ]{1,}$" data-pattern-error="Solo letras (sin acento)" data-required-error="Debe llenar este campo"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="apellidomtutor" class="control-label">Apellido Materno:</label>
                        <input class="form-control input-sm caps" type="text" id="apellidomtutor" name="apellidomtutor" pattern="^[A-Za-zñÑ ]{1,}$" data-pattern-error="Solo letras (sin acento)"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form/group has feedback col-md-4">
                        <label for="sexotutor" class="control-label">Sexo:</label>
                        <select class="form-control input-sm" name="sexotutor" id="sexotutor">
                            <option value="-1" disabled selected>---Seleccione sexo---</option>
                            <?php echo fillSelect($sexo); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>                        
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="tutorfechanacimiento" class="control-label">Fecha de nacimiento:<small> *</small></label>
                        <div class="input-group date" data-provide="datepicker">
                            <input type="text" class="form-control" name="tutorfechanacimiento" id="tutorfechanacimiento" data-required-error="Debe llenar este campo"/>
                            <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                        </div>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 50px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </div>
        </form>
        <div id="mymodal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4>Buscar codigo postal</h4>
                    </div>
                    <div class="modal-body">
                        <iframe src="http://www.correosdemexico.gob.mx/lservicios/servicios/Descarga.aspx" style="width: 100%"; height="400px"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>