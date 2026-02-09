<?php
echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "    <meta charset='UTF-8'>";
echo "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "    <title>Configuración BD - Periodismo UTB</title>";
echo "    <style>";
echo "        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f5f5f5; }";
echo "        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }";
echo "        h2 { color: #1a5fb4; border-bottom: 2px solid #1a5fb4; padding-bottom: 10px; }";
echo "        .success { color: green; background-color: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0; border: 1px solid #c3e6cb; }";
echo "        .error { color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0; border: 1px solid #f5c6cb; }";
echo "        .warning { color: #856404; background-color: #fff3cd; padding: 10px; border-radius: 5px; margin: 10px 0; border: 1px solid #ffeaa7; }";
echo "        .btn { display: inline-block; padding: 10px 20px; margin: 10px 5px; text-decoration: none; border-radius: 5px; font-weight: bold; }";
echo "        .btn-primary { background-color: #1a5fb4; color: white; }";
echo "        .btn-secondary { background-color: #e95420; color: white; }";
echo "        ul { background-color: #f8f9fa; padding: 15px; border-radius: 5px; }";
echo "        li { margin: 5px 0; }";
echo "    </style>";
echo "</head>";
echo "<body>";
echo "<div class='container'>";
echo "<h2>Configuración de Base de Datos - Periodismo UTB</h2>";

// Conexión a MySQL (sin especificar base de datos)
$conn = @new mysqli('localhost', 'root', '');

