<?php
// Verificar migración
session_status() === PHP_SESSION_NONE && session_start();

@$check_conn = new mysqli('localhost', 'root', '', 'periodismo_utb');
$show_migration_alert = !(
    $check_conn->query("SHOW TABLES LIKE 'noticias_subtitulos'")
    && $check_conn->query("SHOW TABLES LIKE 'noticias_subtitulos'")->num_rows > 0
);
$check_conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impacto-Diario - Universidad Técnica de Babahoyo</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>

<?php if ($show_migration_alert): ?>
    <div style="background: #fff3cd; border-bottom: 2px solid #ffc107; padding: 15px 20px; text-align: center; font-family: Arial;">
        <p style="color: #856404; margin: 0; font-weight: 500;">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Sistema no configurado.</strong>
            <a href="migracion_multimedia.php" style="color: #0056b3; text-decoration: underline; font-weight: bold;">
                Haz clic aquí para ejecutar la migración
            </a>
        </p>
    </div>
<?php endif; ?>

<!-- Barra de navegación -->
<nav class="navbar">
    <div class="container nav-container">
        <a href="index.php" class="logo" style="display: flex; align-items: center; gap: 15px;">
            <!-- Logo grande -->
            <img
                src="assets/WhatsApp Image 2026-01-22 at 8.41.20 PM.jpeg"
                alt="Impacto Dario - Periodismo UTB"
                style="height: 70px; border-radius: 5px; box-shadow: 0 3px 20px rgba(0,0,0,0.15);"
            >

            <!-- Texto al lado -->
            <div style="line-height: 1.2;">
                <div style="font-size: 1.4rem; font-weight: 700; color: #1a5fb4;">
                    <i class="fas fa-newspaper"></i> Periodismo-UTB
                </div>
                <div style="font-size: 0.9rem; color: #666; font-weight: 500;">
                </div>
            </div>
        </a>

        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>

        <ul class="nav-menu" id="navMenu">
            <li><a href="#inicio" class="nav-link active">Inicio</a></li>
            <li><a href="#noticias" class="nav-link">Noticias</a></li>
            <li><a href="secciones.php" class="nav-link">Secciones</a></li>
            <li><a href="#acerca" class="nav-link">Acerca de</a></li>
            <li><a href="#contacto" class="nav-link">Contacto</a></li>
            <li><a href="admin/login.php" class="nav-link admin-btn" target="_blank">Panel Admin</a></li>
        </ul>
    </div>
</nav>

<!-- Sección de inicio -->
<section id="inicio" class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Portal Informativo del Estudiante de Periodismo</h1>
            <p class="subtitle">Universidad Técnica de Babahoyo</p>
            <p>Un espacio dedicado a las noticias y reportajes creados por los futuros periodistas de la UTB</p>
            <a href="#noticias" class="btn">Ver Noticias Recientes</a>
        </div>

               <div class="hero-image">
                <img
                    src="assets/WhatsApp%20Image%202026-01-22%20at%208.41.20%20PM%20(3).jpeg"
                    alt="Impacto Diario"
                >
                </div>


</section>

<!-- Sección de noticias -->
<section id="noticias" class="noticias-section">
    <div class="container">
        <h2 class="section-title">Noticias Recientes</h2>

        <div class="noticias-grid">
            <?php
            // Intentar conectar a la base de datos
            try {
                @$conn = new mysqli('localhost', 'root', '', 'periodismo_utb');

                if (!$conn->connect_error) {
                    $conn->set_charset("utf8");

                    $sql = "SELECT * FROM noticias ORDER BY fecha_publicacion DESC LIMIT 6";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {

                            $fecha = date("d/m/Y", strtotime($row['fecha_publicacion']));
                            $imagen = !empty($row['imagen'])
                                ? "img/uploads/" . $row['imagen']
                                : "https://placehold.co/400x300/1a5fb4/ffffff?text=Noticia";

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
                        echo "<p class='no-noticias'>No hay noticias publicadas aún. <a href='admin/login.php' target='_blank'>Inicia sesión</a> para crear la primera noticia.</p>";
                    }

                    $conn->close();
                } else {
                    throw new Exception("No se pudo conectar a la base de datos");
                }
            } catch (Exception $e) {
                // Mostrar noticias de ejemplo si no hay conexión a BD
                mostrarNoticiasEjemplo();
            }

            // Función para mostrar noticias de ejemplo
            function mostrarNoticiasEjemplo()
            {
                $noticias_ejemplo = [
                    [
                        'titulo' => 'Inauguración del nuevo laboratorio de periodismo',
                        'descripcion' => 'La UTB ha inaugurado un moderno laboratorio equipado con la última tecnología para la formación de periodistas digitales.',
                        'categoria' => 'Universidad',
                        'fecha' => '15/01/2026',
                        'autor' => 'Estudiante de Periodismo UTB'
                    ],
                    [
                        'titulo' => 'Estudiantes de periodismo ganan concurso nacional',
                        'descripcion' => 'Un grupo de estudiantes de nuestra universidad obtuvo el primer lugar en el concurso nacional de reportajes investigativos.',
                        'categoria' => 'Logros',
                        'fecha' => '10/01/2026',
                        'autor' => 'Estudiante de Periodismo UTB'
                    ],
                    [
                        'titulo' => 'Taller de periodismo digital con expertos internacionales',
                        'descripcion' => 'Se llevó a cabo un taller especializado con periodistas de renombre internacional para capacitar a nuestros estudiantes.',
                        'categoria' => 'Talleres',
                        'fecha' => '05/01/2026',
                        'autor' => 'Estudiante de Periodismo UTB'
                    ]
                ];

                foreach ($noticias_ejemplo as $noticia) {
                    echo "<div class='noticia-card'>";
                        echo "<div class='noticia-img'>";
                            echo "<img src='https://placehold.co/400x300/1a5fb4/ffffff?text=Noticia' alt='{$noticia['titulo']}'>";
                        echo "</div>";

                        echo "<div class='noticia-content'>";
                            echo "<span class='noticia-categoria'>{$noticia['categoria']}</span>";
                            echo "<h3 class='noticia-titulo'>{$noticia['titulo']}</h3>";
                            echo "<p class='noticia-desc'>{$noticia['descripcion']}</p>";

                            echo "<div class='noticia-info'>";
                                echo "<span><i class='far fa-calendar'></i> {$noticia['fecha']}</span>";
                                echo "<span><i class='far fa-user'></i> {$noticia['autor']}</span>";
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                }
            }
            ?>
        </div>

        <div class="text-center">
            <a
                href="#noticias"
                class="btn-secondary"
                onclick="alert('En una versión completa, esto llevaría a una página con todas las noticias')"
            >
                Ver Todas las Noticias
            </a>
        </div>
    </div>
