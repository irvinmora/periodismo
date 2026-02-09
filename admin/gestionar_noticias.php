<?php
session_start();
require_once '../includes/config.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$noticias = [];
$mensaje = '';
$error = '';

if ($conn === false) {
    $error = 'Error: No se pudo conectar a la base de datos.';
} else {
    // Obtener todas las noticias
    $result = $conn->query("SELECT id, titulo, categoria, imagen, fecha_publicacion FROM noticias ORDER BY fecha_publicacion DESC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $noticias[] = $row;
        }
    }
    
    // Procesar eliminación
    if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
        $id = intval($_GET['eliminar']);
        
        // Usar prepared statement para seguridad
        $stmt = $conn->prepare("SELECT imagen FROM noticias WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result_img = $stmt->get_result();
            
            if ($result_img->num_rows > 0) {
                $noticia_img = $result_img->fetch_assoc();
                if (!empty($noticia_img['imagen']) && file_exists("../img/uploads/" . $noticia_img['imagen'])) {
                    unlink("../img/uploads/" . $noticia_img['imagen']);
                }
            }
            $stmt->close();
        }
        
        // Eliminar noticia con prepared statement
        $stmt_delete = $conn->prepare("DELETE FROM noticias WHERE id = ?");
        if ($stmt_delete) {
            $stmt_delete->bind_param("i", $id);
            if ($stmt_delete->execute()) {
                $mensaje = 'Noticia eliminada correctamente';
            } else {
                $error = 'Error al eliminar la noticia';
            }
            $stmt_delete->close();
        }
    }
}

if (isset($_GET['mensaje'])) {
    $mensaje = htmlspecialchars($_GET['mensaje']);
}
if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
}

$mensaje = $_GET['mensaje'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Noticias - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-container {
            padding: 120px 20px 20px;
        }
        .admin-container h1 {
            color: var(--primary-color);
            margin-bottom: 30px;
            text-align: center;
        }
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--dark-color);
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .acciones {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn-accion {
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
        }
        .btn-editar {
            background-color: #28a745;
            color: white;
        }
        .btn-eliminar {
            background-color: #dc3545;
            color: white;
        }
        .btn-ver {
            background-color: var(--primary-color);
            color: white;
        }
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: var(--gray-color);
        }
        .thumbnail {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: var(--primary-color);
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .actions-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container nav-container">
            <a href="../index.php" class="logo">
                <i class="fas fa-newspaper"></i>
                <span>Periodismo UTB - Admin</span>
            </a>
            <div>
                <a href="dashboard.php" class="btn" style="margin-right: 10px;">Dashboard</a>
                <a href="crear_noticia.php" class="btn" style="margin-right: 10px; background-color: #28a745;">+ Nueva</a>
                <a href="logout.php" class="btn" style="background-color: var(--secondary-color);">Cerrar Sesión</a>
            </div>
        </div>
    </nav>
    
    <div class="admin-container container">
        <a href="dashboard.php" class="back-link">&larr; Volver al Dashboard</a>
        <h1>Gestionar Noticias</h1>
        
        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <div class="actions-header">
            <div>
                <strong>Total de noticias:</strong> <?php echo count($noticias); ?>
            </div>
            <a href="crear_noticia.php" class="btn">+ Crear Nueva Noticia</a>
        </div>
        
        <?php if (count($noticias) > 0): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Categoría</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($noticias as $noticia): ?>
                        <tr>
                            <td><?php echo $noticia['id']; ?></td>
                            <td>
                                <?php if (!empty($noticia['imagen'])): ?>
                                    <img src="../img/uploads/<?php echo htmlspecialchars($noticia['imagen']); ?>" 
                                         alt="Miniatura" class="thumbnail"
                                         onerror="this.style.display='none'">
                                <?php else: ?>
                                    <span style="color: #999;">Sin imagen</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars(substr($noticia['titulo'], 0, 50)); ?></strong>
                                <?php echo strlen($noticia['titulo']) > 50 ? '...' : ''; ?>
                            </td>
                            <td><?php echo htmlspecialchars($noticia['categoria']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($noticia['fecha_publicacion'])); ?></td>
                            <td>
                                <div class="acciones">
                                    <a href="#" class="btn-accion btn-ver" 
                                       onclick="alert('Ver noticia: <?php echo addslashes($noticia['titulo']); ?>')">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    <a href="editar_noticia.php?id=<?php echo $noticia['id']; ?>" 
                                       class="btn-accion btn-editar">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="gestionar_noticias.php?eliminar=<?php echo $noticia['id']; ?>" 
                                       class="btn-accion btn-eliminar"
                                       onclick="return confirm('¿Estás seguro de eliminar esta noticia?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-data">
                <h3>No hay noticias publicadas</h3>
                <p>Crea tu primera noticia para empezar</p>
                <a href="crear_noticia.php" class="btn">Crear Primera Noticia</a>
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 30px; padding: 20px; background-color: #f8f9fa; border-radius: 5px;">
            <h3>Instrucciones:</h3>
            <ul>
                <li><strong>Ver:</strong> Previsualiza la noticia (en desarrollo)</li>
                <li><strong>Editar:</strong> Modifica el contenido de la noticia (en desarrollo)</li>
                <li><strong>Eliminar:</strong> Elimina permanentemente la noticia y su imagen</li>
                <li>Las noticias se ordenan por fecha de publicación (más recientes primero)</li>
            </ul>
        </div>
    </div>
    
    <script>
        // Confirmación para eliminar
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (!confirm('¿Estás seguro de que quieres eliminar esta noticia? Esta acción no se puede deshacer.')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>