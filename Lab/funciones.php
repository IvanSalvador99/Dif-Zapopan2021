<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Dif Zapopan/Lab/config.php");
date_default_timezone_set('America/Mexico_City');
function iniciar_sesion()
{

    global $session_timeout;
    ini_set('session.gc_maxlifetime', $session_timeout);
    session_regenerate_id();
    session_start();
    if (isset($_SESSION['testing']) && (time() - $_SESSION['testing'] > $session_timeout) || !isset($_SESSION['padron_admin_activo'])) {
        $output = "/Lab/logout.php";
        echo "<script>window.location = '" . $output . "'</script>";
    }
    $_SESSION['testing'] = time(); // Update session
}

function conectar()
{
    global $servidordb;
    global $userdb;
    global $passdb;
    global $nombredb;

    if (!($con = mysql_connect($servidordb, $userdb, $passdb))) {
        die("No se conecto correctamente a la base de datos.");
    }
    if (!mysql_select_db($nombredb, $con)) {
        die("No se encuentra la base de datos especificada.");
    } //*/
    mysql_set_charset('utf8', $con);
    return $con;
}

function fillSelect($tabla)
{
    if (!isset($con)) $con = conectar();
    
        $query = "SELECT * FROM $tabla ORDER BY id";

    $output = "";
    if ($result = mysql_query($query, $con)) {
        while ($row = mysql_fetch_row($result)) {
            if (count($row) == 2) {
                $output .= "<option value=\"" . $row[0] . "\">" . $row[1] . "</option>";
            } else {
                $output .= "<option value=\"" . $row[0] . "\">" . $row[1] . " " . $row[2] . "</option>";
            }                        
        }
        return $output;
    } else {
        return "<option>Error en el query: $query</option>";
    }
}

function fillSelectInci($tabla)
{
    if (!isset($con)) $con = conectar();
    $query = "SELECT * FROM $tabla ORDER BY id";
    $output = "";
    if ($result = mysql_query($query, $con)) {
        while ($row = mysql_fetch_row($result)) {
            $output .= "<option value=\"" . $row[0] . "\">" . $row[1] . " </option>";
        }
        return $output;
    } else {
        return "<option>Error en el query: $query</option>";
    }
}

function fillSelectData($tabla, $data)
{
    if (!isset($con)) $con = conectar();
    $query = "SELECT * FROM $tabla ORDER BY id";
    $output = "";
    $hayalguno = false;
    if ($result = mysql_query($query, $con)) {
        while ($row = mysql_fetch_row($result)) {
            if ($row[0] == $data) {
                $output .= "<option value=\"" . $row[0] . "\" selected>" . $row[1] . " " . $row[2] . "</option>";
                $hayalguno = true;
            } else {
                $output .= "<option value=\"" . $row[0] . "\">" . $row[1] . " " . $row[2] . "</option>";
            }
        }
        if (!$hayalguno) {
            $output = "<option value=\"-1\" disabled selected>---Seleccione---</option>" . $output;
        }
        return $output;
    } else {
        return "<option>Error en el query: $query</option>";
    }
}

function fillSelectResult($result)
{
    while ($row = mysql_fetch_row($result)) {
        $output .= "<option value=\"" . $row[0] . "\">" . $row[1] . "</option>";
    }
    return $output;
}

function fillSelectResultData($result, $data)
{
    $hayalguno = false;
    while ($row = mysql_fetch_row($result)) {
        if ($row[0] == $data) {
            $output .= "<option value=\"" . $row[0] . "\" selected>" . $row[1] . "</option>";
            $hayalguno = true;
        } else {
            $output .= "<option value=\"" . $row[0] . "\">" . $row[1] . "</option>";
        }
    }
    if (!$hayalguno) {
        $output = "<option value=\"-1\" disabled selected>---Seleccione---</option>" . $output;
    }
    return $output;
}

function fillSelectWithPadre($tabla)
{
    if (!isset($con)) $con = conectar();
    $query = "SELECT * FROM $tabla ORDER BY id";
    $output = "";
    $flag = false;
    $catalogo = array();
    if ($result = mysql_query($query, $con)) {
        while ($row = mysql_fetch_row($result)) {
            $catalogo[] = $row;
        }
        for ($i = 0; $i < count($catalogo); $i++) {
            if ($catalogo[$i][2] == 0) {
                for ($j = 0; $j < count($catalogo); $j++) {
                    if ($catalogo[$j][2] == $catalogo[$i][0]) {
                        if (!$flag) {
                            $output .= "<optgroup label = \"" . $catalogo[$i][1] . "\">";
                            $output .= "<option value = \"" . $catalogo[$j][0] . "\">" . $catalogo[$j][1] . "</option>";
                            $flag = true;
                        } else {
                            $output .= "<option value = \"" . $catalogo[$j][0] . "\">" . $catalogo[$j][1] . "</option>";
                        }
                    }
                }
                if ($flag) {
                    $output .= "</optgroup>";
                    $flag = false;
                } else {
                    $output .= "<option value = \"" . $catalogo[$i][0] . "\">" . $catalogo[$i][1] . "</option>";
                }
            }
        }
        return $output;
    } else {
        return "<option>Error en el query: $query</option>";
    }
}

