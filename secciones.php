<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secciones - Periodismo UTB</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar">
    <div class="container nav-container">
        <a href="index.php" class="logo" style="display: flex; align-items: center; gap: 15px;">
            <!-- Logo grande -->
            <img src="assets/WhatsApp Image 2026-01-22 at 8.41.20 PM.jpeg" 
                 alt="Impacto Dario - Periodismo UTB" 
                 style="height: 70px; border-radius: 5px; box-shadow: 0 3px 20px rgba(0,0,0,0.15);">
            
            <!-- Texto al lado -->
            <div style="line-height: 1.2;">
                <div style="font-size: 1.4rem; font-weight: 700; color: #1a5fb4;">
                    <i class="fas fa-newspaper"></i> Periodismo UTB
                </div>
                <div style="font-size: 0.9rem; color: #666; font-weight: 500;">
                    
                </div>
            </div>
        </a>
        
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
        <ul class="nav-menu" id="navMenu">
            <li><a href="index.php" class="nav-link">Inicio</a></li>
            <li><a href="secciones.php" class="nav-link active">Secciones</a></li>
            <li><a href="index.php#acerca" class="nav-link">Acerca de</a></li>
            <li><a href="index.php#contacto" class="nav-link">Contacto</a></li>
            <li><a href="admin/login.php" class="nav-link admin-btn" target="_blank">Panel Admin</a></li>
        </ul>
    </div>
