<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
define('BASE_URL', '/final_web/'); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - AutoAgencia GAM</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css"> 
</head>
<body>

<main class="container mt-5">
