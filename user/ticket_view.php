<?php
require __DIR__ . '/../includes/guard_user.php';
require __DIR__ . '/../api/_conn.php';
$uid = (int)$_SESSION['user_id'];
$tid = (int)($_GET['id'] ?? 0);

$stmt = $mysqli->prepare("SELECT id, user_id, subject, status, created_at, updated_at FROM tickets WHERE id=? LIMIT 1");
$stmt->bind_param('i',$tid); $stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();
if (!$ticket || (int)$ticket['user_id'] !== $uid) { header('Location: tickets.php'); exit; }

$msgs = $mysqli->prepare("SELECT tm.id, tm.user_id, tm.message, tm.created_at, u.username
  FROM ticket_messages tm
  LEFT JOIN users u ON u.id = tm.user_id
  WHERE tm.ticket_id=? ORDER BY tm.id ASC");
$msgs->bind_param('i',$tid); $msgs->execute();
$msgs = $msgs->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Ticket #<?php echo $ticket['id']; ?> — CreditOrg</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/main.css">
  <style>
    .bubble{border:1px solid var(--co-line);background:#fff;padding:12px;border-radius:12px}
    .bubble.me{background:#eaf2ff;border-color:#cfe0ff}
    .thread{display:grid;gap:10px}
  </style>
</head>
<body class="co-body">
<header class="co-header">
  <div class="co-wrap co-header__in">
    <a class="co-brand" href="tickets.php"><span class="co-brand__badge">CO</span><span class="co-brand__name">CreditOrg</span></a>
    <nav class="co-nav"><div class="co-nav__cta"><a class="co-btn" href="../logout.php">Salir</a></div></nav>
  </div>
</header>

<main class="co-wrap">
  <h1 class="co-ttl">Ticket #<?php echo $ticket['id']; ?> — <?php echo htmlspecialchars($ticket['subject']); ?></h1>
  <p class="co-muted">Estado: <b><?php echo htmlspecialchars($ticket['status']); ?></b></p>

  <div class="thread">
    <?php foreach($msgs as $m): ?>
      <div class="bubble <?php echo ($m['user_id']===$uid?'me':''); ?>">
        <small class="co-muted">
          <?php echo htmlspecialchars($m['username'] ?? 'usuario'); ?> — <?php echo $m['created_at']; ?>
        </small>
        <p><?php echo nl2br(htmlspecialchars($m['message'])); ?></p>
      </div>
    <?php endforeach; ?>
  </div>

  <?php if ($ticket['status'] !== 'closed'): ?>
  <form class="co-card co-form" method="post" action="../api/ticket_reply.php" style="margin-top:12px">
    <input type="hidden" name="id" value="<?php echo $ticket['id']; ?>">
    <label class="co-label">Responder</label>
    <textarea class="co-input" name="message" rows="4" required></textarea>
    <div class="co-actions">
      <button class="co-btn co-btn--primary" type="submit">Enviar</button>
      <a class="co-btn co-btn--ghost" href="tickets.php">Volver</a>
      <button class="co-btn" formaction="../api/ticket_close.php">Cerrar ticket</button>
    </div>
  </form>
  <?php else: ?>
    <div class="co-card"><p class="co-muted">Este ticket está cerrado.</p><a class="co-btn" href="tickets.php">Volver</a></div>
  <?php endif; ?>
</main>

<footer class="co-footer">
  <div class="co-wrap co-footer__in"><div></div><small class="co-copy">© <?php echo date('Y'); ?> CreditOrg</small></div>
</footer>
</body>
</html>
