<?php
session_start();
header('Content-Type: text/html; charset=utf-8'); // redirigimos, no JSON 

$usuario    = trim($_POST['usuario'] ?? '');
$contrasena = (string)($_POST['contrasena'] ?? '');
if ($usuario === '' || $contrasena === '') { header('Location: ../login.php?e=1'); exit; }

require __DIR__.'/_conn.php';

$stmt = $mysqli->prepare("SELECT id, username, password, is_admin FROM users WHERE username=? LIMIT 1");
$stmt->bind_param('s', $usuario);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

if(!$user || $user['password'] !== $contrasena){
  header('Location: ../login.php?e=2'); exit;
}

$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['is_admin'] = ((int)$user['is_admin']===1);

if($_SESSION['is_admin']) header('Location: ../admin/'); else header('Location: ../user/');
exit;
