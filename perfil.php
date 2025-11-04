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

$usuario = $sistema->fetch("SELECT * FROM usuarios WHERE id_usuario = ?", [$_SESSION['id_usuario']]);
if (!$usuario) {
    $sistema->logout();
    header('Location: login.php');
    exit;
}

$cotizo = $sistema->fetch("
    SELECT auto_interes, plazo_meses, enganche_dado, fecha_cotizacion
    FROM cotizaciones 
    WHERE id_usuario = ? 
    ORDER BY fecha_cotizacion DESC LIMIT 1
", [$_SESSION['id_usuario']]);

include 'includes/header.php';
?>

<div class="container mt-5 pb-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0">MI PERFIL</h2>
        </div>
        <div class="card-body p-4">

            <div class="row text-center text-md-start">
                <div class="col-md-6">
                    <p class="fs-4"><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre_completo']) ?></p>
                    <p class="fs-5"><strong>Correo:</strong> <?= htmlspecialchars($usuario['correo']) ?></p>
                </div>
                <div class="col-md-6">
                    
                    <p class="fs-5"><strong>Ingresos:</strong> 
                        <span class="text-success fw-bold">$<?= number_format($usuario['ingresos_mensuales'] ?? 0, 2) ?></span>
                    </p>
                </div>
            </div>

            <hr class="my-5">
            <h3 class="text-primary text-center mb-4">ESTADO DE TU CRÉDITO</h3>

            <?php if ($cotizo): ?>
                
                    <h5 class="display-5">Tu cotización</h5>
                    
                        <div class="col-md-8">
                            <p class="lead"><strong>Auto:</strong> <?= htmlspecialchars($cotizo['auto_interes']) ?></p>
                            <p class="lead"><strong>Plazo:</strong> <?= $cotizo['plazo_meses'] ?> meses</p>
                            <p class="lead"><strong>Enganche:</strong> $<?= number_format($cotizo['enganche_dado'], 2) ?></p>
                            <p class="lead"><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($cotizo['fecha_cotizacion'])) ?></p>
                        </div>
                    </div>
                    <hr>
                    
                    <p class="text-muted">¡Gracias por confiar en GAM Multimarca!</p>
                </div>
            <?php else: ?>
                <div class="text-center p-5 bg-light rounded shadow">
                    
                    <p class="lead">Cotiza en 1 minuto y te llamamos</p>
                    <a href="cotizador.php" class="btn btn-primary btn-lg px-5 mt-3">
                        COTIZAR AHORA
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>