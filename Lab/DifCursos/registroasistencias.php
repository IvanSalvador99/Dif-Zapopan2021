<?php
    include("menucursos.php");
    
    /*echo "Post:</br>";
    var_dump($_POST);*/
    
    $query = "SELECT * FROM padron_unico.vw_cursos_asistentes WHERE idcurso = '" . $_POST['curso'] . "' ORDER BY nombre ASC";
    
    $numero = 0;
    $asistentes = array();
    if ($result = mysql_query($query, $con)) {
        $numero = mysql_num_rows($result);
        while ($row = mysql_fetch_assoc($result)) {
            $asistentes[] = $row;
        }
    }
    
    $query = "SELECT * FROM $docentes WHERE idcurso = '" . $_POST['curso'] . "'";
    
    if ($result = mysql_query($query, $con)) {
        $rowdocente = mysql_fetch_assoc($result);
    } else {
        echo "</br></br>Error en el query: " . $query;
    }
    
    $asistentes = json_encode($asistentes, JSON_FORCE_OBJECT);
    
    /*echo "</br></br>Asistentes:</br>";
    var_dump($asistentes);*/
?>
        <script type="text/javascript">
            var participantes = [];
            
            function reloadtable (){
                var output = "";
                $("#tablaparticipantes").empty();
                for (var i = 0; i  < participantes.length; i ++) {
                    var insert = "<div class='form-group has-feedback'>"
                                                + "<input class='form-control input-sm' type='text' id='as" + i + "' pattern='^[afj]{1}$' size='1' maxlength='1' data-pattern-error='Solamente A, F o J' data-required-error='Debe llenar este campo' required/>"
                                                + "<span class='glyphicon form-control-feedback' aria-hidden='true'></span>"
                                                + "<div class='help-block with-errors'></div>"
                                                + "</div>";
                    output += "<tr><td>" + (i + 1) + 
                                "</td><td>" + participantes[i].iddifzapopan + 
                                "</td><td>" + participantes[i].nombre +
                                "</td><td>" + insert +
                                "</td></tr>";
                };
                
                $("#tablaparticipantes").append(output);
            }
            
            $(document).ready(function(){
                var temp = <?= $asistentes ?>;
                
                for (i in temp) {
                    participantes.push(temp[i]);
                }
                reloadtable ();
                
                $('.date').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    language: 'es'
                });
                
                $("#btnsubmit").on("click", function(e) {
                    if(($(this).attr("class")).includes("disabled")) {
                        alert ("Boton desabilitado favor de llenar la forma correctamente");
                    } else {
                        var i;
                        for (i = 0; i < participantes.length; i++) {
                            participantes[i].asistencia = $("#as" + i).val();
                        }
                        
                        console.log(participantes);
                        
                        var form = $("#formasistencias");
                            
                        form.append($("<input type='hidden' name='curso'/>").val(<?= $_POST['curso'] ?>));
                        form.append($("<input type='hidden' name='asistencias'/>").val(JSON.stringify(participantes)));
                        console.log(form);
                        form.get(0).submit();
                    }
                });
            });
        </script>
        
        <div class="row">
            <h2 class="text-center">Registro de asistencias en el curso: <?= $rowcurso['nombre'] ?></h2>
            <h3>Docente: <?= $rowdocente['nombre'] ?></h3>
            <br />
            <h6>Nota: En el campo asistencias solo son validos los siguientes elementos <b>a</b> = asistencia, <b>f</b> = falta y <b>j</b> = falta justificada. Cualquier otro elemento, o si se deja vacio se registrara como falta.</h6>
        </div>
        <form id="formasistencias" action="engine/engine_registrarasistencias.php" method="post" data-toggle="validator">
            <div class="row">
                <div class="form-group has-feedback col-md-2">
                    <label for="fecha" class="control-label">Fecha:</label>
                    <div class="input-group date" data-provide="datepicker">
                        <input type="text" class="form-control input-sm" name="fecha" id="fecha" placeholder="AAAA-MM-DD" data-required-error="Debe llenar este campo" required/>
                        <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                    </div>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 50px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <table class="table table-sm table-dark table-responsive">
                    <thead>
                        <tr>
                            <th>Numero</th>
                            <th>ID DIF Zapopan</th>
                            <th>Nombre</th>
                            <th>Asistencia</th>
                        </tr>
                    </thead>
                    <tbody id="tablaparticipantes">
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="form-group text-center col-md-12">
                    <button type="submit" class="btn btn-primary" id="btnsubmit">Registrar</button>
                </div>
            </div>
        </form>