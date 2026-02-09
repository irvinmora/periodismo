<?php
session_start();
require_once '../config/database.php';

$db = Database::getInstance();
$conn = $db->getConnection();

if (!$db->isConnected()) {
    die('Error: No se pudo conectar a la base de datos');
}

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$mensaje = '';
$error = '';
$noticia_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($noticia_id <= 0) {
    header('Location: gestionar_noticias.php');
    exit();
}

$sql = "SELECT * FROM noticias WHERE id = $noticia_id";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    header('Location: gestionar_noticias.php');
    exit();
}

$noticia = $result->fetch_assoc();
$subtitulos = [];
$sql_subs = "SELECT * FROM noticias_subtitulos WHERE noticia_id = $noticia_id ORDER BY numero_subtitulo";
$result_subs = $conn->query($sql_subs);
if ($result_subs) {
    while ($sub = $result_subs->fetch_assoc()) {
        $subtitulos[$sub['numero_subtitulo']] = $sub;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $contenido_completo = trim($_POST['contenido_completo'] ?? '');
    $categoria = trim($_POST['categoria'] ?? 'General');
    $autor = trim($_POST['autor'] ?? '');
    $estado = trim($_POST['estado'] ?? 'publicada');
    $destacada = isset($_POST['destacada']) ? 1 : 0;
    
    if (empty($titulo) || strlen($titulo) < 5) {
        $error = 'El titulo debe tener al menos 5 caracteres';
    } else {
        try {
            $titulo = $conn->real_escape_string($titulo);
            $descripcion = $conn->real_escape_string($descripcion);
            $contenido_completo = $conn->real_escape_string($contenido_completo);
            $categoria = $conn->real_escape_string($categoria);
            $autor = $conn->real_escape_string($autor);
            $estado = $conn->real_escape_string($estado);
            
            $imagen_parte = '';
            if (isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error'] === 0) {
                $archivo_tipo = $_FILES['imagen_principal']['type'];
                if (strpos($archivo_tipo, 'image/') === 0) {
                    $archivo_temp = $_FILES['imagen_principal']['tmp_name'];
                    $archivo_original = $_FILES['imagen_principal']['name'];
                    $extension = strtolower(pathinfo($archivo_original, PATHINFO_EXTENSION));
                    $imagen_nombre = 'principal_' . uniqid() . '_' . time() . '.' . $extension;
                    $ruta = '../img/uploads/' . $imagen_nombre;
                    
                    if (@move_uploaded_file($archivo_temp, $ruta)) {
                        $imagen_parte = ", imagen = '$imagen_nombre'";
                    }
                }
            }
            
            $sql_update = "UPDATE noticias SET titulo = '$titulo', descripcion = '$descripcion', contenido_completo = '$contenido_completo', 
                          categoria = '$categoria', autor = '$autor', estado = '$estado', destacada = $destacada $imagen_parte WHERE id = $noticia_id";
            
            if (!$conn->query($sql_update)) {
                throw new Exception('Error al actualizar noticia: ' . $conn->error);
            }
            
            for ($i = 1; $i <= 5; $i++) {
                $sub_titulo = trim($_POST["subtitulo_titulo_$i"] ?? '');
                $sub_desc = trim($_POST["subtitulo_desc_$i"] ?? '');
                $sub_contenido = trim($_POST["subtitulo_contenido_$i"] ?? '');
                $sub_video = trim($_POST["subtitulo_video_$i"] ?? '');
                $sub_link = trim($_POST["subtitulo_link_$i"] ?? '');
                
                $sub_titulo = $conn->real_escape_string($sub_titulo);
                $sub_desc = $conn->real_escape_string($sub_desc);
                $sub_contenido = $conn->real_escape_string($sub_contenido);
                $sub_video = $conn->real_escape_string($sub_video);
                $sub_link = $conn->real_escape_string($sub_link);
                
                $sub_img = '';
                $sub_audio = '';
                
                if (isset($_FILES["subtitulo_imagen_$i"]) && $_FILES["subtitulo_imagen_$i"]['error'] === 0) {
                    $archivo_tipo = $_FILES["subtitulo_imagen_$i"]['type'];
                    if (strpos($archivo_tipo, 'image/') === 0) {
                        $archivo_temp = $_FILES["subtitulo_imagen_$i"]['tmp_name'];
                        $archivo_original = $_FILES["subtitulo_imagen_$i"]['name'];
                        $extension = strtolower(pathinfo($archivo_original, PATHINFO_EXTENSION));
                        $sub_img = 'sub_' . $i . '_' . uniqid() . '.' . $extension;
                        $ruta = '../img/uploads/' . $sub_img;
                        @move_uploaded_file($archivo_temp, $ruta);
                    }
                }
                
                if (isset($_FILES["subtitulo_audio_$i"]) && $_FILES["subtitulo_audio_$i"]['error'] === 0) {
                    $archivo_tipo = $_FILES["subtitulo_audio_$i"]['type'];
                    if (strpos($archivo_tipo, 'audio/') === 0) {
                        $archivo_temp = $_FILES["subtitulo_audio_$i"]['tmp_name'];
                        $archivo_original = $_FILES["subtitulo_audio_$i"]['name'];
                        $extension = strtolower(pathinfo($archivo_original, PATHINFO_EXTENSION));
                        $sub_audio = 'sub_' . $i . '_' . uniqid() . '.' . $extension;
                        $ruta = '../admin/audios/uploads/' . $sub_audio;
                        @move_uploaded_file($archivo_temp, $ruta);
                    }
                }
                
                if (!empty($sub_titulo) || !empty($sub_desc) || !empty($sub_contenido)) {
                    $sql_check = "SELECT id FROM noticias_subtitulos WHERE noticia_id = $noticia_id AND numero_subtitulo = $i";
                    $result_check = $conn->query($sql_check);
                    
                    if ($result_check && $result_check->num_rows > 0) {
                        $img_parte = !empty($sub_img) ? ", imagen = '$sub_img'" : '';
                        $audio_parte = !empty($sub_audio) ? ", audio = '$sub_audio'" : '';
                        
                        $sql_update_sub = "UPDATE noticias_subtitulos SET subtitulo = '$sub_titulo', descripcion = '$sub_desc', 
                                         contenido = '$sub_contenido', video = '$sub_video', link = '$sub_link' $img_parte $audio_parte
                                         WHERE noticia_id = $noticia_id AND numero_subtitulo = $i";
                        @$conn->query($sql_update_sub);
                    } else {
                        $sql_insert_sub = "INSERT INTO noticias_subtitulos (noticia_id, numero_subtitulo, subtitulo, descripcion, contenido, imagen, video, audio, link, orden) 
                                          VALUES ($noticia_id, $i, '$sub_titulo', '$sub_desc', '$sub_contenido', '$sub_img', '$sub_video', '$sub_audio', '$sub_link', $i)";
                        @$conn->query($sql_insert_sub);
                    }
                } else {
                    $sql_delete_sub = "DELETE FROM noticias_subtitulos WHERE noticia_id = $noticia_id AND numero_subtitulo = $i";
                    @$conn->query($sql_delete_sub);
                }
            }
            
            $mensaje = 'Noticia y subtitulos actualizados correctamente';
            $sql = "SELECT * FROM noticias WHERE id = $noticia_id";
            $result = $conn->query($sql);
            $noticia = $result->fetch_assoc();
            $subtitulos = [];
            $sql_subs = "SELECT * FROM noticias_subtitulos WHERE noticia_id = $noticia_id ORDER BY numero_subtitulo";
            $result_subs = $conn->query($sql_subs);
            if ($result_subs) {
                while ($sub = $result_subs->fetch_assoc()) {
                    $subtitulos[$sub['numero_subtitulo']] = $sub;
                }
            }
            
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Noticia - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f5f5f5; }
        .admin-container { padding: 120px 20px 20px; max-width: 1100px; margin: 0 auto; }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 20px; }
        h1, h2 { color: #1a5fb4; margin-top: 0; }
        h1 { font-size: 2rem; margin-bottom: 30px; }
        h2 { font-size: 1.5rem; margin-top: 30px; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #1a5fb4; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; font-family: inherit; }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: #1a5fb4; box-shadow: 0 0 0 3px rgba(26,95,180,0.1); }
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .alert-error { background: #f8d7da; color: #721c24; }
        .alert-success { background: #d4edda; color: #155724; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
        .checkbox-group { display: flex; align-items: center; gap: 10px; }
        .checkbox-group input { width: auto; }
        .buttons { display: flex; gap: 15px; margin-top: 30px; }
        .btn { padding: 12px 30px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-block; }
        .btn-primary { background: #1a5fb4; color: white; flex: 1; }
        .btn-primary:hover { background: #135a9c; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #5a6268; }
        .subtitulos-section { background: #f8f9fa; padding: 25px; border-radius: 8px; margin-top: 30px; border-left: 4px solid #1a5fb4; }
        .subtitulo-card { background: white; padding: 20px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #ddd; }
        .subtitulo-card h3 { color: #1a5fb4; margin-top: 0; font-size: 1.2rem; }
        .file-info { font-size: 0.85rem; color: #666; margin-top: 5px; }
        .noticia-id { background: #e7f3ff; padding: 10px 15px; border-left: 4px solid #1a5fb4; margin-bottom: 20px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="noticia-id">
            <strong>ID Noticia:</strong> <?php echo htmlspecialchars($noticia_id); ?>
        </div>
        
        <h1><i class="fas fa-edit"></i> Editar Noticia Completa con Subtitulos</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($mensaje): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" class="form-container">
            <h2><i class="fas fa-newspaper"></i> Noticia Principal</h2>
            
            <div class="form-group">
                <label for="titulo"><i class="fas fa-heading"></i> Titulo Principal *</label>
                <input type="text" id="titulo" name="titulo" required minlength="5" maxlength="255" value="<?php echo htmlspecialchars($noticia['titulo']); ?>">
            </div>
            
            <div class="form-group">
                <label for="descripcion"><i class="fas fa-align-left"></i> Descripcion Breve *</label>
                <textarea id="descripcion" name="descripcion" required minlength="20" maxlength="500"><?php echo htmlspecialchars($noticia['descripcion']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="contenido_completo"><i class="fas fa-file-alt"></i> Contenido Completo</label>
                <textarea id="contenido_completo" name="contenido_completo"><?php echo htmlspecialchars($noticia['contenido_completo']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="imagen_principal"><i class="fas fa-image"></i> Cambiar Imagen Principal</label>
                <input type="file" id="imagen_principal" name="imagen_principal" accept="image/*">
                <div class="file-info">Deja vacio para mantener la imagen actual</div>
                <?php if (!empty($noticia['imagen'])): ?>
                    <div class="file-info">Imagen actual: <?php echo htmlspecialchars($noticia['imagen']); ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="categoria"><i class="fas fa-tag"></i> Categoria</label>
                    <select id="categoria" name="categoria">
                        <option value="Deporte" <?php echo $noticia['categoria'] === 'Deporte' ? 'selected' : ''; ?>>Deporte</option>
                        <option value="Tecnologia" <?php echo $noticia['categoria'] === 'Tecnologia' ? 'selected' : ''; ?>>Tecnologia</option>
                        <option value="Sociedad" <?php echo $noticia['categoria'] === 'Sociedad' ? 'selected' : ''; ?>>Sociedad</option>
                        <option value="Educacion" <?php echo $noticia['categoria'] === 'Educacion' ? 'selected' : ''; ?>>Educacion</option>
                        <option value="Internacional" <?php echo $noticia['categoria'] === 'Internacional' ? 'selected' : ''; ?>>Internacional</option>
                        <option value="Local/Regional" <?php echo $noticia['categoria'] === 'Local/Regional' ? 'selected' : ''; ?>>Local/Regional</option>
                        <option value="Politica" <?php echo $noticia['categoria'] === 'Politica' ? 'selected' : ''; ?>>Politica</option>
                        <option value="Negocios" <?php echo $noticia['categoria'] === 'Negocios' ? 'selected' : ''; ?>>Negocios</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="autor"><i class="fas fa-user"></i> Autor</label>
                    <input type="text" id="autor" name="autor" value="<?php echo htmlspecialchars($noticia['autor']); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="estado"><i class="fas fa-filter"></i> Estado</label>
                    <select id="estado" name="estado">
                        <option value="publicada" <?php echo $noticia['estado'] === 'publicada' ? 'selected' : ''; ?>>Publicada</option>
                        <option value="borrador" <?php echo $noticia['estado'] === 'borrador' ? 'selected' : ''; ?>>Borrador</option>
                        <option value="archivada" <?php echo $noticia['estado'] === 'archivada' ? 'selected' : ''; ?>>Archivada</option>
                    </select>
                </div>
                <div class="form-group checkbox-group">
                    <input type="checkbox" id="destacada" name="destacada" value="1" <?php echo $noticia['destacada'] ? 'checked' : ''; ?>>
                    <label for="destacada" style="margin: 0;"><i class="fas fa-star"></i> Destacar esta noticia</label>
                </div>
            </div>
            
            <div class="subtitulos-section">
                <h2><i class="fas fa-list-ol"></i> Editar Subtitulos (Opcional - 5 maximo)</h2>
                
                <?php for ($i = 1; $i <= 5; $i++): 
                    $sub = $subtitulos[$i] ?? [];
                ?>
                    <div class="subtitulo-card">
                        <h3>Subtitulo #<?php echo $i; ?></h3>
                        
                        <div class="form-group">
                            <label for="subtitulo_titulo_<?php echo $i; ?>"><i class="fas fa-bold"></i> Titulo (Negrita)</label>
                            <input type="text" id="subtitulo_titulo_<?php echo $i; ?>" name="subtitulo_titulo_<?php echo $i; ?>" maxlength="255" value="<?php echo htmlspecialchars($sub['subtitulo'] ?? ''); ?>" placeholder="Titulo del subtitulo en negrita">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="subtitulo_desc_<?php echo $i; ?>"><i class="fas fa-quote-left"></i> Descripcion Breve</label>
                                <textarea id="subtitulo_desc_<?php echo $i; ?>" name="subtitulo_desc_<?php echo $i; ?>" style="min-height: 80px;" placeholder="Comentario breve para este subtitulo"><?php echo htmlspecialchars($sub['descripcion'] ?? ''); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="subtitulo_imagen_<?php echo $i; ?>"><i class="fas fa-image"></i> Cambiar Imagen</label>
                                <input type="file" id="subtitulo_imagen_<?php echo $i; ?>" name="subtitulo_imagen_<?php echo $i; ?>" accept="image/*">
                                <?php if (!empty($sub['imagen'])): ?>
                                    <div class="file-info">Actual: <?php echo htmlspecialchars($sub['imagen']); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="subtitulo_contenido_<?php echo $i; ?>"><i class="fas fa-file-alt"></i> Contenido Completo</label>
                            <textarea id="subtitulo_contenido_<?php echo $i; ?>" name="subtitulo_contenido_<?php echo $i; ?>" style="min-height: 100px;" placeholder="Contenido completo de este subtitulo"><?php echo htmlspecialchars($sub['contenido'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-row-3">
                            <div class="form-group">
                                <label for="subtitulo_video_<?php echo $i; ?>"><i class="fab fa-youtube"></i> Video URL</label>
                                <input type="url" id="subtitulo_video_<?php echo $i; ?>" name="subtitulo_video_<?php echo $i; ?>" value="<?php echo htmlspecialchars($sub['video'] ?? ''); ?>" placeholder="https://youtube.com/watch?v=...">
                            </div>
                            <div class="form-group">
                                <label for="subtitulo_audio_<?php echo $i; ?>"><i class="fas fa-volume-up"></i> Cambiar Audio</label>
                                <input type="file" id="subtitulo_audio_<?php echo $i; ?>" name="subtitulo_audio_<?php echo $i; ?>" accept="audio/*">
                                <?php if (!empty($sub['audio'])): ?>
                                    <div class="file-info">Actual: <?php echo htmlspecialchars($sub['audio']); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="subtitulo_link_<?php echo $i; ?>"><i class="fas fa-link"></i> Enlace</label>
                                <input type="url" id="subtitulo_link_<?php echo $i; ?>" name="subtitulo_link_<?php echo $i; ?>" value="<?php echo htmlspecialchars($sub['link'] ?? ''); ?>" placeholder="https://enlace.com">
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
            
            <div class="buttons">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                <a href="gestionar_noticias.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>
    <?php $conn->close(); ?>
</body>
</html>