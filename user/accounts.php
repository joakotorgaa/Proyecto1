<?php
require __DIR__ . '/../includes/guard_user.php';
require __DIR__ . '/../api/_conn.php';
$uid = (int)$_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT id, type, currency, alias, cbu, balance, status FROM accounts WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param('i',$uid);
$stmt->execute();
$accounts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$ok  = $_GET['ok'] ?? null;     // ok=1 (cuenta creada) | ok=alias
$err = $_GET['error'] ?? null;  // error codes
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Mis cuentas — CreditOrg</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/main.css">
</head>
<body class="co-body">
<?php include __DIR__ . '/../includes/global_nav.php'; ?>

<main class="co-wrap">
  <h1 class="co-ttl">Mis cuentas</h1>

  <?php if($ok): ?>
    <div class="co-card co-msg co-msg--ok">
      <?php echo $ok==='alias' ? 'Alias actualizado.' : 'Cuenta creada.'; ?>
    </div>
  <?php endif; ?>
  <?php if($err): ?>
    <div class="co-card co-msg co-msg--error">
      <?php
        $map=['1'=>'Ocurrió un error.','forbidden'=>'No autorizado.','dup_alias'=>'Ese alias ya existe.'];
        echo $map[$err] ?? 'No pudimos completar la acción.';
      ?>
    </div>
  <?php endif; ?>

  <div class="co-grid co-grid--3">
    <?php if (!$accounts): ?>
      <div class="co-card"><p class="co-muted">Aún no tenés cuentas.</p></div>
    <?php else: foreach($accounts as $a): ?>
      <div class="co-card">
        <h3><?php echo htmlspecialchars($a['type']); ?> (<?php echo htmlspecialchars($a['currency']); ?>)</h3>
        <p><b>Alias:</b> <?php echo htmlspecialchars($a['alias']); ?></p>
        <p><b>CBU/CVU:</b> <?php echo htmlspecialchars($a['cbu']); ?></p>
        <p><b>Saldo:</b> <?php echo ($a['currency']=='USD'?'US$ ':'$ ').number_format($a['balance'],2,',','.'); ?></p>
        <p class="co-muted">Estado: <?php echo htmlspecialchars($a['status']); ?></p>

        <form class="co-grid" style="grid-template-columns:1fr auto" method="post" action="../api/update_alias.php">
          <input type="hidden" name="account_id" value="<?php echo $a['id']; ?>">
          <input class="co-input" name="alias" placeholder="Nuevo alias" value="<?php echo htmlspecialchars($a['alias']); ?>">
          <button class="co-btn" type="submit">Guardar</button>
        </form>
      </div>
    <?php endforeach; endif; ?>
  </div>

  <h2 class="co-ttl" style="margin-top:18px">Abrir nueva cuenta</h2>
  <form class="co-card co-grid co-grid--2" method="post" action="../api/account_open.php">
    <div>
      <label class="co-label">Tipo</label>
      <select class="co-input" name="type" required>
        <option value="Caja de ahorro">Caja de ahorro</option>
        <option value="Cuenta corriente">Cuenta corriente</option>
      </select>
    </div>
    <div>
      <label class="co-label">Moneda</label>
      <select class="co-input" name="currency" required>
        <option value="ARS">ARS</option>
        <option value="USD">USD</option>
      </select>
    </div>
    <div>
      <label class="co-label">Alias (opcional)</label>
      <input class="co-input" name="alias" placeholder="mi.alias.creditorg">
    </div>
    <div class="co-actions">
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
