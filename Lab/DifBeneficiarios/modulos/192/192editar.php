<?php
    require_once ("../../menupadron.php");
    
    $familiares = array();
    $sesiones = array();
    $query = "SELECT * FROM $vw_tpsicologica WHERE idservicio = '" . $_POST['idservicio'] . "'";
    
    if ($result = mysql_query($query, $con)) {
        $rowtpsicologica = mysql_fetch_assoc($result);
    } else {
        echo "Error en el query: " . $query . "</br>Error:" . mysql_error();
    }
    
    $query = "SELECT * FROM $comp_familiar WHERE idtspicologica = '" . $_POST['idservicio'] . "'";
    if ($result = mysql_query($query, $con)) {
        while ($row = mysql_fetch_assoc($result)) {
            $familiares[] = $row;
        }
    } else {
        echo "Error en el query: " . $query . "</br>Error:" . mysql_error();
    }
    
    $query = "SELECT * FROM $vw_tpsicologica_sesiones WHERE idtpsicologica = '" . $_POST['idservicio'] . "'";
    if ($result = mysql_query($query, $con)) {
        while ($row = mysql_fetch_assoc($result)) {
            $sesiones[] = $row;
        }
    } else {
        echo "Error en el query: " . $query . "</br>Error:" . mysql_error();
    }
    
    if (!in_array($_SESSION['padron_admin_id'], $ver_tpsicologicas) && $_SESSION['padron_admin_id'] !=  $rowtpsicologica['idpsicologo']) {
        echo "No tienes permisos para ver esta terapia.";
        die;
    }
