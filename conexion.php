<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'periodismo_utb';

// Variable to avoid establishing connection when we only want to load credentials
// For example, in crear_db.php before the database exists
if (!isset($skip_db_connection) || !$skip_db_connection) {
    @$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    // Si la conexión fue exitosa, configurar charset
    if (!$conn->connect_error) {
        $conn->set_charset("utf8mb4");
    }
}
?>
