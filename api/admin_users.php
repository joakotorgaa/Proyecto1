<?php
header('Content-Type: application/json; charset=utf-8');
$db_host = 'torga.com.ar';
$db_user = 'creditorg';
$db_pass = 'creditorg$2025';
$db_name = 'creditorg_db';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'DB error']); exit;
} 
$mysqli->set_charset('utf8mb4');
$res = $mysqli->query("SELECT id, username, email, is_admin, created_at FROM users ORDER BY id DESC LIMIT 200");
$data = [];
while($row = $res->fetch_assoc()){ $row['is_admin'] = (int)$row['is_admin']===1; $data[] = $row; }
echo json_encode(['success'=>true,'data'=>$data]);