function fillSelectDataWithPadre($tabla, $data)
{
    if (!isset($con)) $con = conectar();
    $query = "SELECT * FROM $tabla ORDER BY id";
    $output = "";
    $flag = false;
    $catalogo = array();
    if ($result = mysql_query($query, $con)) {
        while ($row = mysql_fetch_row($result)) {
            $catalogo[] = $row;
        }
        for ($i = 0; $i < count($catalogo); $i++) {
            if ($catalogo[$i][2] == 0) {
                for ($j = 0; $j < count($catalogo); $j++) {
                    if ($catalogo[$j][2] == $catalogo[$i][0]) {
                        if (!$flag) {
                            $output .= "<optgroup label = \"" . $catalogo[$i][1] . "\">";
                            if ($data == $catalogo[$j][0]) {
                                $output .= "<option value = \"" . $catalogo[$j][0] . "\" selected>" . $catalogo[$j][1] . "</option>";
                            } else {
                                $output .= "<option value = \"" . $catalogo[$j][0] . "\">" . $catalogo[$j][1] . "</option>";
                            }
                            $flag = true;
                        } else {
                            if ($data == $catalogo[$j][0]) {
                                $output .= "<option value = \"" . $catalogo[$j][0] . "\" selected>" . $catalogo[$j][1] . "</option>";
                            } else {
                                $output .= "<option value = \"" . $catalogo[$j][0] . "\">" . $catalogo[$j][1] . "</option>";
                            }
                        }
                    }
                }
                if ($flag) {
                    $output .= "</optgroup>";
                    $flag = false;
                } else {
                    if ($data == $catalogo[$i][0]) {
                        $output .= "<option value = \"" . $catalogo[$i][0] . "\" selected>" . $catalogo[$i][1] . "</option>";
                    } else {
                        $output .= "<option value = \"" . $catalogo[$i][0] . "\">" . $catalogo[$i][1] . "</option>";
                    }
                }
            }
        }
        return $output;
    } else {
        return "<option>Error en el query: $query</option>";
    }
}

function fillSelectLocaciones()
{
    global $locacion;
    if (!isset($con)) $con = conectar();
    $query = "SELECT * FROM $locacion ORDER BY id";
    $output = "";
    $flag = false;
    $catalogo = array();
    if ($result = mysql_query($query, $con)) {
        while ($row = mysql_fetch_assoc($result)) {
            $catalogo[] = $row;
        }
        for ($i = 0; $i < count($catalogo); $i++) {
            if ($catalogo[$i]['padre'] == 0) {
                for ($j = 0; $j < count($catalogo); $j++) {
                    if ($catalogo[$j]['padre'] == $catalogo[$i]['id']) {
                        if (!$flag) {
                            $output .= "<optgroup label = \"" . $catalogo[$i]['locacion'] . "\">";
                            $output .= "<option value = \"" . $catalogo[$j]['id'] . "\">" . $catalogo[$j]['locacion'] . "</option>";
                            $flag = true;
                        } else {
                            $output .= "<option value = \"" . $catalogo[$j]['id'] . "\">" . $catalogo[$j]['locacion'] . "</option>";
                        }
                    }
                }
                if ($flag) {
                    $output .= "</optgroup>";
                    $flag = false;
                } else {
                    $output .= "<option value = \"" . $catalogo[$i]['id'] . "\">" . $catalogo[$i]['locacion'] . "</option>";
                }
            }
        }
        return $output;
    } else {
        return "<option>Error en el query: $query</option>";
    }
}

