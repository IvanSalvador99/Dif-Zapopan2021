<?php
    include_once 'menuincidencias.php';
    // Un comentario random.
    
    if (!esAdminIncidencias($_SESSION['padron_admin_id']) && $_SESSION['padron_admin_permisos'] != 1) {
        echo "<script>window.location = 'incidencias.php'</script>";
    }
?>
        <script type="text/javascript">
            var temp = null;
            $("body").on("click", "#btnborrarsecre", function(){
                //alert (temp);
                $.post('ajax/ajax_borrasecre.php',{"idsecre": $(this).data("value"), "idusuario": temp}, function(respuesta){
                    if (respuesta == "succes") {
                        alert ("Secretaria(o) desvinculado del usuario correctamente.");
                        $("#idusuario").val(temp);
                        $("#btnbuscarusuario").trigger("click");
                    } else {
                        alert (respuesta);
                    }
                });
            });
            
            $("body").on("click", "#btnborrarjefe", function(){
                //alert (temp);
                $.post('ajax/ajax_borrajefe.php',{"idjefe": $(this).data("value"), "idusuario": temp}, function(respuesta){
                    if (respuesta == "succes") {
                        alert ("Jefe(a) desvinculado del usuario correctamente.");
                        $("#idusuario").val(temp);
                        $("#btnbuscarusuario").trigger("click");
                    } else {
                        alert (respuesta);
                    }
                });
            });
        
            $(document).ready(function(){
                $("#selectjefes").multiSelect({
                    selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='ID'>",
                    selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='ID'>",
                    afterInit: function(ms){
                      var that = this,
                          $selectableSearch = that.$selectableUl.prev(),
                          $selectionSearch = that.$selectionUl.prev(),
                          selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                          selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';
                
                      that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                      .on('keydown', function(e){
                        if (e.which === 40){
                          that.$selectableUl.focus();
                          return false;
                        }
                      });
                
                      that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                      .on('keydown', function(e){
                        if (e.which == 40){
                          that.$selectionUl.focus();
                          return false;
                        }
                      });
                    },
                    afterSelect: function(){
                      this.qs1.cache();
                      this.qs2.cache();
                    },
                    afterDeselect: function(){
                      this.qs1.cache();
                      this.qs2.cache();
                    }
                });
                
                $("#selectsecres").multiSelect({
                    selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='ID'>",
                    selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='ID'>",
                    afterInit: function(ms){
                      var that = this,
                          $selectableSearch = that.$selectableUl.prev(),
                          $selectionSearch = that.$selectionUl.prev(),
                          selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                          selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';
                
                      that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                      .on('keydown', function(e){
                        if (e.which === 40){
                          that.$selectableUl.focus();
                          return false;
                        }
                      });
                
                      that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                      .on('keydown', function(e){
                        if (e.which == 40){
                          that.$selectionUl.focus();
                          return false;
                        }
                      });
                    },
                    afterSelect: function(){
                      this.qs1.cache();
                      this.qs2.cache();
                    },
                    afterDeselect: function(){
                      this.qs1.cache();
                      this.qs2.cache();
                    }
                });
                
                $("#divusuario").hide();
                $("#divjefe").hide();
                $("#divsecre").hide();
                
                $("#btnbuscarusuario").on("click", function(e){
                    $.post('ajax/ajax_getjs.php',{"idusuario": $("#idusuario").val()}, function(respuesta){
                        var i;
                        var output = "";
                        for (i = 0; i < respuesta.cantsecretarias; i++) {
                            output += "<tr><td>" + respuesta.secretarias[i][0] + "</td><td>" + respuesta.secretarias[i][1] + "</td><td><button type='button' class='btn btn-default' id='btnborrarsecre' data-value='" + respuesta.secretarias[i][0] + "'><span class='glyphicon glyphicon-remove'></button></td></tr>";
                        }
                        $("#tablasec").html("");
                        $("#tablasec").append(output);
                        
                        output = "";
                        
                        for (i = 0; i < respuesta.cantjefes; i++) {
                            output += "<tr><td>" + respuesta.jefes[i][0] + "</td><td>" + respuesta.jefes[i][1] + "</td><td><button type='button' class='btn btn-default' id='btnborrarjefe' data-value='" + respuesta.jefes[i][0] + "'><span class='glyphicon glyphicon-remove'></button></td></tr>";
                        }
                        
                        $("#tablajef").empty();
                        $("#tablajef").append(output);
                        
                        $("#titulousuario").html("<h2>" + respuesta.usuario + "</h2>");
                        temp = $("#idusuario").val();
                        $("#divusuario").show();
                        $("#divjefe").hide();
                        $("#divsecre").hide();
                    });
                });
                
                $("#btnbuscarjefe").on("click", function(e){
                    $.post('ajax/ajax_getjefeusuarios.php',{"idusuario": $("#idusuario").val()}, function(respuesta){
                        var output = "";
                        
                        $("#selectjefes").empty();
                        $("#selectjefes").html(respuesta.output);
                        $("#selectjefes").multiSelect('refresh');
                        
                        $("#titulousuario1").html("<h2>" + respuesta.usuario + "</h2>");
                        temp = $("#idusuario").val();
                        $("#divusuario").hide();
                        $("#divjefe").show();
                        $("#divsecre").hide();
                    });
                });
                
                $("#btnbuscarsecre").on("click", function(e){
                    $.post('ajax/ajax_getsecreusuarios.php',{"idusuario": $("#idusuario").val()}, function(respuesta){
                        var output = "";
                        
                        $("#selectsecres").empty();
                        $("#selectsecres").html(respuesta.output);
                        $("#selectsecres").multiSelect('refresh');
                        
                        $("#titulousuario2").html("<h2>" + respuesta.usuario + "</h2>");
                        temp = $("#idusuario").val();
                        $("#divusuario").hide();
                        $("#divjefe").hide();
                        $("#divsecre").show();
                    });
                });
                
                $("#btnasignarsecre").on("click", function(e){
                    //alert ($("#asignarsecre").val());
                    $.post('ajax/ajax_asignarsecre.php',{"idusuario": temp, "idsecre": $("#asignarsecre").val()}, function(respuesta){
                        if (respuesta == "succes") {
                            alert ("Secretaria(o) vinculado al usuario correctamente.");
                            $("#idusuario").val(temp);
                            $("#btnbuscarusuario").trigger("click");
                        } else {
                            alert (respuesta);
                            
                        }
                    });
                });
                
                $("#btnasignarjefe").on("click", function(e){
                    //alert ($("#asignarsecre").val());
                    $.post('ajax/ajax_asignarjefe.php',{"idusuario": temp, "idjefe": $("#asignarjefe").val()}, function(respuesta){
                        if (respuesta == "succes") {
                            alert ("Jefe(a) vinculado al usuario correctamente.");
                            $("#idusuario").val(temp);
                            $("#btnbuscarusuario").trigger("click");
                        } else {
                            alert (respuesta);
                        }
                    });
                });
                
                $("#btnguardarjefe").on("click", function(e){
                    $.post('ajax/ajax_guardarjefe.php',{"idjefe": temp, "idusuarios": $("#selectjefes").val()}, function(respuesta){
                        if (respuesta == "succes") {
                            alert ("Cambios realizados correctamente");
                            $("#idusuario").val(temp);
                            $("#btnbuscarjefe").trigger("click");
                        } else {
                            alert (respuesta);
                        }
                    });
                });
                
                $("#btnguardarsecre").on("click", function(e){
                    $.post('ajax/ajax_guardarsecre.php',{"idsecre": temp, "idusuarios": $("#selectsecres").val()}, function(respuesta){
                        if (respuesta == "succes") {
                            alert ("Cambios realizados correctamente");
                            $("#idusuario").val(temp);
                            $("#btnbuscarsecre").trigger("click");
                        } else {
                            alert (respuesta);
                        }
                    });
                });
            });
            
        </script>
        <div class="row text-center">
            <h2>Actualización de jefes y secretarias para base de datos de incidencias</h2>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-4"></div>
            <div class="form-group has-feedback col-md-4">
                <label for="idusuario" class="control-label" style="margin-bottom: 0px">ID Usuario:</label>
                <input class="form-control input-sm" type="text" id="idusuario" name="idusuario" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                <div class="help-block with-errors"></div>
            </div>
            <div class="col-md-4"></div>
        </div>
        <div class="row text-center">
            <button type="button" class="btn btn-primary" id="btnbuscarusuario" style="margin-top: 17px">Buscar usuario</button>
            <button type="button" class="btn btn-primary" id="btnbuscarjefe" style="margin-top: 17px">Buscar como jefe(a)</button>
            <button type="button" class="btn btn-primary" id="btnbuscarsecre" style="margin-top: 17px">Buscar como secretaria(o)</button>
        </div>
        <div id="divusuario">
            <div class="row text-center" id="titulousuario"></div>
            <br />
            <div class="row">
                <div class="col-md-6" style="border-right: solid 4px #AAAAAA">
                    <h3>Secretarias</h3>
                    <div class="form-group has-feedback col-md-9">
                        <label for="asignarsecre" class="control-label" style="margin-bottom: 0px">Asignar a secretaria(o)</label>
                        <input class="form-control input-sm" type="text" id="asignarsecre" name="asignarsecre" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group col-md-3">
                        <button type="button" class="btn btn-primary" id="btnasignarsecre" style="margin-top: 17px">Asignar</button>
                    </div>
                    <table class="table table-sm table-dark table-responsive">
                        <thead>
                            <tr>
                                <th>ID Secretaria</th>
                                <th>Nombre</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tablasec"></tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h3>Jefes</h3>
                    <div class="form-group has-feedback col-md-9">
                        <label for="asignarjefe" class="control-label" style="margin-bottom: 0px">Asignar a jefe(a)</label>
                        <input class="form-control input-sm" type="text" id="asignarjefe" name="asignarjefe" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                        <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group col-md-3">
                        <button type="button" class="btn btn-primary" id="btnasignarjefe" style="margin-top: 17px">Asignar</button>
                    </div>
                    <table class="table table-sm table-dark table-responsive">
                        <thead>
                            <tr>
                                <th>ID Jefe</th>
                                <th>Nombre</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tablajef"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="divjefe">
            <div class="row text-center" id="titulousuario1"></div>
            <br />
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <select id="selectjefes" multiple="multiple"></select>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row text-center">
                <button type="button" class="btn btn-primary" id="btnguardarjefe">Guardar cambios</button>
            </div>
        </div>
        <div id="divsecre">
            <div class="row text-center" id="titulousuario2"></div>
            <br />
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <select id="selectsecres" multiple="multiple"></select>
                </div>
                <div class="col-md-1"></div>
            </div>
            <br />
            <div class="row text-center">
                <button type="button" class="btn btn-primary" id="btnguardarsecre">Guardar cambios</button>
            </div>
        </div>
            