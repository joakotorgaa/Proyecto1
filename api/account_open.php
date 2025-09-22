<?php
session_start();
require __DIR__ . '/_conn.php';
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }
$uid = (int)$_SESSION['user_id'];

$type = trim($_POST['type'] ?? 'Caja de ahorro');
$currency = strtoupper(trim($_POST['currency'] ?? 'ARS'));
if (!in_array($currency,['ARS','USD'])) $currency = 'ARS';

// Generar CBU único (simple)
function gen_cbu($mysqli){
  do {
    $prefix = '0'.strval(rand(10,99));
    $body = strval(rand(10000000000,99999999999)); // 11 digits
    $cbu = $prefix . $body;
    $stmt = $mysqli->prepare("SELECT id FROM accounts WHERE cbu = ? LIMIT 1");
    $stmt->bind_param('s',$cbu);
    $stmt->execute();
    $exists = $stmt->get_result()->fetch_assoc();
  } while($exists);
  return $cbu;
}

$alias = trim($_POST['alias'] ?? '');
if ($alias === '') {
  $alias = 'alias_' . strtolower(substr($_SESSION['username'],0,6)) . rand(10,99);
}
$cbu = gen_cbu($mysqli);

// Crear cuenta con saldo 0
$stmt = $mysqli->prepare("INSERT INTO accounts (user_id,type,currency,alias,cbu,balance,status,created_at) VALUES (?,?,?,?,?,0,'active',NOW())");
$stmt->bind_param('issss',$uid,$type,$currency,$alias,$cbu);
$ok = $stmt->execute();

if (!$ok) {
  header('Location: ../user/accounts.php?error=1'); exit;
}
header('Location: ../user/accounts.php?ok=1');
exit;
