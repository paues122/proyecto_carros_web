<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['validado']) || !$_SESSION['validado']) {
    header('Location: login.php');
    exit;
}

require_once 'models/sistemam.php';
$sistema = new Sistema();

$permisos = json_decode($sistema->fetch("SELECT permisos FROM usuarios WHERE id_usuario = ?", [$_SESSION['id_usuario']])['permisos'] ?? '{}', true) ?: [];

$puede_agregar   = $permisos['agregar_vehiculo']   ?? false;
$puede_eliminar  = $permisos['eliminar_vehiculo']  ?? false;

$esAdmin = ($_SESSION['rol'] ?? '') === 'admin';
if ($esAdmin) {
    $puede_agregar = $puede_eliminar = true;
}

require_once 'models/inventariom.php';
$inventario = new Inventario();

if ($puede_agregar && ($_POST['accion'] ?? '') === 'agregar') {
    $data = [
        'nombre'      => $_POST['nombre'] ?? '',
        'modelo'      => $_POST['modelo'] ?? '',
        'marca'       => $_POST['marca'] ?? '',
        'anio'        => (int)($_POST['anio'] ?? 0),
        'precio'      => (float)($_POST['precio'] ?? 0),
        'mensualidad' => (float)($_POST['mensualidad'] ?? 0),
        'enganche'    => (float)($_POST['enganche'] ?? 0)
    ];
    $inventario->create($data);
    header('Location: inventario.php?ok=1');
    exit;
}

if ($puede_eliminar && ($_GET['accion'] ?? '') === 'eliminar' && !empty($_GET['id'])) {
    $inventario->delete($_GET['id']);
    header('Location: inventario.php?del=1');
    exit;
}

$autos = $inventario->read();

include 'includes/header.php';
?>

<?php if (isset($_GET['ok'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        Auto agregado con éxito 
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['del'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        Auto eliminado
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($puede_agregar): ?>
<div class="container my-5">
    
        
            <h3>Agregar Nuevo Auto</h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="accion" value="agregar">
                <div class="row g-3">
                    <div class="col-md-3"><input name="nombre" class="form-control" placeholder="Nombre" required></div>
                    <div class="col-md-3"><input name="modelo" class="form-control" placeholder="Modelo" required></div>
                    <div class="col-md-3"><input name="marca" class="form-control" placeholder="Marca" required></div>
                    <div class="col-md-3"><input name="anio" type="number" class="form-control" placeholder="Año" required></div>
                    <div class="col-md-4"><input name="precio" type="number" step="0.01" class="form-control" placeholder="Precio" required></div>
                    <div class="col-md-4"><input name="mensualidad" type="number" step="0.01" class="form-control" placeholder="Mensualidad"></div>
                    <div class="col-md-4"><input name="enganche" type="number" step="0.01" class="form-control" placeholder="Enganche"></div>
                </div>
                <div class="text-center mt-4">
                    <button class="btn btn-success btn-lg px-5">GUARDAR AUTO</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="container my-5">
    <h2 class="text-center mb-4">INVENTARIO DISPONIBLE</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($autos as $auto): ?>
        <div class="col">
            <div class="card h-100 shadow hover-shadow">
                <div class="image-placeholder bg-light"></div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($auto['nombre']) ?></h5>
                    <p class="card-text">
                        <strong><?= $auto['marca'] ?> <?= $auto['modelo'] ?> <?= $auto['anio'] ?></strong><br>
                        Precio: <span class="text-success fs-5">$<?= number_format($auto['precio'], 2) ?></span>
                    </p>
                    <p>Mensualidad: $<?= number_format($auto['mensualidad'], 2) ?><br>
                       Enganche: $<?= number_format($auto['enganche'], 2) ?></p>

                    <?php if ($puede_eliminar): ?>
                    <a href="?accion=eliminar&id=<?= $auto['id_vehiculo'] ?>" 
                       class="btn btn-danger mt-auto"
                       onclick="return confirm('¿Seguro que quieres eliminar este auto?')">
                       Eliminar
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>