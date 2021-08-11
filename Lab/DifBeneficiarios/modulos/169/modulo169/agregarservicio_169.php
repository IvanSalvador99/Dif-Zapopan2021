<?php
    require_once("../../../menupadron.php");
    
    if (isset($_GET['action']) == 1) {
        alerta_bota("No registraste ningun servicio", 0);
    }
    if (!empty($_GET['idservicio'])) {
        $query = "SELECT * FROM $vw_entrevista_inicial WHERE idservicio = '" . $_GET['idservicio'] . "'";        
    } else {
        $query = "SELECT * FROM $vw_entrevista_inicial WHERE idservicio = '" . $_GET['id'] . "'";
    }
    if ($result = mysql_query($query, $con)) {        
        $row_servicio = mysql_fetch_assoc($result);
        if (!empty($row_servicio['apoyo'])) {
            $apoyo = strpos($row_servicio['apoyo'], ",");
            // echo "Apoyo = ".$apoyo;
            if (empty($apoyo)) {
                $query = "SELECT apoyosservicios FROM $apoyos WHERE id = '" . $row_servicio['apoyo'] . "'";
                if ($result = mysql_query($query, $con)) {
                    $apoyoR = mysql_fetch_assoc($result);                                
                } else {
                    echo "</br>Error en el query: " . $query . "</br>Error: " . mysql_error();
                }
                $apoyo = 1;
            }else {
                $siapoyo = $apoyo;
                $siapoyo++;
            }                                                            
        }else {
            $apoyo = 0;
        }
    } else {
        echo "</br>Error en el query: " . $query . "</br>Error: " . mysql_error();
    }    
        // echo $num." ".$row_servicio['apoyo']."  ".$apoyo;
    // alerta_bota($apoyo, 0);
