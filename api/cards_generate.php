<?php
session_start();
require __DIR__ . '/_conn.php';
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }
$uid = (int)$_SESSION['user_id'];

$label = trim($_POST['label'] ?? '');
$type  = ($_POST['type'] ?? 'debit') === 'credit' ? 'credit' : 'debit';

// Generar PAN simulado y vencimiento
function random_pan(){ $s=''; for($i=0;$i<16;$i++) $s.=rand(0,9); return $s; }
$pan     = random_pan();
$last4   = substr($pan,-4);
$exp_mon = rand(1,12);
$exp_yr  = (int)date('Y') + rand(2,5);
$status  = 'active';

$stmt = $mysqli->prepare("INSERT INTO cards (user_id,label,pan,last4,exp_month,exp_year,type,status,created_at) VALUES (?,?,?,?,?,?,?,?,NOW())");
/* 8 parámetros: i s s s i i s s  -> 'isssiiss' */
$stmt->bind_param('isssiiss', $uid, $label, $pan, $last4, $exp_mon, $exp_yr, $type, $status);
$stmt->execute();

header('Location: ../user/cards.php');
