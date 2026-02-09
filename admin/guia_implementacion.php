<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guía de Implementación - Periodismo UTB</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .guia-container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
        }
        .guia-header {
            text-align: center;
            background: linear-gradient(135deg, #1a5fb4 0%, #135a9c 100%);
            color: white;
            padding: 40px;
            border-radius: 8px;
            margin-bottom: 40px;
        }
        .guia-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .seccion-guia {
            background: white;
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 8px;
            border-left: 5px solid #1a5fb4;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .seccion-guia h2 {
            color: #1a5fb4;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }
        .seccion-guia h3 {
            color: #333;
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        .paso {
            background: #f9f9f9;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
            border-left: 4px solid #2196F3;
        }
        .paso strong {
            color: #1a5fb4;
        }
        .code-block {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ff9800;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .success-box {
            background: #d4edda;
            border-left: 4px solid #4CAF50;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .info-box {
            background: #d1ecf1;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .lista-puntos {
            margin-left: 20px;
            line-height: 1.8;
        }
        .lista-puntos li {
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background: #1a5fb4;
            color: white;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .btn-volver {
            display: inline-block;
            padding: 12px 25px;
            background: #1a5fb4;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            margin-top: 20px;
        }
        .btn-volver:hover {
            background: #135a9c;
        }
    </style>
</head>
<body>
    <div class="guia-container">
        <div class="guia-header">
            <h1><i class="fas fa-book"></i> Guía de Implementación</h1>
            <p>Sistema de Noticias con Multimedia y Subtítulos</p>
            <p style="font-size: 0.9rem; opacity: 0.9;">Periodismo UTB - Universidad Técnica de Babahoyo</p>
        </div>

        <!-- PASO 1: MIGRACIÓN -->
        <div class="seccion-guia">
            <h2><i class="fas fa-database"></i> Paso 1: Ejecutar Migración de Base de Datos</h2>
            
            <p>Antes de usar el nuevo sistema, es necesario actualizar la base de datos con los nuevos campos.</p>
            
            <div class="paso">
                <strong>¿Qué hace?</strong> Agrega campos para multimedia (video, audio, link) y crea la tabla de subtítulos.
            </div>
            
            <h3>Instrucciones:</h3>
            <ol class="lista-puntos">
                <li>Accede a: <code>http://localhost:3000/migracion_multimedia.php</code></li>
                <li>Revisa que todos los campos se hayan creado correctamente (aparecerán mensajes en verde)</li>
                <li>Si hay errores, verifica la conexión a MySQL</li>
                <li>Una vez completada, ya puedes crear noticias con el nuevo sistema</li>
            </ol>
            
            <div class="success-box">
                <strong><i class="fas fa-check-circle"></i> Nota:</strong> Si la migración muestra que los campos ya existen, significa que ya has ejecutado el script antes. ¡Perfecto!
            </div>
        </div>

        <!-- PASO 2: CREAR NOTICIAS -->
        <div class="seccion-guia">
            <h2><i class="fas fa-pencil"></i> Paso 2: Crear Noticias Completas</h2>
            
            <h3>Acceso:</h3>
            <p>Panel Admin → Crear Noticia Completa</p>
            <p>URL: <code>http://localhost:3000/admin/crear_noticia_completa.php</code></p>
            
            <h3>Estructura de una Noticia Completa:</h3>
            
            <table>
                <tr>
                    <th>Sección</th>
                    <th>Contenido</th>
                    <th>Requerimientos</th>
                </tr>
                <tr>
                    <td><strong>Información Principal</strong></td>
                    <td>Título, Categoría, Autor</td>
                    <td>Todos requeridos</td>
                </tr>
                <tr>
                    <td><strong>Descripción</strong></td>
                    <td>Corta (100-500 caracteres)<br>Larga (mín. 500 caracteres)</td>
                    <td>Ambas requeridas</td>
                </tr>
                <tr>
                    <td><strong>Contenido</strong></td>
                    <td>Contenido (mín. 500)<br>Contenido Completo (mín. 5000)</td>
                    <td>Ambos requeridos</td>
                </tr>
                <tr>
                    <td><strong>Multimedia Principal</strong></td>
                    <td>Imagen, Video, Audio, Link</td>
                    <td>Opcional (cualquier formato)</td>
                </tr>
                <tr>
                    <td><strong>5 Subtítulos</strong></td>
                    <td>Cada uno con: Título, Descripción, Contenido, Multimedia</td>
                    <td>Mínimo 5 requeridos</td>
                </tr>
            </table>
            
            <h3>Campos de cada Subtítulo:</h3>
            <ul class="lista-puntos">
                <li><strong>Título:</strong> Nombre del subtítulo (requerido)</li>
                <li><strong>Descripción:</strong> Mínimo 100 caracteres (requerida)</li>
                <li><strong>Contenido:</strong> Mínimo 500 caracteres (requerido)</li>
                <li><strong>Imagen:</strong> Imagen opcional (JPG, PNG, GIF)</li>
                <li><strong>Video:</strong> Link de YouTube, Vimeo, etc. (opcional)</li>
                <li><strong>Audio:</strong> Archivo MP3, WAV, OGG, M4A (opcional)</li>
                <li><strong>Link:</strong> Cualquier enlace externo (opcional)</li>
            </ul>
            
            <div class="warning-box">
                <strong><i class="fas fa-exclamation-triangle"></i> Importante:</strong> La plataforma requiere exactamente 5 subtítulos con contenido para guardar una noticia. No puedes dejar subtítulos vacíos.
            </div>
        </div>

        <!-- PASO 3: VER NOTICIAS -->
        <div class="seccion-guia">
            <h2><i class="fas fa-eye"></i> Paso 3: Ver Noticias en la Web</h2>
            
            <h3>En la Página Principal (index.php):</h3>
            <ul class="lista-puntos">
                <li>Se muestran las noticias recientes</li>
                <li>Cada noticia tiene un botón azul con icono de ojo: <strong>"Ver Más"</strong></li>
                <li>Al hacer clic, se abre la noticia completa</li>
            </ul>
            
            <h3>En Secciones (secciones.php):</h3>
            <ul class="lista-puntos">
                <li>Puedes filtrar por categoría (Universidad, Deportes, Cultura, etc.)</li>
                <li>Cada noticia muestra un botón <strong>"Ver Más"</strong> con icono de ojo</li>
                <li>Al hacer clic, accedes a la noticia completa</li>
            </ul>
            
            <h3>Página Completa (noticia.php?id=X):</h3>
            <p>Muestra:</p>
            <ul class="lista-puntos">
                <li>Encabezado con título, categoría, fecha y autor</li>
                <li>Imagen principal</li>
                <li>Descripción larga</li>
                <li>Contenido completo (mínimo 5000 caracteres)</li>
                <li>Multimedia principal (video, audio, link)</li>
                <li><strong>5 Subtítulos</strong> con todo su contenido y multimedia</li>
            </ul>
        </div>

        <!-- ESTRUCTURA CARPETAS -->
        <div class="seccion-guia">
            <h2><i class="fas fa-folder"></i> Estructura de Carpetas</h2>
            
            <p>El sistema crea automáticamente las siguientes carpetas para almacenar archivos:</p>
            
            <div class="code-block">
img/uploads/              (imágenes de noticias y subtítulos)
admin/audios/uploads/     (archivos de audio)
admin/videos/uploads/     (videos locales - opcional)
            </div>
            
            <div class="info-box">
                <strong><i class="fas fa-info-circle"></i> Nota:</strong> Se recomienda usar links de videos en lugar de subir archivos locales para ahorrar espacio en el servidor.
            </div>
        </div>

        <!-- CARACTERES MÍNIMOS -->
        <div class="seccion-guia">
            <h2><i class="fas fa-ruler"></i> Validaciones de Contenido</h2>
            
            <table>
                <tr>
                    <th>Campo</th>
                    <th>Mínimo</th>
                    <th>Máximo</th>
                    <th>Nota</th>
                </tr>
                <tr>
                    <td>Título Principal</td>
                    <td>-</td>
                    <td>255 caracteres</td>
                    <td>Requerido</td>
                </tr>
                <tr>
                    <td>Descripción Corta</td>
                    <td>100 caracteres</td>
                    <td>500 caracteres</td>
                    <td>Se muestra en listados</td>
                </tr>
                <tr>
                    <td>Descripción Larga</td>
                    <td>500 caracteres</td>
                    <td>Ilimitado</td>
                    <td>Se muestra en página completa</td>
                </tr>
                <tr>
                    <td>Contenido</td>
                    <td>500 caracteres</td>
                    <td>Ilimitado</td>
                    <td>Contenido principal</td>
                </tr>
                <tr>
                    <td>Contenido Completo</td>
                    <td>5000 caracteres</td>
                    <td>Ilimitado</td>
                    <td>Detalle completo de la noticia</td>
                </tr>
                <tr>
                    <td>Subtítulo Título</td>
                    <td>-</td>
                    <td>255 caracteres</td>
                    <td>Requerido</td>
                </tr>
                <tr>
                    <td>Subtítulo Descripción</td>
                    <td>100 caracteres</td>
                    <td>Ilimitado</td>
                    <td>Requerida</td>
                </tr>
                <tr>
                    <td>Subtítulo Contenido</td>
                    <td>500 caracteres</td>
                    <td>Ilimitado</td>
                    <td>Requerido</td>
                </tr>
            </table>
        </div>

        <!-- TIPOS DE MULTIMEDIA -->
        <div class="seccion-guia">
            <h2><i class="fas fa-file"></i> Formatos de Multimedia Soportados</h2>
            
            <h3>Imágenes:</h3>
            <ul class="lista-puntos">
                <li>JPG / JPEG</li>
                <li>PNG</li>
                <li>GIF</li>
            </ul>
            
            <h3>Audio:</h3>
            <ul class="lista-puntos">
                <li>MP3</li>
                <li>WAV</li>
                <li>OGG</li>
                <li>M4A</li>
            </ul>
            
            <h3>Video:</h3>
            <ul class="lista-puntos">
                <li>Links de YouTube</li>
                <li>Links de Vimeo</li>
                <li>Cualquier URL de video</li>
            </ul>
            
            <h3>Links:</h3>
            <ul class="lista-puntos">
                <li>Cualquier URL válida (http/https)</li>
            </ul>
        </div>

        <!-- FLUJO EDITORIAL -->
        <div class="seccion-guia">
            <h2><i class="fas fa-project-diagram"></i> Flujo Editorial Completo</h2>
            
            <div class="paso" style="background: #f0f7ff; border-left: 4px solid #2196F3;">
                <strong>1. Redactor</strong><br>
                Accede a Admin → Crear Noticia Completa<br>
                Completa todos los campos (título, descripción, contenido, 5 subtítulos con multimedia)
            </div>
            
            <div class="paso" style="background: #f0f7ff; border-left: 4px solid #2196F3;">
                <strong>2. Validación</strong><br>
                El sistema valida que se cumplan los mínimos de caracteres<br>
                Verifica que todos los 5 subtítulos tengan contenido
            </div>
            
            <div class="paso" style="background: #f0f7ff; border-left: 4px solid #2196F3;">
                <strong>3. Publicación</strong><br>
                La noticia se guarda en la base de datos<br>
                Aparece en la página principal y en secciones
            </div>
            
            <div class="paso" style="background: #f0f7ff; border-left: 4px solid #2196F3;">
                <strong>4. Lectura</strong><br>
                Usuarios acceden desde index.php o secciones.php<br>
                Hacen clic en "Ver Más" (icono de ojo)<br>
                Leen la noticia completa con todos sus subtítulos
            </div>
        </div>

        <!-- TROUBLESHOOTING -->
        <div class="seccion-guia">
            <h2><i class="fas fa-tools"></i> Solución de Problemas</h2>
            
            <h3>Error: "Los campos no se crean"</h3>
            <p><strong>Solución:</strong> Ejecuta primero <code>migracion_multimedia.php</code></p>
            
            <h3>Error: "No se puede subir archivo"</h3>
            <p><strong>Solución:</strong> Verifica que las carpetas existan:</p>
            <div class="code-block">
img/uploads/
admin/audios/uploads/
            </div>
            
            <h3>Tabla de subtítulos vacía</h3>
            <p><strong>Solución:</strong> Debes crear noticias con el nuevo formulario <code>crear_noticia_completa.php</code></p>
            
            <h3>No aparecen noticia completa</h3>
            <p><strong>Solución:</strong> Verifica que el archivo <code>noticia.php</code> esté en la raíz</p>
        </div>

        <!-- INFORMACIÓN ADICIONAL -->
        <div class="seccion-guia" style="background: #f0f7ff; border-left: 4px solid #2196F3;">
            <h2><i class="fas fa-info-circle"></i> Información Adicional</h2>
            
            <ul class="lista-puntos">
                <li><strong>Base de Datos:</strong> periodismo_utb</li>
                <li><strong>Tabla Principal:</strong> noticias</li>
                <li><strong>Tabla Subtítulos:</strong> noticias_subtitulos</li>
                <li><strong>Conexión:</strong> MySQLi (Prepared Statements)</li>
                <li><strong>Codificación:</strong> UTF-8 MB4</li>
            </ul>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="admin/dashboard.php" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </div>
</body>
</html>
