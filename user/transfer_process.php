<?php
require __DIR__ . '/../includes/guard_user.php';
require __DIR__ . '/../api/_conn.php';
require_once __DIR__ . '/../includes/app_config.php';

$uid = (int)$_SESSION['user_id'];
$dest = $_SESSION['transfer_dest'] ?? null;
if (!$dest) { header('Location: '.url_path('user/transfer.php')); exit; }

/* Cuentas del usuario filtradas por moneda del destino */
$stmt = $mysqli->prepare("SELECT id, alias, currency, balance FROM accounts WHERE user_id=? AND currency=? ORDER BY id DESC");
$stmt->bind_param('is',$uid,$dest['currency']); $stmt->execute();
$myAccounts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Confirmar transferencia — CreditOrg</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/main.css">
  <style>
    .co-page{min-height:100dvh;display:flex;flex-direction:column}
    main.co-wrap{flex:1}
    .muted{color:#667085}
  </style>
</head>
<body class="co-body">
<div class="co-page">
  <?php include __DIR__ . '/../includes/global_nav.php'; ?>

  <main class="co-wrap">
    <h1 class="co-ttl">Confirmar transferencia</h1>

    <div class="co-card" style="margin-bottom:12px">
      <h3>Destino</h3>
      <p class="muted"><?php echo htmlspecialchars($dest['holder']); ?></p>
      <p><b>Alias:</b> <?php echo htmlspecialchars($dest['alias'] ?? '—'); ?></p>
      <p><b>CBU:</b> <?php echo htmlspecialchars($dest['cbu']   ?? '—'); ?></p>
      <p><b>Moneda:</b> <?php echo htmlspecialchars($dest['currency']); ?></p>
    </div>

    <?php if (empty($myAccounts)): ?>
      <div class="co-card co-msg co-msg--error">
        No tenés cuentas en <b><?php echo htmlspecialchars($dest['currency']); ?></b>.
        <a class="co-btn co-btn--outline" href="<?php echo url_path('user/accounts.php'); ?>">Abrir cuenta</a>
      </div>
    <?php else: ?>
      <form class="co-card co-form co-grid co-grid--2" method="post" action="<?php echo url_path('api/transfer.php'); ?>" onsubmit="return verify()">
        <input type="hidden" name="account_to" value="<?php echo (int)$dest['id']; ?>">

        <div>
          <label class="co-label">Cuenta a debitar</label>
          <select class="co-input" name="account_from" id="account_from" required>
            <option value="">Elegí una cuenta…</option>
            <?php foreach($myAccounts as $a): ?>
              <option value="<?php echo $a['id']; ?>">
                #<?php echo $a['id']; ?> — <?php echo htmlspecialchars($a['alias']); ?> (<?php echo $a['currency']; ?>) — Saldo: <?php echo number_format($a['balance'],2,',','.'); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="co-label">Monto</label>
          <input class="co-input" type="number" name="amount" id="amount" step="0.01" min="0.01" required>
        </div>

        <div class="co-grid" style="grid-template-columns:1fr">
          <label class="co-label">Concepto (opcional)</label>
          <input class="co-input" name="concept" maxlength="80" placeholder="Ej: alquiler">
        </div>

        <div class="co-actions">
          <button class="co-btn co-btn--primary" type="submit">Transferir</button>
          <a class="co-btn co-btn--ghost" href="<?php echo url_path('user/transfer.php'); ?>">Volver</a>
        </div>
      </form>
    <?php endif; ?>
  </main>

  <footer class="co-footer">
    <div class="co-wrap co-footer__in"><div></div><small class="co-copy">© <?php echo date('Y'); ?> CreditOrg</small></div>
  </footer>
</div>

<script>
function verify(){
  const acc = document.getElementById('account_from').value;
  const amt = parseFloat(document.getElementById('amount').value || '0');
  if(!acc){ alert('Elegí la cuenta a debitar.'); return false; }
  if(!(amt>0)){ alert('Ingresá un monto válido.'); return false; }
  return true;
}
</script>
</body>
</html>
