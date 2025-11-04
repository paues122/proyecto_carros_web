<?php
include_once("models/sistemam.php");
$app = new Sistema();

$app->logout();

header("Location: login.php?action=logout_success");
exit;
?>
