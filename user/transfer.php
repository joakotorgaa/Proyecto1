<?php
require __DIR__ . '/../includes/guard_user.php';
require __DIR__ . '/../api/_conn.php';
$uid = (int)$_SESSION['user_id'];

/* Cuentas del usuario (para ORIGEN) */
$stmt = $mysqli->prepare("SELECT id, alias, currency, balance FROM accounts WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param('i',$uid); $stmt->execute();
$myAccounts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$ok = isset($_GET['ok']);
$err = $_GET['error'] ?? null;
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Transferir — CreditOrg</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/main.css">
  <style>
    .step{display:grid;gap:12px}
    .dest-card{display:none}
    .dest-card.show{display:block}
  </style>
</head>
<body class="co-body">
<?php include __DIR__ . '/../includes/global_nav.php'; ?>

<main class="co-wrap">
  <h1 class="co-ttl">Transferencia interna</h1>

  <?php if($ok): ?><div class="co-card co-msg co-msg--ok">Transferencia realizada.</div><?php endif; ?>
  <?php if($err): ?><div class="co-card co-msg co-msg--error">No se pudo completar la operación.</div><?php endif; ?>

  <!-- PASO 1: Buscar destino -->
  <section class="co-card step">
    <h3>1) Ingresá Alias / CBU / CVU de destino</h3>
    <div class="co-grid co-grid--2" style="align-items:end;">
      <div>
        <label class="co-label">Alias / CBU / CVU</label>
        <input class="co-input" id="dest-key" placeholder="Ej: mi.alias.banco o 01701234...">
      </div>
      <div class="co-actions" style="align-items:end;display:flex;">
        <button class="co-btn co-btn--primary" id="btn-lookup" type="button">Buscar</button>
      </div>
    </div>

    <div id="dest-card" class="co-card dest-card">
      <h4>Destino encontrado</h4>
      <p id="dest-holder" class="co-muted"></p>
      <p><b>Alias:</b> <span id="dest-alias"></span></p>
      <p><b>CBU:</b> <span id="dest-cbu"></span></p>
      <p><b>Moneda:</b> <span id="dest-currency"></span></p>
      <input type="hidden" id="dest-id">
    </div>
  </section>

  <!-- PASO 2: Monto y cuenta origen -->
  <section class="co-card step">
    <h3>2) Monto y cuenta a debitar</h3>
    <form id="tx-form" class="co-grid co-grid--2" method="post" action="../api/transfer.php" onsubmit="return verifyReady()">
      <input type="hidden" name="account_to" id="form-account-to">
      <div>
        <label class="co-label">Cuenta origen</label>
        <select class="co-input" name="account_from" id="account_from" required disabled>
          <option value="">Elegí una cuenta…</option>
          <?php foreach($myAccounts as $a): ?>
            <option value="<?php echo $a['id']; ?>" data-currency="<?php echo $a['currency']; ?>">
              #<?php echo $a['id']; ?> — <?php echo htmlspecialchars($a['alias']); ?> (<?php echo $a['currency']; ?>) — Saldo: <?php echo number_format($a['balance'],2,',','.'); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="co-label">Monto</label>
        <input class="co-input" type="number" step="0.01" min="0.01" name="amount" id="amount" required disabled>
      </div>
      <div style="display:flex;align-items:end;gap:12px;">
        <div style="flex:1;">
          <label class="co-label">Concepto (opcional)</label>
          <input class="co-input" name="concept" maxlength="80" placeholder="Ej: pago servicios" disabled id="concept">
        </div>
        <div class="co-actions" style="align-items:end;display:flex;gap:8px;">
          <button class="co-btn co-btn--primary" type="submit" id="submit-btn" disabled>Transferir</button>
          <a class="co-btn co-btn--ghost" href="index.php">Cancelar</a>
        </div>
      </div>
    </form>
  </section>
</main>

<footer class="co-footer">
  <div class="co-wrap co-footer__in"><div></div><small class="co-copy">© <?php echo date('Y'); ?> CreditOrg</small></div>
</footer>

<script>
const elKey = document.getElementById('dest-key');
const btn = document.getElementById('btn-lookup');
const card = document.getElementById('dest-card');
const destId = document.getElementById('dest-id');
const destAlias = document.getElementById('dest-alias');
const destCBU = document.getElementById('dest-cbu');
const destCur = document.getElementById('dest-currency');
const holder = document.getElementById('dest-holder');

const selFrom = document.getElementById('account_from');
const amount = document.getElementById('amount');
const concept = document.getElementById('concept');
const formTo = document.getElementById('form-account-to');
const submitBtn = document.getElementById('submit-btn');

btn.addEventListener('click', async () => {
  const key = elKey.value.trim();
  if (!key){ alert('Ingresá un Alias o CBU/CVU.'); return; }
  const fd = new FormData(); fd.append('key', key);
  const res = await fetch('../api/account_lookup.php', {method:'POST', body: fd});
  const data = await res.json();
  if (!data.ok){ alert('Cuenta no encontrada. Verifica el Alias/CBU.'); return; }

  // Llenar tarjeta destino
  card.classList.add('show');
  destId.value = data.account.id;
  destAlias.textContent = data.account.alias || '—';
  destCBU.textContent = data.account.cbu || '—';
  destCur.textContent = data.account.currency;
  holder.textContent = 'Titular: ' + (data.account.holder || '—');

  // Habilitar paso 2: monto+origen
  selFrom.disabled = false;
  amount.disabled = false;
  concept.disabled = false;
  formTo.value = data.account.id;

  // Filtrar cuentas origen que no coincidan con moneda
  const destCurrency = data.account.currency;
  [...selFrom.options].forEach((opt, i) => {
    if (i === 0) return;
    opt.hidden = (opt.dataset.currency !== destCurrency);
  });
});

function verifyReady(){
  if (!formTo.value){ alert('Primero buscá y confirmá el destino.'); return false; }
  if (!selFrom.value){ alert('Elegí la cuenta origen.'); return false; }
  if (!amount.value || parseFloat(amount.value) <= 0){ alert('Ingresá un monto válido.'); return false; }
  return true;
}
</script>
</body>
</html>
