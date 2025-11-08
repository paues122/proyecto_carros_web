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

use Intervention\Image\ImageManagerStatic as Image;

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

    $fotos = $_FILES['fotos'] ?? null;
    $foto_paths = [];

    if ($fotos && $fotos['error'][0] !== UPLOAD_ERR_NO_FILE) {
        $upload_dir = 'uploads/inventario/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        foreach ($fotos['tmp_name'] as $index => $tmp_name) {
            if ($fotos['error'][$index] === UPLOAD_ERR_OK) {
                $ext = pathinfo($fotos['name'][$index], PATHINFO_EXTENSION);
                $filename = uniqid('auto_') . '_' . $index . '.' . $ext;
                $path = $upload_dir . $filename;

                move_uploaded_file($tmp_name, $path);

              
                try {
                    $img = Image::make($path)->resize(300, 200, function ($c) {
                        $c->aspectRatio();
                        $c->upsize();
                    });
                    $img->save($upload_dir . $filename . '_thumb.jpg', 80);
                } catch (Exception $e) {
                    error_log("Error thumbnail: " . $e->getMessage());
                }

                $foto_paths[] = $filename;
            }
        }
    }

    $data['fotos'] = !empty($foto_paths) ? json_encode($foto_paths) : null;

    $inventario->create($data);
    header('Location: inventario.php?ok=1');
    exit;
}


if ($puede_eliminar && ($_GET['accion'] ?? '') === 'eliminar' && !empty($_GET['id'])) {
    $auto = $inventario->read($_GET['id']);
    if ($auto && !empty($auto['fotos'])) {
        $fotos = json_decode($auto['fotos'], true);
        $upload_dir = 'uploads/inventario/';
        foreach ($fotos as $foto) {
            @unlink($upload_dir . $foto);
            @unlink($upload_dir . $foto . '_thumb.jpg');
        }
    }
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
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h3 class="mb-0">Agregar Nuevo Auto</h3>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="agregar">
                <div class="row g-3">
                    <div class="col-md-3"><input name="nombre" class="form-control" placeholder="Nombre" required></div>
                    <div class="col-md-3"><input name="modelo" class="form-control" placeholder="Modelo" required></div>
                    <div class="col-md-3"><input name="marca" class="form-control" placeholder="Marca" required></div>
                    <div class="col-md-3"><input name="anio" type="number" class="form-control" placeholder="Año" required></div>
                    <div class="col-md-4"><input name="precio" type="number" step="0.01" class="form-control" placeholder="Precio" required></div>
                    <div class="col-md-4"><input name="mensualidad" type="number" step="0.01" class="form-control" placeholder="Mensualidad"></div>
                    <div class="col-md-4"><input name="enganche" type="number" step="0.01" class="form-control" placeholder="Enganche"></div>
                    
               
                    <div class="col-12 mt-3">
                        <label class="form-label fw-bold">Fotos del Auto (máx 5)</label>
                        <input type="file" name="fotos[]" class="form-control" accept="image/*" multiple>
                        <small class="text-muted">Se generarán miniaturas automáticamente.</small>
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
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($autos as $auto): 
            $fotos = !empty($auto['fotos']) ? json_decode($auto['fotos'], true) : [];
            $has_fotos = !empty($fotos);
        ?>
        <div class="col">
            <div class="card h-100 shadow hover-shadow">
                
                <?php if ($has_fotos): 
                    $first = $fotos[0];
                    $thumb = "uploads/inventario/{$first}_thumb.jpg";
                    $full  = "uploads/inventario/{$first}";
                ?>
                    <a href="<?= $full ?>" target="_blank">
                        <img src="<?= $thumb ?>" class="card-img-top" alt="<?= htmlspecialchars($auto['nombre']) ?>" style="height:200px; object-fit:cover;">
                    </a>
                <?php else: ?>
                    <div class="image-placeholder bg-light d-flex align-items-center justify-content-center" style="height:200px;">
                        <span class="text-muted">Sin foto</span>
                    </div>
                <?php endif; ?>

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($auto['nombre']) ?></h5>
                    <p class="card-text">
                        <strong><?= $auto['marca'] ?> <?= $auto['modelo'] ?> <?= $auto['anio'] ?></strong><br>
                        Precio: <span class="text-success fs-5">$<?= number_format($auto['precio'], 2) ?></span>
                    </p>
                    <p>Mensualidad: $<?= number_format($auto['mensualidad'], 2) ?><br>
                       Enganche: $<?= number_format($auto['enganche'], 2) ?></p>

                
                    <?php if ($has_fotos && count($fotos) > 1): ?>
                    <div class="mt-3">
                        <small class="text-muted">Más fotos:</small>
                        <div class="d-flex gap-1 flex-wrap mt-1">
                            <?php foreach (array_slice($fotos, 1) as $foto): 
                                $thumb = "uploads/inventario/{$foto}_thumb.jpg";
                                $full  = "uploads/inventario/{$foto}";
                                if (file_exists($thumb)):
                            ?>
                                <a href="<?= $full ?>" target="_blank">
                                    <img src="<?= $thumb ?>" alt="Foto" class="img-thumbnail" style="width:60px; height:40px; object-fit:cover;">
                                </a>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($puede_eliminar): ?>
                    <a href="?accion=eliminar&id=<?= $auto['id_vehiculo'] ?>" 
                       class="btn btn-danger mt-auto btn-sm"
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