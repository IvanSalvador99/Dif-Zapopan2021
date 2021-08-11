<?php
    include("menuserviciosnv.php");
    if ($_GET['action'] == 1) {
        alerta_bota("Servicio registrados correctamente", 0);
    }
?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#tablaserviciosnv').DataTable({
                    "ordering": false,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                    },
                    "dom": "<'top'>fpltri<'bottom'p><'clear'>"
                });
                
                $('body').on('focus',".date", function(){
                    $(this).datepicker({
                        format: 'yyyy/mm/dd',
                        autoclose: true,
                        endDate: "today",
                        language: 'es'
                    });
                });
            });
        </script>
        <br />
        <br />
        <?php
            if ($_SESSION['padron_admin_permisos'] <= 7) {
                echo '<div class="row text-center"><a href="serviciosnvregistro.php" class="btn btn-primary">Registrar servicio no vinculante</a></div>';
            }
        ?>
        <br />
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-striped table-condensed" id="tablaserviciosnv">
                    <thead>
                        <tr>
                            <th style='vertical-align: middle'>ID</th>
                            <th style='vertical-align: middle'>Fecha</th>
                            <th style='vertical-align: middle'>Departamento</th>
                            <th style='vertical-align: middle'>Servicio</th>
                            <th style='vertical-align: middle'>Localidad</th>
                            <th style='vertical-align: middle'>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $query = "SELECT * FROM $vw_serviciosnv";
                        $output = "";
                        if ($result = mysql_query($query, $con)) {
                            while ($row = mysql_fetch_array($result)) {
                                if ($row['colonia'] != "") {
                                    $output .= "<tr>
                                                <td>" . $row['id'] . "</td>
                                                <td>" . $row['fecha'] . "</td>
                                                <td>" . $row['departamento'] . "</td>
                                                <td>" . $row['servicio'] . "</td>
                                                <td>" . $row['locacion'] . " - " . $row['colonia'] . "</td>
                                                <td>" . $row['cantidades'] . "</td>
                                                </tr>";
                                } else {
                                    $output .= "<tr>
                                                <td>" . $row['id'] . "</td>
                                                <td>" . $row['fecha'] . "</td>
                                                <td>" . $row['departamento'] . "</td>
                                                <td>" . $row['servicio'] . "</td>
                                                <td>" . $row['locacion'] . "</td>
                                                <td>" . $row['cantidades'] . "</td>
                                                </tr>";
                                }    
                            }
                            echo $output;
                        } else {
                            echo "</br>Error en el query: " . $query . "</br>Error: " . mysql_error() . "</br></br>";
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>