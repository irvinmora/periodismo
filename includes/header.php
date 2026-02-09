<?php
/**
 * Header común para todas las páginas del admin
 */

if (!isset($titulo)) {
    $titulo = 'Administración';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo); ?> - Panel de Administración</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .navbar {
            background-color: #333;
            padding: 1rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 1rem;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .table th {
            background-color: #333;
            color: white;
            padding: 1rem;
            text-align: left;
        }
        .table td {
            padding: 1rem;
            border-bottom: 1px solid #ddd;
        }
        .table tr:hover {
            background-color: #f9f9f9;
        }
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            margin: 0.25rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-warning {
            background-color: #ffc107;
            color: black;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .error {
            color: #dc3545;
            padding: 1rem;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            margin: 1rem 0;
        }
        .success {
            color: #155724;
            padding: 1rem;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            margin: 1rem 0;
        }
        h1 {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <h2 style="margin: 0;">Panel de Administración</h2>
        </div>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="gestionar_noticias.php">Noticias</a>
            <a href="gestionar_suscriptores.php">Suscriptores</a>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>
    </div>
