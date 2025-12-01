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
$puede_editar    = $puede_agregar; 

$esAdmin = ($_SESSION['rol'] ?? '') === 'admin';
if ($esAdmin) {
    $puede_agregar = $puede_eliminar = $puede_editar = true;
}

require_once 'models/inventariom.php';
$inventario = new Inventario();

$autoEditar = null;

if ($puede_editar && ($_GET['accion'] ?? '') === 'editar' && !empty($_GET['id'])) {
    $autoEditar = $inventario->readOne($_GET['id']);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    
  
    $data = [
        'nombre'      => $_POST['nombre'] ?? '',
        'modelo'      => $_POST['modelo'] ?? '',
        'marca'       => $_POST['marca'] ?? '',
        'anio'        => (int)($_POST['anio'] ?? 0),
        'precio'      => (float)($_POST['precio'] ?? 0),
        'mensualidad' => (float)($_POST['mensualidad'] ?? 0),
        'enganche'    => (float)($_POST['enganche'] ?? 0),
        'imagen'      => $_POST['imagen_actual'] ?? '' 
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

        if (in_array($imageFileType, $validTypes) && $_FILES['imagen']['size'] <= 5 * 1024 * 1024) {
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $filePath)) {
               
                if ($accion === 'actualizar' && !empty($data['imagen']) && file_exists($data['imagen'])) {
                    unlink($data['imagen']);
                }
                $data['imagen'] = $filePath; 
            }
        }
    }


    if ($puede_agregar && $accion === 'agregar') {
        if (empty($data['imagen'])) {
        
        }
        $inventario->create($data);
        header('Location: inventario.php?ok=1');
        exit;
    }


    if ($puede_editar && $accion === 'actualizar') {
        $id_vehiculo = $_POST['id_vehiculo'] ?? 0;
        $inventario->update($data, $id_vehiculo);
        header('Location: inventario.php?updated=1');
        exit;
    }
}


if ($puede_eliminar && ($_GET['accion'] ?? '') === 'eliminar' && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $auto = $inventario->readOne($id);
    
    // Borrar imagen del servidor
    if ($auto && !empty($auto['imagen']) && file_exists($auto['imagen'])) {
        unlink($auto['imagen']); 
    }
    
    // Borrar de BD (Usa transacción en el modelo)
    $inventario->delete($id);
    header('Location: inventario.php?del=1');
    exit;
}

// Leer listado final
$autos = $inventario->read();

include 'includes/header.php';
?>

<?php if (isset($_GET['ok'])): ?>
    <div class="alert alert-success alert-dismissible fade show m-3">
        Auto agregado con éxito 
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-info alert-dismissible fade show m-3">
        Auto actualizado correctamente (Transacción Exitosa)
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['del'])): ?>
    <div class="alert alert-danger alert-dismissible fade show m-3">
        Auto eliminado correctamente
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($puede_agregar || ($puede_editar && $autoEditar)): ?>
<div class="container my-5">
    <div class="card shadow">
        <div class="card-header <?= $autoEditar ? 'bg-warning text-dark' : 'bg-primary text-white' ?>">
            <h3 class="mb-0">
                <?= $autoEditar ? 'Editar Auto' : 'Agregar Nuevo Auto' ?>
            </h3>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="<?= $autoEditar ? 'actualizar' : 'agregar' ?>">
                
                <?php if ($autoEditar): ?>
                    <input type="hidden" name="id_vehiculo" value="<?= $autoEditar['id_vehiculo'] ?>">
                    <input type="hidden" name="imagen_actual" value="<?= $autoEditar['imagen'] ?>">
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Nombre</label>
                        <input name="nombre" class="form-control" placeholder="Nombre" required 
                               value="<?= $autoEditar['nombre'] ?? '' ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Modelo</label>
                        <input name="modelo" class="form-control" placeholder="Modelo" required
                               value="<?= $autoEditar['modelo'] ?? '' ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Marca</label>
                        <input name="marca" class="form-control" placeholder="Marca" required
                               value="<?= $autoEditar['marca'] ?? '' ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Año</label>
                        <input name="anio" type="number" class="form-control" placeholder="Año" min="1900" max="2100" required
                               value="<?= $autoEditar['anio'] ?? '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Precio ($)</label>
                        <input name="precio" type="number" step="0.01" class="form-control" placeholder="Precio" required
                               value="<?= $autoEditar['precio'] ?? '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Mensualidad ($)</label>
                        <input name="mensualidad" type="number" step="0.01" class="form-control" placeholder="Mensualidad"
                               value="<?= $autoEditar['mensualidad'] ?? '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Enganche ($)</label>
                        <input name="enganche" type="number" step="0.01" class="form-control" placeholder="Enganche"
                               value="<?= $autoEditar['enganche'] ?? '' ?>">
                    </div>
                    
                    <div class="col-12">
                        <label for="imagen" class="form-label fw-bold">Imagen del vehículo</label>
                        <?php if($autoEditar && !empty($autoEditar['imagen'])): ?>
                            <div class="mb-2">
                                <img src="<?= $autoEditar['imagen'] ?>" width="100" class="img-thumbnail">
                                <small class="text-muted d-block">Imagen actual (Sube otra para cambiarla)</small>
                            </div>
                        <?php endif; ?>
                        
                        <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" <?= $autoEditar ? '' : 'required' ?>>
                        <small class="text-muted">Máximo 5MB. Formatos: JPG, PNG, GIF, WEBP</small>
                    </div>
                </div>
                <div class="text-center mt-4 d-flex gap-2 justify-content-center">
                    <?php if ($autoEditar): ?>
                        <a href="inventario.php" class="btn btn-secondary btn-lg">CANCELAR</a>
                        <button class="btn btn-warning btn-lg px-5">ACTUALIZAR DATOS</button>
                    <?php else: ?>
                        <button class="btn btn-success btn-lg px-5">GUARDAR AUTO</button>
                    <?php endif; ?>
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

                        <div class="mt-auto d-flex gap-2">
                            <?php if ($puede_editar): ?>
                                <a href="?accion=editar&id=<?= $auto['id_vehiculo'] ?>" 
                                   class="btn btn-warning flex-grow-1">
                                   Editar
                                </a>
                            <?php endif; ?>

                            <?php if ($puede_eliminar): ?>
                                <a href="?accion=eliminar&id=<?= $auto['id_vehiculo'] ?>" 
                                   class="btn btn-danger flex-grow-1"
                                   onclick="return confirm('¿Seguro que quieres eliminar este auto?')">
                                   Eliminar
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>