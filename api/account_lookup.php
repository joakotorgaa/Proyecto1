<?php
// Busca una cuenta destino por alias/CBU/CVU y devuelve JSON
session_start();
require __DIR__ . '/_conn.php';
header('Content-Type: application/json');

if (empty($_SESSION['user_id'])) { http_response_code(403); echo json_encode(['ok'=>0]); exit; }

$key = trim($_POST['key'] ?? '');
if ($key === '') { echo json_encode(['ok'=>0,'error'=>'empty']); exit; }

/* Normalizamos entrada */
$k = preg_replace('/\s+/', '', $key);

/* Buscamos por alias exacto o CBU/CVU exacto */
$stmt = $mysqli->prepare("
  SELECT a.id, a.user_id, a.alias, a.cbu, a.currency, u.first_name, u.last_name, u.username
  FROM accounts a
  LEFT JOIN users u ON u.id = a.user_id
  WHERE a.alias = ? OR a.cbu = ?
  LIMIT 1
");
$stmt->bind_param('ss', $k, $k);
$stmt->execute();
$acc = $stmt->get_result()->fetch_assoc();

if (!$acc) { echo json_encode(['ok'=>0,'error'=>'not_found']); exit; }

/* Armamos respuesta (sin datos sensibles) */
$holder = trim(($acc['first_name'] ?? '').' '.($acc['last_name'] ?? ''));
if ($holder === '') $holder = $acc['username'] ?? 'Titular';

echo json_encode([
  'ok'=>1,
  'account'=>[
    'id'       => (int)$acc['id'],
    'alias'    => $acc['alias'],
    'cbu'      => $acc['cbu'],
    'currency' => $acc['currency'],
    'holder'   => $holder
  ]
]);