function fillSelectLocacionesData($lugar)
{
    global $locacion;
    if (!isset($con)) $con = conectar();
    $query = "SELECT * FROM $locacion ORDER BY id";
    $output = "";
    $flag = false;
    $catalogo = array();
    if ($result = mysql_query($query, $con)) {
        while ($row = mysql_fetch_assoc($result)) {
            $catalogo[] = $row;
        }
        for ($i = 0; $i < count($catalogo); $i++) {
            if ($catalogo[$i]['padre'] == 0) {
                for ($j = 0; $j < count($catalogo); $j++) {
                    if ($catalogo[$j]['padre'] == $catalogo[$i]['id']) {
                        if (!$flag) {
                            $output .= "<optgroup label = \"" . $catalogo[$i]['locacion'] . "\">";
                            if ($lugar == $catalogo[$j]['id']) {
                                $output .= "<option value = \"" . $catalogo[$j]['id'] . "\" selected>" . $catalogo[$j]['locacion'] . "</option>";
                            } else {
                                $output .= "<option value = \"" . $catalogo[$j]['id'] . "\">" . $catalogo[$j]['locacion'] . "</option>";
                            }
                            $flag = true;
                        } else {
                            if ($lugar == $catalogo[$j]['id']) {
                                $output .= "<option value = \"" . $catalogo[$j]['id'] . "\" selected>" . $catalogo[$j]['locacion'] . "</option>";
                            } else {
                                $output .= "<option value = \"" . $catalogo[$j]['id'] . "\">" . $catalogo[$j]['locacion'] . "</option>";
                            }
                        }
                    }
                }
                if ($flag) {
                    $output .= "</optgroup>";
                    $flag = false;
                } else {
                    if ($lugar == $catalogo[$i]['id']) {
                        $output .= "<option value = \"" . $catalogo[$i]['id'] . "\" selected>" . $catalogo[$i]['locacion'] . "</option>";
                    } else {
                        $output .= "<option value = \"" . $catalogo[$i]['id'] . "\">" . $catalogo[$i]['locacion'] . "</option>";
                    }
                }
            }
        }
        return $output;
    } else {
        return "<option>Error en el query: $query</option>";
    }
}

function mysql_este($sql, $campo, $con)
{
    if ($result = mysql_query($sql, $con)) {
        if ($row = mysql_fetch_array($result)) {
            return $row[$campo];
        } else {
            return mysql_error();
        }
    } else {
        return mysql_error();
    }
}

function obtenerFechaInicio()
{
    global $incidencia_fecha_corte;
    if (!isset($con)) $con = conectar();

    $hoy = time();
    $periodoactual = ((date("j") < 16) ? date("n") * 2 - 1 : date("n") * 2);
    $periodocorte = $periodoactual - 1;
    //$periodocorte = $periodoactual;
    $dia16 = strtotime("first day of 00:00:00") + 60 * 60 * 24 * 15;
    $dia1 = strtotime("first day of 00:00:00");
    $dia16previo = strtotime("first day of last month 00:00:00") + 60 * 60 * 24 * 15;

    /*echo "</br></br>Periodo actual: ",$periodoactual; 
    echo "</br>Periodo de corte: ", $periodocorte;
    echo "</br>Dia 16 del mes anterior: ", $dia16previo, " --- ", date("Y-m-d", $dia16previo);
    echo "</br>Dia 1 de este mes: ", $dia1, " --- ", date("Y-m-d", $dia1);
    echo "</br>Dia 16 de este mes: ", $dia16, " --- ", date("Y-m-d", $dia16);*/

    if ($periodoactual % 2 == 0) {
        /*$query = "SELECT * FROM $incidencia_fecha_corte WHERE id = '" . $periodocorte . "'";
        if ($result = mysql_query($query, $con)) {
            $rowfecha = mysql_fetch_array($result);
            if ($hoy < strtotime($rowfecha['fechacorte']) + 60 * 60 * 24) {
                $diainicio = $dia1;
            } else {
                $diainicio = $dia16;
            }
        } else {
            echo "Error en el query: " . $query . ", Error: " . mysql_error();
        }*/
        $diainicio = $dia16;
    } else {
        /*$query = "SELECT * FROM $incidencia_fecha_corte WHERE id = '" . $periodocorte . "'";
        if ($result = mysql_query($query, $con)) {
            $rowfecha = mysql_fetch_array($result);
            if ($hoy < strtotime($rowfecha['fechacorte']) + 60 * 60 * 24) {
                $diainicio = $dia16previo;
            } else {
                $diainicio = $dia1;
            }
        } else {
            echo "Error en el query: " . $query . ", Error: " . mysql_error();
        }*/
        $diainicio = $dia1;
    }
    return $diainicio;
}

function isFechasAbiertas()
{
    global $variables_fijas;
    global $fecha_captura_ext;
    if (!isset($con)) $con = conectar();

    $query = "SELECT * FROM $variables_fijas WHERE nombre='$fecha_captura_ext'";
    if ($result = mysql_query($query, $con)) {
        $row = mysql_fetch_array($result);
        if (time() < $row['valor']) {
            return "true";
        } else {
            return "false";
        }
    } else {
        return "Error en el query: " . $query . ", Error: " . mysql_error();
    }
}

