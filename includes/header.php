<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['validado']) || !$_SESSION['validado']) {
    header('Location: login.php');
    exit;
}

define('BASE_URL', '/final_web/'); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AutoAgencia GAM</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css"> 
</head>
<body>

<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?php echo BASE_URL; ?>index.php">AutoAgencia GAM</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>index.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>inventario.php">Inventario</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>cotizador.php">Cotizar</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL; ?>perfil.php">Mi Perfil</a>
          </li>
            <?php if ($_SESSION['rol'] ?? '' === 'admin'): ?>
<li class="nav-item">
    <a class="nav-link btn btn-danger text-white px-4" href="admin_panel.php">
        Panel Administrador
    </a>
</li>
<?php endif; ?>
          <li class="nav-item">
            <a class="nav-link btn btn-danger text-white ms-lg-2" href="<?php echo BASE_URL; ?>logout.php">Cerrar Sesión</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>
