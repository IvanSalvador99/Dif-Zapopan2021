<?php
    include("menuusuarios.php");
    
    if ($_SESSION['padron_admin_permisos'] != 1 && !isset($_POST['self']) && !esAdminIncidencias($_SESSION['padron_admin_id'])) {
        echo "<script>window.location = 'usuarios.php'</script>";
    }
    
    $query = "SELECT * FROM $trabajadores WHERE id = '" . $_POST['id'] . "'";
    if ($result = mysql_query($query, $con)){
        $row = mysql_fetch_array($result);
        echo "</br></br></br></br>";
        //var_dump($row);
    }     
?>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#passrow").hide();
                $("#formeditarusuario").validator("validate");
                
                $("#modpassword").change(function() {
                    if(this.checked) {
                        $("#passrow").show();
                        $("#password").prop("required", true);
                        $("#passwordconf").prop("required", true);
                        $("#password").val("");
                        $("#passwordconf").val("");
                    } else {
                        $("#passrow").hide();
                        $("#password").prop("required", false);
                        $("#passwordconf").prop("required", false);
                        $("#password").val("");
                        $("#passwordconf").val("");
                    }
                    $("#formeditarusuario").validator("validate");
                });
            });
            
            function checarpwd(){
                if ($("#password").val() != $("#passwordconf").val()){
                    $(".nota").html("El password no coincide");
                    $(".nota").css("color", "red");
                    $(".btn").prop("disabled", true);
                } else {
                    $(".nota").html("El password coincide");
                    $(".nota").css("color", "#006600");
                    $(".btn").prop("disabled", false);
                }
            }
        </script>
        <form method="post" action="engine/engine_usuarioeditar.php" id="formeditarusuario" data-toggle="validator">
            <h2 class="text-center">Modificar Usuario</h2>
            <br />
            <div class="row">
                <div class="form-group has-feedback col-md-4">
                    <label for="nombreusuario">Nombre de Usuario:</label>
                    <input class="form-control input-sm" type="text" id="nombreusuario" name="nombreusuario" value="<?=$row['username']?>" pattern="^[0-9A-Za-z]{6,}$" data-pattern-error="Al menos 6 caracteres alfanumericos" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="email">E-mail institucional:</label>
                    <input class="form-control input-sm" type="text" id="email" name="email" value="<?=$row['email']?>" pattern="^[a-zA-Z0-9_]+(?:\.[A-Za-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?!([a-zA-Z0-9]*\.[a-zA-Z0-9]*\.[a-zA-Z0-9]*\.))(?:[A-Za-z0-9](?:[a-zA-Z0-9-]*[A-Za-z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$" data-pattern-error="No es un email valido" data-required-error="Debe llenar este campo"/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="permiso">Permisos:</label>
                        <?php
                            if (isset($_POST['self']) && $_POST['self'] == "true") {
                                $output = "<input type='hidden' name='permiso' value='" . $row['permiso'] . "'/><select class='form-control input-sm' name='permiso' id='permiso' disabled>";
                            } else {
                                $output = "<select class='form-control input-sm' name='permiso' id='permiso' required>";
                            }
                            
                            if ($_SESSION['padron_admin_permisos'] == 1 || esAdminIncidencias($_SESSION['padron_admin_id'])) {
                                $output .= fillSelectData($permisos, $row['permiso']);
                            } else {
                                $output .= fillSelectData($permisos, $row['permiso']);
                                $indexstart = strpos($output, ">") + 1;
                                for ($i = 0; $i < $_SESSION['padron_admin_permisos']; $i++) {
                                    $output = substr_replace($output, " disabled", strpos($output, ">", $indexstart), 0);
                                    $indexstart = strpos($output, ">", strpos($output, ">", $indexstart) + 1) + 1;
                                }
                            }
                            echo $output;
                        ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label class="checkbox-inline"><input type="checkbox" id="modpassword"/> ¿Modificar password?</label>
                </div>
            </div>
            <div class="row" id="passrow">
                <div class="form-group has-feedback col-md-6">
                    <label for="password">Contraseña:</label> <span><small class="nota"></small></span>
                    <input class="form-control input-sm" type="password" id="password" name="password" pattern="^((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,})$" data-pattern-error="Al menos 8 caracteres, una mayuscula y un simbolo y un digito (excepto guion/guion bajo)" data-required-error="Debe llenar este campo"/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-6">
                    <label for="passwordconf">Confirmar contraseña:</label> <span><small class="nota"></small></span>
                    <input class="form-control input-sm" type="password" id="passwordconf" name="passwordconf" oninput="checarpwd()" pattern="^((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,})$" data-pattern-error="Al menos 8 caracteres, una mayuscula y un simbolo y un digito (excepto guion/guion bajo)" data-required-error="Debe llenar este campo"/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-4">
                    <label for="nombre" class="control-label">Nombre(s):</label>
                    <input class="form-control input-sm" type="text" id="nombre" name="nombre" value="<?=$row['nombre']?>" pattern="^[A-Za-z Á-ÿ.]{1,}$" data-pattern-error="Solo letras" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="apellidop" class="control-label">Apellido Paterno:</label>
                    <input class="form-control input-sm" type="text" id="apellidop" name="apellidop" value="<?=$row['apaterno']?>" pattern="^[A-Za-z Á-ÿ]{1,}$" data-pattern-error="Solo letras" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="apellidom" class="control-label">Apellido Materno:</label>
                    <input class="form-control input-sm" type="text" id="apellidom" name="apellidom" value="<?=$row['amaterno']?>" pattern="^[A-Za-z Á-ÿ]{1,}$" data-pattern-error="Solo letras" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-6">
                    <label for="departamento">Departamento:</label>
                    <?php
                        if ($_SESSION['padron_admin_permisos'] == 1 || esAdminIncidencias($_SESSION['padron_admin_id'])) {
                            if (isset($_POST['self']) && $_POST['self'] == "true") {
                                $output = "<input type='hidden' name='departamento' value='" . $row['departamento'] . "'/><select class='form-control input-sm' name='departamento' id='departamento' disabled>";
                            } else {
                                $output = "<select class='form-control input-sm' name='departamento' id='departamento' required>";
                            }
                            echo $output . fillSelectData($departamentos, $row['departamento']);
                        } else {
                            if (isset($_POST['self']) && $_POST['self'] == "true") {
                                $output = "<input type='hidden' name='departamento' value='" . $row['departamento'] . "'/><select class='form-control input-sm' name='departamento' id='departamento' disabled>";
                            } else {
                                $output = "<select class='form-control input-sm' name='departamento' id='departamento' required>";                                    
                            }
                            echo $output . fillSelectResultData(deptosubordinado($_SESSION['padron_admin_area']), $row['departamento']);
                        }
                    ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-3">
                    <label for="tipo">Tipo de empleado:</label>
                    <?php
                        if ((isset($_POST['self']) && $_POST['self'] == "true") || ($_SESSION['padron_admin_permisos'] != 1 && !esAdminIncidencias($_SESSION['padron_admin_id']))) {
                            $output = "<input type='hidden' name='tipo' value='" . $row['tipo'] . "'/><select class='form-control input-sm' name='tipo' id='tipo' disabled>";
                        } else {
                            $output = "<select class='form-control input-sm' name='tipo' id='tipo' required>";
                        }
                        echo $output . fillSelectData($regimen, $row['tipo']);
                    ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-3">
                    <label for="numeroempleado" class="control-label">Numero de empleado:</label>
                    <?php 
                        if ((isset($_POST['self']) && $_POST['self'] == "true") || ($_SESSION['padron_admin_permisos'] != 1 && !esAdminIncidencias($_SESSION['padron_admin_id']))) {
                            ?><input class="form-control input-sm" type="text" id="numeroempleado" name="numeroempleado" value="<?=$row['numeroempleado']?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required readonly/><?php
                        } else {
                            ?><input class="form-control input-sm" type="text" id="numeroempleado" name="numeroempleado" value="<?=$row['numeroempleado']?>" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/><?php
                        }
                    ?>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group text-center">
                    <input type="hidden" name="idusuario" value="<?=$_POST['id']?>"/>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </div>
        </form>
    </div>
</body>