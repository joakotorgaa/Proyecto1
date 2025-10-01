<?php
// public/api/transfer_stage.php
session_start();
require __DIR__ . '/_conn.php';

if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }

$dest_id  = (int)($_POST['dest_id'] ?? 0);
$currency = trim($_POST['dest_currency'] ?? '');

if ($dest_id <= 0 || $currency === '') { header('Location: ../user/transfer.php'); exit; }

/* Validar que el destino existe (defensa extra) */
$stmt = $mysqli->prepare("SELECT a.id, a.user_id, a.alias, a.cbu, a.currency, u.first_name, u.last_name, u.username
                          FROM accounts a LEFT JOIN users u ON u.id=a.user_id
                          WHERE a.id=? LIMIT 1");
$stmt->bind_param('i',$dest_id); $stmt->execute();
$acc = $stmt->get_result()->fetch_assoc();
if (!$acc) { header('Location: ../user/transfer.php'); exit; }

/* Guardar destino en sesión y redirigir a proceso */
$_SESSION['transfer_dest'] = [
  'id'       => (int)$acc['id'],
  'alias'    => $acc['alias'],
  'cbu'      => $acc['cbu'],
  'currency' => $acc['currency'],
  'holder'   => trim(($acc['first_name'] ?? '').' '.($acc['last_name'] ?? '')) ?: ($acc['username'] ?? 'Titular')
];

header('Location: ../user/transfer_process.php');
exit;
