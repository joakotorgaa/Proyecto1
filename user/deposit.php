<?php
session_start(); if(empty($_SESSION['user_id'])){ header('Location: ../login.php'); exit; } 
?>
<!doctype html><html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ingresar dinero - CreditOrg</title>
<link rel="stylesheet" href="../assets/main.css"></head><body>
<header><div class="header-inner"><a class="logo" href="index.php"><span class="badge">CO</span> Mi Panel</a><nav class="nav"><a class="btn" href="index.php">Volver</a></nav></div></header>
<main class="container"><div class="card" style="max-width:720px;margin:0 auto;">
  <h2>Ingresar dinero</h2>
  <form method="post" action="../api/deposit.php" style="display:grid;gap:10px;">
    <label>Cuenta destino (ID)</label><input class="input" name="account_id" required>
    <label>Monto (ARS)</label><input class="input" name="amount" type="number" step="0.01" required>
    <button class="btn btn-primary">Depositar</button>
  </form>
</div></main></body></html>
