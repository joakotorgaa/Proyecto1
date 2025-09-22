<?php
require __DIR__ . '/../includes/guard_user.php';
require __DIR__ . '/../api/_conn.php';
$uid = (int)$_SESSION['user_id'];

/* Buscar tickets del usuario */
$stmt = $mysqli->prepare("SELECT id, subject, status, updated_at FROM tickets WHERE user_id=? ORDER BY updated_at DESC");
$stmt->bind_param('i',$uid); $stmt->execute();
$tickets = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Tickets — CreditOrg</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/main.css">
</head>
<body class="co-body">
<header class="co-header">
  <div class="co-wrap co-header__in">
    <a class="co-brand" href="index.php"><span class="co-brand__badge">CO</span><span class="co-brand__name">CreditOrg</span></a>
    <nav class="co-nav">
      <ul class="co-nav__links">
        <li><a class="co-nav__link" href="index.php">Panel</a></li>
        <li><a class="co-nav__link" href="tickets.php">Tickets</a></li>
      </ul>
      <div class="co-nav__cta"><a class="co-btn" href="../logout.php">Salir</a></div>
    </nav>
  </div>
</header>

<main class="co-wrap">
  <h1 class="co-ttl">Tickets</h1>

  <section>
    <h2 class="co-ttl">Crear nuevo</h2>
    <form class="co-card co-form" method="post" action="../api/ticket_create.php">
      <div>
        <label class="co-label">Asunto</label>
        <input class="co-input" name="subject" maxlength="160" required>
      </div>
      <div>
        <label class="co-label">Mensaje</label>
        <textarea class="co-input" name="message" rows="4" required></textarea>
      </div>
      <div class="co-actions">
        <button class="co-btn co-btn--primary" type="submit">Abrir ticket</button>
      </div>
    </form>
  </section>

  <section>
    <h2 class="co-ttl">Mis tickets</h2>
    <div class="co-grid co-grid--3">
      <?php if (empty($tickets)): ?>
        <div class="co-card"><p class="co-muted">No tenés tickets aún.</p></div>
      <?php else: foreach ($tickets as $t): ?>
        <a class="co-card" href="ticket_view.php?id=<?php echo $t['id']; ?>">
          <h3>#<?php echo $t['id']; ?> — <?php echo htmlspecialchars($t['subject']); ?></h3>
          <p class="co-muted">Estado: <?php echo htmlspecialchars($t['status']); ?></p>
          <small class="co-muted">Actualizado: <?php echo $t['updated_at']; ?></small>
        </a>
      <?php endforeach; endif; ?>
    </div>
  </section>
</main>

<footer class="co-footer">
  <div class="co-wrap co-footer__in"><div></div><small class="co-copy">© <?php echo date('Y'); ?> CreditOrg</small></div>
</footer>
</body>
</html>