</section>

<!-- Sección Acerca de -->
<section id="acerca" class="acerca-section">
    <div class="container">
        <h2 class="section-title">Acerca de Nosotros</h2>

        <div class="acerca-content">
            <div class="acerca-text">
                <h3>Alto Impacto</h3>
                <p>Este portal informativo es un proyecto académico desarrollado por estudiantes de la carrera de Ciencias Sociales con mención en Periodismo de la Universidad Técnica de Babahoyo.</p>
                <p>Nuestro objetivo es brindar un espacio donde los futuros periodistas puedan publicar sus trabajos, reportajes y noticias, desarrollando habilidades prácticas en el ámbito del periodismo digital.</p>

                <div class="acerca-features">
                    <div class="feature">
                        <i class="fas fa-graduation-cap"></i>
                        <h4>Formación Práctica</h4>
                        <p>Desarrollamos habilidades periodísticas reales</p>
                    </div>

                    <div class="feature">
                        <i class="fas fa-newspaper"></i>
                        <h4>Información Veraz</h4>
                        <p>Comprometidos con la verdad y la objetividad</p>
                    </div>

                    <div class="feature">
                        <i class="fas fa-users"></i>
                        <h4>Comunidad Universitaria</h4>
                        <p>Por y para la comunidad UTB</p>
                    </div>
                </div>
            </div>

            <div class="acerca-img">
                <img
                    src="assets/WhatsApp Image 2026-01-22 at 8.41.20 PM (3).jpeg"
                    alt="Universidad Técnica de Babahoyo"
                    onerror="this.onerror=null; this.src='assets/'"
                >
            </div>
        </div>
    </div>
</section>

<!-- Sección de contacto -->
<section id="contacto" class="contacto-section">
    <div class="container">
        <h2 class="section-title">Contáctanos</h2>

        <div class="contacto-content">
            <div class="contacto-info">
                <h3>Información de Contacto</h3>

                <div class="contacto-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4>Dirección</h4>
                        <p>
                            Universidad Técnica de Babahoyo<br>
                            Av. Universitaria, Babahoyo - Los Ríos, Ecuador
                        </p>
                    </div>
                </div>

                <div class="contacto-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h4>Teléfono</h4>
                        <p>+593 987296574</p>
                    </div>
                </div>

                <div class="contacto-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email</h4>
                        <p>periodismo@utb.edu.ec</p>
                    </div>
                </div>

                <div class="redes-sociales">
                    <h4>Síguenos en redes sociales</h4>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>

            <div class="contacto-form">
                <h3>Formulario de Suscripción</h3>
                <p>Suscríbete para recibir las últimas noticias directamente en tu correo</p>

                <form id="formSuscripcion" method="POST">
                    <div class="form-group">
                        <input type="text" id="nombre" name="nombre" placeholder="Tu nombre completo" required minlength="2" maxlength="100">
                    </div>

                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="Tu correo electrónico" required maxlength="100">
                    </div>

                    <div class="form-group">
                        <textarea
                            id="comentario"
                            name="comentario"
                            placeholder="Cuéntanos tu opinión, sugerencias o comentarios (opcional)"
                            maxlength="500"
                            rows="5"
                            style="resize: vertical; font-family: 'Roboto', sans-serif; padding: 10px; border: 1px solid #ddd; border-radius: 4px; width: 100%;"
                        ></textarea>
                    </div>

                    <button type="submit" class="btn" id="btnSuscripcion">
                        <span id="btnText">Suscribirse</span>
                        <span id="btnLoading" style="display:none;">
                            <i class="fas fa-spinner fa-spin"></i> Procesando...
                        </span>
                    </button>
                </form>

                <div id="mensajeSuscripcion" style="margin-top: 15px; display: none;"></div>

                <div style="margin-top: 15px; font-size: 0.9rem; color: #666;">
                    <i class="fas fa-shield-alt"></i> Tu información está segura. No compartiremos tu email con terceros.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pie de página -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-logo">
                <i class="fas fa-newspaper"></i>
                <h3>IMPACTO-DIARIO</h3>
                <p>
                    Portal informativo de la carrera de Periodismo<br>
                    Universidad Técnica de Babahoyo
                </p>
            </div>

            <div class="footer-links">
                <h4>Enlaces Rápidos</h4>
                <ul>
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#noticias">Noticias</a></li>
                    <li><a href="#acerca">Acerca de</a></li>
                    <li><a href="#contacto">Contacto</a></li>
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
