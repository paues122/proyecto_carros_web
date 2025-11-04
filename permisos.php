<?php
// permisos.php → SOLO EL ADMIN VE ESTO
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// SI NO ES ADMIN → FUERA
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}

require_once 'models/sistemam.php';
$sistema = new Sistema();
$alerta = '';

// === GUARDAR PERMISOS ===
if (isset($_POST['guardar'])) {
    $id = (int)$_POST['id_usuario'];
    $permisos = [
        'agregar_vehiculo'   => isset($_POST['agregar_vehiculo']),
        'insertar_vehiculo'  => isset($_POST['insertar_vehiculo']),
        'aprobar_credito'    => isset($_POST['aprobar_credito']),
        'rechazar_credito'   => isset($_POST['rechazar_credito']),
        'eliminar_vehiculo'  => isset($_POST['eliminar_vehiculo'])
    ];

    $sistema->query(
        "UPDATE usuarios SET permisos = :p WHERE id_usuario = :id",
        [':p' => json_encode($permisos), ':id' => $id]
    );

    $alerta = '<div class="alert alert-success alert-dismissible fade show">
                 <strong>¡Perfecto!</strong> Permisos guardados.
                 <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
               </div>';
}

// === CARGAR TODOS LOS USUARIOS ===
$usuarios = $sistema->fetchAll("SELECT * FROM usuarios ORDER BY nombre_completo");

include 'includes/header.php';
?>

<div class="container mt-5 pb-5">
    <div class="text-center mb-4">
        <h1 class="display-5 text-danger">GESTIÓN DE PERMISOS</h1>
        <p class="lead">Marca las casillas para dar o quitar permisos</p>
    </div>

    <?= $alerta ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle shadow">
            <thead class="table-danger text-white">
                <tr>
                    <th>USUARIO</th>
                    <th>ROL</th>
                    <th class="text-center">Agregar Vehículo</th>
                    <th class="text-center">Insertar Vehículo</th>
                    <th class="text-center">Aprobar Crédito</th>
                    <th class="text-center">Rechazar Crédito</th>
                    <th class="text-center">Eliminar Vehículo</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u):
                    $p = json_decode($u['permisos'] ?? '{}', true);
                ?>
                <tr class="<?= $u['rol']=='admin' ? 'table-warning' : '' ?>">
                    <form method="POST">
                        <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                        <td class="fw-bold">
                            <?= htmlspecialchars($u['nombre_completo']) ?><br>
                            <small class="text-muted"><?= $u['correo'] ?></small>
                        </td>
                        <td>
                            <span class="badge bg-<?= $u['rol']=='admin'?'danger':'primary' ?>">
                                <?= strtoupper($u['rol']) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="agregar_vehiculo"
                                       <?= ($p['agregar_vehiculo']??false) ? 'checked' : '' ?>>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="insertar_vehiculo"
                                       <?= ($p['insertar_vehiculo']??false) ? 'checked' : '' ?>>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="aprobar_credito"
                                       <?= ($p['aprobar_credito']??false) ? 'checked' : '' ?>>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="rechazar_credito"
                                       <?= ($p['rechazar_credito']??false) ? 'checked' : '' ?>>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="eliminar_vehiculo"
                                       <?= ($p['eliminar_vehiculo']??false) ? 'checked' : '' ?>>
                            </div>
                        </td>
                        <td>
                            <button name="guardar" class="btn btn-success btn-sm w-100">
                                GUARDAR
                            </button>
                        </td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <a href="admin_panel.php" class="btn btn-outline-danger btn-lg px-5">
            ← VOLVER AL PANEL ADMIN
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>