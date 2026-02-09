<?php
session_start();
if (!isset($_SESSION['admin'])) {
    die('Acceso denegado');
}

echo "<h2>Verificación de Base de Datos</h2>";

$conn = @new mysqli('localhost', 'root', '', 'periodismo_utb');

if ($conn->connect_error) {
    echo "<p style='color:red;'>Error de conexión: " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color:green;'>✓ Conexión exitosa a la base de datos</p>";
    
    // Verificar tablas
    $tablas = ['noticias', 'administradores', 'suscriptores'];
    
    foreach ($tablas as $tabla) {
        $result = $conn->query("SHOW TABLES LIKE '$tabla'");
        if ($result->num_rows > 0) {
            echo "<p style='color:green;'>✓ Tabla '$tabla' existe</p>";
            
            // Mostrar contenido de la tabla administradores
            if ($tabla === 'administradores') {
                $result_admin = $conn->query("SELECT * FROM administradores");
                echo "<h3>Contenido de la tabla administradores:</h3>";
                echo "<table border='1' cellpadding='5' cellspacing='0'>";
                echo "<tr><th>ID</th><th>Usuario</th><th>Contraseña (hash)</th><th>Nombre</th><th>Email</th></tr>";
                while ($row = $result_admin->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['usuario'] . "</td>";
                    echo "<td>" . substr($row['contrasena'], 0, 30) . "...</td>";
                    echo "<td>" . $row['nombre'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                // Verificar si podemos autenticar con admin/admin123
                $result_check = $conn->query("SELECT * FROM administradores WHERE usuario = 'admin'");
                if ($result_check->num_rows > 0) {
                    $admin = $result_check->fetch_assoc();
                    $hash = $admin['contrasena'];
                    
                    echo "<h3>Verificación de contraseña:</h3>";
                    echo "<p>Hash almacenado: " . substr($hash, 0, 30) . "...</p>";
                    
                    // Intentar verificar
                    if (password_verify('admin123', $hash)) {
                        echo "<p style='color:green;'>✓ La contraseña 'admin123' es válida con password_verify()</p>";
                    } else {
                        echo "<p style='color:red;'>✗ La contraseña 'admin123' NO es válida con password_verify()</p>";
                        echo "<p>Intentando autenticación alternativa...</p>";
                        
                        // Si no funciona, actualizar la contraseña
                        $nuevo_hash = password_hash('admin123', PASSWORD_DEFAULT);
                        $sql_update = "UPDATE administradores SET contrasena = '$nuevo_hash' WHERE usuario = 'admin'";
                        if ($conn->query($sql_update)) {
                            echo "<p style='color:green;'>✓ Contraseña actualizada con nuevo hash</p>";
                        } else {
                            echo "<p style='color:red;'>✗ Error al actualizar contraseña: " . $conn->error . "</p>";
                        }
                    }
                }
            }
        } else {
            echo "<p style='color:red;'>✗ Tabla '$tabla' NO existe</p>";
        }
    }
    
    $conn->close();
}

echo "<hr>";
echo "<p><a href='dashboard.php'>Volver al Dashboard</a> | ";
echo "<a href='login.php'>Ir al Login</a></p>";
?>