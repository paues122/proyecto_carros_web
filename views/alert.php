<?php

if (isset($alerta) && !empty($alerta['mensaje'])) {
    $tipo = htmlspecialchars($alerta['tipo'], ENT_QUOTES, 'UTF-8');
    $mensaje = htmlspecialchars($alerta['mensaje'], ENT_QUOTES, 'UTF-8');
    
    echo "<div class=\"alert alert-{$tipo} mt-3\" role=\"alert\">";
    echo $mensaje;
    echo "</div>";
}
?>
