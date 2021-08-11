<?php
include("menuusuarios.php");

if ($_GET['action'] == 1) {
    alerta_bota("Usuario creado correctamente", 0);
} elseif ($_GET['action'] == 2) {
    alerta_bota("Usuario actualizado correctamente", 0);
}
?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#tablausuarios').DataTable({
                    "ordering": false,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                    },
                    "dom": "<'top'>fpltri<'bottom'p><'clear'>"
                });
            });
            function editarclick(idusuario){
                var $form = $("#editarusuario");
                $form.append($("<input type='hidden' name='id'>").val(idusuario));
                $form.get(0).submit();
            }
        </script>
        <form id="editarusuario" action="usuarioeditar.php" method="post"></form>
        <br />
        <?php
            if ($_SESSION['padron_admin_permisos'] <= 4) {
        ?>
                <div class="row text-center">
                    <a href="usuarioregistro.php" class="btn btn-primary">Agregar usuario</a>                                        
                </div>            
        <?php
            }
        ?>
        <br />
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-striped table-condensed" id="tablausuarios">
                    <thead>
                        <tr>
                            <th style='vertical-align: middle'>Id</th>
                            <th style='vertical-align: middle'>Nombre de Usuario</th>
                            <th style='vertical-align: middle'>Nombre</th>
                            <th style='vertical-align: middle'>Email institucional</th>
                            <th style='vertical-align: middle'>Departamento</th>
                            <th style='vertical-align: middle'>Tipo</th>
                            <th style='vertical-align: middle'>Numero de empleado</th>
                            <th style='vertical-align: middle'>Permisos</th>
                            <th style='vertical-align: middle'>Acciones</th>
                        </tr>
                    </thead>
                    <?php
                        $query = "SELECT * FROM $vw_usuarios";
                        if ($result = mysql_query($query, $con)){
                            while ($row = mysql_fetch_array($result)) {
                                $output = "";
                                $output .= "<td style='vertical-align: middle'>" . $row['id'] . "</td>
                                            <td style='vertical-align: middle'>" . $row['username'] . "</td>
                                            <td style='vertical-align: middle'>" . $row['nombre'] . " " . $row['apaterno'] . " " . $row['amaterno'] . "</td>
                                            <td style='vertical-align: middle'>" . $row['email'] . "</td>
                                            <td style='vertical-align: middle'>" . $row['departamento'] . "</td>
                                            <td style='vertical-align: middle'>" . $row['tipo'] . "</td>
                                            <td style='vertical-align: middle'>" . $row['numeroempleado'] . "</td>
                                            <td style='vertical-align: middle'>" . $row['permiso']. "</td>";
                                            
                                if ($_SESSION['padron_admin_permisos'] <= 1) {
                                    $output = substr_replace($output, "<tr class='success'>", 0, 0);
                                    $output .= "<td style='vertical-align: middle'>
                                                    <a onclick='editarclick(" . $row['id'] . ")' class='btn btn-primary' style='width: 100px;'><span class='glyphicon glyphicon-edit'></span> Editar</a>
                                                    <a href='#' class='btn btn-primary' style='width: 100px;'><span class='glyphicon glyphicon-remove'></span> Eliminar</a>
                                                </td></tr>";
                                } elseif ($_SESSION['padron_admin_permisos'] <= 2) {
                                    if ($row['idpermiso'] > $_SESSION['padron_admin_permisos']) {
                                        $resultdeptos = deptosubordinado($_SESSION['padron_admin_area']);
                                        $coincidencia = FALSE;
                                        while ($rowdepto = mysql_fetch_array($resultdeptos)) {
                                            if ($row['iddepartamento'] == $rowdepto['id']){
                                                $output = substr_replace($output, "<tr class='success'>", 0, 0);
                                                $output .= "<td style='vertical-align: middle'>
                                                        <a onclick='editarclick(" . $row['id'] . ")' class='btn btn-primary' style='width: 100px;'><span class='glyphicon glyphicon-edit'></span> Editar</a>
                                                        <a href='#' class='btn btn-primary' style='width: 100px;'><span class='glyphicon glyphicon-remove'></span> Eliminar</a>
                                                    </td></tr>";
                                                $coincidencia = TRUE;
                                            }
                                        }
                                        if ($coincidencia == FALSE)
                                            $output .= "<td style='vertical-align: middle'>Sin Permisos</td></tr>";
                                    } else {
                                        $output .= "<td style='vertical-align: middle'>Sin Permisos</td></tr>";
                                    }
                                } elseif ($_SESSION['padron_admin_permisos'] <= 3) {
                                    if ($row['idpermiso'] > $_SESSION['padron_admin_permisos']) {
                                        if ($row['iddepartamento'] == $_SESSION['padron_admin_area']){
                                            $output = substr_replace($output, "<tr class='success'>", 0, 0);
                                            $output .= "<td style='vertical-align: middle'>
                                                    <a onclick='editarclick(" . $row['id'] . ")' class='btn btn-primary' style='width: 100px;'><span class='glyphicon glyphicon-edit'></span> Editar</a>
                                                    <a href='#' class='btn btn-primary' style='width: 100px;'><span class='glyphicon glyphicon-remove'></span> Eliminar</a>
                                                </td></tr>";
                                        } else {
                                            $output .= "<td style='vertical-align: middle'>Sin Permisos</td></tr>";
                                        }
                                    } else {
                                        $output .= "<td style='vertical-align: middle'>Sin Permisos</td></tr>";
                                    }
                                } elseif ($_SESSION['padron_admin_permisos'] <= 4) {
                                    if ($row['idpermiso'] > $_SESSION['padron_admin_permisos']) {
                                        if ($row['iddepartamento'] == $_SESSION['padron_admin_area']){
                                            $output = substr_replace($output, "<tr class='success'>", 0, 0);
                                            $output .= "<td style='vertical-align: middle'>
                                                    <a onclick='editarclick(" . $row['id'] . ")' class='btn btn-primary' style='width: 100px;'><span class='glyphicon glyphicon-edit'></span> Editar</a>
                                                    <a href='#' class='btn btn-primary' style='width: 100px;'><span class='glyphicon glyphicon-remove'></span> Eliminar</a>
                                                </td></tr>";
                                        } else {
                                            $output .= "<td style='vertical-align: middle'>Sin Permisos</td></tr>";
                                        }
                                    } else {
                                        $output .= "<td style='vertical-align: middle'>Sin Permisos</td></tr>";
                                    }
                                } else {
                                    $output .= "<td style='vertical-align: middle'>Sin Permisos</td></tr>";
                                }
                                echo $output;
                            }
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
