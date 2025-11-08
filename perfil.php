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


$qr_file = null;
if ($cotizo) {
    $qr_lib = 'libs/phpqrcode/qrlib.php';
    if (file_exists($qr_lib)) {
        require_once $qr_lib;

        $target_url = "https://wa.me/message/UOTTUER73EA3B1";
        $qr_dir = 'qrcodes/';
        if (!is_dir($qr_dir)) {
            mkdir($qr_dir, 0755, true);
        }
        $qr_file = $qr_dir . 'qr-aprobado-' . $_SESSION['id_usuario'] . '.png';
        QRcode::png($target_url, $qr_file, QR_ECLEVEL_L, 6);
    }
}

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
                <div class="col-md-6 text-md-end">
                    <p class="fs-5"><strong>Ingresos:</strong> 
                        <span class="text-success fw-bold">$<?= number_format($usuario['ingresos_mensuales'] ?? 0, 2) ?></span>
                    </p>
                </div>
            </div>

            <hr class="my-5">

           
            <h3 class="text-primary text-center mb-4">ESTADO DE TU CRÉDITO</h3>

            <?php if ($cotizo): ?>
                <div class="bg-light rounded-4 p-4 shadow-sm border">
                    <div class="row justify-content-center align-items-center g-4">
                        
                     
                        <div class="col-lg-6 text-center text-lg-start">
                            <h5 class="display-6 mb-3">Tu cotización</h5>
                            <p class="lead mb-2"><strong>Auto:</strong> <?= htmlspecialchars($cotizo['auto_interes']) ?></p>
                            <p class="lead mb-2"><strong>Plazo:</strong> <?= $cotizo['plazo_meses'] ?> meses</p>
                            <p class="lead mb-2"><strong>Enganche:</strong> $<?= number_format($cotizo['enganche_dado'], 2) ?></p>
                            <p class="lead mb-0"><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($cotizo['fecha_cotizacion'])) ?></p>
                        </div>

                        <div class="col-lg-6 text-center">
                            <div class="bg-white p-3 rounded-4 shadow-sm d-inline-block" style="max-width: 200px;">
                                <p class="mb-2 fw-bold text-primary small lh-1">
                                    Muestra tu<br>aprobación
                                </p>
                                <?php if ($qr_file && file_exists($qr_file)): ?>
                                    <img src="<?= $qr_file ?>?v=<?= time() ?>" 
                                         alt="QR Aprobación" 
                                         class="img-fluid rounded border mb-2" 
                                         style="width: 120px; height: 120px;">
                                <?php else: ?>
                                    <div class="bg-secondary-subtle border rounded mx-auto mb-2" style="width: 120px; height: 120px;"></div>
                                <?php endif; ?>
                                <small class="text-muted d-block">Escanea aquí</small>
                            </div>

                        
<div class="mt-4 text-center">
    <form action="generar_pdf.php" method="POST">
        <input type="hidden" name="generar_pdf" value="1">
        <button type="submit" class="btn btn-success btn-lg px-5">
            GENERAR PDF
        </button>
    </form>
</div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <p class="text-muted text-center mb-0">¡Gracias por confiar en GAM Multimarca!</p>
                </div>

                
                <?php if (isset($_GET['pdf'])): ?>
                    <?php if ($_GET['pdf'] === 'success'): ?>
                        <div class="alert alert-success text-center mt-4">
                            Reporte generado y enviado a tu correo.
                        </div>
                    <?php elseif ($_GET['pdf'] === 'error'): ?>
                        <div class="alert alert-danger text-center mt-4">
                            Error al generar el reporte. Intenta de nuevo.
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

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