<?php
// todas_noticias.php
require_once 'config/db.php';

$conn = conectarDB();

// Paginación
$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$por_pagina = 12;
$offset = ($pagina - 1) * $por_pagina;

// Contar total de noticias
$sql_count = "SELECT COUNT(*) as total FROM noticias WHERE estado = 'publicada'";
$total_noticias = $conn->query($sql_count)->fetch_assoc()['total'];
$total_paginas = ceil($total_noticias / $por_pagina);

// Obtener noticias paginadas
$sql_noticias = "SELECT n.*, 
    (SELECT COUNT(*) FROM noticias_subtitulos WHERE noticia_id = n.id) as subtitulos_count
    FROM noticias n 
    WHERE n.estado = 'publicada'
    ORDER BY n.fecha_creacion DESC 
    LIMIT $offset, $por_pagina";
$result = $conn->query($sql_noticias);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todas las Noticias - Periodismo UTB</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .todas-noticias {
            padding: 140px 0 60px;
        }
        
        .header-filtros {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .filtros {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .filtro-btn {
            padding: 8px 20px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .filtro-btn.active {
            background: #1a5fb4;
            color: white;
            border-color: #1a5fb4;
        }
        
        .paginacion {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 50px;
            flex-wrap: wrap;
        }
        
        .pagina-btn {
            padding: 8px 15px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }
        
        .pagina-btn.active {
            background: #1a5fb4;
            color: white;
            border-color: #1a5fb4;
        }
        
        .pagina-btn:hover:not(.active) {
            background: #f8f9fa;
        }
        
        .info-paginacion {
            text-align: center;
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container nav-container">
            <a href="index.php" class="logo" style="display: flex; align-items: center; gap: 15px;">
                <img src="assets/WhatsApp Image 2026-01-22 at 8.41.20 PM.jpeg" 
                     alt="Periodismo UTB" 
                     style="height: 70px; border-radius: 5px; box-shadow: 0 3px 20px rgba(0,0,0,0.15);">
                <div style="line-height: 1.2;">
                    <div style="font-size: 1.4rem; font-weight: 700; color: #1a5fb4;">
                        <i class="fas fa-newspaper"></i> Periodismo UTB
                    </div>
                </div>
            </a>
            
            <ul class="nav-menu">
                <li><a href="index.php#inicio">Inicio</a></li>
                <li><a href="index.php#noticias" class="active">Noticias</a></li>
                <li><a href="index.php#acerca">Acerca</a></li>
                <li><a href="index.php#contacto">Contacto</a></li>
            </ul>
        </div>
    </nav>

    <section class="todas-noticias">
        <div class="container">
            <h1 class="section-title">Todas las Noticias</h1>
            
            <div class="header-filtros">
                <div class="info-paginacion">
                    Mostrando <?php echo min($por_pagina, $total_noticias - $offset); ?> de <?php echo $total_noticias; ?> noticias
                </div>
                
                <div class="filtros">
                    <button class="filtro-btn active">Todas</button>
                    <button class="filtro-btn">Recientes</button>
                    <button class="filtro-btn">Destacadas</button>
                </div>
            </div>
            
            <div class="noticias-grid">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="noticia-card">
                            <div class="noticia-img">
                                <?php
                                $imagen = 'https://placehold.co/400x300/1a5fb4/ffffff?text=Noticia';
                                if (!empty($row['imagen_principal'])) {
                                    $imagen = 'img/uploads/' . $row['imagen_principal'];
                                } elseif (!empty($row['link_imagen_principal'])) {
                                    $imagen = $row['link_imagen_principal'];
                                }
                                ?>
                                <img src="<?php echo $imagen; ?>" alt="<?php echo htmlspecialchars($row['titulo']); ?>"
                                     onerror="this.onerror=null; this.src='https://placehold.co/400x300/1a5fb4/ffffff?text=Noticia'">
                            </div>
                            <div class="noticia-content">
                                <span class="noticia-categoria"><?php echo htmlspecialchars($row['categoria']); ?></span>
                                <h3 class="noticia-titulo"><?php echo htmlspecialchars($row['titulo']); ?></h3>
                                <p class="noticia-desc"><?php echo substr(htmlspecialchars($row['descripcion']), 0, 150) . '...'; ?></p>
                                <div class="card-footer">
                                    <div class="noticia-info">
                                        <span><i class="far fa-calendar"></i> <?php echo date('d/m/Y', strtotime($row['fecha_creacion'])); ?></span>
                                        <span><i class="far fa-user"></i> <?php echo htmlspecialchars($row['autor']); ?></span>
                                        <span><i class="far fa-file-alt"></i> <?php echo $row['subtitulos_count']; ?> secciones</span>
                                    </div>
                                    <a href="ver_noticia.php?id=<?php echo $row['id']; ?>" class="ver-mas-btn">
                                        <i class="far fa-eye"></i> Ver Más
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="grid-column: 1/-1; text-align: center; padding: 40px; color: #666;">
                        No hay noticias disponibles.
                    </p>
                <?php endif; ?>
            </div>
            
            <!-- Paginación -->
            <?php if ($total_paginas > 1): ?>
                <div class="paginacion">
                    <?php if ($pagina > 1): ?>
                        <a href="?pagina=<?php echo $pagina - 1; ?>" class="pagina-btn">
                            <i class="fas fa-chevron-left"></i> Anterior
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <?php if ($i == 1 || $i == $total_paginas || ($i >= $pagina - 2 && $i <= $pagina + 2)): ?>
                            <a href="?pagina=<?php echo $i; ?>" 
                               class="pagina-btn <?php echo ($i == $pagina) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php elseif ($i == $pagina - 3 || $i == $pagina + 3): ?>
                            <span class="pagina-btn">...</span>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($pagina < $total_paginas): ?>
                        <a href="?pagina=<?php echo $pagina + 1; ?>" class="pagina-btn">
                            Siguiente <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
                
                <div class="info-paginacion">
                    Página <?php echo $pagina; ?> de <?php echo $total_paginas; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Periodismo UTB - Universidad Técnica de Babahoyo. Todos los derechos reservados.</p>
                <p><a href="index.php" style="color: #1a5fb4;">← Volver al inicio</a></p>
            </div>
        </div>
    </footer>

    <script src="js/script.js"></script>
    <script>
        // Filtros (ejemplo básico)
        document.querySelectorAll('.filtro-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Aquí iría la lógica de filtrado real
                // Por ahora solo muestra un mensaje
                console.log('Filtrar por:', this.textContent);
            });
        });
    </script>
</body>
</html>