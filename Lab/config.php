<?php 
// VARIABLES PARA CONEXION A BASE DE DATOS
$nombredb="pruebas";
$passdb="";
$userdb="root";
$servidordb="localhost";

// NOMBRE DE LAS TABLAS.

// Principales
$personas = "persona";
$trabajadores = "usuario";
$personas_programas = "persona_programas";
$personas_ventanilla_unica = "persona_trabajo_social";
$persona_sicats = "persona_sicats";
$incidencias = "incidencias";
$variables_fijas = "variables_fijas";
$servicios_otorgados = "persona_servicios_otorgados";
$persona_despensas = "persona_despensas";
$persona_despensas_2019 = "persona_despensas_2019";
$persona_despensas_2020 = "persona_despensas_2020";
$persona_despensas_2021 = "persona_despensas_2021";
$serviciosnv = "serviciosnv";
$escpadres = "persona_escuela_padres";
$casoreportado = "persona_caso_reportado";
$tpsicologica = "persona_tpsicologica";
$atenjuridica = "persona_atenjuridica";
$cursos = "cursos";
$estudio_socioeconomico = "estsocio";
$acompanamiento = "acompanamiento";

// Categorias de padron
$derivaciones = "persona_cat_derivacion";
$apoyos = "persona_cat_apoyo";
$colonias = "persona_cat_colonias";
$procedencia = "persona_cat_procedencia";
$detonantes = "persona_cat_detonantes";
$diagnostico = "persona_cat_diagnostico";
$enfermedades = "persona_cat_enfermedad";
$escolaridad = "persona_cat_escolaridad";
$estado_civil = "persona_cat_estado_civil";
$estatus = "persona_cat_estatus";
$grupo_prioritario = "persona_cat_grupo_prioritario";
$idioma = "persona_cat_idioma";
$ocupacion = "persona_cat_ocupacion";
$otros_apoyos = "persona_cat_otros_apoyos";
$paises = "persona_cat_paises";
$parentesco = "persona_cat_parentesco";
$perfil_atencion = "persona_cat_perfil_atencion";
$problematicas = "persona_cat_problematica";
$tipodelito = "persona_cat_tipodedelito";
$servicios = "persona_cat_servicio";
$servicios_medicos = "persona_cat_servicios_medicos";
$sexo = "persona_cat_sexo";
$temporalidad = "persona_cat_temporalidad";
$vivienda = "persona_cat_vivienda";
$locacion = "persona_cat_locacion";
$tipo_despensa = "persona_cat_tipo_despensa";
$comunidades = "persona_cat_comunidades";
$problematicadpna = "persona_cat_problematicadpna";

// Categorias de trabajadores
$departamentos = "usuario_cat_departamento";
$permisos = "usuario_cat_permiso";
$regimen = "usuario_cat_regimen";

// Categorias de incidencias
$incidencia_conceptos = "incidencia_cat_concepto";
$incidencia_estatus = "incidencia_cat_estatus";
$incidencia_fecha_corte = "incidencia_cat_fechas_corte";
$incidencia_tipos = "incidencia_cat_tipo";
$incidencia_secretarias = "incidencia_cat_secretarias";
$incidencia_jefes = "incidencia_cat_jefes";
$hist_activar_captura_ext = "hist_activar_captura_ext";

// Categorias de servicios no vinculantes
$serviciosnv_servicios = "serviciosnv_cat_servicios";

// Categorias de reportes
$tipo_reporte = "cat_reportes";

// Categorias modulo de ventanilla unica
$apoyos_otorgados = "persona_vent_apoyo";
$vent_unica_programa = "vent_unica_cat_programa";

// Categorias de escuela para padres
$escpad_escolaridad = "ecpadres_cat_escolaridad";
$escpad_estado_civil = "ecpadres_cat_estado_civil";
$escpad_frecuencia = "ecpadres_cat_frecuencia";
$escpad_genero = "ecpadres_cat_genero";
$escpad_municipio = "ecpadres_cat_municipios";
$escpad_ocupacion = "ecpadres_cat_ocupacion";
$escpad_tipo_vialidad = "ecpadres_cat_tipo_vialidad";
$escpad_vulnerabilidad = "ecpadres_cat_vulnerabilidad";
$escpad_orientador = "ecpadres_cat_orientador";
$escpad_tipo_apoyo = "ecpadres_cat_tipo_apoyo";
$escpad_tipo_asentamiento = "ecpadres_cat_tipo_asentamiento";
$escpad_discapacidad = "ecpadres_cat_discapacidad";

