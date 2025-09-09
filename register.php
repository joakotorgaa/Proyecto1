<?php session_start(); if(!empty($_SESSION['user_id'])){ header('Location: user/'); exit; } ?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registrarse - CreditOrg</title> 
<link rel="stylesheet" href="assets/main.css">
</head>
<body>
<header><div class="header-inner">
  <a class="logo" href="index.php"><span class="badge">CO</span> CreditOrg</a>
  <nav class="nav"><a class="btn" href="login.php">Ingresar</a></nav>
</div></header>
<main class="container">
  <div class="card" style="max-width:500px;margin:20px auto;">
    <h2>Crear cuenta</h2>
    <form method="post" action="api/register.php">
      <label>Usuario</label>
      <input class="input" name="username" required>
      <label>Email</label>
      <input class="input" type="email" name="email" required>
      <label>Contraseña</label>
      <input class="input" type="password" name="password" required>
      <div style="margin-top:10px;display:flex;gap:8px;">
        <button class="btn btn-primary" type="submit">Registrarme</button>
        <a class="btn btn-outline" href="index.php">Cancelar</a>
      </div>
    </form>
  </div>
</main>
</body>
</html>
