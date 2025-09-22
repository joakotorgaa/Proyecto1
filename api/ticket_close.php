<?php
session_start(); require __DIR__ . '/_conn.php';
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }
$uid=(int)$_SESSION['user_id']; $tid=(int)($_POST['id'] ?? 0);
if ($tid<=0){ header('Location: ../user/tickets.php'); exit; }

$stmt=$mysqli->prepare("UPDATE tickets SET status='closed', updated_at=NOW() WHERE id=? AND user_id=?");
$stmt->bind_param('ii',$tid,$uid); $stmt->execute();
header('Location: ../user/ticket_view.php?id='.$tid);
