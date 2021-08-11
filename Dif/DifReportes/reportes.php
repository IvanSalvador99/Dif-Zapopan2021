<?php
    require_once("menureportes.php");
    if ((!perteneceA($_SESSION['padron_admin_area'], 8) && !perteneceA($_SESSION['padron_admin_area'], 7)) && $_SESSION['padron_admin_permisos'] != 1) {
        alerta_bota("No perteneces al departamento asignado para esta aplicación", "../menu.php");
        //echo "<script>window.location = '../menu.php'</script>";
    }
    if ($_GET['action'] == 1) {
        alerta_bota("Servicio registrados correctamente", 0);
    }
?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('body').on('focus',".date", function(){
                    $(this).datepicker({
                        format: 'yyyy/mm/dd',
                        autoclose: true,
                        endDate: "today",
                        language: 'es'
                    });
                });
                
                $("#botongenerar").on("click", function(){
                    $("#modalreporte").modal();
                });
                
                $("#generarbtn").on("click", function(){
                    $("#generarform").submit();
                });
                
                $("#tiporeporte").on("change", function(){
                    $("#divform").empty();
                    $("#divform").load('ajax/ajax_' + this.value + '.php');
                });
                
                $("#modalreporte").on("hidden.bs.modal", function(){
                    $("#divform").empty();
                    $("#tiporeporte option[value='-1']").prop("selected", true);
                });
            });
        </script>
        <br />
        <br />
        <?php
            if ($_SESSION['padron_admin_permisos'] <= 6) {
                echo '<div class="row text-center"><a href="#" class="btn btn-primary" id="botongenerar">Generar reporte</a></div>';
            }
        ?>
        <br />
        <div class="modal fade" id="modalreporte" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Reporte</h4>
                        <div class="row text-center">
                            <div class="form-group has-feedback col-md-12">
                                <label for="tiporeporte" class="control-label">Tipo de reporte:</label>
                                <select class="form-control input-sm" name="tiporeporte" id="tiporeporte" required>
                                    <option value="-1" disabled selected>---Seleccione tipo de reporte---</option>
                                    <?php echo fillSelect($tipo_reporte); ?>
                                </select>
                                <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div id="divform"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="generarbtn" data-dismiss="modal">Generar</button>
                    </div>
                </div>
            </div>
        </div>
        <br />
        <div class="col-sm-12">
            
        </div>