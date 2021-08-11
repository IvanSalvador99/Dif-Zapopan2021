<?php
require_once("menupadron.php");

if ((!perteneceA($_SESSION['padron_admin_area'], 8) && !perteneceA($_SESSION['padron_admin_area'], 7)) && $_SESSION['padron_admin_permisos'] != 1 && $_SESSION['padron_admin_id'] != 750) {
	alerta_bota("No perteneces al departamento asignado para esta aplicación", "../menu.php");
    //echo "<script>window.location = '../menu.php'</script>";
}

if ($_GET['action'] == 1) {
    alerta_bota("Beneficiario creado correctamente", 0);
} elseif ($_GET['action'] == 2) {
	alerta_bota("Beneficiario actualizado correctamente", 0);
}

if ($_POST['accion'] == 1) {
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
        
        if (mysql_query($query, $con)){
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
            $(document).ready(function(){
                $('#tablabeneficiarios').DataTable({
                    "ordering": false,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                    },
                    "dom": "<'top'>fpltri<'bottom'p><'clear'>",
                    "deferRender": true
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
            
            Date.prototype.yyyymmdd = function() {
                var mm = this.getMonth() + 1; // getMonth() is zero-based
                var dd = this.getDate();
                return [this.getFullYear(), "/", (mm>9 ? '' : '0') + mm, "/", (dd>9 ? '' : '0') + dd].join('');
            };
            
            $(document).on("dblclick", ".table tr", function(e){
                window.open("beneficiariovista.php?id=" + this.id, "_blank");
            });
 
            function editarclick(idbeneficiario, event){
                event.preventDefault();
                event.stopPropagation();
                var $form = $("#editarbeneficiario");
                $form.append($("<input type='hidden' name='id'>").val(idbeneficiario));
                $form.get(0).submit();
            }
            
            function cambiarestatus(idbeneficiario, event){
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
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-striped table-condensed" id="tablabeneficiarios">
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
                    <tbody>
                    <?php
                        $query = "SELECT * FROM $vw_personas";
                        /*echo "</br></br>QUERY:</br>";
                        echo ($query);*/
                        $finalstr = "";
                        if ($result = mysql_query($query, $con)){
                            while ($row = mysql_fetch_array($result)) {
                                if ($row['estatus'] == "Activo") {
                                    $output = "<tr class='success' id='". $row['id'] . "'>";
                                } else {
                                    $output = "<tr class='danger' id='". $row['id'] . "'>";
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
                                    $output .= "<td>
                                                    botoncito
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