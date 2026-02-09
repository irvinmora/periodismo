<?php
// Funciones comunes para el sistema

function conectarDB() {
    $host = 'localhost';
    $usuario = 'root';
    $password = '';
    $database = 'periodismo_utb';
    
    $conn = new mysqli($host, $usuario, $password, $database);
    
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8");
    return $conn;
}

function subirImagen($archivo) {
    if ($archivo['error'] === UPLOAD_ERR_OK) {
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array(strtolower($extension), $extensiones_permitidas)) {
            $nombre_unico = uniqid() . '_' . time() . '.' . $extension;
            $ruta_destino = '../img/uploads/' . $nombre_unico;
            
            if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
                return $nombre_unico;
            }
        }
    }
    return '';
}

function limpiarInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}
?>