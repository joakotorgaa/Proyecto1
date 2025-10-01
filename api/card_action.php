<?php
session_start();
require __DIR__ . '/_conn.php';
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }
$uid = (int)$_SESSION['user_id'];

$id = (int)($_POST['id'] ?? 0);
$action = $_POST['action'] ?? '';
if ($id<=0){ header('Location: ../user/cards.php?error=1'); exit; }

$stmt = $mysqli->prepare("SELECT user_id,status FROM cards WHERE id=? LIMIT 1");
$stmt->bind_param('i',$id); $stmt->execute();
$c = $stmt->get_result()->fetch_assoc();
if (!$c || (int)$c['user_id'] !== $uid){ header('Location: ../user/cards.php?error=forbidden'); exit; }

if ($action === 'toggle') {
  $new = ($c['status']==='paused') ? 'active' : 'paused';
  $stmt = $mysqli->prepare("UPDATE cards SET status=? WHERE id=?");
  $stmt->bind_param('si',$new,$id); $stmt->execute();
} elseif ($action === 'report') {
  $stmt = $mysqli->prepare("UPDATE cards SET status='reported' WHERE id=?");
  $stmt->bind_param('i',$id); $stmt->execute();
} elseif ($action === 'delete') {
  $stmt = $mysqli->prepare("DELETE FROM cards WHERE id=?");
  $stmt->bind_param('i',$id); $stmt->execute();
}

header('Location: ../user/cards.php');