?>
<script type="text/javascript">
	var primeraentrevista = '<div class="row"> <div class="form-group has-fedback col-md-12"> <label for="motivoconsulta" class="control-label">Motivo de consulta:</label> <textarea class="form-control input-sm textolargo" rows="4" name="motivoconsulta" id="motivoconsulta" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div> <div class="row"> <div class="form-group has-fedback col-md-12"> <label for="solucionesintentadas" class="control-label">Soluciones intentadas:</label> <textarea class="form-control input-sm textolargo" rows="4" name="solucionesintentadas" id="solucionesintentadas" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div> <div class="row"> <div class="form-group has-feedback col-md-12"> <label for="genograma" class="control-label">Genograma:</label> <input type="file" class="form-control-file" name="genograma[]" id="fileinput" multiple/> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div> <div class="row"><div class="form-group has-fedback col-md-4"> <label for="fechasesion" class="control-label">Fecha de Sesión:</label> <input class="form-control input-sm caps" name="fecha" id="fecha" type="date" max="<?= date("Y-m-d"); ?>" required> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div>  <div class="row"> <div class="form-group has-fedback col-md-12"> <label for="hipotesisrelacional" class="control-label">Hipotesis inicial:</label> <textarea class="form-control input-sm textolargo" rows="4" name="hipotesisrelacional" id="hipotesisrelacional" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div> <div class="row"> <div class="form-group has-fedback col-md-12"> <label for="objetivosterapeuticos" class="control-label">Objetivos terapeuticos:</label> <textarea class="form-control input-sm textolargo" rows="4" name="objetivosterapeuticos" id="objetivosterapeuticos" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div> <div class="row"> <div class="form-group has-fedback col-md-12"> <label for="observaciones" class="control-label">Observaciones:</label> <textarea class="form-control input-sm textolargo" rows="4" name="observaciones" id="observaciones" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div>';
	var primeraentrevistaconimg = '<div class="row"> <div class="form-group has-fedback col-md-12"> <label for="motivoconsulta" class="control-label">Motivo de consulta:</label> <textarea class="form-control input-sm textolargo" rows="4" name="motivoconsulta" id="motivoconsulta" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div> <div class="row"> <div class="form-group has-fedback col-md-12"> <label for="solucionesintentadas" class="control-label">Soluciones intentadas:</label> <textarea class="form-control input-sm textolargo" rows="4" name="solucionesintentadas" id="solucionesintentadas" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div> <div class="row"> <div class="form-group has-feedback col-md-12"> <label for="genograma" class="control-label">Genograma:</label> <input type="file" class="form-control-file" name="genograma[]" id="fileinput" multiple/> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div> <div class="row text-center" id="imgenograma"></div></br> <div class="row"> <div class="form-group has-fedback col-md-12"> <label for="hipotesisrelacional" class="control-label">Hipotesis inicial:</label> <textarea class="form-control input-sm textolargo" rows="4" name="hipotesisrelacional" id="hipotesisrelacional" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div> <div class="row"> <div class="form-group has-fedback col-md-12"> <label for="objetivosterapeuticos" class="control-label">Objetivos terapeuticos:</label> <textarea class="form-control input-sm textolargo" rows="4" name="objetivosterapeuticos" id="objetivosterapeuticos" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div> <div class="row"> <div class="form-group has-fedback col-md-12"> <label for="observaciones" class="control-label">Observaciones:</label> <textarea class="form-control input-sm textolargo" rows="4" name="observaciones" id="observaciones" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div>';
	var sesionregular = '<div class="row"> <div class="form-group has-fedback col-md-12"> <label for="avances" class="control-label">Avances:</label> <textarea class="form-control input-sm textolargo" rows="4" name="avances" id="avances" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div> <div class="row"> <div class="form-group has-fedback col-md-12"> <label for="dificultades" class="control-label">Dificultades:</label> <textarea class="form-control input-sm textolargo" rows="4" name="dificultades" id="dificultades" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div> <div class="row"> <div class="form-group has-fedback col-md-12"> <label for="notassesion" class="control-label">Notas de sesión:</label> <textarea class="form-control input-sm textolargo" rows="4" name="notassesion" id="notassesion" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div> <div class="row"> <div class="form-group has-fedback col-md-12"> <label for="tareas" class="control-label">Tareas:</label> <textarea class="form-control input-sm textolargo" rows="4" name="tareas" id="tareas" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div><div class="row"><div class="form-group has-fedback col-md-4"> <label for="fechasesion" class="control-label">Fecha de Sesión:</label> <input class="form-control input-sm" name="fecha" id="fecha" type="date" max="<?= date("Y-m-d"); ?>" required> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div>';
	var inasistencia = '<div class="row"> <div class="form-group has-fedback col-md-12"> <label for="justinasistencia" class="control-label">Justificación:</label> <textarea class="form-control input-sm textolargo" rows="4" name="justinasistencia" id="justinasistencia" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div><div class="row"><div class="form-group has-fedback col-md-4"> <label for="fechasesion" class="control-label">Fecha de Inasitencia:</label> <input class="form-control input-sm caps" name="fecha" id="fecha" type="date" max="<?= date("Y-m-d"); ?>" required> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div>';
	var conclusion = '<div class="row"> <div class="form-group has-fedback col-md-12"> <label for="evfinal" class="control-label">Evaluación final:</label> <textarea class="form-control input-sm textolargo" rows="4" name="evfinal" id="evfinal" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div> <div class="row"> <div class="form-group has-fedback col-md-12"> <label for="motivoconclusion" class="control-label">Motivo de conclusión:</label> <textarea class="form-control input-sm textolargo" rows="4" name="motivoconclusion" id="motivoconclusion" pattern="^[A-Za-z0-9À-ÖØ-öø-ÿ ,.]{1,}$" maxlength="2000" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo" required></textarea> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div><div class="row"><div class="form-group has-fedback col-md-4"> <label for="fechasesion" class="control-label">Fecha de Conclución:</label> <input class="form-control input-sm caps" name="fecha" id="fecha" type="date" max="<?= date("Y-m-d"); ?>" required> <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span> <div class="help-block with-errors"></div> </div> </div>';
    var idserv = <?= $_POST['idservicio'] ?>;
    
	$("body").on("change keyup paste selectionchange propertychange", ".textolargo", function() {
		var maxlength = $(this).attr('maxlength');
		var newlines = ($(this).val().match(/\n/g) || []).length;

		if ($(this).val().length + newlines > maxlength) {
			alert("Ha superado el numero de caracteres permitidos (2000)");
			$(this).val($(this).val().substring(0, maxlength - newlines));
		}
	});
	
	$("body").on("click", "#botonreabrir", function(){
	    $.post("../ajax/ajax_reabrirterapia.php", {"idterapia": <?= $rowtpsicologica['id'] ?>}, function(respuesta){
            if (respuesta.exito) {
                alert (respuesta.mensaje);
                $("#divstatus").empty();
                $("#divstatus").html('<h3>Estatus: Abierto');                
            }
        });
	});
	
	$("body").on("click", "#btneditarprimses", function(){
	    $.post("../ajax/ajax_getsesion.php", {"idsesion" : $(this).val()}, function(respuesta){
	        console.log(respuesta.sesion[0]);
	        
	        $("#tiposesion").parent().hide();
	        $("#modalbody").empty();
            $("#modalbody").html(primeraentrevistaconimg);
            $("#modalagregarsesion").modal();
            $("#motivoconsulta").val(respuesta.sesion[0]['motivoconsulta']);
            $("#solucionesintentadas").val(respuesta.sesion[0]['solucionesintentadas']);
            $("#hipotesisrelacional").val(respuesta.sesion[0]['hipotesisrel']);
            $("#objetivosterapeuticos").val(respuesta.sesion[0]['objterapeuticos']);
            $("#observaciones").val(respuesta.sesion[0]['observaciones']);
            $("#imgenograma").html("<img src='" + respuesta.sesion[0].genograma + "' width='500'/>");
            $("#imgenograma").append("<input id='idsesionenv' type='hidden' value='" + respuesta.sesion[0].id + "'  />");
	    });
	})
	
	$("body").on("click", "#btnversesion", function(){
	    $.post("../ajax/ajax_getsesion.php", {"idsesion" : $(this).val()}, function(respuesta){
            console.log(respuesta.sesion[0]);
            
            $("#tiposesion").parent().hide();
            $("#modalbody").empty();
            $("#btnagregar").hide();
            $("#modalagregarsesion").modal();
            
            if (respuesta.sesion[0].tiposesion == 2) {
                $("#modalbody").html(sesionregular);
                $("#avances").val(respuesta.sesion[0].avances);
                $("#avances").prop("readonly", true);
                $("#dificultades").val(respuesta.sesion[0].dificultades);
                $("#dificultades").prop("readonly", true);
                $("#notassesion").val(respuesta.sesion[0].notasesion);
                $("#notassesion").prop("readonly", true);
                $("#tareas").val(respuesta.sesion[0].tareas);
                $("#tareas").prop("readonly", true);
            } else if (respuesta.sesion[0].tiposesion == 3) {
                $("#modalbody").html(inasistencia);
                $("#justinasistencia").val(respuesta.sesion[0].just);
                $("#justinasistencia").prop("readonly", true);
            } else if (respuesta.sesion[0].tiposesion == 4) {
                $("#modalbody").html(conclusion);
                $("#evfinal").val(respuesta.sesion[0].evfinal);
                $("#evfinal").prop("readonly", true);
                $("#motivoconclusion").val(respuesta.sesion[0].motivoconclusion);
                $("#motivoconclusion").prop("readonly", true);
            }
            
            //$("#modalbody").html(primeraentrevistaconimg);
        });
    })
	// 
	
	function reloadTable(sesiones){
	    var output = "";
	    var i = 0;
	    var insert;
	    console.log(sesiones);
	    for (i in sesiones) {
	        if (sesiones[i].tiposesion == 1) {
	            insert = "<button type='button' class='btn btn-primary' id='btneditarprimses' value='" + sesiones[i].id + "'>Editar sesión</button>"
	        } else {
	            insert = "<button type='button' class='btn btn-primary' id='btnversesion' value='" + sesiones[i].id + "'>Ver sesión</button>";
	        }
	        output += "<tr> <td>" + (~~i + ~~1) + "</td> <td>" + sesiones[i].tiposesionletra + "</td> <td>" + sesiones[i].nombrepsicologo + "</td> <td>" + sesiones[i].fechahora.slice(11, 19) + "</td> <td>" + sesiones[i].fechahora.slice(0, 10) + "</td><td>" + insert + "</td></tr>";
	    }
	    
	    console.log(output);
	    
	    $("#bodysesiones").empty();
	    $("#bodysesiones").html(output);
	}

	$(document).ready(function() {
		$("#btnagregarsesion").on("click", function(e) {
			//alert("Hola");
			$("#modalagregarsesion").modal();
		});

		$("#tiposesion").on("change", function(e) {
			$("#modalbody").empty();
			if ($(this).val() == 1) {
				$("#modalbody").html(primeraentrevista);
			} else if ($(this).val() == 2) {
				$("#modalbody").html(sesionregular);
			} else if ($(this).val() == 3) {
			    $("#modalbody").html(inasistencia);
			} else if ($(this).val() == 4) {
				$("#modalbody").html(conclusion);
			} else {

			}
		});

		$("#btnagregar").on("click", function(e) {
			e.preventDefault();
			var formData = new FormData();
			formData.append("idterapia", idserv);
			formData.append("idtpsicologica", <?= $rowtpsicologica['id'] ?>);
			
			if (<?= $rowtpsicologica['cerrada'] ?> == 0) {
			    if ($("#tiposesion").val() == 1) {
                    
                    var fileInput = document.getElementById('fileinput');
                    var files = fileInput.files;
                    
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        
                        formData.append('genograma[]', file, file.name);
                    }
    
                    formData.append("motivoconsulta", $.trim($("#motivoconsulta").val()));
                    formData.append("solucionesintentadas", $.trim($("#solucionesintentadas").val()));
                    formData.append("fecha", $.trim($("#fecha").val()));
                    formData.append("hipotesisrelacional", $.trim($("#hipotesisrelacional").val()));
                    formData.append("objetivosterapeuticos", $.trim($("#objetivosterapeuticos").val()));
                    formData.append("observaciones", $.trim($("#observaciones").val()));
                    formData.append("tiposesion", 1);
    
                    $.ajax({
                        url : "../ajax/ajax_agregarsesion.php",
                        data : formData,
                        cache : false,
                        processData : false,
                        contentType : false,
                        type : 'POST',
                        success : function(respuesta) {
                            if (respuesta.exito) {
                                alert(respuesta.mensaje);
                                reloadTable(respuesta.sesiones);
                            } else {
                                alert(respuesta.mensaje);
                            }
                        }
                    });
                } else if ($("#tiposesion").val() == 2) {
                    
                    formData.append("avances", $.trim($("#avances").val()));
                    formData.append("dificultades", $.trim($("#dificultades").val()));
                    formData.append("notassesion", $.trim($("#notassesion").val()));
                    formData.append("tareas", $.trim($("#tareas").val()));
                    formData.append("fecha", $.trim($("#fecha").val()));
                    formData.append("tiposesion", 2);
                    
                    $.ajax({
                        url : "../ajax/ajax_agregarsesion.php",
                        data : formData,
                        cache : false,
                        processData : false,
                        contentType : false,
                        type : 'POST',
                        success : function(respuesta) {
                            if (respuesta.exito) {
                                alert(respuesta.mensaje);
                                reloadTable(respuesta.sesiones);
                            } else {
                                alert(respuesta.mensaje);
                            }
                        }
                    });
                } else if ($("#tiposesion").val() == 3) {
                    
                    formData.append("justinasistencia", $.trim($("#justinasistencia").val()));
                    formData.append("fecha", $.trim($("#fecha").val()));
                    formData.append("tiposesion", 3);
                    
                    $.ajax({
                        url : "../ajax/ajax_agregarsesion.php",
                        data : formData,
                        cache : false,
                        processData : false,
                        contentType : false,
                        type : 'POST',
                        success : function(respuesta) {
                            if (respuesta.exito) {
                                alert(respuesta.mensaje);
                                reloadTable(respuesta.sesiones);
                            } else {
                                alert(respuesta.mensaje);
                            }
                        }
                    });
                } else if ($("#tiposesion").val() == 4) {
                    
                    formData.append("evfinal", $.trim($("#evfinal").val()));
                    formData.append("motivoconclusion", $.trim($("#motivoconclusion").val()));
                    formData.append("fecha", $.trim($("#fecha").val()));
                    formData.append("tiposesion", 4);
                    
                    $.ajax({
                        url : "../ajax/ajax_agregarsesion.php",
                        data : formData,
                        cache : false,
                        processData : false,
                        contentType : false,
                        type : 'POST',
                        success : function(respuesta) {
                            if (respuesta.exito) {
                                alert(respuesta.mensaje);
                                reloadTable(respuesta.sesiones);
                                $("#divstatus").empty();
                                $("#divstatus").html('<h3>Estatus: Cerrado</h3><button type="button" id="botonreabrir" class="btn btn-primary">Reabrir terapia</button>');
                            } else {
                                alert(respuesta.mensaje);
                            }
                        }
                    });
                } else {
                    var fileInput = document.getElementById('fileinput');
                    var files = fileInput.files;
                    
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        
                        formData.append('genograma[]', file, file.name);
                    }
    
                    formData.append("motivoconsulta", $.trim($("#motivoconsulta").val()));
                    formData.append("solucionesintentadas", $.trim($("#solucionesintentadas").val()));
                    formData.append("hipotesisrelacional", $.trim($("#hipotesisrelacional").val()));
                    formData.append("objetivosterapeuticos", $.trim($("#objetivosterapeuticos").val()));
                    formData.append("observaciones", $.trim($("#observaciones").val()));
                    formData.append("tiposesion", 5);
                    formData.append("idsesion", $("#idsesionenv").val());
    
                    $.ajax({
                        url : "../ajax/ajax_agregarsesion.php",
                        data : formData,
                        cache : false,
                        processData : false,
                        contentType : false,
                        type : 'POST',
                        success : function(respuesta) {
                            if (respuesta.exito) {
                                alert(respuesta.mensaje);
                                reloadTable(respuesta.sesiones);
                            } else {
                                alert(respuesta.mensaje);
                            }
                        }
                    });
                }
			} else {
			    alert ("Esta terapia esta actualmente cerrada, debe reabrirla para poder registrar sesiones nuevas.");
			}
		});

		$(".glyphicon").on("click", function() {
			var text = $(this).attr("class").split(" ");
			if (text[1] == "glyphicon-collapse-down") {
				$(this).attr("class", "glyphicon glyphicon-collapse-up");
			} else {
				$(this).attr("class", "glyphicon glyphicon-collapse-down");
			}
		});

		$("#modalagregarsesion").on("hidden.bs.modal", function(e) {
			$("#modalbody").empty();
			$("#tiposesion").parent().show();
			$("#tiposesion").val(-1);
			$("#btnagregar").show();
		});
	});
        </script>
        <div class="modal fade" id="modalagregarsesion" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">AGREGAR SESIÓN</h4>
                        <br />
                        <div class="row">
                            <div class="form-group has-feedback col-md-12">
                                <label for="tiposesion">Tipo de sesión:</label>
                                <select class="form-control input-sm" name="tiposesion" id="tiposesion">
                                    <option value="-1" disabled selected>---Seleccione el tipo de sesión---</option>
                                    <?php echo fillSelect($tipo_sesion); ?>
                                </select>
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
            <h2 class="text-center">Modificación o seguimiento terapia psicologica de</h2>
            <h2 class="text-center"><?=$rowtpsicologica['nombre'] ?></h2>
        </div>
        <br />
        <div>
            <div id="divstatus">
                <h3>Estatus: <?php echo ($rowtpsicologica['cerrada'] == 0) ? "Abierto" : "Cerrado" ; ?></h3>
                <?php if ($rowtpsicologica['cerrada'] == 1) {
                    echo '<button type="button" id="botonreabrir" class="btn btn-primary">Reabrir terapia</button>';
                } ?>
            </div>
            <br /><br />
            <h4>Información general de la terapia <a href="#datostpsicologica" data-toggle="collapse"><span class="glyphicon glyphicon-collapse-up"></span></a></h4>
            <div class="seccion col-md-12 collapse in" id="datostpsicologica">
                <div class="row">
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <p><b>Expediente:</b><br /><?=$rowtpsicologica['expediente'] ?></p>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <p><b>Tipo de terapia:</b><br /><?=$rowtpsicologica['tipoterapia'] ?></p>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <p><b>Tipo de sistema familiar:</b><br /><?=$rowtpsicologica['tiposistemafamiliar'] ?></p>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                        <p><b>Etapa del ciclo vital familiar:</b><br /><?=$rowtpsicologica['ciclofamiliar'] ?></p>
                    </div>
                </div>
            </div>
            <h4>Composición familiar <a href="#datoscompfamiliar" data-toggle="collapse"><span class="glyphicon glyphicon-collapse-up"></span></a></h4>
            <div class="col-md-12 collapse in" id="datoscompfamiliar">
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
                        <tbody>
                            <?php
                            $output = "";
                            foreach ($familiares as $key => $value) {
                                $output .= "<tr> <td>" . ($key + 1) . "</td> <td>" . $value['nombre'] . "</td> <td>" . $value['fechanacimiento'] . "</td> <td>" . $value['curp'] . "</td> <td>" . $value['edad'] . "</td> <td>" . $value['estadocivil'] . "</td> <td>" . $value['parentesco'] . "</td> <td>" . $value['escolaridad'] . "</td> <td>" . $value['ocupacion'] . "</td> <td></td> </tr>";
                            }
                            echo $output;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-4">
                    <h4>Historial de sesiones <a href="#datossesiones" data-toggle="collapse"><span class="glyphicon glyphicon-collapse-up"></span></a></h4>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary" id="btnagregarsesion">Agregar sesión</button>
                </div>
            </div>  
            <div class="col-md-12 collapse in" id="datossesiones">
                <div class="row">
                    <table class="table table-sm table-dark table-responsive">
                        <thead>
                            <tr>
                                <th>Numero</th>
                                <th>Tipo de sesión</th>
                                <th>Psicologo</th>
                                <th>Hora de registro</th>
                                <th>Fecha de sesión</th>
                                <th>Detalles</th>
                            </tr>
                        </thead>
                        <tbody id="bodysesiones">
                            <?php
                            
                                $output = "";
                                foreach ($sesiones as $key => $value) {                                    
                                    $output .= "<tr> <td>" . ($key + 1) . "</td> <td>" . $value['tiposesionletra'] . "</td> <td>" . $value['nombrepsicologo'] . "</td> <td>" . strstr($value['fechahora'], " ") . "</td> <td>". substr($value['fechahora'], 0, 10) . "</td> <td>" .  (($value['tiposesion'] == 1) ? "<button type='button' class='btn btn-primary' id='btneditarprimses' value='" . $value['id'] . "'>Editar sesión</button>" : "<button type='button' class='btn btn-primary' id='btnversesion' value='" . $value['id'] . "'>Ver sesión</button>")  . "</td></tr>";
                                }
                                echo $output;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
