<?php
    include("menucursos.php");
    if (!empty($_GET)) {
        if ($_GET['action'] == 1) {
            alerta_bota("Curso registrado correctamente", 0);
        } elseif ($_GET['action'] == 2) {
            alerta_bota("Participantes registrados correctamente", 0);
        } elseif ($_GET['action'] == 3) {
            alerta_bota("Asistencias registradas correctamente", 0);
        } elseif ($_GET['action'] == 4) {
            alerta_bota("Ese dia ya cuenta con asistencias registradas", 0);
        } elseif ($_GET['action'] == 5) {
            alerta_bota("No enviaste los datos necesarios", 0);
        }
    }    
?>
        <script type="text/javascript">
            $('body').on('focus',".date", function(){
                $(this).datepicker({
                    format: 'yyyy/mm/dd',
                    autoclose: true,
                    endDate: "today",
                    language: 'es'
                });
            });
            
            $("body").on("click", "#btnaddppl", function(){
                $('<form>', {
                    "id": "formaddppl",
                    "method": "post",
                    "html": '<input type="hidden" id="curso" name="curso" value="' + $(this).data("value") + '" />',
                    "action": 'agregarparticipantes.php'
                }).appendTo(document.body).submit();
            });
            
            $("body").on("click", "#btnlistas", function(){
                $('<form>', {
                    "id": "formlistas",
                    "method": "post",
                    "html": '<input type="hidden" id="curso" name="curso" value="' + $(this).data("value") + '" />',
                    "action": 'registroasistencias.php'
                }).appendTo(document.body).submit();
            });
                
            $(document).ready(function(){
                $('#tablacursos').DataTable({
                    "ordering": false,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                    },
                    "dom": "<'top'>fpltri<'bottom'p><'clear'>"
                });
            });
        </script>
        <br />
        <br />
        <?php
            if ($_SESSION['padron_admin_permisos'] <= 1) {
                echo '<div class="row text-center"><a href="cursosregistro.php" class="btn btn-primary">Registrar nuevo curso</a></div>';
            }
        ?>
        <br />
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-striped table-condensed" id="tablacursos">
                    <thead>
                        <tr>
                            <th style='vertical-align: middle'>ID</th>
                            <th style='vertical-align: middle'>Nombre del curso</th>
                            <th style='vertical-align: middle'>Nombre del expositor</th>
                            <th style='vertical-align: middle'>Sede</th>
                            <th style='vertical-align: middle'>Duracion del curso</th>
                            <th style='vertical-align: middle'>Fecha de inicio y fin</th>
                            <th style='vertical-align: middle'>Regularidad</th>
                            <th style='vertical-align: middle'>Aula</th>
                            <th style='vertical-align: middle'>Horario y duración de sesión</th>
                            <th style='vertical-align: middle'>Estatus</th>
                            <th style='vertical-align: middle'>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $query = "SELECT * FROM $vw_cursos";
                        // <td>" . $row['locacion'] . "</td>
                        $output = "";
                        if ($result = mysql_query($query, $con)) {
                            while ($row = mysql_fetch_array($result)) {
                                $output .= "<tr>
                                            <td>" . $row['id'] . "</td>
                                            <td>" . $row['nombre'] . "</td>
                                            <td>" . $row['docente'] . "</td>
                                            <td>" . $row['locacion'] . "</td>
                                            <td>" . $row['duracioncurso'] . "</td>
                                            <td>" . $row['fechainicio'] . " al " . $row['fechafin'] . "</td>
                                            <td>" . $row['regularidad'] . "</td>
                                            <td>" . $row['aula'] . "</td>
                                            <td>" . $row['duracionsesion'] . "</td>
                                            <td>" . $row['estatus'] . "</td>
                                            <td class='text-center'><button type='button' class='btn btn-primary' id='btnaddppl' data-value='" . $row['id'] . "'>Editar <span class='glyphicon glyphicon-edit'></button></br></br><button type='button' class='btn btn-primary' id='btnlistas' data-value='" . $row['id'] . "'>Toma de asistencia <span class='glyphicon glyphicon-edit'></button></td>
                                            </tr>";
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