<?php
/**
 * Script de migración para agregar campos de multimedia y subtítulos
 * Este script agrega nuevos campos a la tabla noticias para soportar:
 * - Descripción expandida (500+ caracteres)
 * - Contenido completo (5000+ caracteres)
 * - Multimedia de la noticia principal (imagen, video, audio, link)
 * - Tabla de subtítulos con sus propios contenidos y multimedia
 */

echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "    <meta charset='UTF-8'>";
echo "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "    <title>Migración de Base de Datos - Periodismo UTB</title>";
echo "    <link rel='stylesheet' href='css/style.css'>";
echo "    <style>";
echo "        .migration-container { max-width: 900px; margin: 30px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }";
echo "        .migration-title { text-align: center; color: #333; margin-bottom: 30px; }";
echo "        .success-msg { background: #4CAF50; color: white; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo "        .error-msg { background: #f44336; color: white; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo "        .warning-msg { background: #ff9800; color: white; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo "        .info-msg { background: #2196F3; color: white; padding: 15px; border-radius: 5px; margin: 10px 0; }";
echo "    </style>";
echo "</head>";
echo "<body>";
echo "<div class='migration-container'>";
echo "<div class='migration-title'>";
echo "<h1><i class='fas fa-database'></i> Migración de Base de Datos</h1>";
echo "<p>Agregando soporte para multimedia y subtítulos</p>";
echo "</div>";

try {
    // Conexión a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'periodismo_utb');
    
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    echo "<div class='info-msg'><i class='fas fa-info-circle'></i> Conectado a la base de datos</div>";
    
    // 1. Verificar y agregar campos a tabla noticias si no existen
    echo "<h2 style='color: #1a5fb4; border-bottom: 2px solid #1a5fb4; padding-bottom: 10px;'>Actualizando tabla 'noticias'...</h2>";
    
    $campos_a_agregar = [
        'descripcion_larga' => "ALTER TABLE noticias ADD COLUMN descripcion_larga LONGTEXT AFTER descripcion",
        'contenido_completo' => "ALTER TABLE noticias ADD COLUMN contenido_completo LONGTEXT AFTER contenido",
        'video_principal' => "ALTER TABLE noticias ADD COLUMN video_principal VARCHAR(500) AFTER imagen",
        'audio_principal' => "ALTER TABLE noticias ADD COLUMN audio_principal VARCHAR(500) AFTER video_principal",
        'link_principal' => "ALTER TABLE noticias ADD COLUMN link_principal VARCHAR(500) AFTER audio_principal"
    ];
    
    foreach ($campos_a_agregar as $campo => $sql) {
        // Verificar si el campo ya existe
        $check = $conn->query("SHOW COLUMNS FROM noticias LIKE '$campo'");
        
        if ($check && $check->num_rows == 0) {
            if ($conn->query($sql)) {
                echo "<div class='success-msg'><i class='fas fa-check'></i> Campo '$campo' agregado correctamente</div>";
            } else {
                echo "<div class='error-msg'><i class='fas fa-times'></i> Error al agregar campo '$campo': " . $conn->error . "</div>";
            }
        } else {
            echo "<div class='warning-msg'><i class='fas fa-info'></i> El campo '$campo' ya existe</div>";
        }
    }
    
    // 2. Crear tabla de subtítulos
    echo "<h2 style='color: #1a5fb4; border-bottom: 2px solid #1a5fb4; padding-bottom: 10px; margin-top: 30px;'>Creando tabla 'noticias_subtitulos'...</h2>";
    
    $sql_subtitulos = "CREATE TABLE IF NOT EXISTS noticias_subtitulos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        noticia_id INT NOT NULL,
        numero_subtitulo INT NOT NULL,
        subtitulo VARCHAR(255) NOT NULL,
        descripcion LONGTEXT NOT NULL,
        contenido LONGTEXT NOT NULL,
        imagen VARCHAR(255),
        video VARCHAR(500),
        audio VARCHAR(500),
        link VARCHAR(500),
        orden INT DEFAULT 0,
        fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (noticia_id) REFERENCES noticias(id) ON DELETE CASCADE,
        UNIQUE KEY unique_subtitulo (noticia_id, numero_subtitulo)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($conn->query($sql_subtitulos)) {
        echo "<div class='success-msg'><i class='fas fa-check'></i> Tabla 'noticias_subtitulos' creada correctamente</div>";
    } else {
        // Verificar si la tabla ya existe
        if (strpos($conn->error, 'already exists') !== false) {
            echo "<div class='warning-msg'><i class='fas fa-info'></i> Tabla 'noticias_subtitulos' ya existe</div>";
        } else {
            echo "<div class='error-msg'><i class='fas fa-times'></i> Error al crear tabla: " . $conn->error . "</div>";
        }
    }
    
    // 3. Crear carpetas de uploads si no existen
    echo "<h2 style='color: #1a5fb4; border-bottom: 2px solid #1a5fb4; padding-bottom: 10px; margin-top: 30px;'>Verificando carpetas...</h2>";
    
    $carpetas = [
        'img/uploads' => 'Imágenes',
        'admin/videos/uploads' => 'Videos',
        'admin/audios/uploads' => 'Audios'
    ];
    
    foreach ($carpetas as $carpeta => $tipo) {
        if (!is_dir($carpeta)) {
            if (@mkdir($carpeta, 0777, true)) {
                echo "<div class='success-msg'><i class='fas fa-check'></i> Carpeta '$carpeta' ($tipo) creada</div>";
            } else {
                echo "<div class='warning-msg'><i class='fas fa-info'></i> No se pudo crear '$carpeta' (puede que ya exista o permisos insuficientes)</div>";
            }
        } else {
            echo "<div class='success-msg'><i class='fas fa-check'></i> Carpeta '$carpeta' ($tipo) ya existe</div>";
        }
    }
    
    echo "<div style='margin-top: 30px; padding: 20px; background: #f9f9f9; border-radius: 5px;'>";
    echo "<h3 style='color: #333;'><i class='fas fa-check-circle' style='color: #4CAF50;'></i> ¡Migración completada!</h3>";
    echo "<p>La base de datos ha sido actualizada con los campos necesarios para:</p>";
    echo "<ul>";
    echo "<li>✓ Descripciones expandidas (500+ caracteres)</li>";
    echo "<li>✓ Contenido completo (5000+ caracteres)</li>";
    echo "<li>✓ Multimedia principal (video, audio, link)</li>";
    echo "<li>✓ Subtítulos con contenido independiente</li>";
    echo "<li>✓ Multimedia por subtítulo</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div style='text-align: center; margin-top: 30px;'>";
    echo "<a href='admin/dashboard.php' class='btn' style='display: inline-block; padding: 10px 20px; background: #1a5fb4; color: white; text-decoration: none; border-radius: 5px; font-weight: 600;'>";
    echo "<i class='fas fa-arrow-left'></i> Volver al Dashboard";
    echo "</a>";
    echo "</div>";
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<div class='error-msg'>";
    echo "<i class='fas fa-exclamation-circle'></i> Error: " . htmlspecialchars($e->getMessage());
    echo "</div>";
}

echo "</div>";
echo "</body>";
echo "</html>";
?>
