<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Categorías - Admin</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        .admin-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .admin-title {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-title {
            font-size: 1.2rem;
            color: #1a5fb4;
            margin-bottom: 15px;
            border-bottom: 2px solid #1a5fb4;
            padding-bottom: 10px;
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
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-valid {
            background: #4CAF50;
            color: white;
        }
        .back-link {
            text-align: center;
            margin-top: 30px;
        }
        .back-link a {
            color: #1a5fb4;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="admin-container">
            <div class="admin-title">
                <h1><i class="fas fa-list"></i> Información de Categorías</h1>
            </div>

            <?php
            try {
                @$conn = new mysqli('localhost', 'root', '', 'periodismo_utb');
                
                if (!$conn->connect_error) {
                    $conn->set_charset("utf8");
                    
                    $categorias_validas = ['Universidad', 'Deportes', 'Cultura', 'Tecnología', 'Sociedad', 'Educación', 'General'];
                    
                    // Mostrar categorías válidas
                    echo "<div class='info-section'>";
                    echo "<h2 class='info-title'><i class='fas fa-check-circle'></i> Categorías Válidas en el Sistema</h2>";
                    echo "<table>";
                    echo "<tr>";
                    echo "<th>Categoría</th>";
                    echo "<th>Cantidad de Noticias</th>";
                    echo "<th>Estado</th>";
                    echo "</tr>";
                    
                    foreach ($categorias_validas as $cat) {
                        $sql = "SELECT COUNT(*) as count FROM noticias WHERE categoria = '" . $conn->real_escape_string($cat) . "'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        
                        echo "<tr>";
                        echo "<td>" . $cat . "</td>";
                        echo "<td>" . $row['count'] . "</td>";
                        echo "<td><span class='badge badge-valid'>Válida</span></td>";
                        echo "</tr>";
                    }
                    
                    echo "</table>";
                    echo "</div>";
                    
                    // Verificar categorías inválidas
                    $result = $conn->query("SELECT DISTINCT categoria FROM noticias");
                    $categorias_existentes = [];
                    while ($row = $result->fetch_assoc()) {
                        $categorias_existentes[] = $row['categoria'];
                    }
                    
                    $categorias_invalidas = array_diff($categorias_existentes, $categorias_validas);
                    
                    if (!empty($categorias_invalidas)) {
                        echo "<div class='info-section'>";
                        echo "<h2 class='info-title'><i class='fas fa-exclamation-triangle'></i> Categorías Inválidas Encontradas</h2>";
                        echo "<table>";
                        echo "<tr>";
                        echo "<th>Categoría Inválida</th>";
                        echo "<th>Cantidad</th>";
                        echo "</tr>";
                        
                        foreach ($categorias_invalidas as $cat_invalid) {
                            $sql = "SELECT COUNT(*) as count FROM noticias WHERE categoria = '" . $conn->real_escape_string($cat_invalid) . "'";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            
                            echo "<tr>";
                            echo "<td>" . $cat_invalid . "</td>";
                            echo "<td>" . $row['count'] . "</td>";
                            echo "</tr>";
                        }
                        
                        echo "</table>";
                        echo "<p style='color: #FF6B6B; margin-top: 10px;'><i class='fas fa-info-circle'></i> Se encontraron categorías que no están en la lista válida.</p>";
                        echo "</div>";
                    }
                    
                    $conn->close();
                } else {
                    throw new Exception("No se pudo conectar a la base de datos");
                }
            } catch (Exception $e) {
                echo "<div style='color: #FF6B6B; text-align: center; padding: 20px; background: #ffebee; border-radius: 5px;'>";
                echo "<i class='fas fa-exclamation-circle'></i> Error: " . htmlspecialchars($e->getMessage());
                echo "</div>";
            }
            ?>

            <div class="back-link">
                <a href="admin/dashboard.php"><i class="fas fa-arrow-left"></i> Volver al Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