if ($conn->connect_error) {
    echo "<div class='error'>✗ Error de conexión a MySQL: " . $conn->connect_error . "</div>";
    echo "<p>Por favor verifica que:</p>";
    echo "<ul>";
    echo "<li>XAMPP esté instalado y corriendo</li>";
    echo "<li>El servicio MySQL esté iniciado</li>";
    echo "<li>El usuario 'root' no tenga contraseña (o modifica la configuración)</li>";
    echo "</ul>";
} else {
    echo "<div class='success'>✓ Conexión a MySQL exitosa</div>";
    
    // 1. Crear la base de datos
    $sql = "CREATE DATABASE IF NOT EXISTS periodismo_utb 
            CHARACTER SET utf8mb4 
            COLLATE utf8mb4_unicode_ci";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='success'>✓ Base de datos 'periodismo_utb' creada exitosamente</div>";
    } else {
        echo "<div class='error'>✗ Error al crear base de datos: " . $conn->error . "</div>";
    }
    
    // 2. Seleccionar la base de datos
    if ($conn->select_db('periodismo_utb')) {
        echo "<div class='success'>✓ Base de datos seleccionada</div>";
    } else {
        echo "<div class='error'>✗ Error al seleccionar base de datos: " . $conn->error . "</div>";
    }
    
    // 3. Crear tabla de noticias
    $sql_noticias = "CREATE TABLE IF NOT EXISTS noticias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(255) NOT NULL,
        descripcion TEXT NOT NULL,
        contenido TEXT NOT NULL,
        imagen VARCHAR(255),
        fecha_publicacion DATETIME DEFAULT CURRENT_TIMESTAMP,
        categoria VARCHAR(100),
        autor VARCHAR(100) DEFAULT 'Estudiante de Periodismo UTB'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($conn->query($sql_noticias) === TRUE) {
        echo "<div class='success'>✓ Tabla 'noticias' creada exitosamente</div>";
    } else {
        echo "<div class='error'>✗ Error al crear tabla 'noticias': " . $conn->error . "</div>";
    }
    
    // 4. Crear tabla de administradores
    $sql_admin = "CREATE TABLE IF NOT EXISTS administradores (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(50) NOT NULL UNIQUE,
        contrasena VARCHAR(255) NOT NULL,
        nombre VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($conn->query($sql_admin) === TRUE) {
        echo "<div class='success'>✓ Tabla 'administradores' creada exitosamente</div>";
        
        // Verificar si ya existe el administrador
        $check = $conn->query("SELECT id FROM administradores WHERE usuario = 'admin'");
        if ($check->num_rows == 0) {
            // Insertar administrador por defecto
            $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
            $insert = "INSERT INTO administradores (usuario, contrasena, nombre, email) 
                       VALUES ('admin', '$password_hash', 'Administrador', 'admin@utb.edu.ec')";
            
            if ($conn->query($insert)) {
                echo "<div class='success'>✓ Administrador por defecto creado:</div>";
                echo "<ul>";
                echo "<li><strong>Usuario:</strong> admin</li>";
                echo "<li><strong>Contraseña:</strong> admin123</li>";
                echo "<li><strong>Nombre:</strong> Administrador</li>";
                echo "<li><strong>Email:</strong> admin@utb.edu.ec</li>";
                echo "</ul>";
                echo "<div class='warning'><strong>IMPORTANTE:</strong> Cambia esta contraseña después del primer inicio de sesión.</div>";
            } else {
                echo "<div class='error'>✗ Error al crear administrador: " . $conn->error . "</div>";
            }
        } else {
            echo "<div class='warning'>✓ Administrador 'admin' ya existe en la base de datos</div>";
        }
    } else {
        echo "<div class='error'>✗ Error al crear tabla 'administradores': " . $conn->error . "</div>";
    }
    
    // 5. Crear tabla de suscriptores
    $sql_suscriptores = "CREATE TABLE IF NOT EXISTS suscriptores (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        fecha_suscripcion DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if ($conn->query($sql_suscriptores) === TRUE) {
        echo "<div class='success'>✓ Tabla 'suscriptores' creada exitosamente</div>";
    } else {
        echo "<div class='error'>✗ Error al crear tabla 'suscriptores': " . $conn->error . "</div>";
    }
    
    $conn->close();
    
    // 6. Crear carpeta de uploads si no existe
    if (!is_dir('img/uploads')) {
        if (mkdir('img/uploads', 0777, true)) {
            echo "<div class='success'>✓ Carpeta 'img/uploads' creada exitosamente</div>";
        } else {
            echo "<div class='error'>✗ Error al crear carpeta 'img/uploads'</div>";
        }
    } else {
        echo "<div class='success'>✓ Carpeta 'img/uploads' ya existe</div>";
    }
    
    // 7. Verificar archivos esenciales
    echo "<div class='success'>✓ Verificando archivos esenciales...</div>";
    $archivos_esenciales = [
        'index.php' => 'Página principal',
        'css/style.css' => 'Estilos CSS',
        'js/script.js' => 'JavaScript',
        'admin/login.php' => 'Login administrador',
        'admin/dashboard.php' => 'Dashboard administrador',
        'admin/crear_noticia.php' => 'Crear noticias',
        'admin/logout.php' => 'Cerrar sesión'
    ];
    
    $todos_ok = true;
    foreach ($archivos_esenciales as $archivo => $descripcion) {
        if (file_exists($archivo)) {
            echo "<div class='success'>✓ $descripcion: OK</div>";
        } else {
            echo "<div class='error'>✗ $descripcion: FALTANTE</div>";
            $todos_ok = false;
        }
    }
    
    echo "<hr>";
    
    if ($todos_ok) {
        echo "<h3 style='color: green;'>✅ Configuración completada exitosamente!</h3>";
    } else {
        echo "<h3 style='color: #e95420;'>⚠ Configuración parcialmente completada</h3>";
        echo "<p>Algunos archivos están faltantes. El sistema básico funcionará, pero algunas funciones pueden no estar disponibles.</p>";
    }
    
    echo "<p>Ahora puedes usar el sistema:</p>";
    echo "<p>";
    echo "<a href='index.php' class='btn btn-primary'>Ir al Sitio Web Principal</a>";
    echo "<a href='admin/login.php' class='btn btn-secondary'>Ir al Panel de Administración</a>";
    echo "</p>";
    
    echo "<div class='warning'>";
    echo "<h4>📝 Credenciales para acceder al panel de administración:</h4>";
    echo "<ul>";
    echo "<li><strong>URL:</strong> http://localhost:3000/admin/login.php</li>";
    echo "<li><strong>Usuario:</strong> admin</li>";
    echo "<li><strong>Contraseña:</strong> admin123</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div class='warning'>";
    echo "<h4>⚠ SEGURIDAD IMPORTANTE:</h4>";
    echo "<ol>";
    echo "<li>Después del primer inicio de sesión, CAMBIA la contraseña 'admin123' por una más segura</li>";
    echo "<li>Elimina este archivo (crear_db.php) del servidor por seguridad</li>";
    echo "<li>No uses estas credenciales en producción</li>";
    echo "</ol>";
    echo "</div>";
}

echo "</div>";
echo "</body>";
echo "</html>";
?>