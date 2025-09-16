<?php
// api/login.php
session_start();
require __DIR__ . '/_conn.php'; // <-- Debe conectar a creditorg_db

$identifier = trim($_POST['username'] ?? ''); // puede ser user o email
$password   = trim($_POST['password'] ?? '');

if ($identifier === '' || $password === '') {
  header('Location: ../login.php?error=1'); exit;
}

/* Buscamos por usuario O por email (ambos son únicos en tu tabla) */
$stmt = $mysqli->prepare("
  SELECT id, username, email, password, is_admin
  FROM users
  WHERE username = ? OR email = ?
  LIMIT 1
");
$stmt->bind_param('ss', $identifier, $identifier);
$stmt->execute();
$res  = $stmt->get_result();
$user = $res->fetch_assoc();

/* Contraseña en texto plano (según tu requerimiento actual) */
if (!$user || $user['password'] !== $password) {
  header('Location: ../login.php?error=1'); exit;
}

/* Sesión y redirección por rol */
$_SESSION['user_id']  = (int)$user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['email']    = $user['email'];
$_SESSION['is_admin'] = (int)$user['is_admin'];

header('Location: ' . ($_SESSION['is_admin'] ? '../admin/' : '../user/'));
exit;