?>
        <script type="text/javascript">            
            var numApoyos = <?php echo "`".$apoyo."`" ?>;   
            var varFija = numApoyos;     
            
            $(document).ready(function(){

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
                    if (numApoyos == 0) {
                        if (numApoyos > 0) {
                        $("#rowapoyos").children().last().remove();
                        numApoyos --;
                        }                    
                        if (numApoyos == 0) {
                            $("#rowapoyos").html("<h3>Ningun apoyo agregado</h3>");
                        }
                    }else{
                        if (numApoyos > varFija) {
                        $("#rowapoyos").children().last().remove();
                        numApoyos --;
                        }
                        if (numApoyos == varFija) {
                            alert("No puedes elminar mas apoyos");
                        }
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
                        form.append($("<input type='hidden' name='idservicio'/>").val(<?=$_GET['idservicio']?>));
                        form.get(0).submit();
                    }
                });
                
            });
        </script>
        <div>
            <h2 class="text-center">Editar Servicios de Entrevista Inicial/Orientación</h2><br />
            <h4>Datos de generales:</h4>            
            <form method="post" action="engine_agregarservicio169.php" id="formregistroentrevista" data-toggle="validator">
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="programa" class="control-label">Programa:<small> *</small></label>
                        <input class="form-control input-sm caps" type="text" name="programa" id="programa" value="<?= $row_servicio['programa'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group col-md-4 has-feedback">
                        <label for="procedencia" class="control-label">Procedencia:<small> *</small></label>
                        <input class="form-control input-sm caps" type="text" name="procedencia" id="procedencia" value="<?= $row_servicio['procedencia'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group col-md-4 has-feedback">
                        <label for="registro">Registro:</label>
                        <input class="form-control input-sm caps" type="text" id="registro" name="registro" value="<?= $row_servicio['registro'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="paisnacimiento" class="control-label">País:<small> *</small></label>
                        <input class="form-control input-sm caps" type="text" name="paisnacimiento" id="paisnacimiento" value="<?= $row_servicio['pais'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="estadonacimiento" class="control-label">Estado de nacimiento:<small> *</small></label>
                        <input class="form-control input-sm caps" type="text" name="estadonacimiento" id="estadonacimiento" value="<?= $row_servicio['estadonacimiento'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="municipionacimiento" class="control-label">Municipio de nacimiento:<small> *</small></label>
                        <input class="form-control input-sm caps" type="text" name="municipionacimiento" id="municipionacimiento" value="<?= $row_servicio['municipionacimiento'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <h4>Datos familiares</h4>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="integrantes" class="control-label">Integrantes<small> *</small></label>
                        <input class="form-control input-sm caps" type="text" id="integrantes" name="integrantes" value="<?= $row_servicio['integrantes'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="integrantesactivos" class="control-label">Integrantes economicamente activos:<small> *</small></label>
                        <input class="form-control input-sm caps" type="text" id="integrantesactivos" name="integrantesactivos" value="<?= $row_servicio['integrantesactivos'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="pagocasa" class="control-label">Pago renta (si aplica):</label>
                        <input class="form-control input-sm caps" type="text" id="pagocasa" name="pagocasa" value="<?= $row_servicio['pagocasa'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="poblacionatendida">Grupo de vulnerabilidad:</label>
                        <input class="form-control input-sm caps" type="text" name="poblacionatendida" id="poblacionatendida" value="<?= $row_servicio['poblacionatendida'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="integrantesenfermos" class="control-label">Integrantes enfermos y/o discapacitados:</label>
                        <input class="form-control input-sm caps" type="text" id="integrantesenfermos" name="integrantesenfermos" value="<?= $row_servicio['integrantesenfermos'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-6">
                        <label for="ingresofamiliar" class="control-label">Ingreso familiar mensual aproximado:</label>
                        <input class="form-control input-sm caps" type="text" id="ingresofamiliar" name="ingresofamiliar" value="<?= $row_servicio['ingresofamiliar'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-6">
                        <label for="deudas" class="control-label">Monto mensual de deuda aproximado:</label>
                        <input class="form-control input-sm caps" type="text" id="deudas" name="deudas" value="<?= $row_servicio['deudas'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-12">
                        <label for="apoyosolicitado" class="control-label">Apoyo solicitado:</label>
                        <input class="form-control input-sm caps" type="text" id="apoyosolicitado" name="apoyosolicitado" value="<?= $row_servicio['apoyosolicitado'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-fedback col-md-12">
                        <label for="descripcioncaso" class="control-label">Diagnostico social inicial:</label>
                        <textarea class="form-control input-sm" form="formregistroentrevista" rows="4" name="descripcioncaso" id="descripcioncaso" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" disabled><?= $row_servicio['descripcioncaso'] ?></textarea>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-6">
                        <label for="tipodelito">Tipo de Delito:</label>
                        <input class="form-control input-sm caps" type="text" name="tipodelito" id="tipodelito" value="<?= $row_servicio['tipodelito'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                        </div>
                    <div class="form-group has-feedback col-md-6">
                        <label for="probvul">Problematica y/o vulnerabilidad:</label>
                        <input class="form-control input-sm caps" type="text" name="probvul" id="probvul" value="<?= $row_servicio['problematica'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-12">
                        <label for="detonante">Detonante:</label>
                        <input class="form-control input-sm caps" type="text" name="detonante" id="detonante" value="<?= $row_servicio['detonante'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-6">
                        <label for="diagnostico">Diagnostico:</label>
                        <input class="form-control input-sm caps" type="text" name="diagnostico" id="diagnostico" value="<?= $row_servicio['diagnostico'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-6">
                        <label for="canalizadoa">Canalización:</label>
                        <input class="form-control input-sm caps" type="text" name="canalizadoa" id="canalizadoa" value="<?= $row_servicio['canalizadoa'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row text-center">
                    <h4 class="text-center">Apoyos y/o servicios otorgados:</h4>
                    <button id="agregarapoyo" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span></button>
                    <button id="eliminarapoyo" class="btn btn-danger"><span class="glyphicon glyphicon-minus"></span></button>
                </div>
                <div class="col-md-12 text-center" id="rowapoyos" style="border: 1px solid #999999; border-radius: 5px; margin-top: 10px; margin-bottom: 15px;">
                
                        
                            <?php                             
                            if ($apoyo == 0) { ?>
                                <h3>Ningun apoyo agregado</h3>
                            <?php }else{
                                if ($apoyo==1) { ?>
                                <div class="form-group has-feedback col-md-6">
                                <label for="apoyo">Apoyo otorgado:</label>
                                <input class="form-control input-sm caps" type="text" value="<?= $apoyoR['apoyosservicios'] ?>" disabled/>
                                
                                <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                                <div class="help-block with-errors"></div>
                                </div>
                                <?php 
                                } else {
                                    $num = 0;
                                    // echo $num." ".$row_servicio['apoyo'];
                                    for ($i=0; $i < $siapoyo ; $i++) {                                         
                                        $query = "SELECT apoyosservicios FROM $apoyos WHERE id = '" . substr($row_servicio['apoyo'], $num,2) . "'";
                                        if ($result = mysql_query($query, $con)) {
                                            $apoyoR = mysql_fetch_assoc($result);                                
                                        } else {
                                            echo "</br>Error en el query: " . $query . "</br>Error: " . mysql_error();
                                        }
                                    if (!empty($apoyoR)) { 
                                ?> 
                                <div class="form-group has-feedback col-md-6">
                                <label for="apoyo">Apoyo otorgado:</label>
                                <input class="form-control input-sm caps" type="text" value="<?= $apoyoR['apoyosservicios'] ?>" disabled/>
                                
                                <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                                <div class="help-block with-errors"></div>
                                </div>
                            <?php  
                            }                           
                            $num += 3;
                                    }
                            }
                        }
                            ?>                        
                    
                </div>
                <div class="row">
                    <div class="form-group has-fedback col-md-12">
                        <label for="descripcionconclusion" class="control-label">Conclusión:</label>
                        <textarea class="form-control input-sm" form="formregistroentrevista" rows="4" name="descripcionconclusion" id="descripcionconclusion" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" disabled><?= $row_servicio['descripcionconclusion'] ?></textarea>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
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