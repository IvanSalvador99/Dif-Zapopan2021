<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/Dif Zapopan/Lab/funciones.php");
    iniciar_sesion();
    require_once($_SERVER['DOCUMENT_ROOT'] . "/Dif Zapopan/Lab/hdr.php");
    date_default_timezone_set('America/Mexico_City');
    $con = conectar();
    
    if ($_SESSION['padron_admin_activo'] != "activa"){
        header("Location: index.php");
    }
?>

<body>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#profile").on("click", function(){
                $("<form>", {
                    "id": "formeditarperfil",
                    "html": "<input type='hidden' name='id' value='" + <?= $_SESSION['padron_admin_id'] ?> + "'/><input type='hidden' name='self' value='true'/>",
                    "method": "post",
                    "action": "/Lab/DifUsuarios/usuarioeditar.php"
                }).appendTo(document.body).submit();
            });
        });
    </script>
    <div class="container">
        <nav class="navbar-wrapper navbar-default" role="navigation" style="min-height: 60px; height: 60px">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Menu</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a class="navbar-brand dropdown-toggle" data-toggle="dropdown" href="#"><img alt="Brand" src="/Dif Zapopan/Lab/imagenes/LogoDIF.jpg" width="40"/></a>
                        <ul class="dropdown-menu">
                            <li><a href="/Lab/DifUsuarios/usuarios.php">Usuarios</a></li>
                            <li><a href="/Lab/DifIncidencias/incidencias.php">Incidencias</a></li>
                            <li><a href="/Lab/DifBeneficiarios/beneficiarios.php">Beneficiarios</a></li>
                            <li><a href="/Lab/DifServiciosnv/serviciosnv.php">Servicios no vinculantes</a></li>
                            <li><a href="/Lab/DifReportes/reportes.php">Reportes</a></li>
                            <li><a href="/Lab/DifCursos/cursos.php">Cursos y Talleres</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="margin-top: 5px"><?=$_SESSION['padron_admin_usuario']?><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#" id="profile"><span class="glyphicon glyphicon-user"></span> Perfil</a></li>
                            <li><a href="/Lab/logout.php"><span class="glyphicon glyphicon-log-out"></span> Cerrar Sesi&oacute;n</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        
<?php
/*echo "GET: <br/>";
var_dump($_GET);
echo "<br/><br/>POST:<br/>";
var_dump($_POST);
echo "<br/><br/>SESSION:<br/>";
var_dump($_SESSION);
echo "<br/><br/>DIRECTORIO RAIZ:<br/>";
echo (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
echo "<br/><br/>DIRECTORIO DOCUMENTO:<br/>";
echo (__DIR__);
echo "<br/><br/>DIRECTORIO ROOT:<br/>";
ECHO $_SERVER['DOCUMENT_ROOT'];*/
?>