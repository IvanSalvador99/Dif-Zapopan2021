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
    
    $query = "SELECT parentesco FROM $parentesco ORDER BY id";
    
    if ($result = mysql_query($query, $con)) {
        while ($rowparentesco = mysql_fetch_row($result)) {
            $arrayparentesco[] = $rowparentesco[0];
        }
        /*echo "</br></br>";
        var_dump($arrayparentesco);*/
    }
    // echo $_POST['padreservicio'];
?>

        <script type="text/javascript">
            
            var parentescos = <?php echo "`".json_encode($arrayparentesco)."`" ?>;
            var familiares = [];
            var temp;
            var selectparentesco = <?php echo "`".fillSelect($parentesco)."`" ?>;
            var selectestadocivil = <?php echo "`".fillSelect($estado_civil)."`" ?>;
            var selectescolaridad = <?php echo "`".fillSelect($escolaridad)."`" ?>;
            var selectocupacion = <?php echo "`".fillSelect($ocupacion)."`" ?>;
            var tipofamiliar = 0;
            
            $("body").on("click", "#btnborrarpariente", function(){
                //alert (familiares[$(this).data("value")].nombre);
                familiares.splice($(this).data("value"), 1);
                
                reloadtable();
            });
            
            $("body").on("blur", "#iddif", function(){
                $.post('../ajax/ajax_getpersona',{"iddif": $.trim($("#iddif").val())}, function(respuesta){
                    if (respuesta.numero == "1") {
                        $("#descpersona").html("");
                        $("#descpersona").html("<b>ID:</b> " + respuesta.id + "<br /><b>Nombre:</b> " + respuesta.nombre + "<br /><b>CURP:</b> " + respuesta.curp);
                        $("#inputparentesco").show();
                        temp = respuesta; 
                    }
                });
            });
            
            $("body").on("blur", ".caps", function(){
                $(this).val($(this).val().toUpperCase());
            });
            
            function reloadtable (){
                var output = "";
                $("#tablafamiliar").empty();
                $("#tablafamiliar").append("<tr><td>1</td><td><?=$row['nombre']?> <?=$row['apaterno']?> <?=$row['amaterno']?></td><td><?=$row['fechanacimiento']?></td><td><?=$row['curp']?></td><td><?=$row['edad']?></td><td><?=$row['estado_civil']?></td><td>Beneficiario</td><td><?=$row['escolaridad']?></td><td><?=$row['ocupacion']?></td><td></td></tr>");
                for (var i = 0; i  < familiares.length; i ++) {
                    output += "<tr><td>" + (i + 2) + 
                                "</td><td>" + familiares[i].nombre + 
                                "</td><td>" + familiares[i].fechanacimiento + 
                                "</td><td>" + familiares[i].curp + 
                                "</td><td>" + familiares[i].edad + 
                                "</td><td>" + familiares[i].estadocivil + 
                                "</td><td>" + familiares[i].parentesco + "</td>" +
                                "<td>" + familiares[i].escolaridad + 
                                "</td><td>" + familiares[i].ocupacion +
                                "</td><td><button type='button' class='btn btn-default' id='btnborrarpariente' data-value='" + i + "'><span class='glyphicon glyphicon-remove'></button>" +
                                "</td></tr>";
                };
                
                $("#tablafamiliar").append(output);
            }            
            $(document).ready(function(){
                $("#agregarmiembro").on("click", function(e){
                    alert("Hola");
                    $("#modalagregarpersona").modal();
                });
                
                $("#btnbeneficiario").on("click", function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    tipofamiliar = 1;
                    $("#modalbody").empty();
                    $("#modalbody").html("<h4 class='modal-title text-center'>Introduzca ID DIF Zapopan:</h4> <br /> <div class='form-group'> <input class='form-control input-sm' type='text' id='iddif' placeholder='ID DIF Zapopan'/> </div> <p id='descpersona'> </p> <div class='form-group has-feedback' id='inputparentesco' style='display:none'> <label for='parentesco'>Parentesco:</label> <select class='form-control input-sm' name='parentesco' id='parentesco'> <option value='-1' disabled selected>---Seleccione el parentesco---</option>" + selectparentesco + "</select></div>");
                });
                
                $("#btnpariente").on("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    tipofamiliar = 2;
                    $("#modalbody").empty();
                    $("#modalbody").html("<div class='row'> <div class='form-group col-md-12 has-feedback caps'> <label for='nombrepariente' class='control-label'>Nombre:</label> <input class='form-control input-sm caps' type='text' id='nombrepariente' name='nombrepariente' pattern='^[A-Za-zñÑ .]{1,}$' data-pattern-error='Solo letras (sin acentos)' data-required-error='Debe llenar este campo'/> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 10px'></span> <div class='help-block with-errors'></div> </div> </div> <div class='row'> <div class='form-group has-feedback col-md-8'> <label for='fechanacpariente' class='control-label'>Fecha de nacimiento:</label> <div class='input-group date' data-provide='datepicker'> <input type='text' class='form-control' name='fechanacpariente' id='fechanacpariente' data-required-error='Debe llenar este campo' required/> <div class='input-group-addon'><span class='glyphicon glyphicon-th'></span></div> </div> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 50px'></span> <div class='help-block with-errors'></div> </div> <div class='form-group has-feedback col-md-4'> <label for='edadpariente' class='control-label'>Edad:</label> <input type='text' class='form-control input-sm' id='edadpariente' name='edadpariente' pattern='^[0-9]{1,}$' data-pattern-error='Solo numeros' data-required-error='Debe llenar este campo'/> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span> <div class='help-block with-errors'></div> </div> </div> <div class='row'> <div class='form-group has-feedback col-md-6'> <label for='curppariente' class='control-label'>CURP:</label> <input class='form-control input-sm caps' type='text' id='curppariente' name='curppariente' data-pattern-error='No es un formato valido de CURP' pattern='^[A-Za-z]{1}[AEIOUXaeioux]{1}[A-Za-z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HMhm]{1}(AS|as|BC|bc|BS|bs|CC|cc|CS|cs|CH|ch|CL|cl|CM|cm|DF|df|DG|dg|GT|gt|GR|gr|HG|hg|JC|jc|MC|mc|MN|mn|MS|ms|NT|nt|NL|nl|OC|oc|PL|pl|QT|qt|QR|qr|SP|sp|SL|sl|SR|sr|TC|tc|TS|ts|TL|tl|VZ|vz|YN|yn|ZS|zs|NE|ne)[B-DF-HJ-NP-TV-Zb-df-hj-np-tv-z]{3}[0-9A-Za-z]{1}[0-9]{1}$'/> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 10px'></span> <div class='help-block with-errors'></div> </div> <div class='form-group has-feedback col-md-6'> <label for='estadocivilpariente' class='control-label'>Estado civil:</label> <select class='form-control input-sm' name='estadocivilpariente' id='estadocivilpariente' required> <option value='-1' disabled selected>---Seleccione estado civil---</option>" + selectestadocivil +
                                            "</select> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span> <div class='help-block with-errors'></div> </div> </div> <div class='row'> <div class='form-group has-feedback col-md-6'> <label for='escolaridadpariente' class='control-label'>Nivel escolar:<small></small></label> <select class='form-control input-sm' name='escolaridadpariente' id='escolaridadpariente' required> <option value='-1' disabled selected>---Seleccione nivel escolar---</option>" + selectescolaridad +
                                            "</select> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span> <div class='help-block with-errors'></div> </div> <div class='form-group has-feedback col-md-6'> <label for='ocupacionpariente' class='control-label'>Ocupaci&oacute;n:</label> <select class='form-control input-sm' name='ocupacionpariente' id='ocupacionpariente' required> <option value='-1' disabled selected>---Seleccione ocupaci&oacute;n---</option>" + selectocupacion + 
                                            "</select> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span> <div class='help-block with-errors'></div> </div> </div> <div class='row'> <div class='form-group has-feedback col-md-12' id='inputparentesco'> <label for='parentesco'>Parentesco:</label> <select class='form-control input-sm' name='parentesco' id='parentesco'> <option value='-1' disabled selected>---Seleccione el parentesco---</option>" + selectparentesco +
                                            "</select> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span> <div class='help-block with-errors'></div> </div> </div>");
                                            
                    $('.date').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                        language: 'es',
                        endDate: '0d'
                    });
                });
                
                $("#modalagregarpersona").on("hidden.bs.modal", function(e){
                    $("#iddif").val("");
                    $("#descpersona").html("");
                    $("#inputparentesco").hide();
                    $("#parentesco").val(-1);
                    $("#modalbody").empty();
                    temp = null;
                    tipofamiliar = 0;
                });
                
                $("#btnagregar").on("click", function(e){
                    alert(familiares.length);
                    if (tipofamiliar == 1) {
                        if (temp != null) {
                            if ($("#parentesco").val() > 0) {
                                familiares.push(temp);
                                familiares[familiares.length - 1].parentesco = $("#parentesco option:selected").text();
                                reloadtable();
                                tipofamiliar = 0;
                                temp = null;
                            } else {
                                alert("No capturaste el parentesco");
                            }
                        } else {
                            alert("No capturaste el ID DIF Zapopan");
                        }
                    } else if (tipofamiliar == 2) {
                        if ($("#parentesco").val() > 0 && $("#nombrepariente").val() != ""){
                            familiares.push({parentesco: $("#parentesco option:selected").text(),
                                    nombre: $("#nombrepariente").val(), 
                                    fechanacimiento: $("#fechanacpariente").val(),
                                    curp: $("#curppariente").val(),
                                    edad: $("#edadpariente").val(),
                                    estadocivil: $("#estadocivilpariente option:selected").text(),
                                    escolaridad: $("#escolaridadpariente option:selected").text(),
                                    ocupacion: $("#ocupacionpariente option:selected").text(),
                                    id:null});
                                    
                            reloadtable();
                        } else {
                            alert("Faltan datos por agregar");
                        }
                        temp = null;
                        tipofamiliar = 0;
                    } else {
                        alert ("No seleccionaste el tipo de familiar a agregar.");
                        temp = null;
                        tipofamiliar = 0;
                    }
                });
                // alert("Entre");
                $("#btnsubmit").on("click", function(e) { 
                    // alert("Entre");
                    e.preventDefault();
                    if(($(this).attr("class")).includes("disabled")) {
                        alert ("Boton desabilitado favor de llenar la forma correctamente");
                    } else {
                        var form = $("#form192");
                        
                        form.append($("<input type='hidden' name='idpersona'/>").val(<?php echo "`".$_POST['idpersona']."`" ?>));
                        form.append($("<input type='hidden' name='servicio'/>").val(<?php echo "`".$_POST['servicio']."`" ?>));
                        form.append($("<input type='hidden' name='locacion'/>").val(<?php echo "`".$_POST['locacion']."`" ?>));
                        form.append($("<input type='hidden' name='fechaservicio'/>").val(<?php echo "`".$_POST['fechaservicio']."`" ?>));
                        form.append($("<input type='hidden' name='idservicio'/>").val(<?php echo "`".$_POST['idservicio']."`" ?>));
                        form.append($("<input type='hidden' name='familiares'/>").val(JSON.stringify(familiares)));
                        console.log(form);
                        form.get(0).submit();
                    }
                });
            });
        </script>
        <div class="modal fade" id="modalagregarpersona" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">AGREGAR INTEGRANTE FAMILIAR</h4>
                        <br />
                        <div class="row text-center">
                            <div class="form-group col-md-6">
                                <button type="button" class="btn btn-primary" id="btnbeneficiario">Agregar beneficiario</button>
                            </div>
                            <div class="form-group col-md-6">
                                <button type="button" class="btn btn-primary" id="btnpariente">Agregar familiar</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body" id="modalbody">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btnagregar" data-dismiss="modal">Agregar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <h2 class="text-center">Registro de terapia psicologica</h2><br />
            <h4>Favor de llenar los siguientes datos</h4>
            <br />
            <form method="post" action="engine/engine_192.php" id="form192" data-toggle="validator">
                <div class="row">
                    <div class="form-group col-md-3 has-feedback">
                        <label for="noexp">Numero de Expediente:<small> *</small></label>
                        <input class="form-control input-sm caps" type="text" id="noexp" name="noexp" pattern="^(CAFOG||UAVV|CAETF|CDC02|CDC04|CDC05|CDC07|CDC10|CDC13|CDC16|CDC17|CDC18|CDC19|CDC20|CDC22|CDC24)\/(TI|TP|TF|TG|IC|VA)\/[0-9]{3}\/[0-9]{4}$" data-pattern-error="El formato es incorrecto" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-3">
                        <label for="tipoterapia">Tipo de terapia:</label>
                        <select class="form-control input-sm" name="tipoterapia" id="tipoterapia" required>
                            <option value="-1" disabled selected>---Seleccione tipo---</option>
                            <?php echo fillSelect($tipo_terapia); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-3">
                        <label for="tiposistemafamiliar">Tipo de sistema familiar:</label>
                        <select class="form-control input-sm" name="tiposistemafamiliar" id="tiposistemafamiliar" required>
                            <option value="-1" disabled selected>---Seleccione tipo---</option>
                            <?php echo fillSelect($tipo_sistema_familiar); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-3">
                        <label for="etapaciclovitalfamiliar">Etapa del ciclo vital familiar:</label>
                        <select class="form-control input-sm" name="etapaciclovitalfamiliar" id="etapaciclovitalfamiliar" required>
                            <option value="-1" disabled selected>---Seleccione etapa---</option>
                            <?php echo fillSelect($etapa_ciclo_vital_familiar); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <!--<div class="row">
                    <div class="form-group has-feedback col-md-3">
                        <label for="nivelsocioeconomico">Nivel familar socioecon&oacute;mico:</label>
                        <select class="form-control input-sm" name="nivelsocioeconomico" id="nivelsocioeconomico" required>
                            <option value="-1" disabled selected>---Seleccione nivel---</option>
                            <?php echo fillSelect($nivel_socioeconomico); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div> -->
                <div class="row">
                    <table class="table table-sm table-dark table-responsive">
                        <thead>
                            <tr>
                                <th>Numero</th>
                                <th>Nombre</th>
                                <th>Fecha de nacimiento</th>
                                <th>CURP</th>
                                <th>Edad</th>
                                <th>Estado civil</th>
                                <th>Parentesco</th>
                                <th>Escolaridad</th>
                                <th>Ocupaci&oacute;n</th>
                                <th>Eliminar?</th>
                            </tr>
                        </thead>
                        <tbody id="tablafamiliar">
                            <tr>
                                <td>1</td>
                                <td><?=$row['nombre']?> <?=$row['apaterno']?> <?=$row['amaterno']?></td>
                                <td><?=$row['fechanacimiento']?></td>
                                <td><?=$row['curp']?></td>
                                <td><?=$row['edad']?></td>
                                <td><?=$row['estado_civil']?></td>
                                <td>Beneficiario</td>
                                <td><?=$row['escolaridad']?></td>
                                <td><?=$row['ocupacion']?></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row text-right">
                    <button type='button' class='btn btn-default' id='agregarmiembro'>Agregar  <span class='glyphicon glyphicon-plus'></button>
                </div>
                <div class="row">
                    <div class="form-group text-center col-md-12">
                        <button type="submit" class="btn btn-primary" id="btnsubmit">Registrar</button>
                    </div>
                </div>
            </form>
        </div>