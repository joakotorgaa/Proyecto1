<?php
require __DIR__ . '/../includes/guard_user.php';
require __DIR__ . '/../api/_conn.php';
$uid = (int)$_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT id, type, currency, alias, cbu, balance, status FROM accounts WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param('i', $uid);
$stmt->execute();
$accounts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8"><title>Mis cuentas — CreditOrg</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/main.css">
</head>
<body class="co-body">

<?php include __DIR__ . '/../includes/global_nav.php'; ?>

<main class="co-wrap">
  <h1 class="co-ttl">Mis cuentas</h1>

  <div class="co-grid co-grid--3">
    <?php if (!$accounts): ?>
      <div class="co-card"><p class="co-muted">Aún no tenés cuentas.</p></div>
    <?php else: foreach($accounts as $a): ?>
      <div class="co-card">
        <h3><?php echo htmlspecialchars($a['type']); ?> (<?php echo htmlspecialchars($a['currency']); ?>)</h3>
        <p><b>Alias:</b> <?php echo htmlspecialchars($a['alias']); ?></p>
        <p><b>CBU/CVU:</b> <?php echo htmlspecialchars($a['cbu']); ?></p>
        <p><b>Saldo:</b> <?php echo ($a['currency'] === 'USD' ? 'US$ ' : '$ '), number_format($a['balance'], 2, ',', '.'); ?></p>
        <p class="co-muted">Estado: <?php echo htmlspecialchars($a['status']); ?></p>
      </div>
    <?php endforeach; endif; ?>
  </div>

  <h2 class="co-ttl" style="margin-top:18px">Abrir nueva cuenta</h2>
  <form class="co-card co-grid co-grid--2" method="post" action="../api/account_open.php">
    <div>
      <label class="co-label">Tipo</label>
      <select class="co-input" name="type" required>
        <option value="caja_ahorro">Caja de ahorro</option>
        <option value="cuenta_corriente">Cuenta corriente</option>
      </select>
    </div>
    <div>
      <label class="co-label">Moneda</label>
      <select class="co-input" name="currency" required>
        <option value="ARS">ARS</option>
        <option value="USD">USD</option>
      </select>
    </div>
    <div class="co-contact co-actions">
      <button class="co-btn co-btn--primary" type="submit">Abrir</button>
      <a class="co-btn co-btn--ghost" href="index.php">Volver</a>
    </div>
  </form>
</main>

<footer class="co-footer">
  <div class="co-wrap co-footer__in"><div></div><small class="co-copy">© <?php echo date('Y'); ?> CreditOrg</small></div>
</footer>
</body>
</html>
