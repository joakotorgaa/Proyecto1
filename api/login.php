<?php
// api/login.php
session_start();
require __DIR__ . '/_conn.php';

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
  header('Location: ../login.php?error=1'); exit;
}

// ⚠️ Versión simple sin hash (como pediste antes)
$stmt = $mysqli->prepare("SELECT id, username, email, is_admin, password FROM users WHERE username=? LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

if (!$user || $user['password'] !== $password) {
  header('Location: ../login.php?error=1'); exit;
}

$_SESSION['user_id']  = (int)$user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['email']    = $user['email'];
$_SESSION['is_admin'] = (int)$user['is_admin'];

header('Location: ' . ((int)$user['is_admin'] ? '../admin/' : '../user/'));
exit;
