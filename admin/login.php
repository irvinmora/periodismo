<?php
session_start();

// Si ya está autenticado, redirigir al dashboard
if (isset($_SESSION['admin'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    
    // Credenciales simples para desarrollo
    $usuario_valido = 'admin';
    $contrasena_valida = 'admin123';
    
    if ($usuario === $usuario_valido && $contrasena === $contrasena_valida) {
        // Autenticación exitosa
        $_SESSION['admin'] = true;
        $_SESSION['admin_id'] = 1;
        $_SESSION['admin_nombre'] = 'Administrador';
        $_SESSION['admin_usuario'] = $usuario;
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Usuario o contraseña incorrectos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        .login-box {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        
        .login-box h2 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        .error {
            color: #e74c3c;
            background-color: #fadbd8;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .login-btn {
            width: 100%;
            padding: 12px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .login-btn:hover {
            background-color: #0d4a9c;
        }
        
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .credentials {
            background-color: #f0f7ff;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 0.9rem;
            text-align: center;
        }
        
        .credentials strong {
            display: block;
            margin-bottom: 5px;
        }
        
        .credentials code {
            background-color: #e9ecef;
            padding: 2px 5px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Panel de Administración</h2>
            
            <?php if ($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="credentials">
                <strong>Credenciales para desarrollo:</strong>
                <div>Usuario: <code>admin</code></div>
                <div>Contraseña: <code>admin123</code></div>
                <small style="color: #666; display: block; margin-top: 10px;">
                    Usa estas credenciales para acceder al panel
                </small>
            </div>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario" required value="admin">
                </div>
                
                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" required value="admin123">
                </div>
                
                <button type="submit" class="login-btn">Iniciar Sesión</button>
            </form>
            
            <a href="../index.php" class="back-link">Volver al sitio principal</a>
        </div>
    </div>
</body>
</html>