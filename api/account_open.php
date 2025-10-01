<?php
session_start();
require __DIR__ . '/_conn.php';
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }
$uid = (int)$_SESSION['user_id'];

$type = trim($_POST['type'] ?? 'Caja de ahorro');
$currency = strtoupper(trim($_POST['currency'] ?? 'ARS'));
if (!in_array($currency, ['ARS','USD'])) $currency = 'ARS';

$alias = trim($_POST['alias'] ?? '');
if ($alias !== '') {
  // chequear alias duplicado en todo el banco
  $stmt = $mysqli->prepare("SELECT id FROM accounts WHERE alias = ? LIMIT 1");
  $stmt->bind_param('s',$alias);
  $stmt->execute();
  if ($stmt->get_result()->fetch_assoc()) {
    header('Location: ../user/accounts.php?error=dup_alias'); exit;
  }
}

// Generar CBU único
function gen_cbu($mysqli){
  do {
    $cbu = strval(rand(100000,999999)) . strval(rand(1000000000000,9999999999999)); // 6 + 13 = 19+ (simulado)
    $stmt = $mysqli->prepare("SELECT id FROM accounts WHERE cbu=? LIMIT 1");
    $stmt->bind_param('s',$cbu); $stmt->execute();
    $exists = $stmt->get_result()->fetch_assoc();
  } while($exists);
  return $cbu;
}
$cbu = gen_cbu($mysqli);

if ($alias === '') {
  $alias = 'alias_' . strtolower(substr(preg_replace('/\W+/','', $_SESSION['username']),0,8)) . rand(10,99);
}

$stmt = $mysqli->prepare("INSERT INTO accounts (user_id,type,currency,alias,cbu,balance,status,created_at) VALUES (?,?,?,?,?,0,'active',NOW())");
$stmt->bind_param('issss', $uid, $type, $currency, $alias, $cbu);
$ok = $stmt->execute();

if (!$ok) { header('Location: ../user/accounts.php?error=1'); exit; }
header('Location: ../user/accounts.php?ok=1'); exit;
