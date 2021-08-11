<?php
session_start();
require_once "funciones.php";
require_once "hdr.php";
if (isset($_SESSION[$session_id])) {
    $_SESSION = array();   
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    echo alerta_bota("Sesion cerrada correctamente", "index.php");
} else {
    echo alerta_bota("Sesion cerrada correctamente", "index.php");
}
?>