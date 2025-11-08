<?php

session_start();

if (!isset($_SESSION['validado']) || !$_SESSION['validado'] || !isset($_POST['generar_pdf'])) {
    header('Location: perfil.php');
    exit;
}

file_put_contents('debug.txt', "generar_pdf.php ejecutado: " . date('H:i:s') . "\n", FILE_APPEND);


$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        body { font-family: "DejaVu Sans", sans-serif; background: #f5e6d3; margin: 0; padding: 40px 20px; color: #333; }
        .container { max-width: 800px; margin: 0 auto; background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .header { text-align: center; padding: 30px 20px 20px; background: #f5e6d3; }
        .logo { height: 60px; margin-bottom: 10px; }
        .title { font-size: 48px; font-weight: bold; color: #1a1a1a; margin: 20px 0 10px; }
        .subtitle { font-size: 22px; color: #d35400; margin: 15px 0; }
        .content { padding: 40px 50px; text-align: center; font-size: 18px; line-height: 1.8; }
        .phone { font-size: 42px; font-weight: bold; color: #2c3e50; margin: 30px 0; letter-spacing: 3px; }
        .qr-box { display: inline-block; background: white; padding: 15px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin: 20px 0; }
        .qr-img { width: 140px; height: 140px; }
        .footer { text-align: center; padding: 20px; font-size: 14px; color: #7f8c8d; background: #f8f9fa; }
        .badge { display: inline-block; background: #e74c3c; color: white; padding: 5px 15px; border-radius: 20px; font-size: 14px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-size:28px; color:#2c3e50;">AUTOS CELAYA</h1>
            <p style="margin:5px 0 0; color:#7f8c8d;">CAMPESTRE</p>
        </div>

        <div class="content">
            <h1 class="title">¡FELICITACIONES!</h1>
            <p class="subtitle">
                Su crédito ha sido <strong>aprobado</strong> por la agencia GAM.
            </p>
            <p>Muchas felicidades por su nuevo vehículo.</p>

            <div class="qr-box">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=https://wa.me/message/UOTTUER73EA3B1" 
                     class="qr-img" alt="QR WhatsApp">
                <p style="margin:5px 0 0; font-size:14px; color:#7f8c8d;">Escanea para contactarnos</p>
            </div>

            <p>Para continuar con su proceso, contáctenos:</p>
            <div class="phone">461 639 1706</div>
            <span class="badge">Llámanos ahora</span>
        </div>
    </div>
</body>
</html>';


require_once 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$output = $dompdf->output();
$filename = "prueba_" . time() . ".pdf";

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');
echo $output;
exit;