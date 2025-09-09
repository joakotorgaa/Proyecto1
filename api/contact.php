<?php
// Contacto básico para la landing (sin sesiones)
// Guarda el mensaje en un archivo local como “bandeja de entrada”.
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$topic   = trim($_POST['topic'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $topic === '' || $message === '') {
  header('Location: ../index.php#contacto');
  exit;
}

$inbox = __DIR__ . '/contact_inbox.txt';
$line = date('Y-m-d H:i:s') . " | $name <$email> | $topic | " . str_replace(["\r","\n"], ' ', $message) . PHP_EOL;
file_put_contents($inbox, $line, FILE_APPEND);

header('Location: ../index.php#contacto');
exit;
