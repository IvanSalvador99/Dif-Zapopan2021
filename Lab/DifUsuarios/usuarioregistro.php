<?php
    include("menuusuarios.php");
    
    if ($_SESSION['padron_admin_permisos'] != 1 && !esAdminIncidencias($_SESSION['padron_admin_id'])) {
        echo "<script>window.location = 'usuarios.php'</script>";
    }    
?>
        <script type="text/javascript">
            $(document).ready(function(){
                          
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
        <form method="post" action="engine/engine_usuarioregistro.php" id="formregistro" data-toggle="validator">
            <h2 class="text-center">Registro</h2>
            <br />
            <div class="row">
                <div class="form-group has-feedback col-md-4">
                    <label for="nombreusuario">Nombre de Usuario:</label>
                    <input class="form-control input-sm" type="text" id="nombreusuario" name="nombreusuario" pattern="^[0-9A-Za-z]{6,}$" data-pattern-error="Al menos 6 caracteres alfanumericos" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="email">E-mail institucional:</label>
                    <input class="form-control input-sm" type="text" id="email" name="email" pattern="^[a-zA-Z0-9_]+(?:\.[A-Za-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?!([a-zA-Z0-9]*\.[a-zA-Z0-9]*\.[a-zA-Z0-9]*\.))(?:[A-Za-z0-9](?:[a-zA-Z0-9-]*[A-Za-z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$" data-pattern-error="No es un email valido" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="permiso">Permisos:</label>
                    <select class="form-control input-sm" name="permiso" id="permiso" required>
                        <option value="-1" disabled selected>---Asignar permisos---</option>
                        <?php echo fillSelect($permisos); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-6">
                    <label for="password">Contraseña:</label> <span><small class="nota"></small></span>
                    <input class="form-control input-sm" type="password" id="password" name="password" pattern="^((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,})$" data-pattern-error="Al menos 8 caracteres, una mayuscula y un simbolo y un digito (excepto guion/guion bajo)" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-6">
                    <label for="passwordconf">Confirmar contraseña:</label> <span><small class="nota"></small></span>
                    <input class="form-control input-sm" type="password" id="passwordconf" name="passwordconf" oninput="checarpwd()" pattern="^((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,})$" data-pattern-error="Al menos 8 caracteres, una mayuscula y un simbolo y un digito (excepto guion/guion bajo)" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-4">
                    <label for="nombre" class="control-label">Nombre(s):</label>
                    <input class="form-control input-sm" type="text" id="nombre" name="nombre" pattern="^[A-Za-z Á-ÿ.]{1,}$" data-pattern-error="Solo letras" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="apellidop" class="control-label">Apellido Paterno:</label>
                    <input class="form-control input-sm" type="text" id="apellidop" name="apellidop" pattern="^[A-Za-z Á-ÿ]{1,}$" data-pattern-error="Solo letras" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-4">
                    <label for="apellidom" class="control-label">Apellido Materno:</label>
                    <input class="form-control input-sm" type="text" id="apellidom" name="apellidom" pattern="^[A-Za-z Á-ÿ]{1,}$" data-pattern-error="Solo letras" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback col-md-6">
                    <label for="departamento">Departamento:</label>
                    <select class="form-control input-sm" name="departamento" id="departamento" required>
                        <option value='-1' disabled selected>---Seleccionar departamento---</option>
                        <?php echo fillSelect($departamentos); ?>
                    </select>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-3">
                    <label for="tipo">Tipo de empleado:</label>
                    <select class="form-control input-sm" name="tipo" id="tipo" required>
                        <option value="-1" disabled selected>---Seleccionar regimen---</option>
                        <?php echo fillSelect($regimen); ?>
                    </select>        
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 25px"></span>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback col-md-3">
                    <label for="numeroempleado" class="control-label">Numero de empleado:</label>
                    <input class="form-control input-sm" type="text" id="numeroempleado" name="numeroempleado" pattern="^[0-9]{1,}$" data-pattern-error="Solo numeros" data-required-error="Debe llenar este campo" required/>
                    <span class="glyphicon form-control-feedback" aria-hidden="true" style="margin-right: 10px"></span>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="row">
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </div>
        </form>
    </div>
</body>