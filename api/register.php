<?php
// api/register.php
session_start();
require __DIR__ . '/_conn.php';

// Campos
$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name'] ?? '');
$email      = trim($_POST['email'] ?? '');
$username   = trim($_POST['username'] ?? '');
$phone      = trim($_POST['phone'] ?? '');
$birthdate  = trim($_POST['birthdate'] ?? '');
$password   = trim($_POST['password'] ?? '');

// Validación básica
if ($first_name === '' || $last_name === '' || $email === '' || $username === '' ||
    $phone === '' || $birthdate === '' || $password === '') {
  header('Location: ../register.php?error=bad'); exit;
}

// Duplicados
$stmt = $mysqli->prepare("SELECT id FROM users WHERE username=? LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
if ($stmt->get_result()->fetch_assoc()) {
  header('Location: ../register.php?error=dup_user'); exit;
}
$stmt = $mysqli->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
$stmt->bind_param('s', $email);
$stmt->execute();
if ($stmt->get_result()->fetch_assoc()) {
  header('Location: ../register.php?error=dup_mail'); exit;
}

// Inserción (sin hash, por pedido)
$stmt = $mysqli->prepare("
  INSERT INTO users (first_name, last_name, email, username, phone, birthdate, password, is_admin, created_at)
  VALUES (?, ?, ?, ?, ?, ?, ?, 0, NOW())
");
if (!$stmt) { header('Location: ../register.php?error=server'); exit; }
$stmt->bind_param('sssssss', $first_name, $last_name, $email, $username, $phone, $birthdate, $password);
$ok = $stmt->execute();

if (!$ok) {
  header('Location: ../register.php?error=server'); exit;
}

// Redirigimos al login con mensaje de éxito
header('Location: ../login.php?ok=1'); exit;