function existePersona($id, $tabla)
{
    global $personas;
    global $personas_programas;
    global $personas_trabajo_social;
    global $servicios_otorgados;
    if (!isset($con)) $con = conectar();

    if ($tabla == "programas") {
        $query = "SELECT * FROM (SELECT id FROM $personas UNION ALL SELECT idpersona FROM $personas_programas) a WHERE id = '$id'";
    } elseif ($tabla == "ts") {
        $query = "SELECT * FROM (SELECT id FROM $personas UNION ALL SELECT idpersona FROM $personas_trabajo_social) a WHERE id = '$id'";
    } elseif ($tabla == "serv") {
        $query = "SELECT * FROM (SELECT id FROM $personas UNION ALL SELECT idpersona FROM $servicios_otorgados) a WHERE id = '$id'";
    }

    if ($result = mysql_query($query, $con)) {
        if (mysql_num_rows($result) >= 2) {
            return true;
        } else {
            return false;
        }
    }
}

function perteneceA($id, $idPadre)
{
    global $personas;
    global $departamentos;
    if (!isset($con)) $con = conectar();

    $query = "SELECT * FROM $departamentos WHERE padre = '$idPadre'";
    $resultado = false;

    if ($id == $idPadre) {
        $resultado = true;
    } else {
        if ($result = mysql_query($query, $con)) {
            while ($row = mysql_fetch_array($result)) {
                if ($row['id'] == $id) {
                    $resultado = true;
                }
            }
        }
    }
    return $resultado;
}

function invierte_fecha($fechita)
{
    if ($fechita != "") {
        if (strpos($fechita, '-') > 0) {
            $fecha = explode('-', $fechita);
        } else {
            $fecha = explode('/', $fechita);
        }
        $dia = $fecha[2];
        $mes = $fecha[1];
        $anio = $fecha[0];
        $fechita = $dia . "/" . $mes . "/" . $anio;
    }
    return $fechita;
}

function enviamail($destino, $nombredestino, $asunto, $mensaje)
{
    require_once($_SERVER['DOCUMENT_ROOT'] . '/Tools/PHPMailer/PHPMailerAutoload.php');

    //Create a new PHPMailer instance
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = "sistemas@difzapopan.gob.mx";
    //Password to use for SMTP authentication
    $mail->Password = "sr5415la";
    //Set who the message is to be sent from
    $mail->setFrom('sistemas@difzapopan.gob.mx', 'Sistema de incidencias');
    //Set an alternative reply-to address
    $mail->addReplyTo('mygonzalez@difzapopan.gob.mx', 'Mayab González');
    //Set who the message is to be sent to
    $mail->addAddress($destino, $nombredestino);
    //Set the subject line
    $mail->Subject = $asunto;
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $mail->msgHTML($mensaje);
    //Replace the plain text body with one created manually
    $mail->AltBody = 'This is a plain-text message body';
    //send the message, check for errors
    if (!$mail->send()) {
        return "Mailer Error: " . $mail->ErrorInfo;
    } else {
        return "Message sent!";
    }
}

function exportarCSV($query)
{
    global $vw_exportar_incidencias;

    $result = mysql_query("SELECT * FROM $vw_exportar_incidencias");
    if (!$result) die('Couldn\'t fetch records');
    $num_fields = mysql_num_fields($result);
    $headers = array();
    for ($i = 0; $i < $num_fields; $i++) {
        $headers[] = mysql_field_name($result, $i);
    }
    $fp = fopen('php://output', 'w');
    if ($fp && $result) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="export_' . date("Y-m-d") . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        fputs($fp, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
        fputcsv($fp, $headers);
        while ($row = mysql_fetch_row($result)) {
            fputcsv($fp, array_values($row));
        }
        die;
    }
}

function deptosubordinado($padre)
{
    global $departamentos;

    $query = "SELECT * FROM $departamentos WHERE padre = '$padre' OR id = '$padre' ORDER BY id";
    if (!isset($con)) $con = conectar();

    if ($result = mysql_query($query, $con)) {
        return $result;
    } else {
        return "Error en query";
    }
}

function alerta_bota($mensaje, $url)
{
?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#avisomensaje").html('<?= $mensaje; ?>');
            $('#ModalAviso').modal();

            $('#ModalAviso').on('hidden.bs.modal', function() {
                if ('<?= $url ?>' == 0) {

                } else if ('<?= $url ?>' == 1) {
                    window.close();
                } else {
                    window.location.href = '<?= $url; ?>';
                }
            });
        });
    </script>

    <div class="modal fade" id="ModalAviso">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <!--Modal Header-->
                <div class="modal-body" id="avisomensaje">
                </div>
                <!--Modal Body-->
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Cerrar</a>
                </div>
                <!--Modal Footer-->
            </div>
            <!--Modal Content-->
        </div>
        <!--Modal Dialog-->
    </div>
    <!--Modal Fade-->
