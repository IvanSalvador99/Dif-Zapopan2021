<?php
    include("menupadron.php");
    
    /*echo "</br></br>ID:</br>";
    var_dump($_GET);*/
    
    if ($_GET['action'] == 1) {
        alerta_bota("Servicio registrado correctamente", 0);
    } elseif ($_GET['action'] == 2) {
        alerta_bota("Beneficiario registrado correctamente en padron de despensas con estatus ACTIVO.", 0);
    } elseif ($_GET['action'] == 3) {
        alerta_bota("El beneficiario se ha activado y recibira apoyo de despensa mensual.", 0);
    } elseif ($_GET['action'] == 4) {
        alerta_bota("El beneficiario se ha desactivado y ya no recibira apoyo de despensa mensual.", 0);
    } elseif ($_GET['action'] == 5) {
        alerta_bota("Se han actualizado los datos del beneficiario en el padron de despensa mensual.", 0);
    } elseif ($_GET['action'] == 6) {
        alerta_bota("No puedes asignar una despensa emergente a alguien que ya recibe apoyo de despensa mensual", 0);
    } 
    
    $query = "SELECT * FROM $vw_persona WHERE id = '" . $_GET['id'] . "'";
    /*echo "</br></br>Query:</br>";
    echo ($query);*/
    
    if ($result = mysql_query($query, $con)){
        $row = mysql_fetch_array($result);
        /*echo "</br></br>Row:</br>";
        var_dump($row);*/
    } else {
        die (mysql_error());
    }
