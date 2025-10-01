<?php
require __DIR__ . '/../includes/guard_user.php';
require __DIR__ . '/../api/_conn.php';
require_once __DIR__ . '/../includes/app_config.php';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Transferir — CreditOrg</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/main.css">
  <style>
    /* Layout: footer siempre abajo */
    .co-page{min-height:100dvh;display:flex;flex-direction:column}
    main.co-wrap{flex:1}

    /* Estilos + animaciones */
    .co-step{display:grid;gap:12px}
    .muted{color:#667085}
    .dest{display:none;opacity:0;transform:translateY(8px)}
    .dest.show{display:block;animation:fadeSlide .28s ease-out forwards}
    @keyframes fadeSlide{to{opacity:1;transform:translateY(0)}}

    .co-alert{padding:10px 12px;border-radius:10px;border:1px solid}
    .co-alert.err{background:#fdecec;border-color:#f3c1c1;color:#b42323}
  </style>
</head>
<body class="co-body">
<div class="co-page">
  <?php include __DIR__ . '/../includes/global_nav.php'; ?>

  <main class="co-wrap">
    <h1 class="co-ttl">Transferencia interna</h1>

    <!-- Paso único: buscar y validar -->
    <section class="co-card co-step" id="step1">
      <h3>Alias / CBU / CVU de destino</h3>
      <form class="co-grid co-grid--2" id="lookup-form" onsubmit="return doLookup(event)">
        <div>
          <label class="co-label">Alias / CBU / CVU</label>
          <input class="co-input" id="dest-key" placeholder="mi.alias.creditorg o 0170..." autofocus autocomplete="off">
          <small id="lookup-help" class="muted"></small>
        </div>
        <div class="co-actions" style="align-items:end">
          <button class="co-btn co-btn--primary" id="btn-lookup" type="submit">Buscar</button>
        </div>
      </form>

      <!-- Tarjeta destino (aparece con animación si es válido) -->
      <div id="dest-card" class="co-card dest">
        <h4>Destino</h4>
        <p class="muted" id="dest-holder"></p>
        <p><b>Alias:</b> <span id="dest-alias"></span></p>
        <p><b>CBU:</b> <span id="dest-cbu"></span></p>
        <p><b>Moneda:</b> <span id="dest-currency"></span></p>

        <form id="continue-form" method="post" action="<?php echo url_path('api/transfer_stage.php'); ?>" style="margin-top:10px">
          <input type="hidden" name="dest_id" id="dest-id">
          <input type="hidden" name="dest_currency" id="dest-currency-input">
          <div class="co-actions">
            <button class="co-btn co-btn--primary" id="btn-continue" type="submit">Continuar</button>
            <button class="co-btn co-btn--ghost" id="btn-cancel" type="button">Cancelar</button>
          </div>
        </form>
      </div>

      <!-- Error -->
      <div id="lookup-error" class="co-alert err" style="display:none">No es válido o no existe. Reingresá el Alias/CBU/CVU.</div>
    </section>
  </main>

  <footer class="co-footer">
    <div class="co-wrap co-footer__in"><div></div><small class="co-copy">© <?php echo date('Y'); ?> CreditOrg</small></div>
  </footer>
</div>

<script>
const elKey   = document.getElementById('dest-key');
const help    = document.getElementById('lookup-help');
const errBox  = document.getElementById('lookup-error');
const card    = document.getElementById('dest-card');
const holder  = document.getElementById('dest-holder');
const dAlias  = document.getElementById('dest-alias');
const dCBU    = document.getElementById('dest-cbu');
const dCur    = document.getElementById('dest-currency');
const dCurIn  = document.getElementById('dest-currency-input');
const dId     = document.getElementById('dest-id');
const btnCancel = document.getElementById('btn-cancel');

function resetUI(){
  errBox.style.display = 'none';
  help.textContent = '';
  card.classList.remove('show');
  dId.value = '';
  dAlias.textContent = '';
  dCBU.textContent   = '';
  dCur.textContent   = '';
  dCurIn.value       = '';
  holder.textContent = '';
}

async function doLookup(e){
  e.preventDefault();
  resetUI();

  const key = elKey.value.trim();
  if(!key){ help.textContent='Ingresá un Alias o CBU/CVU.'; return false; }

  const fd = new FormData(); fd.append('key', key);

  try {
    const res = await fetch('<?php echo url_path("api/account_lookup.php"); ?>', {method:'POST', body: fd});
    const data = await res.json();

    if (!data.ok){
      errBox.style.display = 'block';
      return false;
    }
    // Completar tarjeta y mostrar con animación
    dId.value      = data.account.id;
    dAlias.textContent = data.account.alias || '—';
    dCBU.textContent   = data.account.cbu || '—';
    dCur.textContent   = data.account.currency || '—';
    dCurIn.value       = data.account.currency || '';
    holder.textContent = 'Titular: ' + (data.account.holder || '—');

    card.classList.add('show'); // aparece con animación
    // Opcional: hacer focus en "Continuar"
    document.getElementById('btn-continue').focus();
  } catch {
    errBox.style.display = 'block';
  }
  return false;
}

btnCancel.addEventListener('click', () => {
  resetUI();
  elKey.focus();
});
</script>
</body>
</html>