<?php
}

function halfString($string, $part)
{
    $words = explode(" ", $string);
    $half = floor(count($words) / 2);
    $output = "";

    if (count($words) == 1) {
        return $string;
    } else {
        if ($part == "low") {
            for ($i = 0; $i < $half; $i++) {
                $output .= $words[$i] . " ";
            }
        } elseif ($part == "high") {
            for ($i = $half; $i  < count($words); $i++) {
                $output .= $words[$i] . " ";
            }
        } else {
            for ($i = 0; $i  < $half; $i++) {
                $output .= $words[$i] . " ";
            }
        }
        return strtoupper($output);
    }
}

function dameFecha($string, $key)
{
    $partes = explode("-", $string);
    switch ($key) {
        case 'd':
            return $partes[2];
            break;
        case 'm':
            return $partes[1];
            break;
        case 'y':
            return $partes[0];
            break;
        case 'all':
            return $partes[2] . "-" . $partes[1] . "-" . $partes[0];
            break;
        default:
            break;
    }
}

function esAdminIncidencias($id)
{
    global $admin_incidencias;
    $result = FALSE;

    $validos = explode(",", $admin_incidencias);

    if (in_array($id, $validos)) {
        $result = TRUE;
    }

    return $result;
}

function fillSelectAula()
{
    global $aula;
    global $locacion;

    if (!isset($con)) $con = conectar();
    $query = "SELECT * FROM $locacion";
    $output = "";
    $locaciones = array();
    $loctemp = 0;
    $flag = FALSE;
    if ($result = mysql_query($query, $con)) {
        while ($rowloc = mysql_fetch_assoc($result)) {
            $locaciones[$rowloc['id']] = $rowloc;
        }
        $query = "SELECT * FROM $aula";

        if ($result = mysql_query($query, $con)) {
            while ($rowaula = mysql_fetch_assoc($result)) {
                if (!$flag) {
                    $loctemp = $rowaula['idlocacion'];
                    $output .= "<optgroup label='" . $locaciones[$rowaula['idlocacion']]['locacion'] . "'>";
                    $output .= "<option value='" . $rowaula['id'] . "'>" . $rowaula['aula'] . "</option>";
                    $flag = TRUE;
                } else {
                    if ($rowaula['idlocacion'] == $loctemp) {
                        $output .= "<option value='" . $rowaula['id'] . "'>" . $rowaula['aula'] . "</option>";
                    } else {
                        $loctemp = $rowaula['idlocacion'];
                        $output .= "<optgroup label='" . $locaciones[$rowaula['idlocacion']]['locacion'] . "'>";
                        $output .= "<option value='" . $rowaula['id'] . "'>" . $rowaula['aula'] . "</option>";
                    }
                }
            }
            return $output;
        } else {
            return "<option>Error en el query: $query</option>";
        }
    } else {
        return "<option>Error en el query: $query</option>";
    }
}

function dividirString($string, $charnum)
{
    $salida = array();
    $strings = explode("*.*.*", wordwrap($string, $charnum, "*.*.*"));

    $salida[] = $strings[0];

    $strings[0] = "";
    $salida[] = implode(" ", $strings);

    return $salida;
}

/*#################################################################  GENERA LA CURP ########################################################################*/

function quitar_acentos($cadena)
{
    $cadena = str_replace("�", "a", $cadena);
    $cadena = str_replace("�", "e", $cadena);
    $cadena = str_replace("�", "i", $cadena);
    $cadena = str_replace("�", "o", $cadena);
    $cadena = str_replace("�", "u", $cadena);
    $cadena = str_replace("�", "A", $cadena);
    $cadena = str_replace("�", "E", $cadena);
    $cadena = str_replace("�", "I", $cadena);
    $cadena = str_replace("�", "O", $cadena);
    $cadena = str_replace("�", "U", $cadena);
    $cadena = str_replace("'", "�", $cadena);
    $cadena = str_replace("%", "", $cadena);
    return $cadena;
}  // Fin funci�n cadena.

function cambiar_acentos($cadena)
{
    $cadena = str_replace("�", "�", $cadena);
    $cadena = str_replace("�", "�", $cadena);
    $cadena = str_replace("�", "�", $cadena);
    $cadena = str_replace("�", "�", $cadena);
    $cadena = str_replace("�", "�", $cadena);
    $cadena = str_replace("�", "�", $cadena);
    $cadena = str_replace("�", "�", $cadena);
    $cadena = str_replace("�", "�", $cadena);
    $cadena = str_replace("�", "�", $cadena);
    $cadena = str_replace("�", "�", $cadena);
    $cadena = str_replace("'", "�", $cadena);
    $cadena = str_replace("%", "", $cadena);
    return $cadena;
}  // Fin funci�n cadena.

