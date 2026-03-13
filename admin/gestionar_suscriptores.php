<?php
session_start();
require_once '../includes/config.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$suscriptores = [];
$mensaje = '';
$error = '';

if ($conn instanceof mysqli) {
    // Procesar eliminación
    if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
        $id = intval($_GET['eliminar']);
        $stmt = $conn->prepare("DELETE FROM suscriptores WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $mensaje = 'Suscriptor eliminado correctamente.';
            } else {
                $error = 'Error al eliminar el suscriptor.';
            }
            $stmt->close();
        }
    }

    // Obtener todos los suscriptores
    $result = $conn->query("SELECT id, nombre, email, comentario, fecha_suscripcion FROM suscriptores ORDER BY fecha_suscripcion DESC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $suscriptores[] = $row;
        }
    } else {
        $error = 'Error al cargar suscriptores: ' . $conn->error;
    }
} else {
    $error = 'No se pudo conectar a la base de datos.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Suscriptores - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-container { padding: 120px 20px 20px; }
        .admin-container h1 { color: var(--primary-color); margin-bottom: 30px; text-align: center; }
        .table-container { background: white; border-radius: 10px; box-shadow: var(--shadow); overflow: hidden; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #f8f9fa; font-weight: 600; color: var(--dark-color); }
        tr:hover { background-color: #f9f9f9; }
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .btn-eliminar { background-color: #dc3545; color: white; padding: 5px 12px; border-radius: 3px; text-decoration: none; font-size: 0.85rem; }
        .btn-eliminar:hover { background-color: #c82333; }
        .no-data { text-align: center; padding: 40px; color: var(--gray-color); }
        .back-link { display: inline-block; margin-bottom: 20px; color: var(--primary-color); text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        .comentario-cell { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .stats-bar { background: rgba(26,95,180,0.1); padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .stats-bar i { color: var(--primary-color); font-size: 1.3rem; }
        .stats-bar strong { color: var(--primary-color); font-size: 1.2rem; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container nav-container">
            <a href="../index.php" class="logo">
                <img src="../assets/logo.jpeg" alt="Periodismo UTB" style="height:70px; border-radius:5px;">
            </a>
            <div>
                <a href="dashboard.php" class="btn" style="margin-right: 10px;">Dashboard</a>
                <a href="logout.php" class="btn" style="background-color: var(--secondary-color);">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="admin-container container">
        <a href="dashboard.php" class="back-link"><i class="fas fa-arrow-left"></i> Volver al Dashboard</a>
        <h1><i class="fas fa-users"></i> Gestionar Suscriptores</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="stats-bar">
            <i class="fas fa-users"></i>
            <span>Total de suscriptores: <strong><?php echo count($suscriptores); ?></strong></span>
        </div>

        <?php if (count($suscriptores) > 0): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Comentario</th>
                            <th>Fecha Suscripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suscriptores as $s): ?>
                        <tr>
                            <td><?php echo $s['id']; ?></td>
                            <td><?php echo htmlspecialchars($s['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($s['email']); ?></td>
                            <td class="comentario-cell" title="<?php echo htmlspecialchars($s['comentario'] ?? ''); ?>">
                                <?php echo !empty($s['comentario']) ? htmlspecialchars(substr($s['comentario'], 0, 50)) . (strlen($s['comentario']) > 50 ? '...' : '') : '<span style="color:#999;font-style:italic;">Sin comentario</span>'; ?>
                            </td>
                            <td><?php echo !empty($s['fecha_suscripcion']) ? date('d/m/Y H:i', strtotime($s['fecha_suscripcion'])) : '—'; ?></td>
                            <td>
                                <a href="mailto:<?php echo htmlspecialchars($s['email']); ?>" style="color:var(--primary-color); margin-right:10px;" title="Enviar email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                                <a href="gestionar_suscriptores.php?eliminar=<?php echo $s['id']; ?>"
                                   class="btn-eliminar"
                                   onclick="return confirm('¿Eliminar a <?php echo htmlspecialchars($s['nombre']); ?>? Esta acción no se puede deshacer.')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-data">
                <i class="fas fa-users" style="font-size:3rem; color:#ddd; display:block; margin-bottom:15px;"></i>
                <h3>No hay suscriptores registrados</h3>
                <p>Los visitantes pueden suscribirse desde el sitio web.</p>
                <a href="../index.php#contacto" class="btn" target="_blank">Ver Formulario de Suscripción</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Tooltip para comentarios truncados
        document.querySelectorAll('.comentario-cell').forEach(cell => {
            cell.style.cursor = 'help';
        });
    </script>
</body>
</html>