<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$titulo = 'Gestionar Suscriptores';
require_once '../includes/header.php';
?>

<div class="container">
    <h1>Gestionar Suscriptores</h1>
    
    <?php
    // Lógica para mostrar y gestionar suscriptores
    if ($conn === false) {
        echo "<p class='error'>Error: No se pudo conectar a la base de datos.</p>";
    } else {
        try {
            $result = $conn->query("SELECT id, nombre, email, comentario, fecha_suscripcion FROM suscriptores ORDER BY fecha_suscripcion DESC");
            if (!$result) {
                throw new Exception("Error en consulta: " . $conn->error);
            }
            
            $suscriptores = [];
            while ($row = $result->fetch_assoc()) {
                $suscriptores[] = $row;
            }
            
            if (!empty($suscriptores)): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Comentario</th>
                            <th>Fecha Suscripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suscriptores as $suscriptor): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($suscriptor['id']); ?></td>
                            <td><?php echo htmlspecialchars($suscriptor['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($suscriptor['email']); ?></td>
                            <td>
                                <?php 
                                    if (!empty($suscriptor['comentario'])) {
                                        $comentario = htmlspecialchars($suscriptor['comentario']);
                                        if (strlen($comentario) > 50) {
                                            echo substr($comentario, 0, 50) . '...<br><small style="color: #666; margin-top: 5px; display: block;">Completo: ' . $comentario . '</small>';
                                        } else {
                                            echo $comentario;
                                        }
                                    } else {
                                        echo '<span style="color: #999; font-style: italic;">Sin comentario</span>';
                                    }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($suscriptor['fecha_suscripcion']); ?></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning" onclick="alert('Función en desarrollo')">Editar</a>
                                <a href="#" class="btn btn-sm btn-danger" onclick="alert('Función en desarrollo')">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay suscriptores registrados.</p>
            <?php endif;
            
        } catch (Exception $e) {
            echo "<p class='error'>Error al cargar suscriptores: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    ?>
</div>

<?php require_once '../includes/footer.php'; ?>