</nav>

    <!-- Sección de Secciones -->
    <section id="secciones" class="noticias-section" style="padding-top: 40px;">
        <div class="container">
            <h2 class="section-title">Noticias por Sección</h2>
            
            <!-- Botones de filtro -->
            <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 40px; justify-content: center;">
                <a href="secciones.php" class="btn-filter<?php echo !isset($_GET['categoria']) ? ' active' : ''; ?>" style="<?php echo !isset($_GET['categoria']) ? 'background: #1a5fb4; color: white;' : 'background: white; color: #333; border: 2px solid #ddd;'; ?>">
                    <i class="fas fa-th"></i> Todas
                </a>
                <a href="secciones.php?categoria=Deporte" class="btn-filter<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'Deporte') ? ' active' : ''; ?>" style="<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'Deporte') ? 'background: #f44336; color: white;' : 'background: white; color: #333; border: 2px solid #ddd;'; ?>">
                    <i class="fas fa-football"></i> Deporte
                </a>
                <a href="secciones.php?categoria=Tecnologia" class="btn-filter<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'Tecnologia') ? ' active' : ''; ?>" style="<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'Tecnologia') ? 'background: #9C27B0; color: white;' : 'background: white; color: #333; border: 2px solid #ddd;'; ?>">
                    <i class="fas fa-laptop"></i> Tecnologia
                </a>
                <a href="secciones.php?categoria=Sociedad" class="btn-filter<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'Sociedad') ? ' active' : ''; ?>" style="<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'Sociedad') ? 'background: #4CAF50; color: white;' : 'background: white; color: #333; border: 2px solid #ddd;'; ?>">
                    <i class="fas fa-users"></i> Sociedad
                </a>
                <a href="secciones.php?categoria=Educacion" class="btn-filter<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'Educacion') ? ' active' : ''; ?>" style="<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'Educacion') ? 'background: #FF5722; color: white;' : 'background: white; color: #333; border: 2px solid #ddd;'; ?>">
                    <i class="fas fa-book"></i> Educacion
                </a>
                <a href="secciones.php?categoria=Internacional" class="btn-filter<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'Internacional') ? ' active' : ''; ?>" style="<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'Internacional') ? 'background: #2196F3; color: white;' : 'background: white; color: #333; border: 2px solid #ddd;'; ?>">
                    <i class="fas fa-globe"></i> Internacional
                </a>
                <a href="secciones.php?categoria=Local/Regional" class="btn-filter<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'Local/Regional') ? ' active' : ''; ?>" style="<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'Local/Regional') ? 'background: #FF9800; color: white;' : 'background: white; color: #333; border: 2px solid #ddd;'; ?>">
                    <i class="fas fa-map-marker-alt"></i> Local/Regional
                </a>
                <a href="secciones.php?categoria=Politica" class="btn-filter<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'Politica') ? ' active' : ''; ?>" style="<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'Politica') ? 'background: #E74C3C; color: white;' : 'background: white; color: #333; border: 2px solid #ddd;'; ?>">
                    <i class="fas fa-gavel"></i> Politica
                </a>
                <a href="secciones.php?categoria=Negocios" class="btn-filter<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'Negocios') ? ' active' : ''; ?>" style="<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'Negocios') ? 'background: #FF6B35; color: white;' : 'background: white; color: #333; border: 2px solid #ddd;'; ?>">
                    <i class="fas fa-briefcase"></i> Negocios
                </a>
            </div>

            <style>
                .btn-filter {
                    padding: 10px 20px;
                    border-radius: 25px;
                    text-decoration: none;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    cursor: pointer;
                }
                .btn-filter:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                }
            </style>

            <!-- Noticias por categoría -->
            <div class="noticias-grid">
                <?php
                try {
                    @$conn = new mysqli('localhost', 'root', '', 'periodismo_utb');
                    
                    if (!$conn->connect_error) {
                        $conn->set_charset("utf8");
                        
                        $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;
                        $categorias_validas = ['Deporte', 'Tecnologia', 'Sociedad', 'Educacion', 'Internacional', 'Local/Regional', 'Politica', 'Negocios'];
                        
                        // Validar categoría
                        if ($categoria && !in_array($categoria, $categorias_validas)) {
                            $categoria = null;
                        }
                        
                        if ($categoria) {
                            // Mostrar una categoría específica
                            $sql = "SELECT * FROM noticias WHERE categoria = '" . $conn->real_escape_string($categoria) . "' ORDER BY fecha_publicacion DESC";
                        } else {
                            // Mostrar todas
                            $sql = "SELECT * FROM noticias ORDER BY fecha_publicacion DESC";
                        }
                        
                        $result = $conn->query($sql);
                        
                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $fecha = date("d/m/Y", strtotime($row['fecha_publicacion']));
                                $imagen = !empty($row['imagen']) ? "img/uploads/" . $row['imagen'] : "https://placehold.co/400x300/1a5fb4/ffffff?text=Noticia";
                                $descripcion_corta = substr($row['descripcion'], 0, 150) . "...";
                                
                                echo "<div class='noticia-card'>";
                                echo "<div class='noticia-img'>";
                                echo "<img src='$imagen' alt='{$row['titulo']}' onerror=\"this.src='https://placehold.co/400x300/1a5fb4/ffffff?text=Noticia'\">";
                                echo "</div>";
                                echo "<div class='noticia-content'>";
                                echo "<span class='noticia-categoria'>{$row['categoria']}</span>";
                                echo "<h3 class='noticia-titulo'>{$row['titulo']}</h3>";
                                echo "<p class='noticia-desc'>{$descripcion_corta}</p>";
                                echo "<div class='noticia-info'>";
                                echo "<span><i class='far fa-calendar'></i> $fecha</span>";
                                echo "<span><i class='far fa-user'></i> {$row['autor']}</span>";
                                echo "</div>";
                                echo "<div style='margin-top: 15px; text-align: center;'>";
                                echo "<a href='noticia_completa.php?id=" . $row['id'] . "' style='display: inline-block; padding: 8px 16px; background: #1a5fb4; color: white; text-decoration: none; border-radius: 4px; font-weight: 600; transition: background 0.3s;'>";
                                echo "<i class='fas fa-eye'></i> Ver Más";
                                echo "</a>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p class='no-noticias' style='grid-column: 1 / -1; text-align: center;'>No hay noticias en esta sección aún.</p>";
                        }
                        
                        $conn->close();
                    } else {
                        throw new Exception("No se pudo conectar a la base de datos");
                    }
                } catch (Exception $e) {
                    echo "<p class='no-noticias' style='grid-column: 1 / -1; text-align: center;'>No hay noticias disponibles en este momento.</p>";
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Pie de página -->
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
                        <li><a href="index.php#inicio">Inicio</a></li>
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
                <p class="github-link">
                    <i class="fab fa-github"></i> 
                    <a href="https://github.com/tu-usuario/periodismo-utb" target="_blank">Ver proyecto en GitHub</a>
                </p>
            </div>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
