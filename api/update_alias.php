<?php
session_start();
require __DIR__ . '/_conn.php';
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }
$uid = (int)$_SESSION['user_id'];
$account_id = (int)($_POST['account_id'] ?? 0);
$alias = trim($_POST['alias'] ?? '');

if ($account_id <= 0 || $alias === '') { header('Location: ../user/accounts.php?error=1'); exit; }

// Comprobar que la cuenta pertenece al usuario
$stmt = $mysqli->prepare("SELECT id FROM accounts WHERE id=? AND user_id=? LIMIT 1");
$stmt->bind_param('ii',$account_id,$uid);
$stmt->execute();
if (!$stmt->get_result()->fetch_assoc()) { header('Location: ../user/accounts.php?error=forbidden'); exit; }

// Actualizar
$stmt = $mysqli->prepare("UPDATE accounts SET alias=? WHERE id=?");
$stmt->bind_param('si',$alias,$account_id);
$stmt->execute();
header('Location: ../user/accounts.php?ok=alias');
