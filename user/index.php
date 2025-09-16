<?php require __DIR__ . '/../includes/guard_user.php'; ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel — CreditOrg</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/main.css">
</head>
<body class="co-body">

<header class="co-header">
  <div class="co-wrap co-header__in">
    <a class="co-brand" href="../index.php">
      <span class="co-brand__badge">CO</span><span class="co-brand__name">CreditOrg</span>
    </a>
    <nav class="co-nav">
      <ul class="co-nav__links">
        <li><a class="co-nav__link" href="accounts.php">Cuentas</a></li>
        <li><a class="co-nav__link" href="transfer.php">Transferir</a></li>
        <li><a class="co-nav__link" href="cards.php">Tarjetas</a></li>
        <li><a class="co-nav__link" href="tickets.php">Ayuda</a></li>
        <li><a class="co-nav__link" href="profile.php">Perfil</a></li>
      </ul>
      <div class="co-nav__cta">
        <span class="co-muted">Hola, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a class="co-btn" href="../logout.php">Salir</a>
      </div>
    </nav>
  </div>
</header>

<main class="co-wrap">
  <h1 class="co-ttl">Panel</h1>
  <div class="co-grid co-grid--3">
    <a class="co-card" href="accounts.php">
      <h3>Cuentas</h3>
      <p class="co-muted">Ver saldos, CBU/Alias y abrir nuevas cuentas.</p>
    </a>
    <a class="co-card" href="transfer.php">
      <h3>Transferencias</h3>
      <p class="co-muted">Enviar dinero a cuentas del banco.</p>
    </a>
    <a class="co-card" href="cards.php">
      <h3>Tarjetas</h3>
      <p class="co-muted">Generar, pausar o denunciar tarjetas.</p>
    </a>
    <a class="co-card" href="tickets.php">
      <h3>Tickets</h3>
      <p class="co-muted">Crear y seguir consultas.</p>
    </a>
    <a class="co-card" href="profile.php">
      <h3>Perfil</h3>
      <p class="co-muted">Actualizar datos y contraseña.</p>
    </a>
  </div>
</main>

<footer class="co-footer">
  <div class="co-wrap co-footer__in">
    <div></div>
    <small class="co-copy">© <?php echo date('Y'); ?> CreditOrg</small>
  </div>
</footer>

</body>
</html>