// Obtiene la primer consonante que hay despu�s de la primer letra de la palabra.
function es_consonante($letra)
{
    if (
        $letra == 'B' or $letra == 'C' or $letra == 'D' or $letra == 'F' or $letra == 'G' or $letra == 'H' or
        $letra == 'J' or $letra == 'K' or $letra == 'L' or $letra == 'M' or $letra == 'N' or $letra == 'P' or
        $letra == 'Q' or $letra == 'R' or $letra == 'S' or $letra == 'T' or $letra == 'V' or $letra == 'W' or
        $letra == 'X' or $letra == 'Y' or $letra == 'Z' or $letra == '�'
    )
        return true;
    else return false;
}  // Fin función es_consonante.

// Obtiene la primer consonante que hay después de la primer letra de la palabra.
function primer_consonante($cosa)
{
    // Se toma la primer palabra de la cosa:
    $arreglo = explode(" ", $cosa);
    $cosa = $arreglo[0];

    $letra = "X";
    $lon = strlen($cosa);
    for ($i = 1; $i < $lon; $i++) {  // Empieza el for desde 1 y no desde cero para no tomar en cuenta la primer letra.
        $letra = strtoupper($cosa[$i]);
        if (es_consonante($letra) && $letra == "�")
            return "X";
        if (es_consonante($letra))
            return $letra;
    }  // Fin for.
    return $letra;  // Retorna X si no hubo consonante.
}  // Fin función primer_consonante.

// Se toma la primer letra del apellido paterno y la primer vocal después de esa letra.
function primeros_dos($appat)
{
    // Se toma la primer palabra del apellido:

    $arreglo = explode(" ", $appat);
    $appat = $arreglo[0];

    $c1 = substr($appat, 0, 1);    // Se toma la primera letra del apellido paterno.
    if ($c1 == "�") $c1 = "X";
    $c2 = "X";
    $lon = strlen($appat);
    for ($i = 1; $i < $lon; $i++) {  // Empieza el for desde 1 y no desde cero para no tomar en cuenta la primer vocal.
        $aux = strtoupper($appat[$i]);
        if ($aux == 'A' or $aux == '�' or $aux == '�') {
            $aux = "A";
            $i = $lon;  // Para interrumpir el ciclo for.
        }
        if ($aux == 'E' or $aux == '�' or $aux == '�') {
            $aux = "E";
            $i = $lon;  // Para interrumpir el ciclo for.
        }
        if ($aux == 'I' or $aux == '�' or $aux == '�') {
            $aux = "I";
            $i = $lon;  // Para interrumpir el ciclo for.
        }
        if ($aux == 'O' or $aux == '�' or $aux == '�') {
            $aux = "O";
            $i = $lon;  // Para interrumpir el ciclo for.
        }
        if ($aux == 'U' or $aux == '�' or $aux == '�') {
            $aux = "U";
            $i = $lon;  // Para interrumpir el ciclo for.
        }
        $c2 = $aux;
    }  // Fin for.
    //  if ($c2=="") $c2="X";
    return $c1 . $c2;
}  // Fin función primeros_dos.

function valida_altisonantes($palabra, $con)
{
    $sql = "SELECT palabra_correcta FROM curp_altisonantes WHERE palabra_mala like '$palabra'";
    $result = mysql_query($sql, $con);
    if ($row = mysql_fetch_array($result)) {
        return $row["palabra_correcta"];
    } else return $palabra;
}  // Fin funci�n valida_altisonantes.

function es_preposicion($prep)
{
    if (
        $prep == "DA"  or $prep == "DAS" or $prep == "DEL" or $prep == "DER" or $prep == "DI"  or
        $prep == "DIE" or $prep == "DD"  or $prep == "EL"  or $prep == "LA"  or $prep == "LOS" or
        $prep == "LAS" or $prep == "LE"  or $prep == "LES" or $prep == "MAC" or $prep == "VAN" or
        $prep == "VON" or $prep == "I"   or $prep == "DE"  or $prep == "MC"
    )  return true;
    else return false;
}  // Fin funci�n es_proposicion.

