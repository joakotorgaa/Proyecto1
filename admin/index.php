<?php require __DIR__ . '/../includes/guard_admin.php'; ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Admin — CreditOrg</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/main.css">
</head>
<body class="co-body">

<header class="co-header">
  <div class="co-wrap co-header__in">
    <a class="co-brand" href="../index.php"><span class="co-brand__badge">CO</span><span class="co-brand__name">CreditOrg</span></a>
    <nav class="co-nav">
      <ul class="co-nav__links">
        <li><a class="co-nav__link" href="users.php">Usuarios</a></li>
        <li><a class="co-nav__link" href="user_accounts.php">Cuentas por usuario</a></li>
      </ul>
      <div class="co-nav__cta">
        <span class="co-muted">Admin: <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a class="co-btn" href="../logout.php">Salir</a>
      </div>
    </nav>
  </div>
</header>

<main class="co-wrap">
  <h1 class="co-ttl">Panel de administración</h1>
  <div class="co-grid co-grid--3">
    <a class="co-card" href="users.php">
      <h3>Usuarios</h3><p class="co-muted">Buscar, editar, promover a admin y resetear clave.</p>
    </a>
    <a class="co-card" href="user_accounts.php">
      <h3>Cuentas</h3><p class="co-muted">Ver/crear cuentas para un usuario.</p>
    </a>
  </div>
</main>

<footer class="co-footer">
  <div class="co-wrap co-footer__in"><div></div><small class="co-copy">© <?php echo date('Y'); ?> CreditOrg</small></div>
</footer>
</body>
</html>
