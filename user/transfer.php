<?php
session_start(); if(empty($_SESSION['user_id'])){ header('Location: ../login.php'); exit; }
?>
<!doctype html><html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Transferencias - CreditOrg</title>
<link rel="stylesheet" href="../assets/main.css"></head><body>
<header><div class="header-inner"><a class="logo" href="index.php"><span class="badge">CO</span> Mi Panel</a><nav class="nav"><a class="btn" href="index.php">Volver</a></nav></div></header>
<main class="container"><div class="card" style="max-width:720px;margin:0 auto;">
  <h2>Transferencias internas</h2>
  <form method="post" action
