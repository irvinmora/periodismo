<?php
session_start();
require_once '../includes/config.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$total_noticias = 0;
$total_suscriptores = 0;
$noticias = [];
$suscriptores = [];
$error_conexion = false;
$error_mensaje = '';

// Intentar obtener estadísticas
if ($conn !== false) {
    // Obtener total de noticias
    $result = $conn->query("SELECT COUNT(*) as total FROM noticias");
    if ($result) {
        $row = $result->fetch_assoc();
        $total_noticias = $row['total'];
    }
    
    // Obtener total de suscriptores
    $result_suscriptores = $conn->query("SELECT COUNT(*) as total FROM suscriptores");
    if ($result_suscriptores) {
        $row = $result_suscriptores->fetch_assoc();
        $total_suscriptores = $row['total'];
    }
    
    // Obtener noticias recientes
    $result_noticias = $conn->query("SELECT id, titulo, categoria, imagen, fecha_publicacion FROM noticias ORDER BY fecha_publicacion DESC LIMIT 5");
    if ($result_noticias) {
        while ($row = $result_noticias->fetch_assoc()) {
            $noticias[] = $row;
        }
    }
    
    // Obtener suscriptores recientes
    $result_suscriptores_list = $conn->query("SELECT id, nombre, email, fecha_suscripcion FROM suscriptores ORDER BY fecha_suscripcion DESC LIMIT 5");
    if ($result_suscriptores_list) {
        while ($row = $result_suscriptores_list->fetch_assoc()) {
            $suscriptores[] = $row;
        }
    }
} else {
    $error_conexion = true;
    $error_mensaje = 'No se pudo conectar a la base de datos. Verifica tu conexión.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .dashboard {
            padding: 120px 20px 20px;
        }
        .dashboard h1 {
            color: var(--primary-color);
            margin-bottom: 30px;
            text-align: center;
        }
        .welcome {
            text-align: center;
            margin-bottom: 40px;
            font-size: 1.2rem;
        }
        .dashboard-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .dashboard-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            text-align: center;
            transition: var(--transition);
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .dashboard-card i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        .dashboard-card h3 {
            margin-bottom: 10px;
            color: var(--dark-color);
        }
        .stats-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin: 20px 0;
        }
        .stat-box {
            background-color: rgba(26, 95, 180, 0.1);
            padding: 20px;
            border-radius: 10px;
            min-width: 150px;
            text-align: center;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-color);
            display: block;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 0.9rem;
            color: var(--gray-color);
        }
        .logout-btn {
            margin-top: 40px;
            text-align: center;
        }
        .btn-dashboard {
            margin-top: 15px;
            display: inline-block;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #f5c6cb;
        }
        .news-table, .subs-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .news-table th, .news-table td,
        .subs-table th, .subs-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .news-table th, .subs-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .recent-news, .recent-subs {
            margin-top: 40px;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }
        .section-title {
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }
        .table-actions a {
            margin-right: 10px;
            text-decoration: none;
        }
        .table-actions .edit {
            color: #28a745;
        }
        .table-actions .delete {
            color: #dc3545;
        }
        .table-actions .view {
            color: var(--primary-color);
        }
        .dashboard-sections {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }
        @media (max-width: 1100px) {
            .dashboard-sections {
                grid-template-columns: 1fr;
            }
        }
        .email-cell {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: var(--gray-color);
        }
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #ddd;
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
                <span style="margin-right: 15px; color: var(--primary-color);">
                    <i class="fas fa-user"></i> <?php echo $_SESSION['admin_nombre']; ?>
                </span>
                <a href="logout.php" class="btn" style="background-color: var(--secondary-color);">Cerrar Sesión</a>
            </div>
        </div>
    </nav>
    
    <div class="dashboard container">
        <h1>Panel de Administración</h1>
        
        <div class="welcome">
            <p>Bienvenido, <strong><?php echo $_SESSION['admin_nombre']; ?></strong></p>
            
            <?php if ($error_conexion): ?>
                <div class="alert-error">
                    <strong>Error de conexión a la base de datos:</strong><br>
                    <?php echo $error_mensaje; ?><br>
                    <small>Por favor, asegúrate de que la base de datos 'periodismo_utb' exista.</small>
                </div>
            <?php else: ?>
                <div class="stats-container">
                    <div class="stat-box">
                        <span class="stat-number"><?php echo $total_noticias; ?></span>
                        <span class="stat-label">Noticias Publicadas</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number"><?php echo $total_suscriptores; ?></span>
                        <span class="stat-label">Suscriptores</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number"><?php echo date('d/m'); ?></span>
                        <span class="stat-label">Fecha Actual</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number"><?php echo count($noticias); ?></span>
                        <span class="stat-label">Noticias Recientes</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (!$error_conexion): ?>
        <div class="dashboard-menu">
            <div class="dashboard-card">
                <i class="fas fa-plus-circle"></i>
                <h3>Crear Noticia</h3>
                <p>Publica una nueva noticia en el sitio</p>
                <a href="crear_noticia.php" class="btn btn-dashboard">Crear Nueva Noticia</a>
            </div>
            
            <div class="dashboard-card">
                <i class="fas fa-edit"></i>
                <h3>Gestionar Noticias</h3>
                <p>Modifica o elimina noticias existentes</p>
                <a href="gestionar_noticias.php" class="btn btn-dashboard">Gestionar Noticias</a>
            </div>
            
            <div class="dashboard-card">
                <i class="fas fa-users"></i>
                <h3>Suscriptores</h3>
                <p>Gestiona la lista de suscriptores</p>
                <a href="gestionar_suscriptores.php" class="btn btn-dashboard">Ver Suscriptores</a>
            </div>
            
            <div class="dashboard-card">
                <i class="fas fa-cog"></i>
                <h3>Configuración</h3>
                <p>Configuración del sitio web</p>
                <a href="#" class="btn btn-dashboard" onclick="alert('Funcionalidad en desarrollo')">Configuración</a>
            </div>
        </div>
        
        <div class="dashboard-sections">
            <div class="recent-news">
                <h3 class="section-title">
                    <i class="fas fa-newspaper"></i> Noticias Recientes
                </h3>
                
                <?php if (count($noticias) > 0): ?>
                    <table class="news-table">
                        <thead>
                            <tr>
                                <th>ID</th>
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
                                <td><?php echo htmlspecialchars(substr($noticia['titulo'], 0, 40)); ?>...</td>
                                <td>
                                    <span style="background-color: rgba(26, 95, 180, 0.1); color: var(--primary-color); padding: 3px 8px; border-radius: 12px; font-size: 0.8rem;">
                                        <?php echo htmlspecialchars($noticia['categoria'] ?? 'Sin categoría'); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($noticia['fecha_publicacion'])); ?></td>
                                <td class="table-actions">
                                    <a href="../index.php#noticias" class="view" title="Ver en sitio" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="editar_noticia.php?id=<?php echo $noticia['id']; ?>" class="edit" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="gestionar_noticias.php?eliminar=<?php echo $noticia['id']; ?>" class="delete" title="Eliminar" onclick="return confirm('¿Eliminar esta noticia?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div style="text-align: center; margin-top: 20px;">
                        <a href="gestionar_noticias.php" class="btn-secondary">Ver Todas las Noticias</a>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-newspaper"></i>
                        <h3>No hay noticias publicadas</h3>
                        <p>Crea tu primera noticia para empezar</p>
                        <a href="crear_noticia.php" class="btn">Crear Primera Noticia</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="recent-subs">
                <h3 class="section-title">
                    <i class="fas fa-users"></i> Suscriptores Recientes
                </h3>
                
                <?php if (count($suscriptores) > 0): ?>
                    <table class="subs-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($suscriptores as $suscriptor): ?>
                            <tr>
                                <td><?php echo $suscriptor['id']; ?></td>
                                <td><?php echo htmlspecialchars(substr($suscriptor['nombre'], 0, 20)); ?></td>
                                <td class="email-cell" title="<?php echo htmlspecialchars($suscriptor['email']); ?>">
                                    <?php echo htmlspecialchars(substr($suscriptor['email'], 0, 25)); ?>...
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($suscriptor['fecha_suscripcion'])); ?></td>
                                <td class="table-actions">
                                    <a href="mailto:<?php echo htmlspecialchars($suscriptor['email']); ?>" class="view" title="Enviar email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                    <a href="gestionar_suscriptores.php?eliminar=<?php echo $suscriptor['id']; ?>" class="delete" title="Eliminar" onclick="return confirm('¿Eliminar este suscriptor?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div style="text-align: center; margin-top: 20px;">
                        <a href="gestionar_suscriptores.php" class="btn-secondary">Ver Todos los Suscriptores</a>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-users"></i>
                        <h3>No hay suscriptores</h3>
                        <p>Los visitantes pueden suscribirse desde el sitio web</p>
                        <a href="../index.php#contacto" class="btn" target="_blank">Ver Formulario de Suscripción</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="logout-btn">
            <a href="../index.php" class="btn-secondary" target="_blank">
                <i class="fas fa-external-link-alt"></i> Ver Sitio Web
            </a>
            <a href="logout.php" class="btn" style="margin-left: 10px; background-color: var(--secondary-color);">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
        </div>
    </div>
    
    <script>
        // Confirmación para eliminar
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('a.delete').forEach(link => {
                link.addEventListener('click', function(e) {
                    const tipo = this.closest('tr').querySelector('td:nth-child(2)').textContent.includes('@') ? 'suscriptor' : 'noticia';
                    if (!confirm(`¿Estás seguro de eliminar este ${tipo}? Esta acción no se puede deshacer.`)) {
                        e.preventDefault();
                    }
                });
            });
            
            // Tooltips para emails truncados
            document.querySelectorAll('.email-cell').forEach(cell => {
                cell.addEventListener('mouseenter', function() {
                    this.title = this.getAttribute('title');
                });
            });
        });
    </script>
</body>
</html>