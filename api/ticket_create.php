<?php
session_start(); require __DIR__ . '/_conn.php';
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }
$uid = (int)$_SESSION['user_id'];
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');
if ($subject === '' || $message === '') { header('Location: ../user/tickets.php?error=1'); exit; }

$mysqli->begin_transaction();
try{
  $stmt = $mysqli->prepare("INSERT INTO tickets (user_id,subject,status,created_at,updated_at) VALUES (?,?, 'open', NOW(), NOW())");
  $stmt->bind_param('is',$uid,$subject); $stmt->execute();
  $ticket_id = $mysqli->insert_id;

  $stmt = $mysqli->prepare("INSERT INTO ticket_messages (ticket_id,user_id,message,created_at) VALUES (?,?,?,NOW())");
  $stmt->bind_param('iis',$ticket_id,$uid,$message); $stmt->execute();

  $mysqli->commit();
  header('Location: ../user/ticket_view.php?id='.$ticket_id);
} catch(Throwable $e){
  $mysqli->rollback();
  header('Location: ../user/tickets.php?error=server');
}
