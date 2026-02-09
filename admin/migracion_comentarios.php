<?php
/**
 * Script para migrar tabla de suscriptores
 * Agrega la columna 'comentario' si no existe
 */

require_once 'includes/config.php';

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Migración de Base de Datos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 600px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #1a5fb4; margin-bottom: 20px; }
        .status { margin: 15px 0; padding: 15px; border-radius: 4px; }
        .success { background: #d4edda; border-left: 4px solid #28a745; color: #155724; }
        .error { background: #f8d7da; border-left: 4px solid #dc3545; color: #721c24; }
        .info { background: #d1ecf1; border-left: 4px solid #17a2b8; color: #0c5460; }
        .back-link { margin-top: 20px; }
        a { color: #1a5fb4; text-decoration: none; font-weight: 600; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class='container'>
        <h1><i class='fas fa-database'></i> Migración de Base de Datos</h1>";

// Verificar conexión
if ($conn === false) {
    echo "<div class='status error'>
        ❌ Error de conexión a la base de datos.
        <br>Por favor, verifica que la base de datos está disponible.
    </div>";
    echo "</div></body></html>";
    exit();
}

// Verificar si la tabla suscriptores existe
$sql_check_table = "SHOW TABLES LIKE 'suscriptores'";
$result = $conn->query($sql_check_table);

if ($result && $result->num_rows == 0) {
    // Crear tabla suscriptores
    $sql_create = "CREATE TABLE suscriptores (
        id INT PRIMARY KEY AUTO_INCREMENT,
        nombre VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        comentario TEXT,
        fecha_suscripcion DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_email (email),
        INDEX idx_fecha (fecha_suscripcion)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($sql_create)) {
        echo "<div class='status success'>
            ✅ Tabla 'suscriptores' creada exitosamente.
        </div>";
    } else {
        echo "<div class='status error'>
            ❌ Error al crear la tabla: " . $conn->error . "
        </div>";
    }
} else {
    // Verificar si la columna comentario existe
    $sql_check_column = "SHOW COLUMNS FROM suscriptores LIKE 'comentario'";
    $result = $conn->query($sql_check_column);
    
    if ($result && $result->num_rows == 0) {
        // Agregar columna comentario
        $sql_alter = "ALTER TABLE suscriptores ADD COLUMN comentario TEXT AFTER email";
        
        if ($conn->query($sql_alter)) {
            echo "<div class='status success'>
                ✅ Columna 'comentario' agregada a la tabla 'suscriptores'.
            </div>";
        } else {
            echo "<div class='status error'>
                ❌ Error al agregar la columna: " . $conn->error . "
            </div>";
        }
    } else {
        echo "<div class='status info'>
            ℹ️ La tabla 'suscriptores' ya tiene la columna 'comentario'.
        </div>";
    }
}

echo "<div class='status success'>
    ✅ Migración completada. Ya puedes usar el formulario de suscripción con comentarios.
</div>";

echo "<div class='back-link'>
    <a href='index.php'>← Volver a la página principal</a>
</div>";

echo "</div></body></html>";

$conn->close();
?>";
