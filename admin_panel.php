<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}

require_once 'models/sistemam.php';
$sistema = new Sistema();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - GAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-danger text-white text-center">
                <h1>PANEL DE ADMINISTRADOR</h1>
            </div>
            <div class="card-body text-center">
                <h2>¡Bienvenido, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
                
                <div class="row g-4 mt-3">
                    <div class="col-md-4">
                        <div class="card h-100 border-primary">
                            <div class="card-body">
                                <h3>Gestionar Permisos</h3>
                                <a href="permisos.php" class="btn btn-primary btn-lg">Ir a Permisos</a>
                            </div>
                        </div>
                    </div>
                    
            </div>
            
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>