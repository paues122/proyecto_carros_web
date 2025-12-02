<?php

session_start();


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;


if (!isset($_SESSION['validado']) || !$_SESSION['validado']) {
    header('Location: login.php');
    exit;
}

require 'vendor/autoload.php';

require_once 'models/sistemam.php';
$sistema = new Sistema();

$mensaje = '';

if (isset($_POST['enviar'])) {
    $auto     = trim($_POST['auto']);
    $plazo    = (int)$_POST['plazo'];
    $enganche = (float)$_POST['enganche'];

    if ($auto && $plazo > 0 && $enganche > 0) {
 
        $sistema->query("
            INSERT INTO cotizaciones 
            (id_usuario, auto_interes, plazo_meses, enganche_dado, fecha_cotizacion)
            VALUES (?, ?, ?, ?, NOW())
        ", [$_SESSION['id_usuario'], $auto, $plazo, $enganche]);

   
        $admin = $sistema->fetch("SELECT correo FROM usuarios WHERE rol='admin' LIMIT 1");
        if ($admin) {
            $html = "
                <h2>¡NUEVA COTIZACIÓN!</h2>
                <p><strong>Cliente:</strong> {$_SESSION['username']} (ID: {$_SESSION['id_usuario']})</p>
                <p><strong>Auto:</strong> $auto</p>
                <p><strong>Plazo:</strong> $plazo meses</p>
                <p><strong>Enganche:</strong> $$enganche</p>
                <hr>
                <a href='http://localhost:8080/final_web/admin_panel.php'>Ir al Panel Admin</a>
            ";
            
     

         
            
            $mail = new PHPMailer(true); 

            try {
               
                
             
                $mail->SMTPDebug = SMTP::DEBUG_OFF; 
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = '21030846@itcelaya.edu.mx'; 
                
               
                $mail->Password   = 'kpxc bscy njsj bkbk';
                
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;
                $mail->CharSet    = 'UTF-8';

             
                $mail->setFrom('21030846@itcelaya.edu.mx', 'Notificaciones Cotizador');
                $mail->addAddress($admin['correo']); 

              
                $mail->isHTML(true);
           
                $mail->Subject = "¡Nueva cotización de {$_SESSION['username']}"; 
         
                $mail->Body    = $html; 
                $mail->AltBody = strip_tags($html);

                $mail->send();
             
                
            } catch (Exception $e) {
           
                error_log("Error de PHPMailer: {$mail->ErrorInfo}");
            }
         

        } 

        $mensaje = '<div class="alert alert-success shadow p-4">
                        <h3>¡ENVIADO CON ÉXITO!</h3>
                        <p>Tu cotización ha sido registrada. Te contactamos en menos de 24 hrs.</p>
                        <a href="perfil.php" class="btn btn-primary">Ir a mi perfil</a>
                    </div>';
    } else {
        $mensaje = '<div class="alert alert-danger">Llena todos los campos</div>';
    }
}

include 'includes/header.php';
?>
<div class="container mt-5 pb-5">
            <h1 class="mb-0">COTIZA TU AUTO</h1>
        </div>

        <div class="card-body p-5">
            <?= $mensaje ?>

            <?php if (!$mensaje || str_contains($mensaje, 'danger')): ?>
            <form method="POST" class="needs-validation" novalidate>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Auto (marca y modelo)</label>
                        <input type="text" name="auto" class="form-control form-control-lg" 
                               placeholder="Ej. Nissan Versa 2024" required>
                        <div class="invalid-feedback">Escribe el auto</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Plazo del crédito</label>
                        <select name="plazo" class="form-select form-select-lg" required>
                            <option value="">Elige meses</option>
                            <option value="54">54 meses</option>
                            <option value="60">60 meses</option>
                            
                        </select>
                        <div class="invalid-feedback">Elige un plazo</div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Enganche disponible</label>
                        <input type="number" name="enganche" class="form-control form-control-lg"
                               placeholder="Ej. 68000" step="0.01" min="10000" required>
                       
                    </div>

                    <div class="text-center mt-5">
                        <button type="submit" name="enviar" class="btn btn-success btn-lg px-5">
                            ENVIAR COTIZACIÓN
                        </button>
                    </div>
                </div>
            </form>
            <?php endif; ?>
        </div>

      
    </div>
</div>

<script>

(() => {
  'use strict'
  const forms = document.querySelectorAll('.needs-validation')
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})()
</script>

<?php include 'includes/footer.php'; ?>