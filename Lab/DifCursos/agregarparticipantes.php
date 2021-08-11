<?php
    include("menucursos.php");
    
    //var_dump($_POST);
    
    $query = "SELECT * FROM $vw_cursos WHERE id = '" . $_POST['curso'] . "'";
    
    if ($result = mysql_query($query, $con)) {
        $rowcurso = mysql_fetch_assoc($result);
    } else {
        echo "</br></br>Error en el query: " . $query;
    }
    
    $query = "SELECT * FROM $docentes WHERE idcurso = '" . $_POST['curso'] . "'";
    
    if ($result = mysql_query($query, $con)) {
        $rowdocente = mysql_fetch_assoc($result);
    } else {
        echo "</br></br>Error en el query: " . $query;
    }
    
    /*echo "</br></br>Curso: </br>";
    var_dump($rowcurso);
    
    echo "</br></br>Docente: </br>";
    var_dump($rowdocente);*/
    
    $query = "SELECT GROUP_CONCAT(idasistente SEPARATOR ',') AS asistentes FROM $asistentes WHERE idcurso = '" . $_POST['curso'] . "'";
    
    if ($result = mysql_query($query, $con)) {
        $rowasistentes = mysql_fetch_assoc($result);
    } else {
        echo "</br></br>Error en el query: " . $query;
    }
    
    $asistentes = $rowasistentes['asistentes'];
    
    $asistentes = explode(",", $asistentes);
    $asistentes = implode("','", $asistentes);
    $asistentes = "'" . $asistentes . "'";
    
    $query = "SELECT * FROM $vw_persona WHERE id IN (" . $asistentes . ")";
    
    $asistentes = array();
    
    if ($result = mysql_query($query, $con)) {
        while ($rowpersona = mysql_fetch_assoc($result)) {
            $asistentes[] = $rowpersona;
        }
    } else {
        echo "</br></br>Error en el query: " . $query;
    }
    
    $asistentes = json_encode($asistentes, JSON_FORCE_OBJECT);
    //echo "</br></br>Asistentes: </br>" . $asistentes;
    
?>
        <script type="text/javascript">
            var participantes = [];
            
            function reloadtable (){
                var output = "";
                $("#tablaparticipantes").empty();
                for (var i = 0; i  < participantes.length; i ++) {
                    output += "<tr><td>" + (i + 1) + 
                                "</td><td>" + participantes[i].iddifzapopan + 
                                "</td><td>" + participantes[i].nombre + " " +  participantes[i].apaterno + " " + participantes[i].amaterno + 
                                "</td><td>" + participantes[i].edad +
                                "</td><td><button type='button' class='btn btn-default' id='btnborrarparticipante' data-value='" + i + "'><span class='glyphicon glyphicon-remove'></button>" +
                                "</td></tr>";
                };
                
                $("#tablaparticipantes").append(output);
            }
            
            $("body").on("click", "#btnagregar", function(){
                $.post('ajax/ajax_getparticipantes.php',{"idsdif": $.trim($("#participantes").val())}, function(respuesta){
                    if (respuesta.numero >= "1") {
                        for (i in respuesta.personas) {
                            participantes.push(respuesta.personas[i]);
                        }
                        reloadtable();
                        $("#participantes").val("");
                    }
                });
            });
            
            $("body").on("click", "#btnborrarparticipante", function(){
                participantes.splice($(this).data("value"), 1);
                
                reloadtable();
            });
            
            $(document).ready(function(){
                var temp = <?= $asistentes ?>;
                
                for (i in temp) {
                    participantes.push(temp[i]);
                }
                reloadtable();
                
                $("#btnsubmit").on("click", function(e) { 
                    e.preventDefault();
                    var temp = '<input type="hidden" id="curso" name="curso" value="' + <?= $_POST['curso'] ?> + '" />';
                    var i;
                    for (i = 0; i < participantes.length; i++) {
                        temp += '<input type="hidden" id="curso" name="participantes[]" value="' + participantes[i].id + '" />';
                    }
                    
                    $('<form>', {
                        "id": "formaddppl",
                        "method": "post",
                        "html": temp,
                        "action": 'engine/engine_agregarparticipantes.php'
                    }).appendTo(document.body).submit();
                });
            });
        </script>
        <div class="row">
            <h2 class="text-center">Registro de participantes en el curso: <?= $rowcurso['nombre'] ?></h2>
            <h3>Docente: <?= $rowdocente['nombre'] ?></h3>
        </div>
        <div class="row">
            <div class="form-group has-feedback col-md-4">
                <label for="participantes" class="control-label">Id(s):</label>
                <input class="form-control input-sm" type="text" id="participantes" placeholder="ID o ID's (separados por coma ',')" name="participantes" pattern="^[A-Za-z Á-ÿ.0-9]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo"/>
            </div>
            <div class="form-group has-feedback col-md-2">
                <button type='button' class='btn btn-default' id='btnagregar' style="margin-top: 22px">Agregar <span class='glyphicon glyphicon-plus'></button>
            </div>
        </div>
        <div class="row">
            <table class="table table-sm table-dark table-responsive">
                <thead>
                    <tr>
                        <th>Numero</th>
                        <th>ID DIF Zapopan</th>
                        <th>Nombre</th>
                        <th>Edad</th>
                        <th>Acción</th>
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