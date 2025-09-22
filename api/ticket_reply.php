<?php
session_start(); require __DIR__ . '/_conn.php';
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }
$uid=(int)$_SESSION['user_id']; $tid=(int)($_POST['id'] ?? 0);
$msg=trim($_POST['message'] ?? '');
if ($tid<=0 || $msg===''){ header('Location: ../user/tickets.php?error=1'); exit; }

/* validar que el ticket es del usuario */
$stmt=$mysqli->prepare("SELECT id FROM tickets WHERE id=? AND user_id=? LIMIT 1");
$stmt->bind_param('ii',$tid,$uid); $stmt->execute();
if (!$stmt->get_result()->fetch_assoc()){ header('Location: ../user/tickets.php?error=forbidden'); exit; }

$mysqli->begin_transaction();
try{
  $stmt=$mysqli->prepare("INSERT INTO ticket_messages (ticket_id,user_id,message,created_at) VALUES (?,?,?,NOW())");
  $stmt->bind_param('iis',$tid,$uid,$msg); $stmt->execute();

  $stmt=$mysqli->prepare("UPDATE tickets SET status='in_progress', updated_at=NOW() WHERE id=?");
  $stmt->bind_param('i',$tid); $stmt->execute();

  $mysqli->commit();
  header('Location: ../user/ticket_view.php?id='.$tid);
} catch(Throwable $e){ $mysqli->rollback(); header('Location: ../user/ticket_view.php?id='.$tid.'&error=server'); }
