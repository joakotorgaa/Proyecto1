<?php
session_start();
require __DIR__ . '/_conn.php';
if (empty($_SESSION['user_id'])) header('Location: ../login.php');
$uid=(int)$_SESSION['user_id'];
$amount = floatval($_POST['amount']); $months = (int)$_POST['months']; $rate = floatval($_POST['rate']);
if ($amount<=0 || $months<=0) header('Location: ../user/loans.php?error=1');

// calcular cuota simple (interés simple para demo)
$total = $amount * (1 + $rate * $months);
$installment = $total / $months;

$stmt = $mysqli->prepare("INSERT INTO loans (user_id,amount,months,rate,total,installment,status,created_at) VALUES (?,?,?,?,?,?, 'pending', NOW())");
$stmt->bind_param('iiddds',$uid,$amount,$months,$rate,$total,$installment);
$stmt->execute();
header('Location: ../user/loans.php?ok=1');
