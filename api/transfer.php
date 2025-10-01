<?php
session_start();
require __DIR__ . '/_conn.php';
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }
$uid = (int)$_SESSION['user_id'];

$from   = (int)($_POST['account_from'] ?? 0);
$to     = (int)($_POST['account_to'] ?? 0);
$amount = (float)($_POST['amount'] ?? 0);
$concept= trim($_POST['concept'] ?? '');

if ($from<=0 || $to<=0 || $amount<=0) { header('Location: ../user/transfer.php?error=bad'); exit; }
if ($from === $to) { header('Location: ../user/transfer.php?error=same'); exit; }

/* Cargar cuentas */
// ...
$stmt = $mysqli->prepare("INSERT INTO transactions (user_id,user_from,user_to,account_from,account_to,amount,currency,concept,created_at) VALUES (?,?,?,?,?,?,?,?,NOW())");
/* tipos: i i i i i d s s -> 'iiiiidss' */
$stmt->bind_param('iiiiidss', $uid, $user_from, $user_to, $from, $to, $amount, $currency, $concept);
// ...

$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
if (count($rows) < 2) { header('Location: ../user/transfer.php?error=notfound'); exit; }

$acc = [];
foreach($rows as $r){ $acc[$r['id']] = $r; }

/* Origen debe ser del usuario */
if ((int)$acc[$from]['user_id'] !== $uid) { header('Location: ../user/transfer.php?error=forbidden'); exit; }

/* Moneda debe coincidir */
if ($acc[$from]['currency'] !== $acc[$to]['currency']) { header('Location: ../user/transfer.php?error=currency'); exit; }

/* Saldo suficiente */
if ((float)$acc[$from]['balance'] < $amount) { header('Location: ../user/transfer.php?error=nofunds'); exit; }

/* Ejecutar */
$mysqli->begin_transaction();
try {
  $stmt = $mysqli->prepare("UPDATE accounts SET balance=balance-? WHERE id=?");
  $stmt->bind_param('di',$amount,$from); $stmt->execute();

  $stmt = $mysqli->prepare("UPDATE accounts SET balance=balance+? WHERE id=?");
  $stmt->bind_param('di',$amount,$to); $stmt->execute();

  $currency = $acc[$from]['currency'];
  $stmt = $mysqli->prepare("INSERT INTO transactions (user_id,user_from,user_to,account_from,account_to,amount,currency,concept,created_at) VALUES (?,?,?,?,?,?,?,?,NOW())");
  $user_from = $acc[$from]['user_id'];
  $user_to   = $acc[$to]['user_id'];
  $stmt->bind_param('iiiiddss', $uid, $user_from, $user_to, $from, $to, $amount, $currency, $concept);
  $stmt->execute();

  $mysqli->commit();
  header('Location: ../user/transfer.php?ok=1'); exit;
} catch(Throwable $e){
  $mysqli->rollback();
  header('Location: ../user/transfer.php?error=tx'); exit;
}
