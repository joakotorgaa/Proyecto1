<?php
session_start();
require __DIR__ . '/_conn.php';
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }
$uid = (int)$_SESSION['user_id'];

$first = trim($_POST['first_name'] ?? '');
$last  = trim($_POST['last_name'] ?? '');
$user  = trim($_POST['username'] ?? '');
$mail  = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$born  = trim($_POST['birthdate'] ?? '');
$pass1 = trim($_POST['password'] ?? '');
$pass2 = trim($_POST['password2'] ?? '');

if ($first==='' || $last==='' || $user==='' || $mail==='') {
  header('Location: ../user/profile.php?error=bad'); exit;
}

/* Chequear duplicados de username y email */
$stmt = $mysqli->prepare("SELECT id FROM users WHERE username=? AND id<>? LIMIT 1");
$stmt->bind_param('si',$user,$uid); $stmt->execute();
if ($stmt->get_result()->fetch_assoc()) { header('Location: ../user/profile.php?error=dup_user'); exit; }

$stmt = $mysqli->prepare("SELECT id FROM users WHERE email=? AND id<>? LIMIT 1");
$stmt->bind_param('si',$mail,$uid); $stmt->execute();
if ($stmt->get_result()->fetch_assoc()) { header('Location: ../user/profile.php?error=dup_mail'); exit; }

/* Actualizar */
$mysqli->begin_transaction();
try {
  $stmt = $mysqli->prepare("UPDATE users SET first_name=?, last_name=?, username=?, email=?, phone=?, birthdate=? WHERE id=?");
  $stmt->bind_param('ssssssi', $first, $last, $user, $mail, $phone, $born, $uid);
  $stmt->execute();

  if ($pass1 !== '' || $pass2 !== '') {
    if ($pass1 !== $pass2) throw new Exception('pass_mismatch');
    // ⚠️ Según tu requerimiento actual seguimos sin hash:
    $stmt = $mysqli->prepare("UPDATE users SET password=? WHERE id=?");
    $stmt->bind_param('si',$pass1,$uid);
    $stmt->execute();
  }

  $mysqli->commit();

  /* Actualizar sesión para mostrar el saludo correcto y navbar */
  $_SESSION['first_name'] = $first;
  $_SESSION['last_name']  = $last;
  $_SESSION['username']   = $user;
  $_SESSION['email']      = $mail;

  header('Location: ../user/profile.php?ok=1'); exit;
} catch(Throwable $e){
  $mysqli->rollback();
  header('Location: ../user/profile.php?error=server'); exit;
}
