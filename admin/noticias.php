<?php
include 'config/database.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todas las Noticias - Periodismo UTB</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="index.php" class="logo">
                <i class="fas fa-newspaper"></i>
                <span>Periodismo UTB</span>
            </a>
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php#inicio" class="nav-link">Inicio</a></li>
                <li><a href="#noticias" class="nav-link active">Noticias</a></li>
                <li><a href="index.php#acerca" class="nav-link">Acerca de</a></li>
                <li><a href="index.php#contacto" class="nav-link">Contacto</a></li>
                <li><a href="admin/login.php" class="nav-link admin-btn">Panel Admin</a></li>
            </ul>
        </div>
    </nav>

    <!-- Sección de todas las noticias -->
    <section id="noticias" class="noticias-section" style="padding-top: 120px;">
        <div class="container">
            <h2 class="section-title">Todas las Noticias</h2>
            
            <div class="noticias-grid">
                <?php
                $sql = "SELECT * FROM noticias ORDER BY fecha_publicacion DESC";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $fecha = date("d/m/Y", strtotime($row['fecha_publicacion']));
                        $imagen = !empty($row['imagen']) ? "img/uploads/" . $row['imagen'] : "assets/default-news.jpg";
                        $descripcion_corta = substr($row['descripcion'], 0, 200) . "...";
                        
                        echo "<div class='noticia-card'>";
                        echo "<div class='noticia-img'>";
                        echo "<img src='$imagen' alt='{$row['titulo']}'>";
                        echo "</div>";
                        echo "<div class='noticia-content'>";
                        echo "<span class='noticia-categoria'>{$row['categoria']}</span>";
                        echo "<h3 class='noticia-titulo'>{$row['titulo']}</h3>";
                        echo "<p class='noticia-desc'>{$descripcion_corta}</p>";
                        echo "<div class='noticia-info'>";
                        echo "<span><i class='far fa-calendar'></i> $fecha</span>";
                        echo "<span><i class='far fa-user'></i> {$row['autor']}</span>";
                        echo "</div>";
                        echo "<a href='#' class='btn' style='margin-top: 15px; display: inline-block;'>Leer más</a>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p class='no-noticias' style='grid-column: 1 / -1; text-align: center; padding: 50px; font-size: 1.2rem;'>No hay noticias publicadas aún.</p>";
                }
                
                $conn->close();
                ?>
            </div>
            
            <div class="text-center" style="margin-top: 40px;">
                <a href="index.php" class="btn-secondary">Volver al Inicio</a>
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
                        <li><a href="#noticias">Noticias</a></li>
                        <li><a href="index.php#acerca">Acerca de</a></li>
                        <li><a href="index.php#contacto">Contacto</a></li>
                        <li><a href="admin/login.php">Panel Admin</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h4>Contacto</h4>
                    <p><i class="fas fa-map-marker-alt"></i> Av. Universitaria, Babahoyo</p>
                    <p><i class="fas fa-phone"></i> +593 5 123 4567</p>
                    <p><i class="fas fa-envelope"></i> periodismo@utb.edu.ec</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Periodismo UTB - Universidad Técnica de Babahoyo. Todos los derechos reservados.</p>
                <p>Desarrollado por estudiantes de la carrera de Periodismo</p>
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