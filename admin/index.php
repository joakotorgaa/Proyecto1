<?php
session_start();
if(empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])){ header('Location: ../login.php'); exit; }
?>
<!doctype html><html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin - CreditOrg</title>
<link rel="stylesheet" href="../assets/main.css"></head><body>
<header><div class="header-inner">
  <a class="logo" href="../index.php"><span class="badge">CO</span> CreditOrg</a>
  <nav class="nav">
    <a class="btn" href="../user/">Panel usuario</a>
    <a class="btn btn-outline" href="../logout.php">Salir</a>
  </nav>
</div></header>
<main class="container">
  <div class="card"><h2>Panel de administrador</h2><p>Gestioná usuarios y cuentas.</p></div>
  <div class="grid">
    <div class="card">
      <h3>Usuarios</h3>
      <form method="get" action="users.php" style="display:flex;gap:8px;margin:10px 0;">
        <input class="input" name="q" placeholder="Buscar usuario/email">
        <button class="btn btn-primary" type="submit">Abrir gestión</button>
      </form>
      <p>Alta/Baja/Edición, ver cuentas, resetear contraseña.</p>
    </div>
  </div>
</main>
</body></html>
