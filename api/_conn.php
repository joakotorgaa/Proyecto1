<?php
// Conexión MySQL (ajustar credenciales si hace falta)
$db_host = 'torga.com.ar';
$db_user = 'creditorg';
$db_pass = 'creditorg$2025';
$db_name = 'creditorg_db';

$mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_errno) {
  die('DB error: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
