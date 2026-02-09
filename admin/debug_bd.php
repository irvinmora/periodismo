<?php
require_once 'includes/config.php';

echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Debug - Estructura BD</title>";
echo "<style>";
echo "body { font-family: Arial; margin: 20px; }";
echo "table { width: 100%; border-collapse: collapse; }";
echo "th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }";
echo "th { background: #1a5fb4; color: white; }";
echo "tr:nth-child(even) { background: #f9f9f9; }";
echo ".section { margin: 30px 0; padding: 20px; background: #f0f0f0; border-radius: 4px; }";
echo "h2 { color: #1a5fb4; }";
echo ".check { color: #4CAF50; font-weight: bold; }";
echo ".cross { color: #d32f2f; font-weight: bold; }";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<h1>Debug - Análisis de Base de Datos</h1>";

// 1. Verificar tabla noticias
echo "<div class='section'>";
echo "<h2>1. Estructura tabla 'noticias'</h2>";
$result = $conn->query("SHOW COLUMNS FROM noticias");
if ($result && $result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th><th>Default</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td><strong>" . $row['Field'] . "</strong></td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . ($row['Null'] == 'YES' ? 'Sí' : 'No') . "</td>";
        echo "<td>" . ($row['Key'] ? $row['Key'] : '-') . "</td>";
        echo "<td>" . ($row['Default'] ? $row['Default'] : '-') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='cross'>✗ Error al obtener columnas</p>";
}
echo "</div>";

// 2. Campos multimedia
echo "<div class='section'>";
echo "<h2>2. Campos Multimedia (Requeridos)</h2>";
$required = ['descripcion_larga', 'contenido_completo', 'video_principal', 'audio_principal', 'link_principal'];
$check = $conn->query("SHOW COLUMNS FROM noticias");
$columns = [];
while ($row = $check->fetch_assoc()) {
    $columns[] = $row['Field'];
}
echo "<ul>";
foreach ($required as $field) {
    if (in_array($field, $columns)) {
        echo "<li><span class='check'>✓</span> $field</li>";
    } else {
        echo "<li><span class='cross'>✗</span> $field <strong>(FALTA - Ejecuta migración)</strong></li>";
    }
}
echo "</ul>";
echo "</div>";

// 3. Tabla de subtítulos
echo "<div class='section'>";
echo "<h2>3. Tabla 'noticias_subtitulos'</h2>";
$check_table = $conn->query("SHOW TABLES LIKE 'noticias_subtitulos'");
if ($check_table && $check_table->num_rows > 0) {
    echo "<p><span class='check'>✓ EXISTE</span></p>";
    $result = $conn->query("SHOW COLUMNS FROM noticias_subtitulos");
    echo "<table>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row['Field'] . "</td><td>" . $row['Type'] . "</td><td>" . ($row['Null'] == 'YES' ? 'Sí' : 'No') . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p><span class='cross'>✗ NO EXISTE - Ejecuta migración</span></p>";
}
echo "</div>";

// 4. Noticias en BD
echo "<div class='section'>";
echo "<h2>4. Noticias en Base de Datos</h2>";
$result = $conn->query("SELECT id, titulo, descripcion, descripcion_larga, contenido_completo FROM noticias LIMIT 5");
if ($result && $result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Título</th><th>Desc. Corta</th><th>Desc. Larga</th><th>Contenido Completo</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . substr($row['titulo'], 0, 30) . "...</td>";
        echo "<td>" . (strlen($row['descripcion']) > 0 ? '<span class="check">✓</span>' : '<span class="cross">✗</span>') . "</td>";
        echo "<td>" . (strlen($row['descripcion_larga'] ?? '') > 0 ? '<span class="check">✓ ' . strlen($row['descripcion_larga']) . ' chars</span>' : '<span class="cross">✗ VACÍO</span>') . "</td>";
        echo "<td>" . (strlen($row['contenido_completo'] ?? '') > 0 ? '<span class="check">✓ ' . strlen($row['contenido_completo']) . ' chars</span>' : '<span class="cross">✗ VACÍO</span>') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No hay noticias en la BD</p>";
}
echo "</div>";

// 5. Acciones recomendadas
echo "<div class='section'>";
echo "<h2>5. Acciones Recomendadas</h2>";
$need_migration = count(array_diff($required, $columns)) > 0;
if ($need_migration) {
    echo "<p><strong>La migración aún NO se ha ejecutado.</strong></p>";
    echo "<p>Columnas faltantes:</p>";
    echo "<ul>";
    foreach (array_diff($required, $columns) as $missing) {
        echo "<li>- $missing</li>";
    }
    echo "</ul>";
    echo "<a href='migracion_multimedia.php' style='background: #1a5fb4; color: white; padding: 15px 30px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block;'>";
    echo "<i class='fas fa-database'></i> Ejecutar Migración Ahora</a>";
} else {
    echo "<p><span class='check'>✓ Base de datos configurada correctamente</span></p>";
    echo "<p><a href='index.php'>Volver a Inicio</a></p>";
}
echo "</div>";

$conn->close();
echo "</body>";
echo "</html>";
?>
