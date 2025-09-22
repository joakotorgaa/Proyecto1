<?php
// public/api/login.php
session_start();
require __DIR__ . '/_conn.php';

$identifier = trim($_POST['username'] ?? '');
$password   = trim($_POST['password'] ?? '');

if ($identifier === '' || $password === '') {
  header('Location: ../login.php?error=1'); exit;
}

/* Buscamos por usuario o email */
$stmt = $mysqli->prepare("
  SELECT id, username, email, password, is_admin,
         /* estas columnas deben existir tras la migración */
         COALESCE(first_name,'') AS first_name,
         COALESCE(last_name,'')  AS last_name
  FROM users
  WHERE username = ? OR email = ?
  LIMIT 1
");
$stmt->bind_param('ss', $identifier, $identifier);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

/* Contraseña en texto plano (según tu requerimiento actual) */
if (!$user || $user['password'] !== $password) {
  header('Location: ../login.php?error=1'); exit;
}

/* Sesión */
$_SESSION['user_id']     = (int)$user['id'];
$_SESSION['username']    = $user['username'];
$_SESSION['email']       = $user['email'];
$_SESSION['is_admin']    = (int)$user['is_admin'];
$_SESSION['first_name']  = $user['first_name'] ?? '';
$_SESSION['last_name']   = $user['last_name'] ?? '';

/* Redirección */
header('Location: ' . ($_SESSION['is_admin'] ? '../admin/' : '../user/'));
exit;
