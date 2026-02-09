<?php
echo "<h2>Verificación de Permisos - Periodismo UTB</h2>";

// Verificar carpeta img/uploads
$carpeta_uploads = 'img/uploads';

echo "<h3>1. Verificando carpeta img/uploads</h3>";
if (is_dir($carpeta_uploads)) {
    echo "<p style='color:green;'>✓ La carpeta existe</p>";
    
    // Verificar si es escribible
    if (is_writable($carpeta_uploads)) {
        echo "<p style='color:green;'>✓ La carpeta tiene permisos de escritura</p>";
        
        // Intentar crear un archivo de prueba
        $archivo_prueba = $carpeta_uploads . '/test_' . time() . '.txt';
        if (file_put_contents($archivo_prueba, 'Prueba de escritura')) {
            echo "<p style='color:green;'>✓ Se puede escribir archivos en la carpeta</p>";
            unlink($archivo_prueba); // Eliminar archivo de prueba
        } else {
            echo "<p style='color:red;'>✗ No se puede escribir archivos en la carpeta</p>";
        }
    } else {
        echo "<p style='color:red;'>✗ La carpeta NO tiene permisos de escritura</p>";
        echo "<p><strong>Solución en Windows:</strong></p>";
        echo "<ol>";
        echo "<li>Abre el Explorador de Archivos</li>";
        echo "<li>Ve a: C:\\xampp\\htdocs\\periodismo-utb\\img\\</li>";
        echo "<li>Haz clic derecho en la carpeta 'uploads' → Propiedades</li>";
        echo "<li>Pestaña 'Seguridad' → Editar → Selecciona 'Usuarios'</li>";
        echo "<li>Marca 'Control total' en Permitir → Aplicar → Aceptar</li>";
        echo "</ol>";
    }
} else {
    echo "<p style='color:red;'>✗ La carpeta no existe</p>";
    echo "<p>Creando carpeta...</p>";
    if (mkdir($carpeta_uploads, 0777, true)) {
        echo "<p style='color:green;'>✓ Carpeta creada</p>";
    } else {
        echo "<p style='color:red;'>✗ No se pudo crear la carpeta</p>";
    }
}

// Verificar extensión GD para manejo de imágenes
echo "<h3>2. Verificando extensiones PHP</h3>";
if (extension_loaded('mysqli')) {
    echo "<p style='color:green;'>✓ Extensión mysqli cargada</p>";
} else {
    echo "<p style='color:red;'>✗ Extensión mysqli NO cargada</p>";
}

if (extension_loaded('gd')) {
    echo "<p style='color:green;'>✓ Extensión GD cargada (para imágenes)</p>";
} else {
    echo "<p style='color:orange;'>⚠ Extensión GD NO cargada (no crítico)</p>";
}

// Verificar configuración de subida de archivos
echo "<h3>3. Configuración PHP para subida de archivos</h3>";
$configs = [
    'file_uploads' => 'Subida de archivos habilitada',
    'upload_max_filesize' => 'Tamaño máximo de archivo para subir',
    'post_max_size' => 'Tamaño máximo de datos POST',
    'max_file_uploads' => 'Máximo número de archivos a subir',
    'upload_tmp_dir' => 'Directorio temporal para subidas'
];

foreach ($configs as $config => $desc) {
    $value = ini_get($config);
    echo "<p><strong>$desc:</strong> $value</p>";
}

// Verificar base de datos
echo "<h3>4. Verificando conexión a base de datos</h3>";
$conn = @new mysqli('localhost', 'root', '', 'periodismo_utb');
if ($conn->connect_error) {
    echo "<p style='color:red;'>✗ Error de conexión: " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color:green;'>✓ Conexión a base de datos exitosa</p>";
    
    // Verificar tablas
    $tablas = ['noticias', 'administradores', 'suscriptores'];
    foreach ($tablas as $tabla) {
        $result = $conn->query("SHOW TABLES LIKE '$tabla'");
        if ($result->num_rows > 0) {
            echo "<p style='color:green;'>✓ Tabla '$tabla' existe</p>";
        } else {
            echo "<p style='color:red;'>✗ Tabla '$tabla' NO existe</p>";
        }
    }
    
    $conn->close();
}

echo "<hr>";
echo "<h3>Resumen</h3>";
echo "<p><a href='admin/login.php' style='background:#1a5fb4; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Ir al Panel de Administración</a></p>";
echo "<p><a href='index.php' style='background:#e95420; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Ir al Sitio Web</a></p>";
?>