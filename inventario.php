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
        'enganche'    => (float)($_POST['enganche'] ?? 0),
        'imagen'      => ''
    ];


    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/vehiculos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = uniqid('veh_') . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "", basename($_FILES['imagen']['name']));
        $filePath = $uploadDir . $fileName;

        $imageFileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $validTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($imageFileType, $validTypes)) {
            die("Error: Solo se permiten imágenes (JPG, JPEG, PNG, GIF, WEBP).");
        }

        if ($_FILES['imagen']['size'] > 5 * 1024 * 1024) { // 5MB máx
            die("Error: La imagen es demasiado grande (máx 5MB).");
        }

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $filePath)) {
            $data['imagen'] = $filePath;
        } else {
            die("Error al subir la imagen.");
        }
    } else {
        $error = $_FILES['imagen']['error'] ?? 'No se subió ninguna imagen';
        die("Error: Debes subir una imagen. Código: $error");
    }

    $inventario->create($data);
    header('Location: inventario.php?ok=1');
    exit;
}


if ($puede_eliminar && ($_GET['accion'] ?? '') === 'eliminar' && !empty($_GET['id'])) {
    $id = $_GET['id'];
    

    $auto = $inventario->readOne($id);
    if ($auto && !empty($auto['imagen']) && file_exists($auto['imagen'])) {
        unlink($auto['imagen']); 
    }
    
    $inventario->delete($id);
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
        Auto eliminado correctamente
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>


<?php if ($puede_agregar): ?>
<div class="container my-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Agregar Nuevo Auto</h3>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="agregar">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input name="nombre" class="form-control" placeholder="Nombre" required>
                    </div>
                    <div class="col-md-3">
                        <input name="modelo" class="form-control" placeholder="Modelo" required>
                    </div>
                    <div class="col-md-3">
                        <input name="marca" class="form-control" placeholder="Marca" required>
                    </div>
                    <div class="col-md-3">
                        <input name="anio" type="number" class="form-control" placeholder="Año" min="1900" max="2100" required>
                    </div>
                    <div class="col-md-4">
                        <input name="precio" type="number" step="0.01" class="form-control" placeholder="Precio" required>
                    </div>
                    <div class="col-md-4">
                        <input name="mensualidad" type="number" step="0.01" class="form-control" placeholder="Mensualidad">
                    </div>
                    <div class="col-md-4">
                        <input name="enganche" type="number" step="0.01" class="form-control" placeholder="Enganche">
                    </div>
                    
                 
                    <div class="col-12">
                        <label for="imagen" class="form-label fw-bold">Imagen del vehículo</label>
                        <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" required>
                        <small class="text-muted">Máximo 5MB. Formatos: JPG, PNG, GIF, WEBP</small>
                    </div>
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
    <?php if (empty($autos)): ?>
        <div class="text-center text-muted py-5">
            <h4>No hay vehículos en el inventario</h4>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($autos as $auto): ?>
            <div class="col">
                <div class="card h-100 shadow hover-shadow border-0">
                    <!-- IMAGEN -->
                    <?php if (!empty($auto['imagen']) && file_exists($auto['imagen'])): ?>
                        <img src="<?= htmlspecialchars($auto['imagen']) ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($auto['nombre']) ?>" 
                             style="height: 200px; object-fit: cover; border-bottom: 1px solid #eee;">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" 
                             style="height: 200px; border-bottom: 1px solid #eee;">
                            <span class="text-muted">Sin imagen</span>
                        </div>
                    <?php endif; ?>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold"><?= htmlspecialchars($auto['nombre']) ?></h5>
                        <p class="card-text text-muted small">
                            <?= htmlspecialchars($auto['marca']) ?> <?= htmlspecialchars($auto['modelo']) ?> <?= $auto['anio'] ?>
                        </p>
                        <p class="card-text">
                            <strong class="text-success fs-5">$<?= number_format($auto['precio'], 2) ?></strong>
                        </p>
                        <p class="text-muted small">
                            Mensualidad: <strong>$<?= number_format($auto['mensualidad'], 2) ?></strong><br>
                            Enganche: <strong>$<?= number_format($auto['enganche'], 2) ?></strong>
                        </p>

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
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>