<?php
/**
 * Sistema de autenticación
 * Verifica si el usuario está autenticado como administrador
 */

function verificarAdmin() {
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
        header('Location: login.php?error=not_authenticated');
        exit('No autenticado');
    }
    return true;
}

function cerrarSesion() {
    $_SESSION = array();
    session_destroy();
}
?>
