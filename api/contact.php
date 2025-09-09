<?php
// Contacto básico: si hay sesión, lo redirigimos a tickets.
// Si no, guardamos el mensaje en un archivo local como “bandeja de entrada”.
session_start();

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$topic   = trim($_POST['topic'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $topic === '' || $message === '') {
  header('Location: ../index.php#contacto');
  exit;
}

if (!empty($_SESSION['user_id'])) {
  // Si está logueado, creamos ticket con el mismo flujo del sistema
  require __DIR__ . '/_conn.php';
  $uid = (int)$_SESSION['user_id'];
  $subject = 'Contacto: ' . $topic . ' - ' . $name;
  $body    = "Email: $email\n\n" . $message;

  $stmt = $mysqli->prepare("INSERT INTO tickets (user_id,subject,body) VALUES (?,?,?)");
  $stmt->bind_param('iss', $uid, $subject, $body);
  $stmt->execute();

  header('Location: ../user/tickets.php');
  exit;
} else {
  // Invitado: logueamos el mensaje en un archivo (simple y básico)
  $inbox = __DIR__ . '/contact_inbox.txt';
  $line = date('Y-m-d H:i:s') . " | $name <$email> | $topic | " . str_replace(["\r","\n"], ' ', $message) . PHP_EOL;
  file_put_contents($inbox, $line, FILE_APPEND);

  // Volvemos a la home con ancla
  header('Location: ../index.php#contacto');
  exit;
}
