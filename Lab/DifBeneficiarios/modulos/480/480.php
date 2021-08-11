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
                         
                                
                // alert("Entre");
                $("#btnsubmit").on("click", function(e) { 
                    // alert("Entre");
                    e.preventDefault();
                    if(($(this).attr("class")).includes("disabled")) {
                        alert ("Boton desabilitado favor de llenar la forma correctamente");
                    } else {
                        var form = $("#form480");
                        
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
            <h2 class="text-center">Registro de Orientación Juridica</h2><br />
            <h4>Favor de llenar los siguientes datos</h4>
            <br />
            <form method="post" action="engine/engine_480.php" id="form480" data-toggle="validator">
                <div class="row">
                    <div class="form-group col-md-6 has-feedback">
                        <label for="noexp">Numero de Expediente:</label>
                        <input class="form-control input-sm caps" type="text" id="noexp" name="noexp" pattern="[0-9]*\/[0-9]{4}$" data-pattern-error="El formato es incorrecto"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-6">
                        <label for="tipodeli">Tipo de Delito:<small> *</small></label>
                        <select class="form-control input-sm" name="tipodeli" id="tipodeli" required>
                            <option value="-1" disabled selected>---Seleccione tipo---</option>
                            <?php echo fillSelect($tipodelito); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>        
                    <div class="form-group has-fedback col-md-12">
                        <label for="problematicaplanteada" class="control-label">Problemática Planteada:<small> *</small></label>
                        <textarea class="form-control input-sm" rows="4" name="problematicaplanteada" id="problematicaplanteada" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>      
                    <div class="form-group has-fedback col-md-12">
                        <label for="sugerenciajuridica" class="control-label">Sugerencia Jurídica:<small> *</small></label>
                        <textarea class="form-control input-sm" rows="4" name="sugerenciajuridica" id="sugerenciajuridica" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>                          
                </div>
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
                <div class="row">
                    <div class="form-group text-center col-md-12">
                        <button type="submit" class="btn btn-primary" id="btnsubmit">Registrar</button>
                    </div>
                </div>
            </form>
        </div>