function nombre_bien($nombre)
{
    $nombre_sin_prep = "";
    $nombre_definitivo = "";
    if ($nombre == "MAR�A" or $nombre == "MARIA" or $nombre == "JOS�" or $nombre == "JOSE")
        $nombre_bien = "X";
    else {
        // Aqu� se le quitan las preposiciones:
        $palabras = explode(" ", $nombre);
        $j = count($palabras);
        for ($i = 0; $i < $j; $i++) {
            if (!es_preposicion($palabras[$i]))
                $nombre_sin_prep .= $palabras[$i] . ' ';
        }  // Fin for.

        // Aqu� se limpian los Mar�a y Jos�.
        $palabras = explode(" ", $nombre_sin_prep);
        $j = count($palabras);
        if (($palabras[0] == "MAR�A" or $palabras[0] == "MARIA" or $palabras[0] == "JOS�" or $palabras[0] == "JOSE") and ($palabras[1] != "")) {
            for ($i = 1; $i < $j; $i++)
                $nombre_definitivo .= $palabras[$i] . ' ';
        } else  $nombre_definitivo = $nombre_sin_prep;

        // Para quitarle el �ltimo espacio:
        $lon = strlen($nombre_definitivo);
        $nombre_definitivo = substr($nombre_definitivo, 0, $lon - 1);
        $nombre_definitivo = quita_caracteres_raros($nombre_definitivo);
        return $nombre_definitivo;
    }  // Fin else.
}  // Fin funci�n nombre_bien.

function apellido_bien($apellido)
{
    $apellido_sin_prep = "";
    // Aqu� se le quitan las preposiciones:
    $palabras = explode(" ", $apellido);
    $j = count($palabras);
    for ($i = 0; $i < $j; $i++) {
        if (!es_preposicion($palabras[$i]))
            $apellido_sin_prep .= $palabras[$i] . ' ';
    }  // Fin for.
    // Para quitarle el �ltimo espacio:
    $lon = strlen($apellido_sin_prep);
    $apellido_sin_prep = substr($apellido_sin_prep, 0, ($lon - 1));

    $apellido_sin_prep = quita_caracteres_raros($apellido_sin_prep);


    return $apellido_sin_prep;
}  // Fin funci�n apellido_bien.

function es_letra($letra)
{
    if (
        $letra == 'A' or $letra == '�' or $letra == 'B' or $letra == 'C' or $letra == 'D' or $letra == 'E' or
        $letra == '�' or $letra == 'F' or $letra == 'G' or $letra == 'H' or $letra == 'I' or $letra == '�' or
        $letra == 'J' or $letra == 'K' or $letra == 'L' or $letra == 'M' or $letra == 'N' or $letra == '�' or
        $letra == 'O' or $letra == '�' or $letra == 'P' or $letra == 'Q' or $letra == 'R' or $letra == 'S' or
        $letra == 'T' or $letra == 'U' or $letra == '�' or $letra == 'V' or $letra == 'W' or $letra == 'X' or
        $letra == 'Y' or $letra == 'Z'
    )
        return true;
    else return false;
}  // Fin funci�n es_letra.

// Reemplaza caracteres raros y los reemplaza por espacio.
function quita_caracteres_raros($cosa)
{

    $cosa_limpia = "";
    $lon = strlen($cosa);
    for ($i = 0; $i < $lon; $i++) {
        $aux = $cosa[$i];
        if (!es_letra($aux)) $aux = ' ';
        $cosa_limpia .= $aux;
    }  // Fin for.
    return $cosa_limpia;
}  // fin funci�n quita_caracteres_raros.

