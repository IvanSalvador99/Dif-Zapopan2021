<?php
    require_once("../../menupadron.php");
    //var_dump($_POST);
    $arrayparentesco = array("null");
    
    $query = "SELECT * FROM $vw_persona WHERE id = '" . $_POST['idpersona'] . "'";
    
    if ($result = mysql_query($query, $con)){
        $row = mysql_fetch_array($result);
        /*echo "</br></br>Row:</br>";
        var_dump($row);*/
    } else {
        die (mysql_error());
    }
    
    //var_dump($row);
?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('.date').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    language: 'es',
                    endDate: '0d'
                });
                
                $("#btnsubmit").on("click", function(e) {
                    e.preventDefault();
                    if(($(this).attr("class")).includes("disabled")) {
                        alert ("Boton desabilitado favor de llenar la forma correctamente");
                    } else {
                        var form = $("#form481");
                        
                        form.append($("<input type='hidden' name='idpersona'/>").val(<?=$_POST['idpersona']?>));
                        form.append($("<input type='hidden' name='servicio'/>").val(<?=$_POST['servicio']?>));
                        form.append($("<input type='hidden' name='locacion'/>").val(<?=$_POST['locacion']?>));
                        form.append($("<input type='hidden' name='fechaservicio'/>").val("<?=$_POST['fechaservicio']?>"));
                        form.append($("<input type='hidden' name='idservicio'/>").val(<?php echo "`".$_POST['idservicio']."`" ?>));
                        form.get(0).submit();
                    }
                });
            });
        </script>
        <div>
            <h2 class="text-center">Registro acompañamiento a la ausencia</h2><br />
            <br />
            <form method="post" action="engine/engine_481.php" id="form481" data-toggle="validator" enctype="multipart/form-data">
                <h4>Datos de la persona desaparecida:</h4>
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
                        <label for="apaterno" class="control-label">Apellido Paterno:<small> *</small></label>
                        <input class="form-control input-sm caps" type="text" id="apaterno" name="apaterno" pattern="^[A-Za-zñÑ ]{1,}$" data-pattern-error="Solo letras (sin acentos)" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="amaterno" class="control-label">Apellido Materno:</label>
                        <input class="form-control input-sm caps" type="text" id="amaterno" name="amaterno" pattern="^[A-Za-zñÑ ]{1,}$" data-pattern-error="Solo letras (sin acentos)"/>
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
                        <label for="fechadesaparicion" class="control-label">Fecha de la desaparici&oacute;n:<small> *</small></label>
                        <div class="input-group date" data-provide="datepicker">
                            <input type="text" class="form-control" name="fechadesaparicion" id="fechadesaparicion" data-required-error="Debe llenar este campo" required/>
                            <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                        </div>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 50px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="haydenuncia" class="control-label">¿Cuenta con denuncia en fiscalia?:<small> *</small></label>
                        <select class="form-control input-sm" name="haydenuncia" id="haydenuncia" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <option value="1">Si</option>
                            <option value="2">No</option>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="documento" class="control-label">Documento de acreditación: <small> *</small></label>
                        <input type="file" class="form-control-file fileinput" name="documento" id="documento" data-required-error="Debe subir la identificaci&oacute;n" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="compartir" class="control-label">¿Autoriza a compartir datos con comisar&iacute;a?:<small> *</small></label>
                        <select class="form-control input-sm" name="compartir" id="compartir" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="localizado" class="control-label">¿Ha sido localizado(a)?:<small> *</small></label>
                        <select class="form-control input-sm" name="localizado" id="localizado" required>
                            <option value="1">Si</option>
                            <option value="0" selected>No</option>
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