// Categorias de Procu
$tipo_maltrato = "creportado_cat_tipo_maltrato";
$procedencia_reporte = "creportado_cat_procedencia_reporte";
$tipo_juicio = "creportado_cat_tipo_juicio";
$derechos_nna = "creportado_cat_derechos_nna";
$derechos_vulnerados = "creportado_derechos_vulnerados";
$tipos_maltrato = "creportado_tipo_maltrato";
$delito = "creportado_delito";

// Categorias de terapias psicologicas
$tipo_terapia = "tpsicologica_cat_tipo_terapia";
$tipo_sistema_familiar = "tpsicologica_cat_tipo_sistema_familiar";
$etapa_ciclo_vital_familiar = "tpsicologica_cat_etapa_ciclo_familiar";
$nivel_socioeconomico = "tpsicologica_cat_nivel_socioeconimico";
$comp_familiar = "tpsicologica_comp_familiar";
$tipo_sesion = "tpsicologica_cat_tipo_sesion";
$sesiones = "tpsicologica_sesiones";
$log_reapertura = "tpsicologica_log_reapertura";

// Categorias de cursos
$unidadduracion = "cursos_cat_unidad_duracion";
$aula = "cursos_cat_aulas";
$asistentes = "cursos_asistentes";
$docentes = "cursos_docente";
$esquemacolaboracion = "cursos_cat_esquemacolaboracion";
$asistencias = "cursos_asistencias";

// Categorias de estudio socio familiar
$tipo_vivienda = "estsocio_cat_tipovivenda";
$agua = "estsocio_cat_agua";
$desechos = "estsocio_cat_desechos";
$electricidad = "estsocio_cat_electricidad";
$piso = "estsocio_cat_piso";
$muro = "estsocio_cat_muro";
$techo = "estsocio_cat_techo";
$zona = "estsocio_cat_zona";
$menaje = "estsocio_cat_menaje";
$tipo_inmueble = "estsocio_cat_tipoinmueble";
$periodo_alimentos = "estsocio_cat_periodocomida";
$escolaridad_estsocio = "estsocio_cat_escolaridad";
$estsocio_comp_familiar = "estsocio_familiares";
$estsocio_apoyos = "estsocio_apoyos";
$estsocio_avances = "estsocio_avances";

// Vistas
$vw_servicios = "vw_servicios";
$vw_serviciosnv = "vw_serviciosnv";
$vw_persona = "vw_persona";
$vw_personas = "vw_personas";
$vw_usuarios = "vw_usuarios";
$vw_incidencias = "vw_incidencias";
$vw_exportar_incidencias = "vw_incidenciasexport";
$vw_entrevista_inicial = "vw_entrevista_inicial";
$vw_sicats = "vw_sicats";
$vw_tpsicologica = "vw_tpsicologica";
$vw_atenjuridica = "vw_atenjuridica";
$vw_tpsicologica_sesiones = "vw_tpsicologica_sesiones";
$vw_cursos = "vw_cursos";
$vw_estudio_sociofamiliar = "vw_estsocio";

// Vistas de reportes
$reporte_alimentaria_padron = "vw_reporte_alimentaria_padron";
$reporte_alimentaria_firmas = "vw_reporte_alimentaria_firmas";
$reporte_alimentaria_transparencia = "vw_reporte_alimentaria_transparencia";
$reporte_escpadres_difjalisco = "vw_reporte_escpadres_difjal";
$reporte_servicios_general = "vw_reporte_servicios_general";
$reporte_serviciosnv_general = "vw_reporte_serviciosnv_general";
$reporte_ventanilla_unica = "vw_reporte_ventanilla_unica";

// VARIABLES DE SESSION

$session_id="padron_admin_id";
$session_usuario="padron_admin_usuario";
$session_tipo="padron_admin_tipo";
$session_nombre="padron_admin_nombre";
$session_activo="padron_admin_activo";
$session_area="padron_admin_area";
$session_permisos="padron_admin_permisos";
$session_timeout = 200000; //1200

// BASICOS

$title_sitio="Sistema DIF Zapopan";
$nombre_sistema="DIF Zapopan";
$admin_incidencias = "711,865,1,755,932,495,844,769";
$reportes_planeacion = array(904, 814, 1, 931);
$ver_tpsicologicas = array(905, 729);

// VARIABLES

$fecha_captura_ext = "fecha_captura_ext";

?>