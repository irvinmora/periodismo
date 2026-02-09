<?php
// Cargar configuración centralizada
require_once '../includes/config.php';

// Configurar cabeceras para JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Verificar si es una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido. Solo se aceptan peticiones POST.'
    ]);
    exit();
}

// Obtener y limpiar datos del formulario
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';

// Validar datos
$errors = [];

if (empty($nombre)) {
    $errors[] = 'El nombre es requerido';
} elseif (strlen($nombre) < 2) {
    $errors[] = 'El nombre debe tener al menos 2 caracteres';
} elseif (strlen($nombre) > 100) {
    $errors[] = 'El nombre es demasiado largo (máximo 100 caracteres)';
}

if (empty($email)) {
    $errors[] = 'El email es requerido';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'El email no tiene un formato válido';
} elseif (strlen($email) > 100) {
    $errors[] = 'El email es demasiado largo (máximo 100 caracteres)';
}

if (strlen($comentario) > 500) {
    $errors[] = 'El comentario es demasiado largo (máximo 500 caracteres)';
}

// Si hay errores, devolverlos
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => implode('. ', $errors)
    ]);
    exit();
}

// Verificar conexión a base de datos
if ($conn === false) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error de conexión a la base de datos. Por favor, intenta nuevamente más tarde.'
    ]);
    exit();
}

// Verificar y crear la columna comentario si no existe
$sql_check_column = "SHOW COLUMNS FROM suscriptores LIKE 'comentario'";
$result_column = $conn->query($sql_check_column);

if ($result_column && $result_column->num_rows == 0) {
    // La columna no existe, crearla
    $sql_alter = "ALTER TABLE suscriptores ADD COLUMN comentario TEXT AFTER email";
    if (!$conn->query($sql_alter)) {
        // Log silencioso del error, pero continuamos
        error_log("Error al agregar columna comentario: " . $conn->error);
    }
}

// Usar prepared statement para evitar SQL injection
// Verificar si el email ya existe
$sql_check = "SELECT id FROM suscriptores WHERE email = ?";
$stmt_check = $conn->prepare($sql_check);
if (!$stmt_check) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error en la base de datos'
    ]);
    exit();
}

$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // Email ya existe
    $stmt_check->close();
    
    echo json_encode([
        'success' => false,
        'message' => 'Este email ya está suscrito. ¡Gracias por tu interés!'
    ]);
    exit();
}

$stmt_check->close();

// Insertar nuevo suscriptor con comentario
$sql_insert = "INSERT INTO suscriptores (nombre, email, comentario, fecha_suscripcion) VALUES (?, ?, ?, NOW())";
$stmt_insert = $conn->prepare($sql_insert);
if (!$stmt_insert) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error en la base de datos'
    ]);
    exit();
}

$stmt_insert->bind_param("sss", $nombre, $email, $comentario);

if ($stmt_insert->execute()) {
    // Éxito
    $nuevo_id = $stmt_insert->insert_id;
    $stmt_insert->close();
    
    echo json_encode([
        'success' => true,
        'message' => "¡Suscripción exitosa, $nombre! Te hemos agregado a nuestra lista de suscriptores.",
        'id' => $nuevo_id
    ]);
} else {
    // Error en la inserción
    $stmt_insert->close();
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al guardar la suscripción. Por favor, intenta nuevamente.'
    ]);
}
?>