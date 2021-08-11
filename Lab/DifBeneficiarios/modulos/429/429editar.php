<?php
    require_once("../../menupadron.php");
    var_dump($_POST);
    $rowpersona = null;
    $rowentrevista = null;
    $rowestudio = null;
    $familia = array();
    $ap = array();
    $av = array();
    
    $query = "SELECT * FROM $estudio_socioeconomico WHERE idservicio = '" . $_POST['idservicio'] . "'";
    if ($result = mysql_query($query, $con)) {
        $rowestudio = mysql_fetch_assoc($result);
        /*echo "</br></br>Estudio:</br>";
        var_dump($rowestudio);*/
    }
    
    $query = "SELECT * FROM $vw_persona WHERE id = '" . $rowestudio['idpersona'] . "'";
    if ($result = mysql_query($query, $con)) {
        $rowpersona = mysql_fetch_assoc($result);
        /*echo "</br></br>Persona:</br>";
        var_dump($rowpersona);*/
    }
    
    $query = "SELECT * FROM $personas_ventanilla_unica WHERE idpersona = '" . $rowestudio['idpersona'] . "' ORDER BY id DESC LIMIT 1";
    if ($result = mysql_query($query, $con)) {
        $rowentrevista = mysql_fetch_assoc($result);
        /*echo "</br></br>Entrevista:</br>";
        var_dump($rowentrevista);*/
    }
    
    $query = "SELECT * FROM $estsocio_comp_familiar WHERE idservicio = '" . $_POST['idservicio'] . "'";
    if ($result = mysql_query($query, $con)) {
        if (mysql_num_rows($result) > 0){
            while ($rowfamiliar = mysql_fetch_assoc($result)) {
                $familia[] = $rowfamiliar;
            }
        }
        /*echo "</br></br>Familiares:</br>";
        var_dump($familia);*/
    }
    
    $query = "SELECT * FROM $estsocio_apoyos WHERE idservicio = '" . $_POST['idservicio'] . "'";
    if ($result = mysql_query($query, $con)) {
        if (mysql_num_rows($result) > 0) {
            while ($rowapoyo = mysql_fetch_assoc($result)) {
                $ap[] = $rowapoyo;
            }
        }
        /*echo "</br></br>Apoyos:</br>";
        var_dump($ap);*/
    }
    
    $query = "SELECT * FROM $estsocio_avances WHERE idservicio = '" . $_POST['idservicio'] . "'";
    if ($result = mysql_query($query, $con)) {
        if (mysql_num_rows($result) > 0) {
            while ($rowavance = mysql_fetch_assoc($result)) {
                $av[] = $rowavance;
            }
        }
        /*echo "</br></br>Avances:</br>";
        var_dump($av);*/
    }
