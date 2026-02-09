<?php
session_start();
require_once '../includes/config.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$mensaje = '';
$error = '';

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // DATOS DE LA NOTICIA PRINCIPAL
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $descripcion_larga = $_POST['descripcion_larga'] ?? '';
    $contenido = $_POST['contenido'] ?? '';
    $contenido_completo = $_POST['contenido_completo'] ?? '';
    $categoria = $_POST['categoria'] ?? 'General';
    $autor = $_POST['autor'] ?? 'Estudiante de Periodismo UTB';
    
    // Validaciones
    $errores = [];
    
    if (empty($titulo)) $errores[] = 'El título es obligatorio';
    if (strlen($descripcion) < 100) $errores[] = 'La descripción debe tener al menos 100 caracteres';
    if (strlen($descripcion_larga) < 500) $errores[] = 'La descripción larga debe tener al menos 500 caracteres';
    if (strlen($contenido) < 500) $errores[] = 'El contenido debe tener al menos 500 caracteres';
    if (strlen($contenido_completo) < 5000) $errores[] = 'El contenido completo debe tener al menos 5000 caracteres';
    
    if (!empty($errores)) {
        $error = implode('<br>', $errores);
    } else {
        // Manejar imagen principal
        $imagen_nombre = '';
        if (isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error'] === 0) {
            $nombre_temporal = $_FILES['imagen_principal']['tmp_name'];
            $nombre_original = $_FILES['imagen_principal']['name'];
            
            if (getimagesize($nombre_temporal) !== false) {
                $extension = pathinfo($nombre_original, PATHINFO_EXTENSION);
                $imagen_nombre = 'noticia_' . uniqid() . '_' . time() . '.' . $extension;
                $ruta_destino = '../img/uploads/' . $imagen_nombre;
                move_uploaded_file($nombre_temporal, $ruta_destino);
            }
        }
        
        // Manejar video principal
        $video_principal = $_POST['video_principal'] ?? '';
        
        // Manejar audio principal
        $audio_principal = '';
        if (isset($_FILES['audio_principal']) && $_FILES['audio_principal']['error'] === 0) {
            $extensiones_audio = ['mp3', 'wav', 'ogg', 'm4a'];
            $extension = strtolower(pathinfo($_FILES['audio_principal']['name'], PATHINFO_EXTENSION));
            if (in_array($extension, $extensiones_audio)) {
                $audio_principal = 'audio_' . uniqid() . '_' . time() . '.' . $extension;
                move_uploaded_file($_FILES['audio_principal']['tmp_name'], '../admin/audios/uploads/' . $audio_principal);
            }
        }
        
        // Manejar link principal
        $link_principal = $_POST['link_principal'] ?? '';
        
        // Insertar noticia principal
        $stmt = $conn->prepare("INSERT INTO noticias (titulo, descripcion, descripcion_larga, contenido, contenido_completo, imagen, video_principal, audio_principal, link_principal, categoria, autor, fecha_publicacion) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        
        if ($stmt) {
            $stmt->bind_param("sssssssss", $titulo, $descripcion, $descripcion_larga, $contenido, $contenido_completo, $imagen_nombre, $video_principal, $audio_principal, $link_principal);
            // Note: The last parameter should be categoria (string) so we need to bind it separately
            
            $stmt->close();
        }
        
        // Intentar con una nueva conexión para bind_param con más de 9 parámetros
        $titulo_e = $conn->real_escape_string($titulo);
        $descripcion_e = $conn->real_escape_string($descripcion);
        $descripcion_larga_e = $conn->real_escape_string($descripcion_larga);
        $contenido_e = $conn->real_escape_string($contenido);
        $contenido_completo_e = $conn->real_escape_string($contenido_completo);
        $categoria_e = $conn->real_escape_string($categoria);
        $autor_e = $conn->real_escape_string($autor);
        
        $sql_noticia = "INSERT INTO noticias (titulo, descripcion, descripcion_larga, contenido, contenido_completo, imagen, video_principal, audio_principal, link_principal, categoria, autor, fecha_publicacion) 
                        VALUES ('$titulo_e', '$descripcion_e', '$descripcion_larga_e', '$contenido_e', '$contenido_completo_e', '$imagen_nombre', '$video_principal', '$audio_principal', '$link_principal', '$categoria_e', '$autor_e', NOW())";
        
        if ($conn->query($sql_noticia)) {
            $noticia_id = $conn->insert_id;
            
            // PROCESAR SUBTÍTULOS
            $subtitulos_procesadas = 0;
            for ($i = 1; $i <= 5; $i++) {
                $subtitulo_titulo = $_POST["subtitulo_titulo_$i"] ?? '';
                $subtitulo_desc = $_POST["subtitulo_descripcion_$i"] ?? '';
                $subtitulo_cont = $_POST["subtitulo_contenido_$i"] ?? '';
                
                // Los subtítulos deben tener contenido
                if (!empty($subtitulo_titulo) && strlen($subtitulo_desc) >= 100 && strlen($subtitulo_cont) >= 500) {
                    // Manejar imagen del subtítulo
                    $subtitulo_imagen = '';
                    if (isset($_FILES["subtitulo_imagen_$i"]) && $_FILES["subtitulo_imagen_$i"]['error'] === 0) {
                        $nombre_temporal = $_FILES["subtitulo_imagen_$i"]['tmp_name'];
                        $nombre_original = $_FILES["subtitulo_imagen_$i"]['name'];
                        
                        if (getimagesize($nombre_temporal) !== false) {
                            $extension = pathinfo($nombre_original, PATHINFO_EXTENSION);
                            $subtitulo_imagen = 'subtitulo_' . $noticia_id . '_' . $i . '_' . uniqid() . '.' . $extension;
                            move_uploaded_file($nombre_temporal, '../img/uploads/' . $subtitulo_imagen);
                        }
                    }
                    
                    // Manejar video del subtítulo
                    $subtitulo_video = $_POST["subtitulo_video_$i"] ?? '';
                    
                    // Manejar audio del subtítulo
                    $subtitulo_audio = '';
                    if (isset($_FILES["subtitulo_audio_$i"]) && $_FILES["subtitulo_audio_$i"]['error'] === 0) {
                        $extensiones_audio = ['mp3', 'wav', 'ogg', 'm4a'];
                        $extension = strtolower(pathinfo($_FILES["subtitulo_audio_$i"]['name'], PATHINFO_EXTENSION));
                        if (in_array($extension, $extensiones_audio)) {
                            $subtitulo_audio = 'subtitulo_audio_' . $noticia_id . '_' . $i . '.' . $extension;
                            move_uploaded_file($_FILES["subtitulo_audio_$i"]['tmp_name'], '../admin/audios/uploads/' . $subtitulo_audio);
                        }
                    }
                    
                    // Manejar link del subtítulo
                    $subtitulo_link = $_POST["subtitulo_link_$i"] ?? '';
                    
                    // Insertar subtítulo
                    $subtitulo_titulo_e = $conn->real_escape_string($subtitulo_titulo);
                    $subtitulo_desc_e = $conn->real_escape_string($subtitulo_desc);
                    $subtitulo_cont_e = $conn->real_escape_string($subtitulo_cont);
                    
                    $sql_subtitulo = "INSERT INTO noticias_subtitulos (noticia_id, numero_subtitulo, subtitulo, descripcion, contenido, imagen, video, audio, link, orden) 
                                      VALUES ($noticia_id, $i, '$subtitulo_titulo_e', '$subtitulo_desc_e', '$subtitulo_cont_e', '$subtitulo_imagen', '$subtitulo_video', '$subtitulo_audio', '$subtitulo_link', $i)";
                    
                    if ($conn->query($sql_subtitulo)) {
                        $subtitulos_procesadas++;
                    }
                }
            }
            
            if ($subtitulos_procesadas < 5) {
                $mensaje = "Noticia creada pero solo se agregaron $subtitulos_procesadas subtítulos. Se requieren al menos 5.";
            } else {
                $mensaje = 'Noticia y subtítulos creados correctamente';
            }
            
            echo "<script>setTimeout(function() { window.location.href = 'gestionar_noticias.php?mensaje=" . urlencode($mensaje) . "'; }, 2000);</script>";
        } else {
            $error = 'Error al guardar la noticia: ' . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Noticia Completa - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-section { background: white; padding: 20px; margin: 15px 0; border-radius: 8px; border-left: 4px solid #1a5fb4; }
        .form-section h3 { color: #1a5fb4; margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; color: #333; }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; }
        .form-group textarea { resize: vertical; min-height: 100px; }
        .char-count { font-size: 0.85rem; color: #666; margin-top: 5px; }
        .char-count.warning { color: #FF6B6B; }
        .char-count.ok { color: #4CAF50; }
        .subtitulo-section { background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ddd; }
        .btn { background: #1a5fb4; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; }
        .btn:hover { background: #135a9c; }
        .error-msg { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin: 15px 0; }
        .success-msg { background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin: 15px 0; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <?php include 'header.php'; ?>
        
        <h1 style="text-align: center; color: #333; margin: 30px 0;">
            <i class="fas fa-newspaper"></i> Crear Noticia Completa
        </h1>
        
        <?php if ($error): ?>
            <div class="error-msg">
                <i class="fas fa-exclamation-circle"></i> <?php echo nl2br($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($mensaje): ?>
            <div class="success-msg">
                <i class="fas fa-check-circle"></i> <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            
            <!-- SECCIÓN: INFORMACIÓN PRINCIPAL -->
            <div class="form-section">
                <h3><i class="fas fa-heading"></i> Información Principal de la Noticia</h3>
                
                <div class="form-group">
                    <label for="titulo">Título Principal *</label>
                    <input type="text" id="titulo" name="titulo" required placeholder="Ingresa el título principal de la noticia">
                </div>
                
                <div class="form-group">
                    <label for="categoria">Categoría *</label>
                    <select id="categoria" name="categoria" required>
                        <option value="Deporte">Deporte</option>
                        <option value="Tecnologia">Tecnologia</option>
                        <option value="Sociedad">Sociedad</option>
                        <option value="Educacion">Educacion</option>
                        <option value="Internacional">Internacional</option>
                        <option value="Local/Regional">Local/Regional</option>
                        <option value="Politica">Politica</option>
                        <option value="Negocios">Negocios</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="autor">Autor</label>
                    <input type="text" id="autor" name="autor" placeholder="Nombre del autor" value="Estudiante de Periodismo UTB">
                </div>
            </div>
            
            <!-- SECCIÓN: DESCRIPCIÓN -->
            <div class="form-section">
                <h3><i class="fas fa-align-left"></i> Descripción</h3>
                
                <div class="form-group">
                    <label for="descripcion">Descripción Corta (100-500 caracteres) *</label>
                    <textarea id="descripcion" name="descripcion" required placeholder="Escribe una descripción breve que aparecerá en los listados" minlength="100" maxlength="500"></textarea>
                    <div class="char-count"><span id="desc-count">0</span>/500 caracteres</div>
                </div>
                
                <div class="form-group">
                    <label for="descripcion_larga">Descripción Larga (mínimo 500 caracteres) *</label>
                    <textarea id="descripcion_larga" name="descripcion_larga" required placeholder="Escribe una descripción más detallada (mínimo 500 caracteres)" minlength="500"></textarea>
                    <div class="char-count"><span id="desc-larga-count">0</span> caracteres (mínimo 500)</div>
                </div>
            </div>
            
            <!-- SECCIÓN: CONTENIDO -->
            <div class="form-section">
                <h3><i class="fas fa-file-alt"></i> Contenido Principal</h3>
                
                <div class="form-group">
                    <label for="contenido">Contenido (mínimo 500 caracteres) *</label>
                    <textarea id="contenido" name="contenido" required placeholder="Contenido de la noticia" minlength="500"></textarea>
                    <div class="char-count"><span id="cont-count">0</span> caracteres (mínimo 500)</div>
                </div>
                
                <div class="form-group">
                    <label for="contenido_completo">Contenido Completo (mínimo 5000 caracteres) *</label>
                    <textarea id="contenido_completo" name="contenido_completo" required placeholder="Contenido completo y detallado de la noticia (mínimo 5000 caracteres)" minlength="5000"></textarea>
                    <div class="char-count"><span id="cont-completo-count">0</span> caracteres (mínimo 5000)</div>
                </div>
            </div>
            
            <!-- SECCIÓN: MULTIMEDIA PRINCIPAL -->
            <div class="form-section">
                <h3><i class="fas fa-image"></i> Multimedia Principal</h3>
                
                <div class="form-group">
                    <label for="imagen_principal">Imagen Principal</label>
                    <input type="file" id="imagen_principal" name="imagen_principal" accept="image/*">
                    <small>Formatos aceptados: JPG, PNG, GIF (máximo 5MB)</small>
                </div>
                
                <div class="form-group">
                    <label for="video_principal">Link de Video (YouTube, Vimeo, etc.)</label>
                    <input type="text" id="video_principal" name="video_principal" placeholder="https://www.youtube.com/watch?v=...">
                </div>
                
                <div class="form-group">
                    <label for="audio_principal">Archivo de Audio</label>
                    <input type="file" id="audio_principal" name="audio_principal" accept="audio/*">
                    <small>Formatos aceptados: MP3, WAV, OGG, M4A (máximo 50MB)</small>
                </div>
                
                <div class="form-group">
                    <label for="link_principal">Link Externo</label>
                    <input type="text" id="link_principal" name="link_principal" placeholder="https://...">
                </div>
            </div>
            
            <!-- SECCIÓN: SUBTÍTULOS -->
            <div class="form-section">
                <h3><i class="fas fa-list-ol"></i> Subtítulos (Se requieren 5 mínimo)</h3>
                <p style="color: #666; margin-bottom: 20px;">Cada subtítulo debe tener título, descripción (mínimo 100 caracteres) y contenido (mínimo 500 caracteres)</p>
                
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="subtitulo-section">
                        <h4 style="color: #333; margin-bottom: 15px;">
                            <i class="fas fa-heading"></i> Subtítulo #<?php echo $i; ?>
                        </h4>
                        
                        <div class="form-group">
                            <label for="subtitulo_titulo_<?php echo $i; ?>">Título del Subtítulo *</label>
                            <input type="text" id="subtitulo_titulo_<?php echo $i; ?>" name="subtitulo_titulo_<?php echo $i; ?>" placeholder="Título del subtítulo <?php echo $i; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subtitulo_descripcion_<?php echo $i; ?>">Descripción (mínimo 100 caracteres) *</label>
                            <textarea id="subtitulo_descripcion_<?php echo $i; ?>" name="subtitulo_descripcion_<?php echo $i; ?>" placeholder="Descripción del subtítulo" minlength="100" required></textarea>
                            <div class="char-count"><span id="subtitulo-desc-<?php echo $i; ?>-count">0</span> caracteres (mínimo 100)</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="subtitulo_contenido_<?php echo $i; ?>">Contenido (mínimo 500 caracteres) *</label>
                            <textarea id="subtitulo_contenido_<?php echo $i; ?>" name="subtitulo_contenido_<?php echo $i; ?>" placeholder="Contenido del subtítulo" minlength="500" required></textarea>
                            <div class="char-count"><span id="subtitulo-cont-<?php echo $i; ?>-count">0</span> caracteres (mínimo 500)</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="subtitulo_imagen_<?php echo $i; ?>">Imagen</label>
                            <input type="file" id="subtitulo_imagen_<?php echo $i; ?>" name="subtitulo_imagen_<?php echo $i; ?>" accept="image/*">
                        </div>
                        
                        <div class="form-group">
                            <label for="subtitulo_video_<?php echo $i; ?>">Link de Video</label>
                            <input type="text" id="subtitulo_video_<?php echo $i; ?>" name="subtitulo_video_<?php echo $i; ?>" placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                        
                        <div class="form-group">
                            <label for="subtitulo_audio_<?php echo $i; ?>">Archivo de Audio</label>
                            <input type="file" id="subtitulo_audio_<?php echo $i; ?>" name="subtitulo_audio_<?php echo $i; ?>" accept="audio/*">
                        </div>
                        
                        <div class="form-group">
                            <label for="subtitulo_link_<?php echo $i; ?>">Link Externo</label>
                            <input type="text" id="subtitulo_link_<?php echo $i; ?>" name="subtitulo_link_<?php echo $i; ?>" placeholder="https://...">
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
            
            <!-- BOTONES DE ACCIÓN -->
            <div style="text-align: center; margin: 30px 0;">
                <button type="submit" class="btn" style="background: #4CAF50; margin-right: 10px;">
                    <i class="fas fa-save"></i> Guardar Noticia Completa
                </button>
                <a href="gestionar_noticias.php" class="btn" style="background: #999; text-decoration: none; display: inline-block;">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
        
        <?php include 'footer.php'; ?>
    </div>
    
    <script>
        // Contadores de caracteres
        document.getElementById('descripcion').addEventListener('input', function() {
            document.getElementById('desc-count').textContent = this.value.length;
        });
        
        document.getElementById('descripcion_larga').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('desc-larga-count').textContent = count;
            document.getElementById('desc-larga-count').parentElement.classList.toggle('ok', count >= 500);
        });
        
        document.getElementById('contenido').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('cont-count').textContent = count;
            document.getElementById('cont-count').parentElement.classList.toggle('ok', count >= 500);
        });
        
        document.getElementById('contenido_completo').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('cont-completo-count').textContent = count;
            document.getElementById('cont-completo-count').parentElement.classList.toggle('ok', count >= 5000);
        });
        
        // Contadores de subtítulos
        <?php for ($i = 1; $i <= 5; $i++): ?>
            document.getElementById('subtitulo_descripcion_<?php echo $i; ?>').addEventListener('input', function() {
                const count = this.value.length;
                document.getElementById('subtitulo-desc-<?php echo $i; ?>-count').textContent = count;
                document.getElementById('subtitulo-desc-<?php echo $i; ?>-count').parentElement.classList.toggle('ok', count >= 100);
            });
            
            document.getElementById('subtitulo_contenido_<?php echo $i; ?>').addEventListener('input', function() {
                const count = this.value.length;
                document.getElementById('subtitulo-cont-<?php echo $i; ?>-count').textContent = count;
                document.getElementById('subtitulo-cont-<?php echo $i; ?>-count').parentElement.classList.toggle('ok', count >= 500);
            });
        <?php endfor; ?>
    </script>
</body>
</html>
