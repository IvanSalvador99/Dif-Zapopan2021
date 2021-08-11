<?php
    require_once("nav.php");
?>
<style>
    .cuadro {
        padding: 5px;
    }

    .cuadro a {
        color: #000000;
        text-decoration: none;
    }
    
    .cuadro h2 {
        margin: 0;
    }
    
    .cuadrito {
        margin: 10px;
        padding: 10px;
        border-radius: 15px;
        background: #EEEEEE;
    }
</style>
        <div class="row cuadro text-center">
            <div class="col-md-4 col-sm-12">
                <div class="cuadrito">
                    <a href="DifUsuarios/usuarios.php">
                        <h2>Usuarios</h2>
                        <img src="imagenes/LogoDIF.jpg" width="100" height="100"/>
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="cuadrito">
                    <a href="DifIncidencias/incidencias.php">
                        <h2>Incidencias</h2>
                        <img src="imagenes/LogoDIF.jpg" width="100" height="100"/>
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="cuadrito">
                    <a href="DifBeneficiarios/beneficiarios.php">
                        <h2>Beneficiarios</h2>
                        <img src="imagenes/LogoDIF.jpg" width="100" height="100"/>
                    </a>
                </div>
            </div>
        </div>
        <div class="row cuadro text-center">
            <div class="col-md-4 col-sm-12">
                <div class="cuadrito">
                    <a href="DifServiciosnv/serviciosnv.php">
                        <h2>Servicios no vinculantes</h2>
                        <img src="imagenes/LogoDIF.jpg" width="100" height="100"/>
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="cuadrito">
                    <a href="DifReportes/reportes.php">
                        <h2>Reportes</h2>
                        <img src="imagenes/LogoDIF.jpg" width="100" height="100"/>
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="cuadrito">
                    <a href="DifCursos/cursos.php">
                        <h2>Cursos y Talleres</h2>
                        <img src="imagenes/LogoDIF.jpg" width="100" height="100"/>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>