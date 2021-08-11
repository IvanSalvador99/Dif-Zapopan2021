<?php
    require_once("../../menupadron.php");
    
    if ((!perteneceA($_SESSION['padron_admin_area'], 8) && !perteneceA($_SESSION['padron_admin_area'], 7)) && $_SESSION['padron_admin_permisos'] != 1) {
        alerta_bota("No perteneces al departamento asignado para esta aplicación", "../../../menu.php");
        //echo "<script>window.location = '../menu.php'</script>";
    }
    
    $query = "SELECT * FROM $personas WHERE id = '" . $_POST['idpersona'] . "'";
    if ($result = mysql_query($query, $con)) {
        $rowpersona = mysql_fetch_array($result);
    }
    
    
?>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#checkboxseguimiento").attr("checked", false);
                
                $("#btnsubmit").on("click", function(e) {
                    e.preventDefault();
                    if(($(this).attr("class")).includes("disabled")) {
                        alert ("Boton desabilitado favor de llenar la forma correctamente");
                    } else {
                        var form = $("#formregistroescpadres");
                        form.append($("<input type='hidden' name='idpersona'/>").val(<?=$_POST['idpersona']?>));
                        form.append($("<input type='hidden' name='servicio'/>").val(<?=$_POST['servicio']?>));
                        form.append($("<input type='hidden' name='locacion'/>").val(<?=$_POST['locacion']?>));
                        form.append($("<input type='hidden' name='fechaservicio'/>").val("<?=$_POST['fechaservicio']?>"));
                        form.get(0).submit();
                    }
                });
                
                $("#checkboxseguimiento").on("change", function(){
                    if (this.checked) {
                        //alert ("Activado");
                        $.post('../../ajax/ajax_datos_escpadres.php',{"idpersona": <?=$_POST['idpersona']?> }, function(respuesta){
                            //alert(respuesta.persona[4]);
                            $("#discapacidad option[value='" + respuesta.persona[7] + "']").prop("selected", true);
                            $("#grupoetnico option[value='" + respuesta.persona[8] + "']").prop("selected", true);
                            $("#estadonacimiento option:contains('" + respuesta.persona[13] + "')").prop("selected", true);
                            $("#tipoasentamiento option[value='" + respuesta.persona[10] + "']").prop("selected", true);
                            $("#asentamiento").val(respuesta.persona[9]);
                            $("#tipovialidad option[value='" + respuesta.persona[3] + "']").prop("selected", true);
                            $("#tipoapoyo option[value='" + respuesta.persona[12] + "']").prop("selected", true);
                            $("#grupo").val(respuesta.persona[5]);
                            $("#consecutivo").val(respuesta.persona[6]);
                            $("#orientador option[value='" + respuesta.persona[4] + "']").prop("selected", true);
                            $("#formregistroescpadres").validator('validate');
                        });
                    } else {
                        //alert ("Desactivado");
                        $("#discapacidad option[value='-1']").prop("selected", true);
                        $("#grupoetnico option[value='-1']").prop("selected", true);
                        $("#estadonacimiento option[value='-1']").prop("selected", true);
                        $("#tipoasentamiento option[value='-1']").prop("selected", true);
                        $("#asentamiento").val("");
                        $("#tipovialidad option[value='-1']").prop("selected", true);
                        $("#tipoapoyo option[value='-1']").prop("selected", true);
                        $("#grupo").val("");
                        $("#consecutivo").val("");
                        $("#orientador option[value='-1']").prop("selected", true);
                        $("#formregistroescpadres").validator('validate');
                    }
                });
                
            });
        </script>
        <div>
            <h2 class="text-center">Escuela para padres</h2>
            <h3 class="text-center"><?php echo $rowpersona['nombre'] . " " . $rowpersona['apaterno'] . " " . $rowpersona['amaterno'] ?></h3>
            <br />
            <div class="checkbox"><label><input type="checkbox" id="checkboxseguimiento" />Es seguimiento?</label></div>
            <form method="post" action="engine/engine_175.php" id="formregistroescpadres" data-toggle="validator">
                <div class="row">
                    <div class="form-group has-feedback col-md-6">
                        <label for="discapacidad">Discapacidad:</label>
                        <select class="form-control input-sm" name="discapacidad" id="discapacidad" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelect($escpad_discapacidad); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-6">
                        <label for="grupoetnico">¿Pertenece a un grupo etnico?</label>
                        <select class="form-control input-sm" name="grupoetnico" id="grupoetnico" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="estadonacimiento" class="control-label">Estado de nacimiento:<small> *</small></label>
                        <select class="form-control input-sm" name="estadonacimiento" id="estadonacimiento" required>
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
                        <label for="tipoasentamiento">Tipo de asentamiento:</label>
                        <select class="form-control input-sm" name="tipoasentamiento" id="tipoasentamiento" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelect($escpad_tipo_asentamiento); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="asentamiento" class="control-label">Asentamiento:</label>
                        <input class="form-control input-sm caps" type="text" id="asentamiento" name="asentamiento" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="tipovialidad">Tipo de vialidad:</label>
                        <select class="form-control input-sm" name="tipovialidad" id="tipovialidad" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelect($escpad_tipo_vialidad); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="form-group has-feedback col-md-6">
                        <label for="tipoapoyo">Tipo de apoyo:</label>
                        <select class="form-control input-sm" name="tipoapoyo" id="tipoapoyo" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelect($escpad_tipo_apoyo); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-6">
                        <label for="grupo" class="control-label">Grupo:</label>
                        <input class="form-control input-sm caps" type="text" id="grupo" name="grupo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-6">
                        <label for="consecutivo" class="control-label">Consecutivo:</label>
                        <input class="form-control input-sm caps" type="text" id="consecutivo" name="consecutivo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-6">
                        <label for="orientador">Orientador:</label>
                        <select class="form-control input-sm" name="orientador" id="orientador" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelect($escpad_orientador); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary" id="btnsubmit">Agregar</button>           
                    </div>
                </div>
            </form>
        </div>