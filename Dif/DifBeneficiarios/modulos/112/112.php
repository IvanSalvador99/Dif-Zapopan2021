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
    
    $query = "SELECT * FROM $persona_depsensas WHERE idpersona = '" . $_POST['idpersona'] . "'";
    //echo "Query: " . $query . "</br>";
    if ($result = mysql_query($query, $con)) {
        if (mysql_num_rows($result) > 0) {
            $registrado = 1;
            $row = mysql_fetch_array($result);
            /*var_dump($row);
            echo "</br>";*/
            if ($row['activo'] > 0) {
                $activo = 1;
            } else {
                $activo = 0;
            }
        } else {
            $registrado = 0;
            $activo = 0;
        }
    } else {
        echo "Error en el query: " . $query . "</br>Error: " . mysql_error() . "</br>";
    }
    //echo "</br></br>Registrado: " . $registrado . "</br>Activo: " . $activo . "</br>";
?>
        <script type="text/javascript">
            $(document).ready(function(){
                
                $("#estadonacimiento").change(function(e) {
                    $("#municipionacimiento").val("Obteniendo municipios...");
                    $.post('../ajax/ajax_municipionacimiento.php',{"estado": this.value }, function(respuesta){
                        //alert(respuesta.mensaje);
                        var output = "<option disabled selected>---Seleccione un municipio---</option>";
                        $.each(respuesta.municipio, function(index, value) {
                            if ('<?=$row['municipionacimiento']?>' == value) {
                                output += "<option selected>" + value + "</option>";
                            } else {
                                output += "<option>" + value + "</option>";
                            }
                            
                        });
                        $("#municipionacimiento").html(output);
                        $("#municipionacimiento").prop("disabled", false);
                    });
                });
                
                $(".caps").blur(function(e){
                    $(this).val($(this).val().toUpperCase());
                });
                
                $("#razondespensa").on("change", function(e) {
                    if (this.value == 0) {
                        $("#btnsubmit").html("Registrar");
                    } else {
                        $("#btnsubmit").html("Registrar/Actualizar");
                    }
                    $.post('ajax/ajax_obtenertipodespensa.php',{"razon": this.value}, function(respuesta){
                        $("#tipodespensa").prop("disabled", false);
                        $("#tipodespensa").html(respuesta.html);
                    });
                });
                
                $("#btnsubmit").on("click", function(e) {
                    e.preventDefault();
                    if(($(this).attr("class")).includes("disabled")) {
                        alert ("Boton desabilitado favor de llenar la forma correctamente");
                    } else {
                        var form = $("#formregistrodespensa");
                        
                        form.append($("<input type='hidden' name='idpersona'/>").val(<?=$_POST['idpersona']?>));
                        form.append($("<input type='hidden' name='servicio'/>").val(<?=$_POST['servicio']?>));
                        form.append($("<input type='hidden' name='locacion'/>").val(<?=$_POST['locacion']?>));
                        form.append($("<input type='hidden' name='fechaservicio'/>").val("<?=$_POST['fechaservicio']?>"));
                        form.append($("<input type='hidden' name='registrado'/>").val("<?=$registrado?>"));
                        form.append($("<input type='hidden' name='activo'/>").val("<?=$activo?>"));
                        form.get(0).submit();
                    }
                });
                
                $("#buttonactivar").on("click", function(e) {
                    $.post('ajax/ajax_activardesactivar.php',{"activo": <?=$activo?>, "idpersona": <?=$_POST['idpersona']?>}, function(respuesta){
                        var respuestas = respuesta.split(",");
                        if (respuestas[0] == "Exito") {
                            if (respuestas[1] == "activado") {
                                window.location.href = "../../beneficiariovista.php?id=" + <?=$_POST['idpersona']?> + "&action=3";
                            } else {
                                window.location.href = "../../beneficiariovista.php?id=" + <?=$_POST['idpersona']?> + "&action=4";
                            }
                        } else {
                            alert (respuestas[1]);
                        }
                    });
                });
                
                $("#estadonacimiento").trigger("change");
            });
        </script>
        <div>
            <h2 class="text-center">Despensas municipales</h2>
            <h3 class="text-center"><?php echo $rowpersona['nombre'] . " " . $rowpersona['apaterno'] . " " . $rowpersona['amaterno'] ?></h3> 
            <?php
                $output = "";
                if ($registrado == 1) {
                    if ($activo == 1) {
                        $output = "<div class='row'>
                                       <div class='col-md-10'><h4>Este beneficiario ya esta registrado en la base de datos de despensas y recibe apoyo de despensa</h4></div>
                                       <div class='col-md-2'><button id='buttonactivar' class='btn btn-primary'>Desactivar</button></div>
                                   </div>";
                    } else {
                        $output = "<div class='row'>
                                       <div class='col-md-10'><h4>Este beneficiario ya esta registrado en la base de datos de despensas y no recibe apoyo de despensa</h4></div>
                                       <div class='col-md-2'><button id='buttonactivar' class='btn btn-primary'>Activar</button></div>
                                   </div>";
                    }
                } else {
                    $output = "<h4>Este beneficiario no esta registrado en la base de datos de despensas</h4>";
                }
                echo $output;
            ?>
            <br />
            <h4>Datos de generales:</h4>
            <form method="post" action="engine/engine_112.php" id="formregistrodespensa" data-toggle="validator">
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
                    <div class="form-group has-feedback col-md-4">
                        <label for="estadonacimiento" class="control-label">Estado de nacimiento:<small> *</small></label>
                        <select class="form-control input-sm" name="estadonacimiento" id="estadonacimiento">
                            <?php
                                $query = "SELECT DISTINCT estado FROM $colonias ORDER BY estado";
                                if ($registrado) {
                                    $output = "";
                                } else {
                                    $output = "<option value='-1' disabled selected>---Seleccione un estado---</option>";
                                }
                                if ($result = mysql_query($query, $con)) {
                                    while ($rowestado = mysql_fetch_array($result)) {
                                        if ($row['estadonacimiento'] == $rowestado['estado']) {
                                            $output .= '<option selected>' . $rowestado['estado'] . '</option>';
                                        } else {
                                            $output .= '<option>' . $rowestado['estado'] . '</option>';
                                        }
                                    }
                                } else {
                                    $output .= '<option>Error en el query: ' . mysql_error() . '</option>';
                                }
                                echo $output;
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
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="ingresos" class="control-label">Ingresos mensuales<small> *</small></label>
                        <?php
                        if ($registrado) {
                            $output = '<input type="text" class="form-control input-sm" id="ingresos" name="ingresos" value="' . $row['ingresos'] . '" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>';
                        } else {
                            $output = '<input type="text" class="form-control input-sm" id="ingresos" name="ingresos" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>';
                        }
                        echo $output
                        ?>                        
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="poblacionatendida">Grupo de vulnerabilidad:</label>
                        <select class="form-control input-sm" name="poblacionatendida" id="poblacionatendida" required>
                            <?php
                            if ($registrado) {
                                $output = fillSelectData($grupo_prioritario, $row['grupoprioritario']);
                                
                            } else {
                                $output = '<option value="-1" disabled selected>---Seleccione---</option>';
                                $output .= fillSelect($grupo_prioritario);
                            }
                            echo $output;
                            ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="comunidad">Comunidad:</label>
                        <select class="form-control input-sm" name="comunidad" id="comunidad" required>
                            <?php
                            if ($registrado) {
                                $output = fillSelectData($comunidades, $row['comunidad']);
                                
                            } else {
                                $output = '<option value="-1" disabled selected>---Seleccione---</option>';
                                $output .= fillSelect($comunidades);
                            }
                            echo $output;
                            ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-6">
                        <label for="razondespensa" class="control-label">Razon:<small> *</small></label>
                        <select class="form-control input-sm" name="razondespensa" id="razondespensa" required>
                            <option value="-1" disabled selected>---Seleccione razon---</option>
                            <option value="0">Emergentes</option>
                            <option value="1">Registro o actualización de datos para programa de despensas municipales</option>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-6">
                        <label for="tipodespensa" class="control-label">Tipo:<small> *</small></label>
                        <select class="form-control input-sm" name="tipodespensa" id="tipodespensa" disabled required>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary" id="btnsubmit">----</button>           
                    </div>
                </div>
            </form>
        </div>