?>
        <link rel="stylesheet" type="text/css" href="css/beneficiariovista.css">
        <script type="text/javascript">
            $(document).ready(function(){
                $('.date').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    language: 'es',
                    endDate: '0d'
                });
                
                $(".glyphicon").on("click", function(){
                    var text = $(this).attr("class").split(" ");
                    if (text[1] == "glyphicon-collapse-down") {
                        $(this).attr("class", "glyphicon glyphicon-collapse-up");
                    } else {
                        $(this).attr("class", "glyphicon glyphicon-collapse-down");
                    }
                });
                
                $("#departamento").on("change", function(){
                    $.post('ajax/ajax_getservicios.php',{"departamento": this.value }, function(respuesta){
                        //alert(respuesta.mensaje);
                        var output = "<option disabled selected>---Seleccione servicio---</option>";
                        output += respuesta.html;
                        $("#servicio").html(output);
                        $("#servicio").prop("disabled", false);
                    });
                });
                
                $("#agregarservicio").on("click", function (){
                    $("#modalagregarservicio").modal();
                });
                
                $("#botonconfserv").on("click", function (){
                    //alert ($("#servicio").val());
                    $.post('ajax/ajax_getservicio.php',{"servicio": $("#servicio").val()}, function(respuesta){
                        if (respuesta.tienemodulo == 0) {
                            $('<form>', {
                                "id": "formregistrarservicios",
                                "method": "post",
                                "html": '<input type="hidden" id="servicio" name="servicio" value="' + respuesta.id + '" /><input type="hidden" id="idpersona" name="idpersona" value="' + <?= $_GET['id'] ?> + '" /><input type="hidden" id="locacion" name="locacion" value="' + $("#locacion").val() + '" /><input type="hidden" id="fechaservicio" name="fechaservicio" value="' + $("#fechaservicio").val() + '" />',
                                "action": 'engine/engine_registrarservicio.php'
                            }).appendTo(document.body).submit();
                        } else {
                            $('<form>', {
                                "id": "formregistrarservicios",
                                "method": "post",
                                "html": '<input type="hidden" id="servicio" name="servicio" value="' + respuesta.id + '" /><input type="hidden" id="idpersona" name="idpersona" value="' + <?= $_GET['id'] ?> + '" /><input type="hidden" id="locacion" name="locacion" value="' + $("#locacion").val() + '" /><input type="hidden" id="fechaservicio" name="fechaservicio" value="' + $("#fechaservicio").val() + '" />',
                                "action": 'modulos/' + respuesta.id + '/' + respuesta.id + '.php'
                            }).appendTo(document.body).submit();
                        }
                    });
                });
            });
            
            function editarclick(idservicio, idcatservicio, event){
                event.preventDefault();
                event.stopPropagation();
                var $form = $("#editarservicio");
                $form.attr('action', "modulos/" + idcatservicio + "/" + idcatservicio + "editar.php")
                $form.append($("<input type='hidden' name='idservicio'>").val(idservicio));
                $form.get(0).submit();
            }
        </script>
        <form id="editarservicio" action="/" method="post"></form>
        <div class="modal fade" id="modalagregarservicio" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group has-feedback col-md-12">
                                <label for="departamento">Departamento:</label>
                                <select class="form-control input-sm" name="departamento" id="departamento" required>
                                    <option value='-1' disabled selected>---Seleccionar departamento---</option>
                                    <?php echo fillSelect($departamentos); ?>
                                </select>
                                <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group has-feedback col-md-12">
                                <label for="servicio" class="control-label">Servicio:</label>
                                <select class="form-control input-sm" name="servicio" id="servicio" required disabled>
                                </select>
                                <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group has-feedback col-md-12">
                                <label for="locacion" class="control-label">Locación:</label>
                                <select class="form-control input-sm" name="locacion" id="locacion" required>
                                    <option value="-1" disabled selected>---Seleccione locación---</option>
                                    <?php echo fillSelectLocaciones($locacion); ?>
                                </select>
                                <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group has-feedback col-md-12">
                                <label for="fechaservicio" class="control-label">Fecha del servicio:</label>
                                <div class="input-group date" data-provide="datepicker">
                                    <input type="text" class="form-control" name="fechaservicio" id="fechaservicio" data-required-error="Debe llenar este campo" required/>
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                </div>
                                <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 50px"></span>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" id="botonconfserv">Agregar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            <h2><?=$row['nombre']?> <?=$row['apaterno']?> <?=$row['amaterno']?></h2>
        </div>
        <div>
            <h4>Informacion para DIF Zapopan: <a href="#datosdif" data-toggle="collapse"><span class="glyphicon glyphicon-collapse-down"></span></a></h4>
            <div class="seccion col-md-12 collapse" id="datosdif">
                <div class="row">
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <p><b>Id:</b><br /><?=$row['iddifzapopan']?></p>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <p><b>Fecha y hora de captura:</b><br /><?=$row['fechacaptura']?></p>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <p><b>Departamento que Captura:</b><br /><?=$row['departamento']?></p>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <p><b>Capturista:</b><br /><?=$row['capturista']?></p>
                    </div>
                </div>     
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <p><b>Perfil de atenci&oacute;n:</b><br /><?=$row['perfil_atencion']?></p>
                    </div>
                </div>
            </div>
            <h4>Informacion general: <a href="#generales" data-toggle="collapse"><span class="glyphicon glyphicon-collapse-down"></span></a></h4>
            <div class="seccion col-md-12 collapse" id="generales">
                <div class="row">
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <p><b>CURP:</b><br /><?=$row['curp']?></p>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <p><b>Nombre:</b><br /><?=$row['nombre']?> <?=$row['apaterno']?> <?=$row['amaterno']?></p>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <p><b>Fecha de nacimiento:</b><br /><?=$row['fechanacimiento']?></p>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <p><b>Sexo:</b><br /><?=$row['sexo']?></p>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <p><b>Estado Civil:</b><br /><?=$row['estado_civil']?></p>
                    </div>
                </div>
                <div class="row">
                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                          <p><b>Ocupación:</b><br /><?=$row['ocupacion']?></p>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                          <p><b>Nivel escolar:</b><br /><?=$row['escolaridad']?></p>
                      </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <p><b>Lengua materna:</b><br /><?=$row['idioma1']?></p>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <p><b>Lengua secundaria:</b><br /><?=$row['idioma2']?></p>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <p><b>Institución de servicios medicos:</b><br /><?=$row['servicios_medicos']?></p>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <p><b>Enfermedad cronica y/o discapacidad:</b><br /><?=$row['enfermedad']?></p>
                    </div>
                </div>
            </div>
            <h4>Informacion de contacto: <a href="#contacto" data-toggle="collapse"><span class="glyphicon glyphicon-collapse-down"></span></a></h4>
            <div class="seccion col-md-12 collapse" id="contacto">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <p><b>Domicilio:</b><br /><?=$row['calle']?> <?=$row['numext']?><?php echo(($row['numint'] == "") ? "" : "-" . $row['numint']); ?></p>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <p><b>Colonia:</b><br /><?=$row['colonia']?></p>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <p><b>Codigo postal</b><br /><?=$row['codigopostal']?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <p><b>Primer cruce:</b><br /><?=$row['primercruce']?></p>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <p><b>Segundo cruce:</b><br /><?=$row['segundocruce']?></p>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <p><b>Tipo de vivienda:</b><br /><?=$row['vivienda']?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <p><b>Estado:</b><br /><?=$row['estado']?></p>
                    </div><div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <p><b>Municipio:</b><br /><?=$row['municipio']?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <?php
                            if ($row['escontacto'] > 0) {
                                echo "<p><b>Telefono:</b><br />" . $row['telefono'] . " (contacto)</p>";
                            } else {
                                echo "<p><b>Telefono:</b><br />" . $row['telefono'] . "</p>";
                            }
                        ?>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <p><b>Celular:</b><br /><?=$row['celular']?></p>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <p><b>Email:</b><br /><?=$row['email']?></p>
                    </div>
                </div>
            </div>
            <h4>Informacion del tutor: <a href="#tutor" data-toggle="collapse"><span class="glyphicon glyphicon-collapse-down"></span></a></h4>
            <div class="seccion col-md-12 collapse" id="tutor">
                <div class="row">
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <p><b>CURP:</b><br /><?=$row['tutorcurp']?></p>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <p><b>Parentesco:</b><br /><?=$row['parentesco']?></p>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <p><b>Nombre:</b><br /><?=$row['tutornombre']?></p>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <p><b>Sexo:</b><br /><?=$row['tutorsexo']?></p>
                    </div>
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <p><b>Fecha de nacimiento:</b><br /><?php echo (($row['tutorfechanacimiento'] == "0000-00-00") ? "" : $row['tutorfechanacimiento'] ); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            <h3>Servicios</h3>
            <button type="button" class="btn btn-primary" id="agregarservicio">Agregar servicio</button>
        </div>
        <h4>Historial de servicios: <a href="#servicios" data-toggle="collapse"><span class="glyphicon glyphicon-collapse-down"></span></a></h4>
        <div>
            <div class="seccion col-md-12 collapse" id="servicios">
                </table>
                <?php
                $query = "SELECT * FROM $vw_servicios WHERE idpersona ='" . $_GET['id'] . "'";
                $output = "<table class='table table-condensed table-responsive'><thead><tr><th>ID</th><th>Fecha</th><th>Locacion</th><th>Servicio</th><th>Descripcion</th><th>Acción</th></tr></thead><tbody>";
                if ($result = mysql_query($query, $con)) {
                    if (mysql_num_rows($result) > 0) {
                        while ($rowservicio = mysql_fetch_array($result)) {
                            $output .= "<tr id='" . $rowservicio['id'] . "'><td>" . $rowservicio['id'] . "</td><td>" . $rowservicio['fecha'] . "</td><td>" . $rowservicio['locacion'] . "</td><td>" . $rowservicio['servicio'] . "</td><td>" . $rowservicio['descripcion'] . "</td><td>coso</td></tr>";
                            if ($rowservicio['tienemodulo'] == '1') {
                                $output = str_replace("coso", "<a onclick='editarclick(" . $rowservicio['id'] . ", " . $rowservicio['idservicio'] . ", event);' class='btn btn-primary' style='width: 100%;'><span class='glyphicon glyphicon-edit'></span> Editar</a>", $output);
                            } else {
                                $output = str_replace("coso", "NA", $output);
                            }
                        }
                        echo $output . "</tbody></table>";
                    } else {
                        echo "<h4>No tiene servicios registrados</h4>";
                    }
                } else {
                    echo "<h4>Error en el query: $query\n Error: " . mysql_error() . "</h4>";
                }
                ?>
            </div>
        </div>
    <div>               
    </div>
</body>