?>
        <script type="text/javascript">
            var parentescos = <?php echo json_encode($arrayparentesco); ?>;
            var familiares = <?php echo json_encode($familia) ?>;
            var avances = <?php echo json_encode($av) ?>;
            var apoyos = <?php echo json_encode($ap) ?>;
            var temp;
            var selectparentesco = '<?php echo fillSelect($parentesco); ?>';
            var selectestadocivil = '<?php echo fillSelect($estado_civil); ?>';
            var selectescolaridad = '<?php echo fillSelect($escolaridad_estsocio); ?>';
            var selectocupacion = '<?php echo fillSelect($ocupacion); ?>';
            var selectsexo = '<?php echo fillSelect($sexo); ?>'
            var tipofamiliar = 0;
            
            $("body").on("click", "#btnborrarpariente", function(){
                //alert (familiares[$(this).data("value")].nombre);
                familiares.splice($(this).data("value"), 1);
                
                reloadtable();
                calcularbalanace();
            });
            
            $("body").on("blur change", "#egresoalimentos, #egresovivienda, #egresoservicios, #egresotransporte, #egresoeducacion, #egresosalud, #egresovestido, #egresorecreacion, #egresodeudas, #egresootros, #ingresotitular, #ingresootros", function(){
                //alert ("Hola egresos y un ingreso");
                calcularbalanace();
            });
            
            $("body").on("blur", "#iddif", function(){
                $.post('../ajax/ajax_getpersona',{"iddif": $.trim($("#iddif").val())}, function(respuesta){
                    $("#descpersona").html("");
                    $("#descpersona").html("<b>ID:</b> " + respuesta.id + "<br /><b>Nombre:</b> " + respuesta.nombre + "<br /><b>CURP:</b> " + respuesta.curp);
                    $("#rowparentesco").show();
                    $("#rowingreso").show();
                    $("#rowescolaridad").show();
                    temp = respuesta;
                });
            });
            
            $("body").on("blur", ".caps", function(){
                $(this).val($(this).val().toUpperCase());
            });
            
            $("body").on("click", "#btnagregar", function(){
                //alert(familiares.length);
                if (tipofamiliar == 1) {
                    if (temp != null) {
                        if ($("#parentesco").val() > 0) {
                            familiares.push(temp);
                            familiares[familiares.length - 1].parentesco = $("#parentesco option:selected").text();
                            familiares[familiares.length - 1].tipoingreso = $("#tipoingresopariente option:selected").text();
                            familiares[familiares.length - 1].ingreso = $("#ingresopariente").val();
                            familiares[familiares.length - 1].grado = $("#gradopariente option:selected").text();
                            familiares[familiares.length - 1].escolaridad = $("#escolaridadpariente option:selected").text();
                            familiares[familiares.length - 1].id = null;
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
                                sexo: $("#sexopariente option:selected").text(),
                                estadocivil: $("#estadocivilpariente option:selected").text(),
                                grado: $("#gradopariente option:selected").text(),
                                escolaridad: $("#escolaridadpariente option:selected").text(),
                                ocupacion: $("#ocupacionpariente option:selected").text(),
                                tipoingreso: $("#tipoingresopariente option:selected").text(),
                                ingreso: $("#ingresopariente").val(),
                                id: null,
                                idpariente:null});
                    } else {
                        alert("Faltan datos por agregar");
                    }
                } else {
                    alert ("No seleccionaste el tipo de familiar a agregar.");
                }
                temp = null;
                tipofamiliar = 0;
                reloadtable();
                calcularbalanace();
            });
            
            function reloadtable (){
                var output = "";
                console.log(familiares);
                $("#tablafamiliar").empty();
                for (var i = 0; i  < familiares.length; i ++) {
                    output += "<tr><td>" + (i + 1) + 
                                "</td><td>" + familiares[i].nombre + 
                                "</td><td>" + familiares[i].fechanacimiento + 
                                "</td><td>" + familiares[i].curp + 
                                "</td><td>" + familiares[i].edad +
                                "</td><td>" + familiares[i].sexo + 
                                "</td><td>" + familiares[i].estadocivil + 
                                "</td><td>" + familiares[i].parentesco + "</td>" +
                                "<td>" + familiares[i].grado + " - " + familiares[i].escolaridad + 
                                "</td><td>" + familiares[i].ocupacion +
                                "</td><td>" + familiares[i].tipoingreso +
                                "</td><td>" + familiares[i].ingreso +
                                "</td><td><button type='button' class='btn btn-default' id='btnborrarpariente' data-value='" + i + "'><span class='glyphicon glyphicon-remove'></button>" +
                                "</td></tr>";
                };
                
                $("#tablafamiliar").append(output);
            }
            
            function reloadtableavance (){
                var output = "";
                //console.log(avances);
                $("#tablaavance").empty();
                for (var i = 0; i  < avances.length; i ++) {
                    output += "<tr><td>" + (i + 1) + 
                                "</td><td>" + avances[i].fechaavance + 
                                "</td><td>" + avances[i].avance + 
                                "</td></tr>";
                };
                
                $("#tablaavance").append(output);
            }
            
            function reloadtableapoyo (){
                var output = "";
                //console.log(apoyos);
                $("#tablaapoyo").empty();
                for (var i = 0; i  < apoyos.length; i ++) {
                    output += "<tr><td>" + (i + 1) + 
                                "</td><td>" + apoyos[i].apoyofecha +
                                "</td><td>" + apoyos[i].apoyoinstitucion +
                                "</td><td>" + apoyos[i].apoyopasado +
                                "</td><td>" + apoyos[i].apoyoperiodo +
                                "</td><td>" + apoyos[i].apoyomonto +
                                "</td></tr>";
                };
                
                $("#tablaapoyo").append(output);
            }
            
            function calcularbalanace() {
                var ingreso = 0;
                var egreso = 0;
                
                for (var i = 0; i  < familiares.length; i ++) {
                    ingreso += Number(familiares[i].ingreso);
                }
                
                $("#ingresofamiliar").val(ingreso); // "<b>Ingreso per capita diario: </b>$" + (ingreso / familiares.length / 30)
                
                ingreso += Number($("#ingresootros").val());
                ingreso += Number($("#ingresotitular").val());
                
                $("#prompercapita").html("<b>Ingreso per capita mensual: </b>$" + (ingreso / (familiares.length + 1)));
                $("#prompercapita").append("</br><b>Ingreso per capita diario: </b>$" + (ingreso / (familiares.length + 1) / 30));
                $("#ingresototal").val(ingreso);
                
                egreso = Number($("#egresoalimentos").val()) + Number($("#egresovivienda").val()) + Number($("#egresoservicios").val()) + Number($("#egresotransporte").val()) + Number($("#egresoeducacion").val()) + Number($("#egresosalud").val()) + Number($("#egresovestido").val()) + Number($("#egresorecreacion").val()) + Number($("#egresodeudas").val()) + Number($("#egresootros").val());
                $("#egresototal").val(egreso);
                
                $("#diferenciatotal").val(ingreso - egreso);
                
            }
            
            $(document).ready(function(){
                $("#programa").on("change", function(){
                    if ($(this).val() == 1) {
                        $("#numeroprograma").val("1056");
                    } else if ($(this).val() == 2) {
                        $("#numeroprograma").val("1057");
                    } else {
                        
                    }
                });
                
                $('.date').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                        language: 'es',
                        endDate: '0d',
                        orientation: 'bottom left'
                    });
                
                $("#estadonacimiento").on("change", function(e) {
                    //alert("Hola");
                    $("#municipionacimiento").val("Obteniendo municipios...");
                    $.post('../ajax/ajax_municipionacimiento.php',{"estado": this.value }, function(respuesta){
                        //alert(respuesta.mensaje);
                        var output = "<option value='-1' disabled selected>---Seleccione un municipio---</option>";
                        $.each(respuesta.municipio, function(index, value) {
                            if (value == '<?= $rowestudio['municipionac'] ?>') {
                                output += "<option selected>" + value + "</option>";
                            } else {
                                output += "<option>" + value + "</option>";
                            }
                        });
                        $("#municipionacimiento").html(output);
                        $("#municipionacimiento").prop("disabled", false);
                        $("#municipionacimiento").prop("required", true);
                        $("#formregistroestudio").validator("update");
                    });
                });
                
                $("#agregarmiembro").on("click", function(e){
                    //alert("Hola");
                    $("#modalagregarpersona").modal();
                });
                
                $("#agregaravance").on("click", function(e){
                    //alert("Hola");
                    $("#modalagregaravance").modal();
                });
                
                $("#agregarapoyo").on("click", function(e){
                    //alert("Hola");
                    $("#modalagregarapoyo").modal();
                });
                
                $("#btnbeneficiario").on("click", function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    tipofamiliar = 1;
                    $("#modalbody").empty();
                    $("#modalbody").html("<h4 class='modal-title text-center'>Introduzca ID DIF Zapopan:</h4> <br /> <div class='form-group'> <input class='form-control input-sm' type='text' id='iddif' placeholder='ID DIF Zapopan' required/> </div> <p id='descpersona'> </p> <div class='row' id='rowescolaridad' style='display:none'> <div class='form-group has-feedback col-md-4'> <label for='gradopariente' class='control-label'>Grado:</label> <select class='form-control input-sm' name='gradopariente' id='gradopariente' required> <option value='-1' disabled selected>---Seleccione grado---</option><option>Terminado</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option><option>12</option></select> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span> <div class='help-block with-errors'></div> </div> <div class='form-group has-feedback col-md-8'> <label for='escolaridadpariente' class='control-label'>Nivel escolar:<small></small></label> <select class='form-control input-sm' name='escolaridadpariente' id='escolaridadpariente' required> <option value='-1' disabled selected>---Seleccione nivel escolar---</option>" + selectescolaridad + "</select> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span> <div class='help-block with-errors'></div> </div> </div> <div class='row' id='rowparentesco' style='display:none'> <div class='form-group has-feedback col-md-12'> <label for='parentesco'>Parentesco:</label> <select class='form-control input-sm' name='parentesco' id='parentesco' required> <option value='-1' disabled selected>---Seleccione el parentesco---</option>" + selectparentesco + "</select><span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 10px'></span> <div class='help-block with-errors'></div></div></div><div class='row' id='rowingreso' style='display:none'><div class='form-group has-feedback col-md-6'><label for='tipoingresopariente' class='control-label'>Tipo de ingreso:</label><select class='form-control input-sm' name='tipoingresopariente' id='tipoingresopariente' required><option disabled selected>---Seleccione tipo de ingreso---</option><option>Sin ingreso</option><option>Permanente</option><option>Eventual</option></select> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 30px'></span> <div class='help-block with-errors'></div> </div> <div class='form-group has-feedback col-md-6'> <label for='ingresopariente' class='control-label'>Ingreso:</label> <input type='text' class='form-control input-sm' id='ingresopariente' name='ingresopariente' pattern='^[0-9]{1,}$' data-pattern-error='Solo numeros' data-required-error='Debe llenar este campo' required/> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span> <div class='help-block with-errors'></div> </div> </div><div class='row text-center'><button type='submit' class='btn btn-primary' id='btnagregar' data-dismiss='modal'>Agregar</button></div>");
                    $("#modalbody").validator("update");
                });
                
                $("#btnpariente").on("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    tipofamiliar = 2;
                    $("#modalbody").empty();
                    $("#modalbody").html("<div class='row'> <div class='form-group col-md-12 has-feedback caps'> <label for='nombrepariente' class='control-label'>Nombre:</label> <input class='form-control input-sm caps' type='text' id='nombrepariente' name='nombrepariente' pattern='^[A-Za-zñÑ .]{1,}$' data-pattern-error='Solo letras (sin acentos)' data-required-error='Debe llenar este campo' required/> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 10px'></span> <div class='help-block with-errors'></div> </div> </div> <div class='row'> <div class='form-group has-feedback col-md-6'> <label for='fechanacpariente' class='control-label'>Fecha de nacimiento:</label> <div class='input-group date' data-provide='datepicker'> <input type='text' class='form-control' name='fechanacpariente' id='fechanacpariente' data-required-error='Debe llenar este campo' required/> <div class='input-group-addon'><span class='glyphicon glyphicon-th'></span></div> </div> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 50px'></span> <div class='help-block with-errors'></div> </div> <div class='form-group has-feedback col-md-3'> <label for='edadpariente' class='control-label'>Edad:</label> <input type='text' class='form-control input-sm' id='edadpariente' name='edadpariente' pattern='^[0-9]{1,}$' data-pattern-error='Solo numeros' data-required-error='Debe llenar este campo' required/> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span> <div class='help-block with-errors'></div> </div> <div class='form-group has-feedback col-md-3'><label for='sexopariente' class='control-label'>Sexo:</label> <select class='form-control input-sm' name='sexopariente' id='sexopariente' required> <option value='-1' disabled selected>---Seleccione sexo---</option><option value='1'>Hombre</option><option value='2'>Mujer</option></select> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span> <div class='help-block with-errors'></div></div> </div> <div class='row'> <div class='form-group has-feedback col-md-6'> <label for='curppariente' class='control-label'>CURP:</label> <input class='form-control input-sm caps' type='text' id='curppariente' name='curppariente' data-pattern-error='No es un formato valido de CURP' pattern='^[A-Za-z]{1}[AEIOUXaeioux]{1}[A-Za-z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HMhm]{1}(AS|as|BC|bc|BS|bs|CC|cc|CS|cs|CH|ch|CL|cl|CM|cm|DF|df|DG|dg|GT|gt|GR|gr|HG|hg|JC|jc|MC|mc|MN|mn|MS|ms|NT|nt|NL|nl|OC|oc|PL|pl|QT|qt|QR|qr|SP|sp|SL|sl|SR|sr|TC|tc|TS|ts|TL|tl|VZ|vz|YN|yn|ZS|zs|NE|ne)[B-DF-HJ-NP-TV-Zb-df-hj-np-tv-z]{3}[0-9A-Za-z]{1}[0-9]{1}$'/> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 10px'></span> <div class='help-block with-errors'></div> </div> <div class='form-group has-feedback col-md-6'> <label for='estadocivilpariente' class='control-label'>Estado civil:</label> <select class='form-control input-sm' name='estadocivilpariente' id='estadocivilpariente' required> <option value='-1' disabled selected>---Seleccione estado civil---</option>" + selectestadocivil +
                                            "</select> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span> <div class='help-block with-errors'></div> </div> </div> <div class='row'> <div class='form-group has-feedback col-md-4'> <label for='gradopariente' class='control-label'>Grado:</label> <select class='form-control input-sm' name='gradopariente' id='gradopariente' required> <option value='-1' disabled selected>---Seleccione grado---</option><option>Terminado</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option><option>12</option></select> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span> <div class='help-block with-errors'></div> </div> <div class='form-group has-feedback col-md-8'> <label for='escolaridadpariente' class='control-label'>Nivel escolar:<small></small></label> <select class='form-control input-sm' name='escolaridadpariente' id='escolaridadpariente' required> <option value='-1' disabled selected>---Seleccione nivel escolar---</option>" + selectescolaridad +
                                            "</select> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span> <div class='help-block with-errors'></div> </div> </div> <div class='row'> <div class='form-group has-feedback col-md-6'> <label for='ocupacionpariente' class='control-label'>Ocupaci&oacute;n:</label> <select class='form-control input-sm' name='ocupacionpariente' id='ocupacionpariente' required> <option value='-1' disabled selected>---Seleccione ocupaci&oacute;n---</option>" + selectocupacion + 
                                            "</select> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span> <div class='help-block with-errors'></div> </div> <div class='form-group has-feedback col-md-6' id='inputparentesco'> <label for='parentesco'>Parentesco:</label> <select class='form-control input-sm' name='parentesco' id='parentesco' required> <option value='-1' disabled selected>---Seleccione el parentesco---</option>" + selectparentesco +
                                            "</select> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span> <div class='help-block with-errors'></div> </div> </div> <div class='row'><div class='form-group has-feedback col-md-6'><label for='tipoingresopariente' class='control-label'>Tipo de ingreso:</label><select class='form-control input-sm' name='tipoingresopariente' id='tipoingresopariente' required><option disabled selected>---Seleccione tipo de ingreso---</option><option>Sin ingreso</option><option>Permanente</option><option>Eventual</option>" +
                                            "</select> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 30px'></span> <div class='help-block with-errors'></div> </div> <div class='form-group has-feedback col-md-6'> <label for='ingresopariente' class='control-label'>Ingreso:</label> <input type='text' class='form-control input-sm' id='ingresopariente' name='ingresopariente' pattern='^[0-9]{1,}$' data-pattern-error='Solo numeros' data-required-error='Debe llenar este campo' required/> <span class='glyphicon form-control-feedback' aria-hidden='true' style='margin-right: 25px'></span> <div class='help-block with-errors'></div> </div> </div> <div class='row text-center'><button type='submit' class='btn btn-primary' id='btnagregar' data-dismiss='modal'>Agregar</button></div>");
                                            
                    $('.date').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                        language: 'es',
                        endDate: '0d',
                        orientation: 'bottom left'
                    });
                    
                    $("#modalbody").validator("update");
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
                
                $("#modalagregaravance").on("hidden.bs.modal", function(e){
                    $("#segavevol").val("");
                    $("#fechaavance").val("");
                    $("#modalbodyavance").validator("validate");
                });
                
                $("#modalagregarapoyo").on("hidden.bs.modal", function(e){
                    $("#apoyoinstitucion").val(-1);
                    $("#apoyopasado").val("");
                    $("#apoyofecha").val("");
                    $("#apoyoperiodo").val("");
                    $("#apoyomonto").val("");
                    $("#modalagregarapoyo").validator("validate");
                });
                
                $("#btnagregaravance").on("click", function (e){
                    if ($("#segavevol").val() != "") {
                        var avance = {fechaavance: $("#fechaavance").val(), avance: $("#segavevol").val()};
                        avances.push(avance);
                        reloadtableavance();
                    } else {
                        alert("El campo descripción no puede estar vacio");
                    }
                });
                
                $("#btnagregarapoyo").on("click", function (e){ 
                    if ($("#apoyoinstitucion").val() != "") {
                        var apoy = {apoyoinstitucion: $("#apoyoinstitucion option:selected").text(),
                                    apoyopasado: $("#apoyopasado").val(),
                                    apoyofecha: $("#apoyofecha").val(),
                                    apoyoperiodo: $("#apoyoperiodo").val(),
                                    apoyomonto: $("#apoyomonto").val()};
                        apoyos.push(apoy);
                        reloadtableapoyo();
                    } else {
                        alert("El campo descripción no puede estar vacio");
                    }
                });
                
                $("#condicionvivienda").on("change", function (e){
                    if (($("#condicionvivienda").val() == 2 || $("#condicionvivienda").val() == 3 || $("#condicionvivienda").val() == 5) && $("#divotrotipoviv").is(":hidden")) {
                        $("#rowotros").css('display','block');
                        $("#rowotros").css('visibility','visible');
                        $("#divotrocond").css('visibility','visible');
                        $("#divotrotipoviv").css('visibility','hidden');
                        $("#divotrocond").prop('required', true);
                        $("#formregistroestudio").validator("update");
                    } else if (($("#condicionvivienda").val() == 2 || $("#condicionvivienda").val() == 3 || $("#condicionvivienda").val() == 5) && $("#divotrotipoviv").is(":visible")) {
                        $("#divotrocond").css('visibility','visible');
                        $("#divotrocond").prop('required', true);
                        $("#formregistroestudio").validator("update");
                    } else if ($("#divotrotipoviv").css('visibility') === 'visible') {
                        $("#divotrocond").css('visibility','hidden');
                        $("#divotrocond").prop('required', false);
                        $("#formregistroestudio").validator("update");
                    } else {
                        $("#rowotros").css('display','none');
                        $("#rowotros").css('visibility','hidden');
                        $("#divotrocond").css('visibility','hidden');
                        $("#divotrocond").prop('required', false);
                        $("#formregistroestudio").validator("update");
                    }
                });
                
                $("#tipovivienda").on("change", function (e){
                    if ($("#tipovivienda").val() == 4 && $("#divotrocond").is(":hidden")) {
                        $("#rowotros").css('display','block');
                        $("#rowotros").css('visibility','visible');
                        $("#divotrotipoviv").css('visibility','visible');
                        $("#divotrocond").css('visibility','hidden');
                        $("#divotrotipoviv").prop('required', true);
                        $("#formregistroestudio").validator("update");
                    } else if ($("#tipovivienda").val() == 4 && $("#divotrocond").is(":visible")) {
                        $("#divotrotipoviv").css('visibility','visible');
                        $("#divotrotipoviv").prop('required', true);
                        $("#formregistroestudio").validator("update");
                    } else if ($("#divotrocond").css('visibility') === 'visible') {
                        $("#divotrotipoviv").css('visibility','hidden');
                        $("#divotrotipoviv").prop('required', false);
                        $("#formregistroestudio").validator("update");
                    } else {
                        $("#rowotros").css('display','none');
                        $("#rowotros").css('visibility','hidden');
                        $("#divotrotipoviv").css('visibility','hidden');
                        $("#divotrotipoviv").prop('required', false);
                        $("#formregistroestudio").validator("update");
                    }
                });
                
                $("#btnsubmit").on("click", function(e) { 
                    e.preventDefault();
                    if(($(this).attr("class")).includes("disabled")) {
                        alert ("Boton desabilitado favor de llenar la forma correctamente");
                    } else {
                        var form = $("#formregistroestudio");
                        
                        form.append($("<input type='hidden' name='servicio'/>").val(<?=$_POST['idservicio']?>));
                        form.append($("<input type='hidden' name='familiares'/>").val(JSON.stringify(familiares)));
                        form.append($("<input type='hidden' name='avances'/>").val(JSON.stringify(avances)));
                        form.append($("<input type='hidden' name='apoyos'/>").val(JSON.stringify(apoyos)));
                        console.log(form);
                        form.get(0).submit();
                    }
                });
                
                $("#tipovivienda").trigger("change");
                $("#condicionvivienda").trigger("change");
                $("#estadonacimiento").trigger("change");
                $("#programa").trigger("change");
                reloadtable();
                reloadtableavance();
                reloadtableapoyo();
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
                    <div class="modal-body">
                        <form method="post" id="modalbody" data-toggle="validator"></form>
                    </div>
                    <div class="modal-footer">                        
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalagregaravance" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">Agregar seguimiento y/o evolución</h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="modalbodyavance" data-toggle="validator">
                            <div class="row">
                                <div class="form-group has-fedback col-md-12">
                                    <label for="segavevol" class="control-label">Avance/evolución:<small> *</small></label>
                                    <textarea class="form-control input-sm" form="modalbodyavance" rows="8" name="segavevol" id="segavevol" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="255" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group has-feedback col-md-6">
                                    <label for="fechaavance" class="control-label">Fecha:<small> *</small></label>
                                    <div class="input-group date" data-provide="datepicker">
                                        <input type="text" class="form-control" name="fechaavance" id="fechaavance" data-required-error="Debe llenar este campo" required/>
                                        <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                    </div>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 50px"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class='row text-center'>
                                <button type='submit' class='btn btn-primary' id='btnagregaravance' data-dismiss='modal'>Agregar</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">                        
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalagregarapoyo" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">Agregar apoyo y/o servicio</h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="modalbodyapoyo" data-toggle="validator">
                            <div class="row">
                                <div class="form-group has-feedback col-md-12"> 
                                    <label for="apoyoinstitucion" class="control-label">Institución:</label>
                                    <select class="form-control input-sm" name="apoyoinstitucion" id="apoyoinstitucion" required>
                                        <option value="-1" selected disabled>--- Seleccione institución ---</option>
                                        <?php echo fillSelect($procedencia); ?>
                                    </select>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group has-feedback col-md-12">
                                    <label for="apoyopasado" class="control-label">Apoyo y/o servicio:</label>
                                    <input class="form-control input-sm" type="text" id="apoyopasado" name="apoyopasado" maxlength="255" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento) y numeros, coma y punto" data-required-error="Debe llenar este campo" required/>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group has-feedback col-md-12">
                                    <label for="apoyofecha" class="control-label">Fecha:<small> *</small></label>
                                    <div class="input-group date" data-provide="datepicker">
                                        <input type="text" class="form-control" name="apoyofecha" id="apoyofecha" data-required-error="Debe llenar este campo" required/>
                                        <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                    </div>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 50px"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group has-feedback col-md-12">
                                    <label for="apoyoperiodo" class="control-label">Periodo:</label>
                                    <input class="form-control input-sm" type="text" id="apoyoperiodo" name="apoyoperiodo" maxlength="100" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento) y numeros, coma y punto" data-required-error="Debe llenar este campo" required/>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group has-feedback col-md-12">
                                    <label for="apoyomonto" class="control-label">Monto:</label>
                                    <input class="form-control input-sm" type="text" id="apoyomonto" name="apoyomonto" maxlength="100" pattern="^(Desconocido|Especie|Donativo|[0-9]{1,})$" data-pattern-error="Solo 'Desconocido', 'Especie', 'Donativo' o la cantidad." data-required-error="Debe llenar este campo" required/>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class='row text-center'>
                                <button type='submit' class='btn btn-primary' id='btnagregarapoyo' data-dismiss='modal'>Agregar</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">                        
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="text-center">
                <h2>Estudio Sociofamiliar</h2>
                <a href="engine/print_429.php?idservicio=<?=$_POST['idservicio']?>" target="_blank" class="btn btn-primary" id="btnimprimir">Imprimir estudio</a>    
            </div>
            <br />
            <form method="post" action="engine/engine_429editar.php" id="formregistroestudio" data-toggle="validator">
                <h4>Datos de identificación del beneficiario</h4>
                <div class="row">
                    <div class="form-group col-md-6 has-feedback">
                        <label for="registro">Registro:<small> *</small></label>
                        <input class="form-control input-sm caps" type="text" id="registro" name="registro" value="<?= $rowestudio['registro'] ?>" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-6">
                        <label for="referidopor" class="control-label">Referido por:<small> *</small></label>
                        <select class="form-control input-sm" name="referidopor" id="referidopor" required>
                            <?php echo fillSelectData($procedencia, $rowestudio['procedencia']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-3">
                        <label for="apellidop" class="control-label">Apellido Paterno:</label>
                        <input class="form-control input-sm caps" type="text" id="apellidop" name="apellidop" value="<?= $rowpersona['apaterno'] ?>" pattern="^[A-Za-zñÑ ]{1,}$" data-pattern-error="Solo letras (sin acentos)" data-required-error="Debe llenar este campo" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-3">
                        <label for="apellidom" class="control-label">Apellido Materno:</label>
                        <input class="form-control input-sm caps" type="text" id="apellidom" name="apellidom" value="<?= $rowpersona['amaterno'] ?>" pattern="^[A-Za-zñÑ ]{1,}$" data-pattern-error="Solo letras (sin acentos)" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-3">
                        <label for="nombre" class="control-label">Nombre(s):</label>
                        <input class="form-control input-sm caps" type="text" id="nombre" name="nombre" value="<?= $rowpersona['nombre'] ?>" pattern="^[A-Za-zñÑ .]{1,}$" data-pattern-error="Solo letras (sin acentos)" data-required-error="Debe llenar este campo" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group col-md-3 has-feedback">
                        <label for="iddif">ID DIF:</label>
                        <input class="form-control input-sm caps" type="text" id="iddif" name="iddif" value="<?= $rowpersona['iddifzapopan'] ?>" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <br />
                <h4>Lugar de nacimiento</h4>
                <div class="row">
                    <div class="form-group has-feedback col-md-3">
                        <label for="fechanacimiento" class="control-label">Fecha de nacimiento:</label>
                        <div class="input-group date" data-provide="datepicker">
                            <input type="text" class="form-control" name="fechanacimiento" id="fechanacimiento" value="<?= $rowpersona['fechanacimiento'] ?>" data-required-error="Debe llenar este campo" disabled/>
                            <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                        </div>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 50px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-3">
                        <label for="paisnacimiento" class="control-label">País:<small> *</small></label>
                        <select class="form-control input-sm" name="paisnacimiento" id="paisnacimiento" required>
                            <option value="-1" disabled selected>---Seleccione un país---</option>
                            <?php echo fillSelectData($paises, $rowestudio['pais']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-3">
                        <label for="estadonacimiento" class="control-label">Estado de nacimiento:<small> *</small></label>
                        <select class="form-control input-sm" name="estadonacimiento" id="estadonacimiento" required>
                            <option value="-1" selected disabled>---Seleccione estado---</option>
                            <?php
                                $query = "SELECT DISTINCT estado FROM $colonias ORDER BY estado";
                                if ($result = mysql_query($query, $con)) {
                                    while ($rowestado = mysql_fetch_array($result)) {
                                        if ($rowestado['estado'] == $rowestudio['estadonac']) {
                                            echo "<option selected>" . $rowestado['estado'] . "</option>";
                                        } else {
                                            echo "<option>" . $rowestado['estado'] . "</option>";
                                        }
                                    }
                                }
                            ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-3">
                        <label for="municipionacimiento" class="control-label">Municipio de nacimiento:<small> *</small></label>
                        <select class="form-control input-sm" name="municipionacimiento" id="municipionacimiento" disabled></select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <br />
                <h4>Lugar de residencia</h4>
                <div class="row">
                    <div class="form-group has-feedback col-md-6">
                        <label for="calle" class="control-label">Calle:</label>
                        <input class="form-control input-sm" type="text" id="calle" name="calle" value="<?= $rowpersona['calle'] ?>" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento), coma y punto" data-required-error="Debe llenar este campo" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-3">
                        <label for="numext" class="control-label">Numero exterior:</label>
                        <input class="form-control input-sm" type="text" id="numext" name="numext" value="<?= $rowpersona['numext'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-3">
                        <label for="numint" class="control-label">Numero interior:</label>
                        <input class="form-control input-sm caps" type="text" id="numint" name="numint" value="<?= $rowpersona['numint'] ?>" pattern="^[0-9A-za-z]{1,}$" data-pattern-error="Solo numeros y letras" data-required-error="Debe llenar este campo" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-6">
                        <label for="primercruce" class="control-label">Calle de interseccion:</label>
                        <input class="form-control input-sm" type="text" id="primercruce" name="primercruce" maxlength="100" value="<?= $rowpersona['primercruce'] ?>" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento), coma y punto" data-required-error="Debe llenar este campo" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-6">
                        <label for="segundocruce" class="control-label">Calle de interseccion:</label>
                        <input class="form-control input-sm" type="text" id="segundocruce" name="segundocruce" maxlength="100" value="<?= $rowpersona['segundocruce'] ?>" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento), coma y punto" data-required-error="Debe llenar este campo" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-3">
                        <label for="colonia" class="control-label">Colonia:</label>
                        <input class="form-control input-sm" type="text" id="colonia" name="colonia" value="<?= $rowpersona['colonia'] ?>" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento), coma y punto" data-required-error="Debe llenar este campo" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-2">
                        <label for="cp" class="control-label">Codigo postal:</label>
                        <input class="form-control input-sm" type="text" id="cp" name="cp" value="<?= $rowpersona['codigopostal'] ?>" pattern="^[0-9]{4,5}$" data-pattern-error="Solo 4 o 5 numeros" data-required-error="Debe llenar este campo" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-2">
                        <label for="estado" class="control-label">Estado:</label>
                        <input class="form-control input-sm" type="text" id="estado" name="estado" value="<?= $rowpersona['estado'] ?>" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras" data-required-error="Debe llenar este campo" required disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-2">
                        <label for="municipio" class="control-label">Municipio:</label>
                        <input class="form-control input-sm" type="text" id="municipio" name="municipio" value="<?= $rowpersona['municipio'] ?>" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras" data-required-error="Debe llenar este campo" required disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-3">
                        <label for="tiempoestado" class="control-label">Tiempo de vivir en el estado:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="tiempoestado" name="tiempoestado" maxlength="100" value="<?= $rowestudio['tiempoestado'] ?>" maxlength="255" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento) y numeros, coma y punto" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="telefono" class="control-label">Telefono:</label>
                        <input class="form-control input-sm" type="text" id="telefono" name="telefono" value="<?= $rowpersona['telefono'] ?>" pattern="^[0-9]{10}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px;"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="celular" class="control-label">Celular:</label>
                        <input class="form-control input-sm" type="text" id="celular" name="celular" value="<?= $rowpersona['celular'] ?>" pattern="^[0-9]{10}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="email" class="control-label">E-mail:</label>
                        <input class="form-control input-sm" type="text" id="email" name="email" value="<?= $rowpersona['email'] ?>" pattern="^[a-zA-Z0-9_]+(?:\.[A-Za-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?!([a-zA-Z0-9]*\.[a-zA-Z0-9]*\.[a-zA-Z0-9]*\.))(?:[A-Za-z0-9](?:[a-zA-Z0-9-]*[A-Za-z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$" data-pattern-error="No es un email valido" data-required-error="Debe llenar este campo" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="programa" class="control-label">Programa:<small> *</small></label>
                        <select class="form-control input-sm" name="programa" id="programa" required>
                            <option value="1" <?php echo ($rowestudio['programa'] == 1) ? "selected" : "" ; ?>>Caso urgente</option>
                            <option value="2" <?php echo ($rowestudio['programa'] == 2) ? "selected" : "" ; ?>>Fortalecimiento sociofamiliar</option>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="numeroprograma" class="control-label">Numero de programa:</label>
                        <input class="form-control input-sm" type="text" id="numeroprograma" name="numeroprograma" pattern="^[0-9]{1,}$" data-pattern-error="Solo 4 o 5 numeros" data-required-error="Debe llenar este campo" readonly/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <br />
                <h4>Servicio o apoyo solicitado</h4>
                <div class="row">
                    <div class="form-group has-feedback col-md-12">
                        <label for="apoyosolicitado" class="control-label">Apoyo solicitado:<small> *</small></label>
                        <input type="text" class="form-control input-sm" id="apoyosolicitado" name="apoyosolicitado" value="<?= $rowestudio['apoyosolicitado'] ?>" maxlength="255" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <br />
                <h4>Composición familiar</h4>
                <div class="row">
                    <table class="table table-sm table-dark table-responsive">
                        <thead>
                            <tr>
                                <th>Numero</th>
                                <th>Nombre</th>
                                <th>Fecha Nac.</th>
                                <th>CURP</th>
                                <th>Edad</th>
                                <th>Sexo</th>
                                <th>Estado civil</th>
                                <th>Parentesco</th>
                                <th>Escolaridad</th>
                                <th>Ocupaci&oacute;n</th>
                                <th>Tipo de ingreso</th>
                                <th>Ingreso</th>
                                <th>Borrar?</th>
                            </tr>
                        </thead>
                        <tbody id="tablafamiliar">
                        </tbody>
                    </table>
                </div>
                <div class="row text-right">
                    <button type='button' class='btn btn-default' id='agregarmiembro'>Agregar  <span class='glyphicon glyphicon-plus'></button>
                </div>
                <br />
                <h4>Vivienda</h4>
                <div class="row">
                    <div class="form-group has-feedback col-md-6">
                        <label for="condicionvivienda" class="control-label">Condición:<small> *</small></label>
                        <select class="form-control input-sm" name="condicionvivienda" id="condicionvivienda" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelectData($vivienda, $rowestudio['condicionvivienda']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-6">
                        <label for="tipovivienda" class="control-label">Tipo de vivienda:<small> *</small></label>
                        <select class="form-control input-sm" name="tipovivienda" id="tipovivienda" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelectData($tipo_vivienda, $rowestudio['tipovivienda']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row" id="rowotros" style="display: none">
                    <div class="form-group has-feedback col-md-6" id="divotrocond">
                        <label for="otrocondicion" class="control-label">¿Por quien? / Otra condición:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="otrocondicion" name="otrocondicion" value="<?= $rowestudio['otrocondicion'] ?>" maxlength="100" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento) y numeros, coma y punto" data-required-error="Debe llenar este campo"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-6" id="divotrotipoviv">
                        <label for="otrotipovivienda" class="control-label">Otro tipo de vivienda:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="otrotipovivienda" name="otrotipovivienda" value="<?= $rowestudio['otrotipovivienda'] ?>" maxlength="100" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento) y numeros, coma y punto" data-required-error="Debe llenar este campo"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <p><label class="control-label col-md-12">Servicios:</label></p>
                    <div class="form-group has-feedback col-md-4">
                        <label for="tipoagua" class="control-label">Agua:<small> *</small></label>
                        <select class="form-control input-sm" name="tipoagua" id="tipoagua" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelectData($agua, $rowestudio['tipoagua']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="tipodesechos" class="control-label">Desechos:<small> *</small></label>
                        <select class="form-control input-sm" name="tipodesechos" id="tipodesechos" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelectData($desechos, $rowestudio['tipodesechos']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="tipoelectricidad" class="control-label">Electricidad:<small> *</small></label>
                        <select class="form-control input-sm" name="tipoelectricidad" id="tipoelectricidad" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelectData($electricidad, $rowestudio['tipoelectricidad']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <p><label class="control-label col-md-12">Distribución</label></p>
                    <div class="form-group has-feedback col-md-2">
                        <label for="distcocina" class="control-label">Cocina:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="distcocina" name="distcocina" value="<?= $rowestudio['distcocina'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-2">
                        <label for="distbano" class="control-label">Baño:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="distbano" name="distbano" value="<?= $rowestudio['distbano'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-2">
                        <label for="distdormitorio" class="control-label">Dormitorio:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="distdormitorio" name="distdormitorio" value="<?= $rowestudio['distdormitorio'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-2">
                        <label for="distsala" class="control-label">Sala:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="distsala" name="distsala" value="<?= $rowestudio['distsala'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-2">
                        <label for="distcomedor" class="control-label">Comedor:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="distcomedor" name="distcomedor" value="<?= $rowestudio['distcomedor'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-2">
                        <label for="otrodistribucion" class="control-label">Otro:</label>
                        <input class="form-control input-sm" type="text" id="otrodistribucion" name="otrodistribucion" value="<?= $rowestudio['otrodistribucion'] ?>" maxlength="100" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento) y numeros, coma y punto" data-required-error="Debe llenar este campo"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <p><label class="control-label col-md-12">Caracteristicas</label></p>
                    <div class="form-group has-feedback col-md-4">
                        <label for="tipopiso" class="control-label">Piso:<small> *</small></label>
                        <select class="form-control input-sm" name="tipopiso" id="tipopiso" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelectData($piso, $rowestudio['tipopiso']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="tipomuro" class="control-label">Muro:<small> *</small></label>
                        <select class="form-control input-sm" name="tipomuro" id="tipomuro" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelectData($muro, $rowestudio['tipomuro']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="tipotecho" class="control-label">Techo:<small> *</small></label>
                        <select class="form-control input-sm" name="tipotecho" id="tipotecho" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelectData($techo, $rowestudio['tipotecho']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="zonavivienda" class="control-label">Zona:<small> *</small></label>
                        <select class="form-control input-sm" name="zonavivienda" id="zonavivienda" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelectData($zona, $rowestudio['zonavivienda']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="menajevivienda" class="control-label">Menaje de casa:<small> *</small></label>
                        <select class="form-control input-sm" name="menajevivienda" id="menajevivienda" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelectData($menaje, $rowestudio['menajevivienda']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="limporg" class="control-label">Limpieza y organización:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="limporg" name="limporg" value="<?= $rowestudio['limporg'] ?>" maxlength="255" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento) y numeros, coma y punto" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <br />
                <h4>Patrimonio</h4>
                <div class="row">
                    <div class="col-md-4">
                        <p><label class="control-label col-md-12 text-center">Inmueble</label></p>
                        <div class="form-group has-feedback col-md-6">
                            <label for="tipoinmueble" class="control-label">Tipo de inmueble:</label>
                            <select class="form-control input-sm" name="tipoinmueble" id="tipoinmueble">
                                <?php
                                    if ($rowestudio['tipoinmueble'] == 0) {
                                        ?>
                                        <option value="-1" disabled selected>---Seleccione---</option>
                                        <?php
                                        echo fillSelect($tipo_inmueble);
                                    } else {
                                        echo fillSelectData($tipo_inmueble, $rowestudio['tipoinmueble']);
                                    } 
                                ?>
                            </select>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback col-md-6">
                            <label for="valorinmueble" class="control-label">Valor de inmueble:</label>
                            <input class="form-control input-sm" type="text" id="valorinmueble" name="valorinmueble" value="<?= $rowestudio['valorinmueble'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo"/>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <p><label class="control-label col-md-12 text-center">Cuenta de ahorro e inversión</label></p>
                        <div class="form-group has-feedback col-md-6">
                            <label for="institucioncuenta" class="control-label">Institución:</label>
                            <input class="form-control input-sm" type="text" id="institucioncuenta" name="institucioncuenta" value="<?= $rowestudio['institucioncuenta'] ?>" maxlength="100" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento), coma y punto" data-required-error="Debe llenar este campo"/>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback col-md-6">
                            <label for="valorcuenta" class="control-label">Valor de la cuenta:</label>
                            <input class="form-control input-sm" type="text" id="valorcuenta" name="valorcuenta" value="<?= $rowestudio['valorcuenta'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo"/>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <p><label class="control-label col-md-12 text-center">Vehiculo</label></p>
                        <div class="form-group has-feedback col-md-6">
                            <label for="marcavehiculo" class="control-label">Marca:</label>
                            <input class="form-control input-sm" type="text" id="marcavehiculo" name="marcavehiculo" value="<?= $rowestudio['marcavehiculo'] ?>" maxlength="100" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento), coma y punto" data-required-error="Debe llenar este campo"/>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback col-md-6">
                            <label for="modelovehiculo" class="control-label">Modelo:</label>
                            <input class="form-control input-sm" type="text" id="modelovehiculo" name="modelovehiculo" value="<?= $rowestudio['modelovehiculo'] ?>" maxlength="100" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento), coma y punto" data-required-error="Debe llenar este campo"/>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <p><label class="control-label col-md-12 text-center">Credito</label></p>
                        <div class="form-group has-feedback col-md-6">
                            <label for="institucioncredito" class="control-label">Institución:</label>
                            <input class="form-control input-sm" type="text" id="institucioncredito" name="institucioncredito" value="<?= $rowestudio['institucioncredito'] ?>" maxlength="100" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento), coma y punto" data-required-error="Debe llenar este campo"/>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback col-md-6">
                            <label for="saldocredito" class="control-label">Saldo:</label>
                            <input class="form-control input-sm" type="text" id="saldocredito" name="saldocredito" value="<?= $rowestudio['saldocredito'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo"/>
                            <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
                <br />
                <h4>Egresos mensuales</h4>
                <div class="row">
                    <div class="form-group has-feedback col-md-2">
                        <label for="egresoalimentos" class="control-label">Alimentos:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="egresoalimentos" name="egresoalimentos" value="<?= $rowestudio['egresoalimentos'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-2">
                        <label for="egresovivienda" class="control-label">Vivienda:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="egresovivienda" name="egresovivienda" value="<?= $rowestudio['egresovivienda'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-2">
                        <label for="egresoservicios" class="control-label">Servicios:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="egresoservicios" name="egresoservicios" value="<?= $rowestudio['egresoservicios'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-2">
                        <label for="egresotransporte" class="control-label">Transporte:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="egresotransporte" name="egresotransporte" value="<?= $rowestudio['egresotransporte'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-2">
                        <label for="egresoeducacion" class="control-label">Educación:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="egresoeducacion" name="egresoeducacion" value="<?= $rowestudio['egresoeducacion'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-2">
                        <label for="egresosalud" class="control-label">Salud:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="egresosalud" name="egresosalud" value="<?= $rowestudio['egresosalud'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-2">
                        <label for="egresovestido" class="control-label">Vestido:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="egresovestido" name="egresovestido" value="<?= $rowestudio['egresovestido'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-2">
                        <label for="egresorecreacion" class="control-label">Recreación:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="egresorecreacion" name="egresorecreacion" value="<?= $rowestudio['egresorecreacion'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-2">
                        <label for="egresodeudas" class="control-label">Deudas:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="egresodeudas" name="egresodeudas" value="<?= $rowestudio['egresodeudas'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-2">
                        <label for="egresootros" class="control-label">Otros:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="egresootros" name="egresootros" value="<?= $rowestudio['egresootros'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="egresototal" class="control-label">Total:</label>
                        <input class="form-control input-sm" type="text" id="egresototal" name="egresototal" value="0" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" readonly/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <br />
                <h4>Ingresos mensuales</h4>
                <div class="row">
                    <div class="form-group has-feedback col-md-3">
                        <label for="ingresotitular" class="control-label">Ingreso del titular:</label>
                        <input class="form-control input-sm" type="text" id="ingresotitular" name="ingresotitular" value="<?php echo $rowestudio['ingresototal'] - $rowestudio['ingresofamiliar'] - $rowestudio['ingresootros'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-3">
                        <label for="ingresofamiliar" class="control-label">Ingreso familiar:</label>
                        <input class="form-control input-sm" type="text" id="ingresofamiliar" name="ingresofamiliar" value="<?= $rowestudio['ingresofamiliar'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" readonly/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-3">
                        <label for="ingresootros" class="control-label">Otros:<small> *</small></label>
                        <input class="form-control input-sm" type="text" id="ingresootros" name="ingresootros" value="<?= $rowestudio['ingresootros'] ?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-3">
                        <label for="ingresototal" class="control-label">Total:</label>
                        <input class="form-control input-sm" type="text" id="ingresototal" name="ingresototal" value="0" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required readonly/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="form-group has-feedback col-md-6">
                        <label for="diferenciatotal" class="control-label">Diferencia total:</label>
                        <input class="form-control input-sm" type="text" id="diferenciatotal" name="diferenciatotal" value="0" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required readonly/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <p id="prompercapita"></p>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-6">
                        <label for="obsbalance" class="control-label">Observaciones:</label>
                        <input class="form-control input-sm" type="text" id="obsbalance" name="obsbalance" value="<?= $rowestudio['obsbalance'] ?>" maxlength="255" pattern="^[A-Za-z0-9ñÑ$ ,.]{1,}$" data-pattern-error="Solo letras (sin acento) y numeros, coma y punto" data-required-error="Debe llenar este campo"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <br />
                <h4>Alimentación</h4>
                <div class="row">
                    <div class="form-group has-feedback col-md-3">
                        <label for="frutas" class="control-label">Frutas y Verduras:<small> *</small></label>
                        <select class="form-control input-sm" name="frutas" id="frutas" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelectData($periodo_alimentos, $rowestudio['frutas']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-3">
                        <label for="cereales" class="control-label">Cereales y tuberculos:<small> *</small></label>
                        <select class="form-control input-sm" name="cereales" id="cereales" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelectData($periodo_alimentos, $rowestudio['cereales']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-3">
                        <label for="leguminosas" class="control-label">Leguminosas:<small> *</small></label>
                        <select class="form-control input-sm" name="leguminosas" id="leguminosas" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelectData($periodo_alimentos, $rowestudio['leguminosas']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-3">
                        <label for="animal" class="control-label">Alimentos de origen animal:<small> *</small></label>
                        <select class="form-control input-sm" name="animal" id="animal" required>
                            <option value="-1" disabled selected>---Seleccione---</option>
                            <?php echo fillSelectData($periodo_alimentos, $rowestudio['animal']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 30px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-12">
                        <label for="obsalim" class="control-label">Observaciones:</label>
                        <input type="text" class="form-control input-sm" id="obsalim" name="obsalim" value="<?= $rowestudio['obsalim'] ?>" maxlength="255" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <br />
                <h4>Apoyos y servicios otorgados</h4>
                <div class="row">
                    <table class="table table-sm table-dark table-responsive">
                        <thead>
                            <tr>
                                <th>Numero</th>
                                <th>Fecha</th>
                                <th>Institución</th>
                                <th>Apoyo y/o servicio</th>
                                <th>Periodo</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody id="tablaapoyo">
                        </tbody>
                    </table>
                </div>
                <div class="row text-right">
                    <button type='button' class='btn btn-default' id='agregarapoyo'>Agregar  <span class='glyphicon glyphicon-plus'></button>
                </div>
                <br />
                <h4>Salud</h4>
                <div class="row">
                    <div class="form-group has-feedback col-md-3">
                        <label for="salud" class="control-label">Servicios de salud:</label>
                        <input class="form-control input-sm" type="text" id="salud" name="Salud" value="<?= $rowpersona['servicios_medicos'] ?>" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento), coma y punto" data-required-error="Debe llenar este campo" disabled/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-9">
                        <label for="obssalud" class="control-label">Observaciones:</label>
                        <input class="form-control input-sm" type="text" id="obssalud" name="obssalud" value="<?= $rowestudio['obssalud'] ?>" maxlength="255" pattern="^[A-Za-z0-9ñÑ ,.]{1,}$" data-pattern-error="Solo letras (sin acento), coma y punto" data-required-error="Debe llenar este campo"/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-fedback col-md-12">
                        <label for="enfermedades" class="control-label">Enfermedades cronicas y/o discapacidades de la familia<small> *</small></label>
                        <textarea class="form-control input-sm" form="formregistroestudio" rows="2" name="enfermedades" id="enfermedades" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="255" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required><?= $rowestudio['enfermedades'] ?></textarea>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <br />
                <h4>Diagnostico sociofamiliar<small> *</small></h4>
                <div class="row">
                    <div class="form-group has-fedback col-md-12">
                        <textarea class="form-control input-sm" form="formregistroestudio" rows="8" name="diagsfamiliar" id="diagsfamiliar" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="7000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required><?= $rowestudio['diagsfamiliar'] ?></>?=></textarea>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <br />
                <h4>Conclusión</h4>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="probvul">Problematica y/o vulnerabilidad:<small> *</small></label>
                        <select class="form-control input-sm" name="probvul" id="probvul" required>
                            <option value="-1" disabled selected>---Seleccione problematica y/o vulnerabilidad---</option>
                            <?php echo fillSelectData($problematicas, $rowestudio['probvul']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="probvul2"> </label>
                        <select class="form-control input-sm" name="probvul2" id="probvul2">
                            <option value="-1" disabled selected>---Seleccione problematica y/o vulnerabilidad---</option>
                            <?php echo fillSelectData($problematicas, $rowestudio['probvul2']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="probvul3"> </label>
                        <select class="form-control input-sm" name="probvul3" id="probvul3">
                            <option value="-1" disabled selected>---Seleccione problematica y/o vulnerabilidad---</option>
                            <?php echo fillSelectData($problematicas, $rowestudio['probvul3']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="detonante">Detonante:<small> *</small></label>
                        <select class="form-control input-sm" name="detonante" id="detonante" required>
                            <option value="-1" disabled selected>---Seleccione Detonante---</option>
                            <?php echo fillSelectData($detonantes, $rowestudio['detonante']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="detonante2"> </label>
                        <select class="form-control input-sm" name="detonante2" id="detonante2">
                            <option value="-1" disabled selected>---Seleccione Detonante---</option>
                            <?php echo fillSelectData($detonantes, $rowestudio['detonante2']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="detonante3"> </label>
                        <select class="form-control input-sm" name="detonante3" id="detonante3">
                            <option value="-1" disabled selected>---Seleccione Detonante---</option>
                            <?php echo fillSelectData($detonantes, $rowestudio['detonante3']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group has-feedback col-md-4">
                        <label for="diagnostico">Diagnostico:<small> *</small></label>
                        <select class="form-control input-sm" name="diagnostico" id="diagnostico" required>
                            <option value="-1" disabled selected>---Seleccione Diagnostico---</option>
                            <?php echo fillSelectData($diagnostico, $rowestudio['diagnostico']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="diagnostico2"> </label>
                        <select class="form-control input-sm" name="diagnostico2" id="diagnostico2">
                            <option value="-1" disabled selected>---Seleccione Diagnostico---</option>
                            <?php echo fillSelectData($diagnostico, $rowestudio['diagnostico2']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group has-feedback col-md-4">
                        <label for="diagnostico3"> </label>
                        <select class="form-control input-sm" name="diagnostico3" id="diagnostico3">
                            <option value="-1" disabled selected>---Seleccione Diagnostico---</option>
                            <?php echo fillSelectData($diagnostico, $rowestudio['diagnostico3']); ?>
                        </select>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <br />
                <h4>Plan de intervención<small> *</small></h4>
                <div class="row">
                    <div class="form-group has-fedback col-md-12">
                        <textarea class="form-control input-sm" form="formregistroestudio" rows="8" name="planinter" id="planinter" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required><?= $rowestudio['planinter'] ?></textarea>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <br />
                <h4>Evaluación<small> *</small></h4>
                <div class="row">
                    <div class="form-group has-fedback col-md-12">
                        <textarea class="form-control input-sm" form="formregistroestudio" rows="8" name="eval" id="eval" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="1500" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required><?= $rowestudio['eval'] ?></textarea>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <br />
                <h4>Notas de seguimiento y/o evolución</h4>
                <div class="row">
                    <table class="table table-sm table-dark table-responsive">
                        <thead>
                            <tr>
                                <th>Numero</th>
                                <th>Fecha</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody id="tablaavance">
                        </tbody>
                    </table>
                </div>
                <div class="row text-right">
                    <button type='button' class='btn btn-default' id='agregaravance'>Agregar  <span class='glyphicon glyphicon-plus'></button>
                </div>
                <div class="row">
                    <div class="form-group text-center col-md-12">
                        <button type="submit" class="btn btn-primary" id="btnsubmit">Registrar</button>
                    </div>
                </div>
                <br />
                <br />
            </form>
        </div>