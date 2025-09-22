<?php
session_start();
require __DIR__ . '/_conn.php';
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }
$uid = (int)$_SESSION['user_id'];

$label = trim($_POST['label'] ?? '');
$type = $_POST['type'] ?? 'debit';

// Generar PAN simulado (16 digits) y exp
function random_pan(){
  $pan = '';
  for($i=0;$i<16;$i++) $pan .= rand(0,9);
  return $pan;
}
$pan = random_pan();
$last4 = substr($pan, -4);
$exp_month = rand(1,12);
$exp_year = date('Y') + rand(2,5);
$status = 'active';

$stmt = $mysqli->prepare("INSERT INTO cards (user_id,label,pan,last4,exp_month,exp_year,type,status,created_at) VALUES (?,?,?,?,?,?,?,?,NOW())");
$stmt->bind_param('isssiisss',$uid,$label,$pan,$last4,$exp_month,$exp_year,$type,$status);
$ok = $stmt->execute();
header('Location: ../user/cards.php');