function generar_CURP($appat, $apmat, $nombre, $fecha_nac, $sexo, $edo_nac, $con)
{

    $appat = apellido_bien($appat);

    $_12 = primeros_dos($appat);
    if ($apmat == "") {
        $consonante_apmat = "X";
        $_3 = "X";
    } else {
        $apmat = apellido_bien($apmat);
        $_3 = substr($apmat, 0, 1);
        $consonante_apmat = primer_consonante($apmat);
    }
    $nombre = nombre_bien($nombre);
    $_4 = substr($nombre, 0, 1);
    $desbaratada = explode("-", $fecha_nac);
    $dia = $desbaratada[2];
    $mes = $desbaratada[1];
    $anio = substr($desbaratada[0], -2);  // Toma los dos ï¿½ltimos dï¿½gitos del aï¿½o.
    $anio2 = $desbaratada[0];
    if ($anio2 < 2000) {
        $pen = 0;
    } else {
        $pen = "A";
    }
    $edo_nac = mysql_este("select abr_curp from tcat_estados where id = '$edo_nac'", "abr_curp", $con);
    $consonante_appat = primer_consonante($appat);
    $consonante_nombre = primer_consonante($nombre);
    $primeras_cuatro = valida_altisonantes($_12 . $_3 . $_4, $con);
    $CURP = $primeras_cuatro . $anio . $mes . $dia . $sexo . $edo_nac . $consonante_appat .
        $consonante_apmat . $consonante_nombre . $pen;

    $CURP = quitar_acentos($CURP);

    $curp_1 = $CURP[0];
    $valor1 = mysql_este("select valor from curp_ultimo_digito where caracter='$curp_1'", "valor", $con);
    $res1 = 18 * $valor1;

    $curp_2 = $CURP[1];
    $valor2 = mysql_este("select valor from curp_ultimo_digito where caracter='$curp_2'", "valor", $con);
    $res2 = 17 * $valor2;

    $curp_3 = $CURP[2];
    $valor3 = mysql_este("select valor from curp_ultimo_digito where caracter='$curp_3'", "valor", $con);
    $res3 = 16 * $valor3;

    $curp_4 = $CURP[3];
    $valor4 = mysql_este("select valor from curp_ultimo_digito where caracter='$curp_4'", "valor", $con);
    $res4 = 15 * $valor4;

    $curp_5 = $CURP[4];
    $valor5 = mysql_este("select valor from curp_ultimo_digito where caracter='$curp_5'", "valor", $con);
    $res5 = 14 * $valor5;

    $curp_6 = $CURP[5];
    $valor6 = mysql_este("select valor from curp_ultimo_digito where caracter='$curp_6'", "valor", $con);
    $res6 = 13 * $valor6;

    $curp_7 = $CURP[6];
    $valor7 = mysql_este("select valor from curp_ultimo_digito where caracter='$curp_7'", "valor", $con);
    $res7 = 12 * $valor7;

    $curp_8 = $CURP[7];
    $valor8 = mysql_este("select valor from curp_ultimo_digito where caracter='$curp_8'", "valor", $con);
    $res8 = 11 * $valor8;

    $curp_9 = $CURP[8];
    $valor9 = mysql_este("select valor from curp_ultimo_digito where caracter='$curp_9'", "valor", $con);
    $res9 = 10 * $valor9;

    $curp_10 = $CURP[9];
    $valor10 = mysql_este("select valor from curp_ultimo_digito where caracter='$curp_10'", "valor", $con);
    $res10 = 9 * $valor10;

    $curp_11 = $CURP[10];
    $valor11 = mysql_este("select valor from curp_ultimo_digito where caracter='$curp_11'", "valor", $con);
    $res11 = 8 * $valor11;

    $curp_12 = $CURP[11];
    $valor12 = mysql_este("select valor from curp_ultimo_digito where caracter='$curp_12'", "valor", $con);
    $res12 = 7 * $valor12;

    $curp_13 = $CURP[12];
    $valor13 = mysql_este("select valor from curp_ultimo_digito where caracter='$curp_13'", "valor", $con);
    $res13 = 6 * $valor13;

    $curp_14 = $CURP[13];
    $valor14 = mysql_este("select valor from curp_ultimo_digito where caracter='$curp_14'", "valor", $con);
    $res14 = 5 * $valor14;

    $curp_15 = $CURP[14];
    $valor15 = mysql_este("select valor from curp_ultimo_digito where caracter='$curp_15'", "valor", $con);
    $res15 = 4 * $valor15;

    $curp_16 = $CURP[15];
    $valor16 = mysql_este("select valor from curp_ultimo_digito where caracter='$curp_16'", "valor", $con);
    $res16 = 3 * $valor16;

    $curp_17 = $CURP[16];
    $valor17 = mysql_este("select valor from curp_ultimo_digito where caracter='$curp_17'", "valor", $con);
    $res17 = 2 * $valor17;

    $suma = $res1 + $res2 + $res3 + $res4 + $res5 + $res6 + $res7 + $res8 + $res9 + $res10 + $res11 + $res12 + $res13 + $res14 + $res15 + $res16 + $res17;
    $division = (int)($suma / 10);

    $residuo = $suma - ($division * 10);

    $ultimo = 10 - $residuo;

    $CURP = $CURP . $ultimo;


    return $CURP;
}  // Fin generar_CURP.
/*#################################################################  GENERA LA CURP ########################################################################*/

/*if ($row['tienehijos'] == 0 && $row['padre'] == 0) {
                if ($flag) {
                    $output .= "</optgroup><option value = '" . $row['id'] . "'>" . $row['servicio'] . "</option>";
                    $flag = false;
                } else {
                    $output .= "<option value = '" . $row['id'] . "'>" . $row['servicio'] . "</option>";
                }
            } elseif ($row['tienehijos'] == 0 && $row['padre'] > 0) {
                $output .= "<option value = '" . $row['id'] . "'>" . $row['servicio'] . "</option>";
            } elseif ($row['tienehijos'] > 0) {
                if ($flag) {
                    $output .= "</optgroup><optgroup label = '" . $row['servicio'] . "'>";
                } else {
                    $output .= "<optgroup label = '" . $row['servicio'] . "'>";
                    $flag = true;
                }   
            }*/
