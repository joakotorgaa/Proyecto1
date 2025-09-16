<?php
require __DIR__ . '/../includes/guard_user.php';
require __DIR__ . '/../api/_conn.php';
$uid = (int)$_SESSION['user_id'];

// Cuentas del usuario (origen)
$stmt = $mysqli->prepare("SELECT id, type, currency, alias, balance FROM accounts WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param('i', $uid);
$stmt->execute();
$accs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Cuentas destino (de todo el banco) — solo mostramos ID y alias/cbu para ejemplo
$dest = $mysqli->query("SELECT id, alias, cbu FROM accounts ORDER BY id DESC LIMIT 100")->fetch_all(MYSQLI_ASSOC);

// Mensajes
$ok  = !empty($_GET['ok']);
$err = $_GET['error'] ?? null;
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8"><title>Transferir — CreditOrg</title>
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
        <li><a class="co-nav__link" href="accounts.php">Cuentas</a></li>
        <li><a class="co-nav__link" href="cards.php">Tarjetas</a></li>
      </ul>
      <div class="co-nav__cta"><a class="co-btn" href="../logout.php">Salir</a></div>
    </nav>
  </div>
</header>

<main class="co-wrap">
  <h1 class="co-ttl">Transferencia interna</h1>

  <?php if ($ok): ?>
    <div class="co-card co-msg co-msg--ok">Transferencia realizada.</div>
  <?php endif; ?>
  <?php if ($err): ?>
    <div class="co-card co-msg co-msg--error">No se pudo completar la transferencia.</div>
  <?php endif; ?>

  <form class="co-card co-grid co-grid--2" method="post" action="../api/transfer.php">
    <div>
      <label class="co-label">Cuenta origen</label>
      <select class="co-input" name="account_from" required>
        <?php foreach($accs as $a): ?>
          <option value="<?php echo $a['id']; ?>">
            #<?php echo $a['id']; ?> — <?php echo htmlspecialchars($a['alias']); ?> (<?php echo $a['currency']; ?>) — Saldo: <?php echo number_format($a['balance'],2,',','.'); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label class="co-label">Cuenta destino</label>
      <select class="co-input" name="account_to" required>
        <?php foreach($dest as $d): ?>
          <option value="<?php echo $d['id']; ?>">#<?php echo $d['id']; ?> — <?php echo htmlspecialchars($d['alias'] ?: $d['cbu']); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label class="co-label">Monto</label>
      <input class="co-input" type="number" step="0.01" min="0.01" name="amount" required>
    </div>
    <div>
      <label class="co-label">Concepto</label>
      <input class="co-input" name="concept" maxlength="80" placeholder="Ej: transferencia a cuenta propia">
    </div>
    <div class="co-actions">
      <button class="co-btn co-btn--primary" type="submit">Transferir</button>
      <a class="co-btn co-btn--ghost" href="index.php">Cancelar</a>
    </div>
  </form>
</main>

<footer class="co-footer">
  <div class="co-wrap co-footer__in"><div></div><small class="co-copy">© <?php echo date('Y'); ?> CreditOrg</small></div>
</footer>
</body>
</html>
