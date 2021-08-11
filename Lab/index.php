<?php
session_start();
if (isset($_SESSION['padron_admin_activo']) == 'activa') {
  header("Location: menu.php");
} else {
  require_once("hdr.php");
?>

  <body>
    <script type="text/javascript">
      $(document).ready(function() {
        /*$("#usuario").blur(function(event) 
        {
          $.post('procesar_persona.php',{opcion:1,usuario:$("#usuario").val()}, function(RespFotoUsuario)
          {
            //alert(RespFotoUsuario);
            if(RespFotoUsuario!='')
            {
              $(".profile-img").removeAttr('src');
              $(".profile-img").attr('src', RespFotoUsuario);
            }
            else
            {
              $(".profile-img").removeAttr('src');
              $(".profile-img").attr('src', 'imagenes/photo.png'); 
            }
          });

        });//USUARIO blur*/

        $("#formlogin").submit(function(e) {

          e.preventDefault();
          $.post('procesar_persona.php', {
            opcion: 2,
            usuario: $("#usuario").val(),
            pass: $("#pass").val()
          }, function(RespUsuario) {
            $("#avisos").removeAttr('class');
            $("#avisos").css("display", "none");
            $("#avisos").fadeIn(1000);

            $("#avisos").attr("class", "col-sm-6 col-md-4 col-md-offset-4 alert alert-danger");
            $("#avisos").html(RespUsuario);

            switch (parseInt(RespUsuario)) {
              case 1:
                //alert("1");
                $("#avisos").attr("class", "col-sm-6 col-md-4 col-md-offset-4 alert alert-success");
                $("#avisos").html("Bienvenido al Sistema");
                $("#avisos").delay(1000, function() {
                  window.location.href = "menu.php";
                });
                break;

                /*case 2:
                  alert("2");              
                  $("#avisos").attr("class", "col-sm-6 col-md-4 col-md-offset-4 alert alert-danger");
                  $("#avisos").html("Disculpe su horario de acceso no es permitido");
                break;*/

              case 2:
                //alert("2");              
                $("#avisos").attr("class", "col-sm-6 col-md-4 col-md-offset-4 alert alert-danger");
                $("#avisos").html("Password Incorrecto");
                break;

              case 3:
                //alert("3");            
                $("#avisos").attr("class", "col-sm-6 col-md-4 col-md-offset-4 alert alert-danger");
                $("#avisos").html("Usuario Incorrecto");
                break;

              case 4:
                //alert("4");            
                $("#avisos").attr("class", "col-sm-6 col-md-4 col-md-offset-4 alert alert-warning");
                $("#avisos").html("Bloqueo preventivo de seguridad intente nuevamente en 1 hora");
                break;
            }
          });

        });
      });
    </script>

    <div class="container">
      <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
          <h1 class="text-center login-title">Padron unico de beneficiarios</h1>
          <div class="account-wall">
            <img class="profile-img" src="imagenes/photo.png" alt="">
            <form class="form-signin" name="formlogin" id="formlogin" method="POST">
              <input name="usuario" id="usuario" type="text" class="form-control" placeholder="Usuario" required autofocus>
              <input name="pass" id="pass" type="password" class="form-control" placeholder="Contrase&ntilde;a" required>
              <input type="submit" class="btn btn-lg btn-primary btn-block" id="enviar" value="Entrar">
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="conitainer">
      <div class="row">
        <div id="avisos">
        </div>
      </div>
    </div>

  </body>

  </html>
<?php
} ?>