<?php
require_once("menupadron.php");

if (isset($_GET['action']) && $_GET['action'] == 1) {
    alerta_bota("Beneficiario creado correctamente", 0);
} elseif (isset($_GET['action']) && $_GET['action'] == 2) {
    alerta_bota("Beneficiario actualizado correctamente", 0);
}

if (isset($_POST['accion']) && $_POST['accion'] == 1) {
    $query = "SELECT * FROM $vw_personas WHERE id = '" . $_POST['id'] . "'";
    if ($result = mysql_query($query, $con)) {
        $row = mysql_fetch_array($result);
        /*echo "<br/><br/>Row:<br/>";
        var_dump($row);*/
        if ($row['estatus'] == "Activo") {
            $query = "UPDATE $personas SET estatus='2' WHERE id='" . $_POST['id'] . "'";
        } else {
            $query = "UPDATE $personas SET estatus='1' WHERE id='" . $_POST['id'] . "'";
        }

        if (mysql_query($query, $con)) {
            echo "<script>window.location = 'beneficiarios.php'</script>";
        } else {
            /*echo "<br/><br/>Error:<br/>";
            echo mysql_error();*/
        }
    } else {
        /*echo "<br/><br/>Error:<br/>";
        echo mysql_error();*/
    }
} else {
?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#inputbusqueda').quicksearch('table#tablabeneficiarios tbody tr', {
                'delay': 100,
                'minValLength': 4
            });

            $("#btnlimpiar").on("click", function() {
                $("#inputbusqueda").val("");
                $("#inputbusqueda").trigger("keyup");
            });

            $('#tablabeneficiarios').DataTable({
                "ordering": false,
                "language": {
                    "url": "/Tools/DataTables/spanish.json"
                },
                "dom": "<'top'>fpltri<'bottom'p><'clear'>"
            });

            $('body').on('focus', ".date", function() {
                $(this).datepicker({
                    format: 'yyyy/mm/dd',
                    autoclose: true,
                    endDate: "today",
                    language: 'es'
                });
            });
        });

        Date.prototype.yyyymmdd = function() {
            var mm = this.getMonth() + 1; // getMonth() is zero-based
            var dd = this.getDate();
            return [this.getFullYear(), "/", (mm > 9 ? '' : '0') + mm, "/", (dd > 9 ? '' : '0') + dd].join('');
        };

        $(document).on("dblclick", ".table tr", function(e) {
            window.open("beneficiariovista.php?id=" + this.id, "_blank");
        });

        function editarclick(idbeneficiario, event) {
            event.preventDefault();
            event.stopPropagation();
            var $form = $("#editarbeneficiario");
            $form.append($("<input type='hidden' name='id'>").val(idbeneficiario));
            $form.get(0).submit();
        }

        function cambiarestatus(idbeneficiario, event) {
            event.preventDefault();
            event.stopPropagation();
            var $form = $("#cambiarestatus");
            $form.append($("<input type='hidden' name='id'>").val(idbeneficiario));
            $form.append($("<input type='hidden' name='accion'>").val(1));
            $form.get(0).submit();
        }
    </script>
    <form id="editarbeneficiario" action="beneficiarioeditar.php" method="post"></form>
    <form id="cambiarestatus" action="beneficiarios.php" method="post"></form>
    <br />
    <?php
    if ($_SESSION['padron_admin_permisos'] <= 7) {
    ?>
        <div class="row text-center">
            <a href="beneficiarioregistro.php" class="btn btn-primary">Agregar beneficiario</a>
        </div>
    <?php
    }
    ?>
    <br />
    <div>

        <!--<div class="row">
                <div class="form-group col-md-4">
                    <label for="inputbusqueda" class="control-label">Busqueda:</label>
                    <input class="form-control input-sm" type="text" id="inputbusqueda" name="inputbusqueda" pattern="^[A-Za-z Á-ÿ.0-9]{1,}$" data-pattern-error="Solo letras y numeros" data-required-error="Debe llenar este campo"/>
                </div>
                <button class="btn btn-primary" id="btnlimpiar">Limpiar</button>
            </div>-->
        <div class="row">
            <div id="buscar">
                <input type="text" name="search" id="search" placeholder="Buscar beneficiario">
            </div>
        </div>
        <div class="row">

            <!-- <table class="table table-striped table-condensed table-responsive" id="tablabeneficiarios"> -->
            <table class="table table-striped table-condensed table-responsive" >
                <thead>
                    <tr>
                        <th style='vertical-align: middle'>ID DIF</th>
                        <th style='vertical-align: middle'>Fecha de registro</th>
                        <th style='vertical-align: middle'>Curp</th>
                        <th style='vertical-align: middle'>Nombre</th>
                        <th style='vertical-align: middle'>Fecha de nacimiento</th>
                        <th style='vertical-align: middle'>Sexo</th>
                        <th style='vertical-align: middle'>Domicilio</th>
                        <th style='vertical-align: middle'>Acciones</th>
                    </tr>
                </thead>

                <tbody class="resultBusqueda">
                    <?php
                    //$query = "SELECT * FROM $vw_personas";
                    $query = "SELECT iddifzapopan, fechacaptura, curp, nombre, apaterno, amaterno, fechanacimiento, sexo, calle, numext, 
                        numint, codigopostal, colonia, municipio, estado, estatus, id FROM $vw_personas ORDER BY fechacaptura DESC LIMIT 0, 10";
                    /*echo "</br></br>QUERY:</br>";
                        echo ($query);*/
                    $finalstr = "";
                    if ($result = mysql_query($query, $con)) {
                        while ($row = mysql_fetch_array($result)) {
                            if ($row['estatus'] == "Activo") {
                                $output = "<tr class='success' id='" . $row['id'] . "'>";
                            } else {
                                $output = "<tr class='danger' id='" . $row['id'] . "'>";
                            }
                            $output .= "<td style='vertical-align: middle'>" . $row['iddifzapopan'] . "</td>
                                            <td style='vertical-align: middle'>" . $row['fechacaptura'] . "</td>
                                            <td style='vertical-align: middle'>" . $row['curp'] . "</td>
                                            <td style='vertical-align: middle'>" . $row['nombre'] . " " . $row['apaterno'] . " " . $row['amaterno'] . "</td>
                                            <td style='vertical-align: middle'>" . $row['fechanacimiento'] . "</td>
                                            <td style='vertical-align: middle'>" . $row['sexo'] . "</td>
                                            <td style='vertical-align: middle'>" . $row['calle'] . " " . $row['numext'] . "numint, " . $row['codigopostal'] . ", " . $row['colonia'] . ", " . $row['municipio'] . ", " . $row['estado'] . "</td>";
                            if ($row['numint'] == "0") {
                                $output = str_replace("numint", "", $output);
                            } else {
                                $output = str_replace("numint", "-" . $row['numint'], $output);
                            }
                            if ($_SESSION['padron_admin_permisos'] <= 5) {
                                $output .= "<td>botoncito
                                                    <a onclick='editarclick(" . $row['id'] . ", event);' class='btn btn-primary' style='width: 100%;'><span class='glyphicon glyphicon-edit'></span> Editar</a>
                                                </td></tr>";
                                if ($row['estatus'] == "Activo") {
                                    $output = str_replace("botoncito", "<a onclick='cambiarestatus(" . $row['id'] . ", event);' class='btn btn-danger' style='width: 100%;'><span class='glyphicon glyphicon-ban-circle'></span> Deshabilitar</a>", $output);
                                } else {
                                    $output = str_replace("botoncito", "<a onclick='cambiarestatus(" . $row['id'] . ", event);' class='btn btn-success' style='width: 100%;'><span class='glyphicon glyphicon-ok-circle'></span> Habilitar</a>", $output);
                                }
                            } else {
                                $output .= "<td style='vertical-align: middle'>Sin Permisos</td></tr>";
                            }
                            $finalstr .= $output;
                        }
                    } else {
                        echo "</br></br>ERROR:</br>";
                        echo (mysql_error());
                    }
                    echo $finalstr;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
    </body>
<?php
}
?>

<script type="text/javascript">
    $(document).ready(function() {

        $('#search').keyup(function() {
            if ($(this).val() == '') {
                console.log('No hay busqueda');
            } else {
                var texto = {
                    "texto": $(this).val()
                };

                $.ajax({
                    data: texto,
                    url: 'ajax/ajax_busqueda.php',
                    type: 'post',
                    beforeSend: function() {},
                    success: function(response) {
                        $(".resultBusqueda").html(response);
                    },
                    error: function() {

                    }

                })
            }

        });


    });
</script>