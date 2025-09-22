<?php
session_start();
if (empty($_SESSION['user_id'])) { http_response_code(403); exit; }
require __DIR__ . '/_conn.php';
$uid = (int)$_SESSION['user_id'];
$mysqli->query("UPDATE users SET seen_tour = 1 WHERE id = {$uid}");
echo '{"ok":1}';
