<?php
// IMPORTANTE: Esto DEBE ser lo primero, ANTES de cualquier salida HTML
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Archivo de configuración centralizado
 * Contiene la configuración de conexión a la base de datos
 */

// Import connection
require_once dirname(__DIR__) . '/conexion.php';

// Verificar conexión
if ($conn->connect_error) {
    // Para desarrollo, mostrar error si es admin
    if (isset($_SESSION['admin'])) {
        die("Error de conexión a la base de datos: " . $conn->connect_error . 
            "<br><br>Asegúrate de que la base de datos 'periodismo_utb' existe.<br>" .
            "Puedes ejecutar <a href='../crear_db.php'>crear_db.php</a> para crearla.");
    }
    // Para usuarios normales
    $conn = false;
}

// Si la conexión fue exitosa, configurar charset
if ($conn !== false) {
    $conn->set_charset("utf8mb4");
}
?>
