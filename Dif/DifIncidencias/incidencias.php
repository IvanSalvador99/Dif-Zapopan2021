<?php
    include_once 'menuincidencias.php';
    
    if ($_GET['action'] == 1) {
        alerta_bota("Incidencia registrada correctamente", 0);
    } elseif ($_GET['action'] == 2) {
        alerta_bota("Incidencia actualizada correctamente", 0);
    }
?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('.date').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    language: 'es'
                });
                
                var tabla = $("#tablaincidencias").DataTable({
                    "ordering": false,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                    },
                    "dom": "<'top'>fpltri<'bottom'p><'clear'>"
                });
                
                $("#rechazarbtn").on("click", function(){
                    $.post('ajax/ajax_rechazarincidencia.php',{"idincidencia": $("#idmodalr").text(), "razon": $("#descripcionrechazo").val()}, function(respuesta){
                        var result = respuesta.split("|");
                        if (result[0] == "Success") {
                            alert (result[1]);
                            window.location.href = window.location.href;
                        } else {
                            alert (result[1]);
                        }
                    });
                });
                
                $("#cancelarbtn").on("click", function(){
                    $.post('ajax/ajax_cancelarincidencia.php',{"idincidencia": $("#idmodalc").text(), "razon": $("#descripcioncancela").val()}, function(respuesta){
                        var result = respuesta.split("|");
                        if (result[0] == "Success") {
                            alert (result[1]);
                            window.location.href = window.location.href;
                        } else {
                            alert (result[1]);
                        }
                    });
                });
                
                $("#exportarbtn").on("click", function(){
                    
                    $("#exportarform").submit();
                });
                
                $("#btncapturaext").on("click", function(){
                    var sure = confirm("¿Permitir captura extemporanea de incidencias?");
                    if (sure) {
                        $.post('ajax/ajax_activarcapturaext.php', function(respuesta){
                            alert(respuesta.mensaje);
                        });
                    }
                });
                
                $("#btnjefesecre").on("click", function(){
                    alert ("Hola");
                });
                
                $(".table tr").on("dblclick", function(e){
                    if ($(this).attr("id") !== undefined) {
                        $.post('ajax/ajax_getincidencia.php',{"idincidencia": $(this).attr("id")}, function(respuesta){
                            if (respuesta.exito == true){
                                var output = "<b>Nombre:</b> " + respuesta.nombreusuario + "</br><b>Departamento:</b> " + respuesta.departamento + "</br><b>Numero de empleado:</b> " + respuesta.numeroempleado + "</br><b>Regimen:</b> " + respuesta.tipoempleado + "</br><b>Tipo:</b> " + respuesta.tipo + "</br><b>Concepto:</b> " + respuesta.concepto + "</br><b>Descripción:</b> " + respuesta.mensaje + "</br><b>Capturó:</b> " + respuesta.nombrecapturista + "</br><b>Autorizó:</b> " + respuesta.nombreautorizador;
                                var str = respuesta.historial.replace(/(?:\r\n|\r|\n)/g, '</br>');
                                $("#datosmodal").html(output);
                                $("#textodescripcion").html(str);
                                $("#modaldescripcion").modal();
                            } else {
                                alert (respuesta.mensaje);
                            }
                        });
                    }
                });
                
                $("#btntodas").on("click", function(){
                    tabla.search("").columns().search("").draw();
                });
                
                $("#btnnuevas").on("click", function(){
                   tabla.search("CREADA").draw();
                });
                
                $("#btnautorizadas").on("click", function(){
                    tabla.search("APROBADA POR JEFE DE DEPARTAMENTO O DIRECTOR").draw();
                });
                
                $("#btnfinalizadas").on("click", function(){
                    tabla.search("APROBADA POR NOMINAS").draw();
                });
                
                $("#btncanceladas").on("click", function(){
                    tabla.search("CANCELADA").draw();
                });
                
                $("#btnexportar").on("click", function(){
                    $("#exportarinicio").val("");
                    $("#exportarfin").val("");
                    $("input:checkbox").removeAttr("checked");
                    $("#modalexportar").modal();
                    /*var win = window.open("ajax/ajax_exportarcsv.php", '_blank');
                    win.focus();*/
                });
            });
            
            function aceptarclick(id, event){
                event.preventDefault();
                event.stopPropagation();
                var sure = confirm("¿Autorizar incidencia con ID: " + id + "?");
                if (sure) {
                    $.post('ajax/ajax_aceptarincidencia.php',{"idincidencia": id}, function(respuesta){
                        var result = respuesta.split("|");
                        if (result[0] == "Success") {
                            alert ("Exito - " + result[1]);
                            window.location.href = window.location.href;
                        } else {
                            alert ("Fallo - " + result[1]);
                        }
                    });
                }
            }
            
            function rechazarclick(id, event) {
                event.preventDefault();
                event.stopPropagation();
                $("#idmodalr").html(id);
                $("#modalrechazar").modal();
            }
            
            function editarclick(id, event) {
                event.preventDefault();
                event.stopPropagation();
                if($("#editar" + id).attr("disabled") != "disabled") {
                    $("<form>", {
                        "id": "formeditar",
                        "html": "<input type='hidden' name='id' value='" + id + "'/>",
                        "method": "post",
                        "action": "incidenciaeditar.php"
                    }).appendTo(document.body).submit();
                }   
            }
            
            function cancelarclick(id, event) {
                event.preventDefault();
                event.stopPropagation();
                $("#idmodalc").html(id);
                $("#modalcancelar").modal();
            }
            
            function imprimirclick(id, event) {
                event.preventDefault();
                event.stopPropagation();
                $("<form>", {
                        /*"target": "_blank",*/
                        "id": "formimprimir",
                        "html": "<input type='hidden' name='id' value='" + id + "'/>",
                        "method": "post",
                        "target": "_blank",
                        "action": "incidenciaprint.php"
                    }).appendTo(document.body).submit();
            }
        </script>
        <div class="modal fade" id="modaldescripcion" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">Datos adicionales:</h4>
                        <br />
                        <p id="datosmodal"></p>
                    </div>
                    <div class="modal-body">
                        <h4 class="modal-title text-center">Historial:</h4>
                        <br />
                        <p id="textodescripcion"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalrechazar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Rechazar Incidencia con ID: <span id="idmodalr"></span></h4>
                    </div>
                    <div class="modal-body">
                        <h4>Razon del rechazo:</h4>
                        <textarea id="descripcionrechazo" rows="5" style="width: 100%"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="rechazarbtn" data-dismiss="modal">Rechazar incidencia</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalcancelar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Cancelar Incidencia con ID: <span id="idmodalc"></span></h4>
                    </div>
                    <div class="modal-body">
                        <h4>Razon de la cancelación:</h4>
                        <textarea id="descripcioncancela" rows="5" style="width: 100%"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="cancelarbtn" data-dismiss="modal">Cancelar incidencia</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalexportar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Exportar</h4>
                    </div>
                    <div class="modal-body">
                        <h4>Fechas:</h4>
                        <form method="post" target="_blank" action="ajax/ajax_exportarcsv.php" id="exportarform" data-toggle="validator">
                            <div class="row">
                                <div class="form-group has-feedback col-md-6">
                                    <label for="exportarinicio" class="control-label">Fecha de inicio:</label>
                                    <div class="input-group date" data-provide="datepicker">
                                        <input type="text" class="form-control input-sm" name="exportarinicio" id="exportarinicio" data-required-error="Debe llenar este campo" required/>
                                        <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                    </div>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 50px"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group has-feedback col-md-6">
                                    <label for="exportarfin" class="control-label">Fecha de termino:</label>
                                    <div class="input-group date" data-provide="datepicker">
                                        <input type="text" class="form-control input-sm" name="exportarfin" id="exportarfin" data-required-error="Debe llenar este campo" required/>
                                        <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                    </div>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 50px"></span>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="form-group has-feedback col-md-12">
                                    <label class="checkbox-inline"><input type="checkbox" name="creadas" />Creadas</label>
                                    <label class="checkbox-inline"><input type="checkbox" name="aprobadas" />Aprobadas</label>
                                    <label class="checkbox-inline"><input type="checkbox" name="finalizadas" />Finalizadas</label>
                                    <label class="checkbox-inline"><input type="checkbox" name="canceladas" />Canceladas</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="exportarbtn" data-dismiss="modal">Exportar</button>
                    </div>
                </div>
            </div>
        </div>
        <br />
        <?php
        if ($_SESSION['padron_admin_permisos'] == 7 || $_SESSION['padron_admin_permisos'] == 1) {
            ?>
            <br />
            <div class="row text-center">
                <a href="incidenciaregistro.php" class="btn btn-primary">Nueva incidencia</a>
            </div>
            <br />
            <?php
        }
        ?>
        <div class="row">
            <div class="col-md-6">
                <label>Filtro:</label><br />
                <a class="btn btn-primary" id="btntodas">Todas</a>
                <a class="btn btn-primary" id="btnnuevas">Nuevas</a>
                <a class="btn btn-primary" id="btnautorizadas">Autorizadas</a>
                <a class="btn btn-primary" id="btnfinalizadas">Finalizadas</a>
                <a class="btn btn-primary" id="btncanceladas">Canceladas</a>
            </div>
            <?php
            if ($_SESSION['padron_admin_id'] == $admin_incidencias || $_SESSION['padron_admin_permisos'] == 1) {
                echo '<div class="col-md-6 text-center"><a class="btn btn-primary" id="btnexportar" style="margin-left: 5px; margin-right: 5px;">Exportar <span class="glyphicon glyphicon-export"></span></a><a class="btn btn-primary" id="btnjefesecre" style="margin-left: 5px; margin-right: 5px;">Asignar jefes y/o secretarias <span class="glyphicon glyphicon-copy"></span></a><a class="btn btn-primary" id="btncapturaext" style="margin-top: 5px">Activar captura extemporanea <span class="glyphicon glyphicon-calendar"></span></a></div>';
            }
            ?>
        </div>
        <br />
        <div class="row table-responsive">
            <table class="table table-striped table-condensed" id="tablaincidencias">
                <thead>
                    <th style="vertical-align: middle">Id</th>
                    <th style="vertical-align: middle">Fecha de captura</th>
                    <th style="vertical-align: middle">Usuario</th>
                    <th style="vertical-align: middle">Fecha de la incidencia</th>
                    <th style="vertical-align: middle">Tipo</th>
                    <th style="vertical-align: middle">Concepto</th>
                    <th style="vertical-align: middle">Horas</th>
                    <th style="vertical-align: middle">Estatus</th>
                    <th style="vertical-align: middle">Acciones</th>
                </thead>
                <tbody>
                    <?php
                        if ($_SESSION['padron_admin_permisos'] == 1 || $_SESSION['padron_admin_id'] == $admin_incidencias) {
                            $query = "SELECT * FROM $vw_incidencias WHERE fecha >= '" . date("Y-m-d", strtotime("-2 months")) . "'";
                            if ($result = mysql_query($query, $con)){
                                $output = "";
                                if (mysql_num_rows($result) > 0) {
                                    while ($row = mysql_fetch_array($result)) {
                                        $output .= "<tr id='" . $row['id'] . "'><td style='vertical-align: middle'>" . $row['id'] . "</td>";
                                        $output .= "<td style='vertical-align: middle'>". $row['fechacaptura'] . "</td>";
                                        $output .= "<td style='vertical-align: middle'>" . $row['nombre'] . " " . $row['apaterno'] . " " . $row['amaterno'] . "</td>";
                                        $output .= "<td style='vertical-align: middle'>" . $row['fecha'] . "</td>";
                                        $output .= "<td style='vertical-align: middle'>" . $row['tipo'] . "</td>";
                                        $output .= "<td style='vertical-align: middle'>" . $row['concepto'] . "</td>";
                                        if ($row['horas'] == 0) {
                                            $output .= "<td style='vertical-align: middle'>" . "NA" . "</td>";
                                        } else {
                                            $output .= "<td style='vertical-align: middle'>" . $row['horas'] . "</td>";
                                        }
                                        $output .= "<td style='vertical-align: middle'>". $row['estatus'] . "</td>";
                                        
                                        if ($_SESSION['padron_admin_permisos'] == 1) {
                                            $output .= "<td style='vertical-align: middle'><a onclick='aceptarclick(" . $row['id'] . ", event)' class='btn btn-success' style='width: 100%;'><span class='glyphicon glyphicon-ok'></span> Autorizar</a>
                                                        <a onclick='rechazarclick(" . $row['id'] . ", event)' class='btn btn-danger' style='width: 100%;'><span class='glyphicon glyphicon-remove'></span> Rechazar</a>
                                                        <a onclick='editarclick(" . $row['id'] . ", event)' id='editar" . $row['id'] . "' class='btn btn-warning' style='width: 100%;'><span class='glyphicon glyphicon-edit'></span> Editar</a>
                                                        <a onclick='cancelarclick(" . $row['id'] . ", event)' class='btn btn-primary' style='width: 100%;'><span class='glyphicon glyphicon-trash'></span> Cancelar</a>
                                                        <a onclick='imprimirclick(" . $row['id'] . ", event)' class='btn btn-default' style='width: 100%;'><span class='glyphicon glyphicon-print'></span> Imprimir</a></td>";
                                        } else {
                                            $output .= "<td style='vertical-align: middle'><a onclick='aceptarclick(" . $row['id'] . ", event)' class='btn btn-success' style='width: 100%;'><span class='glyphicon glyphicon-ok'></span> Autorizar</a>
                                                        <a onclick='rechazarclick(" . $row['id'] . ", event)' class='btn btn-danger' style='width: 100%;'><span class='glyphicon glyphicon-remove'></span> Rechazar</a>
                                                        <a onclick='cancelarclick(" . $row['id'] . ", event)' class='btn btn-primary' style='width: 100%;'><span class='glyphicon glyphicon-trash'></span> Cancelar</a>
                                                        <a onclick='imprimirclick(" . $row['id'] . ", event)' class='btn btn-default' style='width: 100%;'><span class='glyphicon glyphicon-print'></span> Imprimir</a></td>";
                                        }
                                    } 
                                    $output .= "</tr>";
                                    echo $output;
                                } else {
                                    $output .= "<td>No hay elementos</td>";
                                    echo $output;
                                }
                            } else {
                                echo (mysql_error());
                            }
                        } elseif ($_SESSION['padron_admin_permisos'] >= 2 && $_SESSION['padron_admin_permisos'] <= 4) {
                            $idjefe = $_SESSION['padron_admin_id'];
                            $query = "SELECT * from $incidencia_jefes WHERE idjefe = $idjefe";
                            if ($result = mysql_query($query, $con)) {
                                $row = mysql_fetch_assoc($result);
                                $validos = ($row['validos'] == "") ? "0" : $row['validos'];
                                //echo $validos . "\n";
                                $query = "SELECT * FROM $vw_incidencias WHERE (idusuario IN ($validos) OR idusuario = $idjefe) AND fecha >= '" . date("Y-m-d", strtotime("-2 months")) . "'";
                                //echo $query . "\n";
                                if ($result = mysql_query($query, $con)){
                                    $output = "";
                                    if (mysql_num_rows($result) > 0) {
                                        while ($row = mysql_fetch_array($result)) {
                                            $output .= "<tr id='" . $row['id'] . "'><td style='vertical-align: middle'>" . $row['id'] . "</td>";
                                            $output .= "<td style='vertical-align: middle'>". $row['fechacaptura'] . "</td>";
                                            $output .= "<td style='vertical-align: middle'>" . $row['nombre'] . " " . $row['apaterno'] . " " . $row['amaterno'] . "</td>";
                                            $output .= "<td style='vertical-align: middle'>" . $row['fecha'] . "</td>";
                                            $output .= "<td style='vertical-align: middle'>" . $row['tipo'] . "</td>";
                                            $output .= "<td style='vertical-align: middle'>" . $row['concepto'] . "</td>";
                                            if ($row['horas'] == 0) {
                                                $output .= "<td style='vertical-align: middle'>" . "NA" . "</td>";
                                            } else {
                                                $output .= "<td style='vertical-align: middle'>" . $row['horas'] . "</td>";
                                            }
                                            $output .= "<td style='vertical-align: middle'>". $row['estatus'] . "</td>";
                                            if ($row['idusuario'] != $_SESSION['padron_admin_id']) {
                                                if ($row['idestatus'] == 2 && $row['idconcepto'] == 10) {
                                                    $output .= "<td style='vertical-align: middle'><a onclick='aceptarclick(" . $row['id'] . ", event)' class='btn btn-success' style='width: 100%;'><span class='glyphicon glyphicon-ok'></span> Autorizar</a>
                                                                <a onclick='rechazarclick(" . $row['id'] . ", event)' class='btn btn-danger' style='width: 100%;'><span class='glyphicon glyphicon-remove'></span> Rechazar</a>
                                                                <a onclick='imprimirclick(" . $row['id'] . ", event)' class='btn btn-default' style='width: 100%;'><span class='glyphicon glyphicon-print'></span> Imprimir</a></td>";
                                                } else {
                                                    $output .= "<td style='vertical-align: middle'><a onclick='aceptarclick(" . $row['id'] . ", event)' class='btn btn-success' style='width: 100%;'><span class='glyphicon glyphicon-ok'></span> Autorizar</a>
                                                                <a onclick='rechazarclick(" . $row['id'] . ", event)' class='btn btn-danger' style='width: 100%;'><span class='glyphicon glyphicon-remove'></span> Rechazar</a></td>";
                                                }
                                            } else {
                                                $output .= "<td style='vertical-align: middle'></td>";
                                            }   
                                        }
                                        $output .= "</tr>";
                                        echo $output;
                                    } else {
                                        $output .= "<td>No hay elementos</td>";
                                        echo $output;
                                    }
                                } else {
                                    echo "Error en el query: " . $query . "\nError: " . mysql_error() . "\n";
                                }
                            } else {
                                echo "Error en el query: " . $query . "\nError: " . mysql_error() . "\n";
                            }   
                        } elseif ($_SESSION['padron_admin_permisos'] == 7) {
                            $idsecretaria = $_SESSION['padron_admin_id'];
                            $query = "SELECT * from $incidencia_secretarias WHERE idsecretaria = $idsecretaria";
                            if ($result = mysql_query($query, $con)) {
                                $row = mysql_fetch_assoc($result);
                                $validos = ($row['validos'] == "") ? "0" : $row['validos'];
                                //echo $validos . "\n";
                                $query = "SELECT * FROM $vw_incidencias WHERE (idusuario IN ($validos) OR idusuario = $idsecretaria) AND fecha >= '" . date("Y-m-d", strtotime("-2 months")) . "'";
                                //echo $query . "\n";
                                if ($result = mysql_query($query, $con)){
                                    $output = "";
                                    if (mysql_num_rows($result) > 0) {
                                        while ($row = mysql_fetch_array($result)) {
                                            $output .= "<tr id='" . $row['id'] . "'><td style='vertical-align: middle'>" . $row['id'] . "</td>";
                                            $output .= "<td style='vertical-align: middle'>". $row['fechacaptura'] . "</td>";
                                            $output .= "<td style='vertical-align: middle'>" . $row['nombre'] . " " . $row['apaterno'] . " " . $row['amaterno'] . "</td>";
                                            $output .= "<td style='vertical-align: middle'>" . $row['fecha'] . "</td>";
                                            $output .= "<td style='vertical-align: middle'>" . $row['tipo'] . "</td>";
                                            $output .= "<td style='vertical-align: middle'>" . $row['concepto'] . "</td>";
                                            if ($row['horas'] == 0) {
                                                $output .= "<td style='vertical-align: middle'>" . "NA" . "</td>";
                                            } else {
                                                $output .= "<td style='vertical-align: middle'>" . $row['horas'] . "</td>";
                                            }
                                            $output .= "<td style='vertical-align: middle'>". $row['estatus'] . "</td>";
                                            if ($row['idestatus'] == 1) {
                                                $output .= "<td style='vertical-align: middle'><a onclick='editarclick(" . $row['id'] . ", event)' id='editar" . $row['id'] . "' class='btn btn-primary' style='width: 100%;'><span class='glyphicon glyphicon-edit'></span> Editar</a>
                                                            <a onclick='cancelarclick(" . $row['id'] . ", event)' class='btn btn-primary' style='width: 100%;'><span class='glyphicon glyphicon-trash'></span> Cancelar</a></td>";
                                            } elseif ($row['idestatus'] == 2 && $row['idconcepto'] == 10) {
                                                $output .= "<td style='vertical-align: middle'><a onclick='imprimirclick(" . $row['id'] . ", event)' class='btn btn-default' style='width: 100%;'><span class='glyphicon glyphicon-print'></span> Imprimir</a></td>";
                                            } else {
                                                $output .= "<td></td>";
                                            }
                                        }
                                        $output .= "</tr>";
                                        echo $output;
                                    } else {
                                        $output .= "<td>No hay elementos</td>";
                                        echo $output;
                                    }
                                } else {
                                    echo "Error en el query: " . $query . "\nError: " . mysql_error() . "\n";
                                }
                            } else {
                                echo "Error en el query: " . $query . "\nError: " . mysql_error() . "\n";
                            }
                        } else {
                            $query = "SELECT * FROM $vw_incidencias WHERE idusuario = '" . $_SESSION['padron_admin_id'] . "' AND fecha >= '" . date("Y-m-d", strtotime("-2 months")) . "'";
                            if ($result = mysql_query($query, $con)){
                                $output = "";
                                if (mysql_num_rows($result) > 0) {
                                    while ($row = mysql_fetch_array($result)) {
                                        $output .= "<tr id='" . $row['id'] . "'><td style='vertical-align: middle'>" . $row['id'] . "</td>";
                                        $output .= "<td style='vertical-align: middle'>". $row['fechacaptura'] . "</td>";
                                        $output .= "<td style='vertical-align: middle'>" . $row['nombre'] . " " . $row['apaterno'] . " " . $row['amaterno'] . "</td>";
                                        $output .= "<td style='vertical-align: middle'>" . $row['fecha'] . "</td>";
                                        $output .= "<td style='vertical-align: middle'>" . $row['tipo'] . "</td>";
                                        $output .= "<td style='vertical-align: middle'>" . $row['concepto'] . "</td>";
                                        if ($row['horas'] == 0) {
                                            $output .= "<td style='vertical-align: middle'>" . "NA" . "</td>";
                                        } else {
                                            $output .= "<td style='vertical-align: middle'>" . $row['horas'] . "</td>";
                                        }
                                        $output .= "<td style='vertical-align: middle'>". $row['estatus'] . "</td><td></td>";
                                    }
                                    $output .= "</tr>";
                                    echo $output;
                                } else {
                                    $output .= "<td>No hay elementos</td>";
                                    echo $output;
                                }
                            } else {
                                echo (mysql_error());
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
        