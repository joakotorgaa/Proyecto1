<?php
session_start(); if(empty($_SESSION['user_id'])){ header('Location: ../login.php'); exit; } 
?>
<!doctype html><html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tarjetas - CreditOrg</title>
<link rel="stylesheet" href="../assets/main.css"></head><body>
<header><div class="header-inner"><a class="logo" href="index.php"><span class="badge">CO</span> Mi Panel</a><nav class="nav"><a class="btn" href="index.php">Volver</a></nav></div></header>
<main class="container"><div class="card" style="max-width:720px;margin:0 auto;">
  <h2>Tarjetas</h2>
  <form method="post" action="../api/cards_generate.php" style="display:flex;gap:8px;align-items:end;">
    <div><label>Marca</label><select class="input" name="brand"><option>VISA</option><option>MASTERCARD</option></select></div>
    <button class="btn btn-primary">Generar tarjeta</button>
  </form>
  <hr style="margin:12px 0">
  <form method="post" action="../api/cards_update.php" style="display:grid;gap:10px;">
    <label>ID Tarjeta</label><input class="input" name="card_id" required>
    <label>Acción</label>
    <select class="input" name="action" required>
      <option value="pausar">Pausar</option>
      <option value="eliminar">Eliminar</option>
      <option value="denunciar">Denunciar</option>
    </select>
    <button class="btn btn-outline">Aplicar</button>
  </form>
</div></main></body></html>
