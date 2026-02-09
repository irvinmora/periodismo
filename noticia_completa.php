<?php
// DEBE SER LO PRIMERO - ANTES de cualquier salida HTML
require_once __DIR__ . '/includes/config.php';


// Verificar que la tabla de subtítulos existe
$check_table = $conn->query("SHOW TABLES LIKE 'noticias_subtitulos'");
$migration_done = $check_table && $check_table->num_rows > 0;

$noticia_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$noticia = null;
$subtitulos = [];
$show_migration_warning = false;

if ($noticia_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM noticias WHERE id = ?");
    $stmt->bind_param("i", $noticia_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $noticia = $result->fetch_assoc();
        
        // Obtener subtítulos SOLO si la tabla existe
        if ($migration_done) {
            $stmt_subtitulos = $conn->prepare("SELECT * FROM noticias_subtitulos WHERE noticia_id = ? ORDER BY orden ASC");
            $stmt_subtitulos->bind_param("i", $noticia_id);
            $stmt_subtitulos->execute();
            $result_subtitulos = $stmt_subtitulos->get_result();
            
            if ($result_subtitulos) {
                while ($row = $result_subtitulos->fetch_assoc()) {
                    $subtitulos[] = $row;
                }
            }
            $stmt_subtitulos->close();
        } else {
            $show_migration_warning = true;
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $noticia ? htmlspecialchars($noticia['titulo']) : 'Noticia'; ?> - Periodismo UTB</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        .noticia-completa-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }
        .noticia-header {
            background: linear-gradient(135deg, #1a5fb4 0%, #135a9c 100%);
            color: white;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .noticia-header h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            line-height: 1.3;
        }
        .noticia-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            font-size: 0.95rem;
            opacity: 0.95;
        }
        .noticia-meta span {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .categoria-badge {
            display: inline-block;
            background: rgba(255,255,255,0.3);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-bottom: 15px;
            width: fit-content;
        }
        .noticia-imagen-principal {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .noticia-descripcion-larga {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #333;
            background: #f9f9f9;
            padding: 20px;
            border-left: 4px solid #1a5fb4;
            margin-bottom: 30px;
            border-radius: 4px;
        }
        .noticia-descripcion-breve {
            font-size: 1.1rem;
            line-height: 1.7;
            color: #555;
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            padding: 20px;
            border-left: 4px solid #7e57c2;
            margin-bottom: 25px;
            border-radius: 4px;
            font-weight: 500;
        }
        .noticia-contenido-completo {
            font-size: 1rem;
            line-height: 1.9;
            color: #444;
            margin-bottom: 30px;
            text-align: justify;
        }
        .noticia-multimedia {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .multimedia-item {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .multimedia-item:last-child {
            margin-bottom: 0;
        }
        .multimedia-item i {
            font-size: 1.5rem;
            color: #1a5fb4;
            min-width: 30px;
        }
        .multimedia-item a {
            color: #1a5fb4;
            text-decoration: none;
            font-weight: 600;
            word-break: break-all;
        }
        .multimedia-item a:hover {
            text-decoration: underline;
        }
        .multimedia-item audio {
            width: 100%;
            margin: 10px 0;
        }
        .subtitulos-seccion {
            margin-top: 50px;
            border-top: 2px solid #1a5fb4;
            padding-top: 30px;
        }
        .subtitulos-titulo {
            font-size: 1.8rem;
            color: #1a5fb4;
            margin-bottom: 30px;
            text-align: center;
        }
        .subtitulo-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s ease;
        }
        .subtitulo-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .subtitulo-header {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white;
            padding: 20px;
        }
        .subtitulo-numero {
            display: inline-block;
            background: rgba(255,255,255,0.3);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-right: 10px;
        }
        .subtitulo-header h3 {
            font-size: 1.5rem;
            margin: 10px 0 0 0;
        }
        .subtitulo-body {
            padding: 20px;
        }
        .subtitulo-descripcion {
            font-size: 1rem;
            line-height: 1.6;
            color: #666;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .subtitulo-contenido {
            font-size: 0.95rem;
            line-height: 1.8;
            color: #444;
            text-align: justify;
            margin-bottom: 15px;
        }
        .subtitulo-multimedia {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        .volver-btn {
            display: inline-block;
            margin-top: 40px;
            padding: 12px 25px;
            background: #1a5fb4;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        .volver-btn:hover {
            background: #135a9c;
        }
        @media (max-width: 768px) {
            .noticia-header h1 {
                font-size: 1.8rem;
            }
            .noticia-meta {
                flex-direction: column;
                gap: 10px;
            }
            .subtitulo-header h3 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="index.php" class="logo" style="display: flex; align-items: center; gap: 15px;">
                <img src="assets/WhatsApp Image 2026-01-22 at 8.41.20 PM.jpeg" 
                     alt="Impacto Dario - Periodismo UTB" 
                     style="height: 70px; border-radius: 5px; box-shadow: 0 3px 20px rgba(0,0,0,0.15);">
                <div style="line-height: 1.2;">
                    <div style="font-size: 1.4rem; font-weight: 700; color: #1a5fb4;">
                        <i class="fas fa-newspaper"></i> Periodismo UTB
                    </div>
                </div>
            </a>
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php" class="nav-link">Inicio</a></li>
                <li><a href="secciones.php" class="nav-link">Secciones</a></li>
                <li><a href="index.php#acerca" class="nav-link">Acerca de</a></li>
                <li><a href="index.php#contacto" class="nav-link">Contacto</a></li>
                <li><a href="admin/login.php" class="nav-link admin-btn" target="_blank">Panel Admin</a></li>
            </ul>
        </div>
    </nav>

    <div class="noticia-completa-container">
        <?php
        if (!$noticia) {
            echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 20px; border-radius: 4px; margin-top: 20px;'>";
            echo "<p style='color: #721c24; margin: 0;'><i class='fas fa-info-circle'></i> Noticia no encontrada.</p>";
            echo "<a href='index.php' class='volver-btn' style='margin-top: 15px;'><i class='fas fa-arrow-left'></i> Volver al Inicio</a>";
            echo "</div>";
        } else {
            // Mostrar encabezado
            echo "<div class='noticia-header'>";
            echo "<div class='categoria-badge'>" . htmlspecialchars($noticia['categoria']) . "</div>";
            echo "<h1>" . htmlspecialchars($noticia['titulo']) . "</h1>";
            echo "<div class='noticia-meta'>";
            if (!empty($noticia['fecha_publicacion'])) {
                echo "<span><i class='far fa-calendar'></i> " . date('d/m/Y H:i', strtotime($noticia['fecha_publicacion'])) . "</span>";
            }
            if (!empty($noticia['autor'])) {
                echo "<span><i class='far fa-user'></i> " . htmlspecialchars($noticia['autor']) . "</span>";
            }
            echo "</div>";
            echo "</div>";
            
            // Descripción breve
            if (!empty($noticia['descripcion'])) {
                echo "<div class='noticia-descripcion-breve'>";
                echo "<strong><i class='fas fa-quote-left'></i> Resumen:</strong><br>";
                echo nl2br(htmlspecialchars($noticia['descripcion']));
                echo "</div>";
            }
            
            // Imagen principal
            if (!empty($noticia['imagen']) && file_exists('img/uploads/' . $noticia['imagen'])) {
                echo "<img src='img/uploads/" . htmlspecialchars($noticia['imagen']) . "' alt='" . htmlspecialchars($noticia['titulo']) . "' class='noticia-imagen-principal'>";
            }
            
            // Descripción larga
            if (!empty($noticia['descripcion_larga'])) {
                echo "<div class='noticia-descripcion-larga'>";
                echo nl2br(htmlspecialchars($noticia['descripcion_larga']));
                echo "</div>";
            }
            
            // Contenido completo
            if (!empty($noticia['contenido_completo'])) {
                echo "<div class='noticia-contenido-completo'>";
                echo nl2br(htmlspecialchars($noticia['contenido_completo']));
                echo "</div>";
            } elseif (!empty($noticia['contenido'])) {
                echo "<div class='noticia-contenido-completo'>";
                echo nl2br(htmlspecialchars($noticia['contenido']));
                echo "</div>";
            }
            
            // Multimedia principal
            if (!empty($noticia['video_principal']) || !empty($noticia['audio_principal']) || !empty($noticia['link_principal'])) {
                echo "<div class='noticia-multimedia'>";
                echo "<h3 style='color: #1a5fb4; margin-bottom: 15px;'><i class='fas fa-paperclip'></i> Multimedia</h3>";
                
                if (!empty($noticia['video_principal'])) {
                    echo "<div class='multimedia-item'>";
                    echo "<i class='fab fa-youtube'></i>";
                    echo "<a href='" . htmlspecialchars($noticia['video_principal']) . "' target='_blank'>Ver video completo</a>";
                    echo "</div>";
                }
                
                if (!empty($noticia['audio_principal']) && file_exists('admin/audios/uploads/' . $noticia['audio_principal'])) {
                    echo "<div class='multimedia-item'>";
                    echo "<i class='fas fa-volume-up'></i>";
                    echo "<audio controls>";
                    echo "<source src='admin/audios/uploads/" . htmlspecialchars($noticia['audio_principal']) . "'>";
                    echo "</audio>";
                    echo "</div>";
                }
                
                if (!empty($noticia['link_principal'])) {
                    echo "<div class='multimedia-item'>";
                    echo "<i class='fas fa-link'></i>";
                    echo "<a href='" . htmlspecialchars($noticia['link_principal']) . "' target='_blank'>Link externo</a>";
                    echo "</div>";
                }
                
                echo "</div>";
            }
            
            // SUBTÍTULOS
            if (count($subtitulos) > 0) {
                echo "<div class='subtitulos-seccion'>";
                echo "<h2 class='subtitulos-titulo'><i class='fas fa-list-ol'></i> Secciones Relacionadas</h2>";
                
                foreach ($subtitulos as $idx => $subtitulo) {
                    echo "<div class='subtitulo-card'>";
                    echo "<div class='subtitulo-header'>";
                    echo "<span class='subtitulo-numero'>Sección " . ($idx + 1) . "</span>";
                    echo "<h3><strong>" . htmlspecialchars($subtitulo['subtitulo']) . "</strong></h3>";
                    echo "</div>";
                    
                    echo "<div class='subtitulo-body'>";
                    
                    // Imagen del subtítulo
                    if (!empty($subtitulo['imagen']) && file_exists('img/uploads/' . $subtitulo['imagen'])) {
                        echo "<img src='img/uploads/" . htmlspecialchars($subtitulo['imagen']) . "' alt='" . htmlspecialchars($subtitulo['subtitulo']) . "' style='width: 100%; max-height: 300px; object-fit: cover; border-radius: 4px; margin-bottom: 15px;'>";
                    }
                    
                    // Descripción del subtítulo
                    if (!empty($subtitulo['descripcion'])) {
                        echo "<div class='subtitulo-descripcion'>";
                        echo nl2br(htmlspecialchars($subtitulo['descripcion']));
                        echo "</div>";
                    }
                    
                    // Contenido del subtítulo
                    if (!empty($subtitulo['contenido'])) {
                        echo "<div class='subtitulo-contenido'>";
                        echo nl2br(htmlspecialchars($subtitulo['contenido']));
                        echo "</div>";
                    }
                    
                    // Multimedia del subtítulo
                    if (!empty($subtitulo['video']) || !empty($subtitulo['audio']) || !empty($subtitulo['link'])) {
                        echo "<div class='subtitulo-multimedia'>";
                        echo "<h4 style='color: #1a5fb4; margin-top: 0;'><i class='fas fa-media-icons'></i> Multimedia</h4>";
                        
                        if (!empty($subtitulo['video'])) {
                            echo "<div class='multimedia-item'>";
                            echo "<i class='fab fa-youtube'></i>";
                            echo "<a href='" . htmlspecialchars($subtitulo['video']) . "' target='_blank'>Ver video</a>";
                            echo "</div>";
                        }
                        
                        if (!empty($subtitulo['audio']) && file_exists('admin/audios/uploads/' . $subtitulo['audio'])) {
                            echo "<div class='multimedia-item'>";
                            echo "<i class='fas fa-volume-up'></i>";
                            echo "<audio controls>";
                            echo "<source src='admin/audios/uploads/" . htmlspecialchars($subtitulo['audio']) . "'>";
                            echo "</audio>";
                            echo "</div>";
                        }
                        
                        if (!empty($subtitulo['link'])) {
                            echo "<div class='multimedia-item'>";
                            echo "<i class='fas fa-link'></i>";
                            echo "<a href='" . htmlspecialchars($subtitulo['link']) . "' target='_blank'>Link externo</a>";
                            echo "</div>";
                        }
                        echo "</div>";
                    }
                    
                    echo "</div>";
                    echo "</div>";
                }
                echo "</div>";
            }
            
            // Botón de volver
            echo "<a href='index.php' class='volver-btn'><i class='fas fa-arrow-left'></i> Volver al Inicio</a>";
            
            // Mostrar advertencia de migración si aplica
            if ($show_migration_warning) {
                echo "<div style='background: #fff3cd; border: 2px solid #ffc107; padding: 20px; margin: 30px 0; border-radius: 4px;'>";
                echo "<h4 style='color: #856404; margin-top: 0;'><i class='fas fa-info-circle'></i> Información Importante</h4>";
                echo "<p style='color: #856404; margin: 10px 0;'>Para acceder a los subtítulos y contenido expandido, debes ejecutar la migración de la base de datos.</p>";
                echo "<a href='migracion_multimedia.php' style='background: #ffc107; color: #333; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; font-weight: bold;'>";
                echo "<i class='fas fa-database'></i> Ejecutar Migración</a>";
                echo "</div>";
            }
        }
        ?>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <i class="fas fa-newspaper"></i>
                    <h3>Periodismo UTB</h3>
                    <p>Portal informativo de la carrera de Periodismo<br>Universidad Técnica de Babahoyo</p>
                </div>
                <div class="footer-links">
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="index.php">Inicio</a></li>
                        <li><a href="secciones.php">Secciones</a></li>
                        <li><a href="index.php#acerca">Acerca de</a></li>
                        <li><a href="index.php#contacto">Contacto</a></li>
                        <li><a href="admin/login.php" target="_blank">Panel Admin</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h4>Contacto</h4>
                    <p><i class="fas fa-map-marker-alt"></i> Av. Universitaria, Babahoyo</p>
                    <p><i class="fas fa-phone"></i> +593 987296574</p>
                    <p><i class="fas fa-envelope"></i> periodismo@utb.edu.ec</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Periodismo UTB - Universidad Técnica de Babahoyo. Todos los derechos reservados.</p>
                <p>Desarrollado por Irvin Adonis Mora Paredes</p>
            </div>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
