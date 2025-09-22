<?php
session_start();
require __DIR__ . '/_conn.php';
if (empty($_SESSION['user_id'])) header('Location: ../login.php');
$uid=(int)$_SESSION['user_id'];
$account_id=(int)$_POST['account_id']; $payee=trim($_POST['payee']); $amount=floatval($_POST['amount']);
if (!$account_id || !$payee || $amount<=0) header('Location: ../user/cheques.php?error=1');

$stmt = $mysqli->prepare("SELECT id,balance,user_id FROM accounts WHERE id=? LIMIT 1");
$stmt->bind_param('i',$account_id); $stmt->execute(); $acc = $stmt->get_result()->fetch_assoc();
if (!$acc || (int)$acc['user_id'] !== $uid) header('Location: ../user/cheques.php?error=forbidden');
if ($acc['balance'] < $amount) header('Location: ../user/cheques.php?error=nofunds');

// Simular emisión: descontar y crear cheque
$mysqli->begin_transaction();
try {
  $stmt = $mysqli->prepare("UPDATE accounts SET balance = balance - ? WHERE id=?");
  $stmt->bind_param('di',$amount,$account_id); $stmt->execute();
  $stmt = $mysqli->prepare("INSERT INTO cheques (user_id,account_id,payee,amount,status,issued_at) VALUES (?,?,?,?, 'issued', NOW())");
  $stmt->bind_param('iids',$uid,$account_id,$payee,$amount); $stmt->execute();
  $mysqli->commit();
  header('Location: ../user/cheques.php?ok=1');
} catch(Exception $e){ $mysqli->rollback(); header('Location: ../user/cheques.php?error=tx');}
