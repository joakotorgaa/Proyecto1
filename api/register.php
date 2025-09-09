<?php
header('Content-Type: text/html; charset=utf-8');

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = (string)($_POST['password'] ?? '');

if($username==='' || $email==='' || $password===''){ header('Location: ../register.php?e=1'); exit; }
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ header('Location: ../register.php?e=em'); exit; }

require __DIR__.'/_conn.php';

$stmt = $mysqli->prepare("SELECT id FROM users WHERE username=? OR email=? LIMIT 1");
$stmt->bind_param('ss', $username, $email);
$stmt->execute();
if($stmt->get_result()->fetch_assoc()){ header('Location: ../register.php?e=ex'); exit; }

$stmt = $mysqli->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?,?,?,0)");
$stmt->bind_param('sss', $username, $email, $password);
if(!$stmt->execute()){ header('Location: ../register.php?e=wr'); exit; }

header('Location: ../login.php?ok=1'); exit;
