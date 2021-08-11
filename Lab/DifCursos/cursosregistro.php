<?php
    include("menucursos.php");
?>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#regularidad").multiSelect();
                
                $('.date').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    language: 'es'
                });
                
                $('.clockpicker').clockpicker({
                    placement: 'bottom',
                    align: 'center',
                    autoclose: true
                });
                
                $("#iddocente").on("blur change", function(){
                    $.post('ajax/ajax_getdocente.php',{"docente": this.value }, function(respuesta){
                        if(respuesta.exito) {
                            $("#datosdocente").empty();
                            $("#datosdocente").html("<b>Nombre: </b>" + respuesta.persona.nombre + " " + respuesta.persona.apaterno + " " + respuesta.persona.amaterno + "<br /><b>Numero de empleado: </b>" + respuesta.persona.numeroempleado);
                            $("#nombredocente").val(respuesta.persona.nombre + " " + respuesta.persona.apaterno + " " + respuesta.persona.amaterno);
                            $("#dependenciadocente").val("DIF Zapopan");
                            $("#esquemacolaboracion").val("9");
                        } else {
                            $("#iddocente").val("");
                            $("#datosdocente").empty();
                            $("#nombredocente").val("");
                            $("#dependenciadocente").val("");
                            $("#esquemacolaboracion").val("-1");
                        }
                        
                    });
                });
            });
        </script>
        
        <br />
        <form method="post" action="engine/engine_cursoregistro.php" id="formregistro" data-toggle="validator">
            <h2 class="text-center">Registro de curso</h2>
            <br />
            <h4>Datos del docente:</h4>
            <div class="row">
                <div class="form-group has-feedback col-md-2">
                    <label for="iddocente" class="control-label">ID del docente:</label>
                    <input class="form-control input-sm" type="text" id="iddocente" name="iddocente" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo"/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-grop col-md-6">
                    <p id="datosdocente"></p>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-3">
                    <label for="nombredocente" class="control-label">Nombre:</label>
                    <input class="form-control input-sm" type="text" id="nombredocente" name="nombredocente" pattern="^[A-Za-z Á-ÿ.]{1,}$" data-pattern-error="Solo letras" data-required-error="Debe llenar este campo"/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-3">
                    <label for="esquemacolaboracion" class="control-label">Esquema de colaboración:</label>
                    <select class="form-control input-sm" name="esquemacolaboracion" id="esquemacolaboracion">
                        <option value="-1" disabled selected>---Seleccione esquema---</option>
                        <?php echo fillSelect($esquemacolaboracion); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-3">
                    <label for="dependenciadocente" class="control-label">Dependencia:</label>
                    <input class="form-control input-sm" type="text" id="dependenciadocente" name="dependenciadocente" pattern="^[A-Za-z Á-ÿ.0-9]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo"/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-3">
                    <label for="teldocente" class="control-label">Telefono de contacto:</label>
                    <input class="form-control input-sm" type="text" id="teldocente" name="teldocente" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo"/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <br />
            <h4>Datos del curso:</h4>
            <div class="row">
                <div class="form-group has-feedback col-md-6">
                    <label for="nombrecurso" class="control-label">Nombre del curso:</label>
                    <input class="form-control input-sm caps" type="text" id="nombrecurso" name="nombrecurso" pattern="^[A-Za-z Á-ÿ.0-9]{1,}$" data-pattern-error="Solo letras" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-4">
                    <label for="locacion" class="control-label">Sede:</label>
                    <select class="form-control input-sm" name="locacion" id="locacion" required>
                        <option value="-1" disabled selected>---Seleccione sede---</option>
                        <?php echo fillSelectLocaciones($locacion); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-1">
                    <label for="duracioncurso" class="control-label">Duración:</label>
                    <input class="form-control input-sm" type="text" id="duracioncurso" name="duracioncurso" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-3">
                    <label for="curacioncurso" class="control-label"> </label>
                    <select class="form-control input-sm" name="unidadduracion" id="unidadduracion" required>
                        <option value="-1" disabled selected>---Seleccione unidad---</option>
                        <?php echo fillSelect($unidadduracion); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-2">
                    <label for="fechainicio" class="control-label">Fecha inicio:</label>
                    <div class="input-group date" data-provide="datepicker">
                        <input type="text" class="form-control input-sm" name="fechainicio" id="fechainicio" placeholder="AAAA-MM-DD" data-required-error="Debe llenar este campo" required/>
                        <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                    </div>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 50px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-2">
                    <label for="fechafin" class="control-label">Fecha final:</label>
                    <div class="input-group date" data-provide="datepicker">
                        <input type="text" class="form-control input-sm" name="fechafin" id="fechafin" placeholder="AAAA-MM-DD" data-required-error="Debe llenar este campo" required/>
                        <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                    </div>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 50px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-4 clockpicker">
                    <label for="horainicio" class="control-label">Horario:</label>
                    <input class="form-control input-sm" type="text" id="horainicio" name="horainicio" placeholder="HH:MM (24hrs)" pattern="^[0-9]{2}:[0-9]{2}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="duracionsesion" class="control-label">Duración de sesión:</label>
                    <input class="form-control input-sm" type="text" id="duracionsesion" name="duracionsesion" placeholder="Horas" pattern="^[0-9.]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="aula" class="control-label">Aula:</label>
                    <select class="form-control input-sm" name="aula" id="aula" required>
                        <option value="-1" disabled selected>---Seleccione aula---</option>
                        <?php echo fillSelectAula(); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                    <optgroup></optgroup>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="form-group has-feedback col-md-6 text-center">
                    <label for="regularidad" class="control-label">Regularidad:</label>
                    <select id="regularidad" name="regularidad[]" multiple="multiple" required>
                        <option value="dom">Domingo</option>
                        <option value="lun">Lunes</option>
                        <option value="mar">Martes</option>
                        <option value="mie">Miercoles</option>
                        <option value="jue">Jueves</option>
                        <option value="vie">Viernes</option>
                        <option value="sab">Sabado</option>
                    </select>
                </div>
                <div class="col-md-3"></div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-3">
                    <label for="cupominimo" class="control-label">Cupo minimo:</label>
                    <input class="form-control input-sm" type="text" id="cupominimo" name="cupominimo" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-3">
                    <label for="cupomaximo" class="control-label">Cupo maximo:</label>
                    <input class="form-control input-sm" type="text" id="cupomaximo" name="cupomaximo" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-3">
                    <label for="edadminima" class="control-label">Edad minima:</label>
                    <input class="form-control input-sm" type="text" id="edadminima" name="edadminima" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-3">
                    <label for="edadmaxima" class="control-label">Edad maxima:</label>
                    <input class="form-control input-sm" type="text" id="edadmaxima" name="edadmaxima" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
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