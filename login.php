<?php

include_once("models/sistemam.php");
$app = new Sistema(); 
$alerta = [];

$action = isset($_GET['action']) ? $_GET['action'] : 'login';

if ($action == 'login' && isset($_SESSION['validado']) && $_SESSION['validado']) {
     header("Location: index.php");
     exit;
}

switch ($action) {
    case 'login':
        if (isset($_POST['enviar'])) {
           
            $username = $_POST['username'];
            $contrasena = $_POST['password'];
            
            $login = $app->login($username, $contrasena);
            
            if ($login) {
               
                header("Location: index.php");
                exit; 
            } else {
                $alerta['mensaje'] = "Usuario o contraseña incorrecta";
                $alerta['tipo'] = "danger";
            }
        }
      
        break;
    
  
    case 'logout_success':
        $alerta['mensaje'] = "Has salido correctamente del sistema";
        $alerta['tipo'] = "success";
        break;

    default:
      
        break;
}

include_once('includes/header_login.php'); 
?>

<section class="login-card card">
  <h1>Bienvenido a GAM multimarca</h1>
  <h3>Inicia sesión</h3>

  <?php
  
    if (!empty($alerta)) {
        include('views/alert.php');
    }
  ?>

  <form action="login.php?action=login" method="POST">
    
    <div class="form-group">
      <input type="text" name="username" placeholder="Nombre de usuario" required>
    </div>
    <div class="form-group">
      <input type="password" name="password" placeholder="Contraseña" required>
    </div>
    
    <button type="submit" name="enviar" class="btn">Iniciar Sesión</button>
  </form>
</section>

<?php 

include_once('includes/footer.php'); 
?>
