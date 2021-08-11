<?php
    require_once("menuserviciosnv.php"); //hola
    if ((!perteneceA($_SESSION['padron_admin_area'], 8) && !perteneceA($_SESSION['padron_admin_area'], 7)) && $_SESSION['padron_admin_permisos'] != 1) {
        alerta_bota("No perteneces al departamento asignado para esta aplicación", "../menu.php");
        //echo "<script>window.location = '../menu.php'</script>";
    }
?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('.date').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    language: 'es',
                    endDate: '0d'
                });
                
                $("#departamento").on("change", function(){
                    $.post('ajax/ajax_getservicionv.php',{"departamento": this.value }, function(respuesta){
                        //alert(respuesta.mensaje);
                        var output = "<option disabled selected>---Seleccione servicio---</option>";
                        output += respuesta.html;
                        $("#servicio").html(output);
                        $("#servicio").prop("disabled", false);
                    });
                });
                
                $("#locacion").on("change", function(){
                    if (this.value == "72") {
                        $.post('ajax/ajax_getcolonias.php',{"locacion": this.value }, function(respuesta){
                        /*alert(respuesta.mensaje);
                        alert(respuesta.html);*/
                        var output = '<div class="form-group has-feedback col-md-6"><label for="colonia" class="control-label">Colonia:</label><select class="form-control input-sm" name="colonia" id="colonia" required><option value="-1" disabled selected>---Seleccione colonia---</option>';
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
        <br />
        <form method="post" action="engine/engine_servicionvregistro.php" id="formregistro" data-toggle="validator">
            <h2 class="text-center">Registro</h2>
            <br />
            <div class="row">
                <div class="form-group has-feedback col-md-6">
                    <label for="departamento">Departamento:</label>
                    <select class="form-control input-sm" name="departamento" id="departamento" required>
                        <option value='-1' disabled selected>---Seleccionar departamento---</option>
                        <?php echo fillSelect($departamentos); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-6">
                    <label for="servicio" class="control-label">Servicio:</label>
                    <select class="form-control input-sm" name="servicio" id="servicio" required disabled>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-6">
                    <label for="locacion" class="control-label">Locación:</label>
                    <select class="form-control input-sm" name="locacion" id="locacion" required>
                        <option value="-1" disabled selected>---Seleccione locación---</option>
                        <?php echo fillSelectLocaciones($locacion); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-6">
                    <label for="fechaservicio" class="control-label">Fecha del servicio:</label>
                    <div class="input-group date" data-provide="datepicker">
                        <input type="text" class="form-control input-sm" name="fechaservicio" id="fechaservicio" data-required-error="Debe llenar este campo" required/>
                        <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                    </div>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 50px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row" id="rowcolonia"></div>
            <h4>Cantidades:</h4>
            <div class="row">
                <div class="form-group has-feedback col-md-4">
                    <label for="mm" class="control-label">Menores masculinos:<small> (0-17 años)</small></label>
                    <input class="form-control input-sm" type="text" id="mm" name="mm" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="am" class="control-label">Adultos masculinos:<small> (18-59 años)</small></label>
                    <input class="form-control input-sm" type="text" id="am" name="am" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="amm" class="control-label">Adultos mayores masculinos:<small> (60+ años)</small></label>
                    <input class="form-control input-sm" type="text" id="amm" name="amm" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-4">
                    <label for="mf" class="control-label">Menores femeninas:<small> (0-17 años)</small></label>
                    <input class="form-control input-sm" type="text" id="mf" name="mf" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="af" class="control-label">Adultas femeninas:<small> (18-59 años)</small></label>
                    <input class="form-control input-sm" type="text" id="af" name="af" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="amf" class="control-label">Adultas mayores femeninas:<small> (60+ años)</small></label>
                    <input class="form-control input-sm" type="text" id="amf" name="amf" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </div>
        </form>