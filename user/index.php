<?php
require __DIR__ . '/../includes/guard_user.php';
require __DIR__ . '/../api/_conn.php';

$uid   = (int)$_SESSION['user_id'];
$fname = trim($_SESSION['first_name'] ?? '');
$lname = trim($_SESSION['last_name'] ?? '');
$who   = ($fname.$lname !== '') ? "$fname $lname" : ($_SESSION['username'] ?? '');

/* Utilidades seguras (si falta una tabla, devolvemos vacío) */
function safe_all($mysqli, $sql, $types = '', ...$params){
  try {
    if ($types){
      $stmt = $mysqli->prepare($sql);
      $stmt->bind_param($types, ...$params);
      $stmt->execute();
      return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
      return $mysqli->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
  } catch (Throwable $e) { return []; }
}

/* 3 cuentas recientes para KPI */
$accounts = safe_all($mysqli,
  "SELECT id, type, currency, alias, cbu, balance, status
   FROM accounts WHERE user_id=? ORDER BY id DESC LIMIT 3", 'i', $uid);

/* últimos movimientos (origen o destino pertenecen al usuario) */
$txs = safe_all($mysqli,
  "SELECT id, account_from, account_to, amount, currency, created_at
   FROM transactions
   WHERE account_from IN (SELECT id FROM accounts WHERE user_id=?)
      OR account_to   IN (SELECT id FROM accounts WHERE user_id=?)
   ORDER BY created_at DESC LIMIT 6", 'ii', $uid, $uid);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel — CreditOrg</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/main.css">
  <style>
    .dash-hero{display:grid;grid-template-columns:1.3fr .7fr;gap:18px;margin:18px 0}
    .dash-head{background:linear-gradient(180deg,#eaf2ff,#fff);border:1px solid var(--co-line);border-radius:16px;padding:18px}
    .dash-actions{display:flex;flex-wrap:wrap;gap:8px;margin-top:10px}
    .kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:12px;margin-top:12px}
    .list-quick{display:grid;gap:8px;padding-left:18px}
    .tx-list{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:12px}
    @media(max-width:980px){.dash-hero{grid-template-columns:1fr}}
  </style>
</head>
<body class="co-body">

<?php include __DIR__ . '/../includes/global_nav.php'; ?>

<main class="co-wrap">
  <!-- HERO -->
  <section class="dash-hero">
    <div class="dash-head">
      <h1 class="co-ttl" style="margin:0">Hola, <?php echo htmlspecialchars($who); ?></h1>
      <p class="co-muted" style="margin:.2rem 0 0">Este es un resumen de tu banca en CreditOrg.</p>

      <!-- Bloque de bienvenida y recorrido rápido -->
      <div class="co-card" style="background:#f7faff;border:1px solid #dbeafe;margin:16px 0 0 0">
        <h3 style="margin-top:0">Bienvenido a tu panel</h3>
        <p style="margin-bottom:8px">Recorrido rápido por los módulos principales:</p>
        <ol style="padding-left:18px;margin:0">
          <li><b>Cuentas</b>: ver CBU y alias; abrir nuevas.</li>
          <li><b>Transferir</b>: envíos internos entre clientes.</li>
          <li><b>Tarjetas</b>: generar, pausar, denunciar.</li>
          <li><b>Préstamos</b>: simular y solicitar.</li>
          <li><b>Tickets</b>: soporte con hilos.</li>
        </ol>
      </div>
      <!-- Fin bloque bienvenida -->

      <div class="dash-actions">
        <a class="co-btn co-btn--primary" href="transfer.php">Transferir</a>
        <a class="co-btn co-btn--outline" href="accounts.php">Abrir/ver cuentas</a>
        <a class="co-btn" href="cards.php">Tarjetas</a>
        <a class="co-btn" href="loans.php">Préstamos</a>
        <a class="co-btn" href="cheques.php">Cheques</a>
        <a class="co-btn" href="tickets.php">Tickets</a>
        <a class="co-btn" href="profile.php">Perfil</a>
      </div>

      <div class="kpis">
        <?php if (!$accounts): ?>
          <div class="co-card">
            <h3>Sin cuentas aún</h3>
            <p class="co-muted">Abrí tu primera cuenta para empezar a operar.</p>
            <a class="co-btn co-btn--primary" href="accounts.php">Abrir cuenta</a>
          </div>
        <?php else: foreach($accounts as $a): ?>
          <div class="co-card">
            <h3><?php echo htmlspecialchars($a['type']); ?> · <?php echo htmlspecialchars($a['currency']); ?></h3>
            <p><b>Alias:</b> <?php echo htmlspecialchars($a['alias']); ?></p>
            <p><b>CBU:</b> <?php echo htmlspecialchars($a['cbu']); ?></p>
            <p><b>Saldo:</b> <?php echo ($a['currency']=='USD'?'US$ ':'$ ').number_format($a['balance'],2,',','.'); ?></p>
          </div>
        <?php endforeach; endif; ?>
      </div>
    </div>

    <aside>
      <div class="co-card">
        <h3>Accesos rápidos</h3>
        <nav class="list-quick">
          <a href="accounts.php">Mis cuentas</a>
          <a href="transfer.php">Transferir</a>
          <a href="cards.php">Gestionar tarjetas</a>
          <a href="loans.php">Simular préstamo</a>
          <a href="cheques.php">Cheques</a>
          <a href="tickets.php">Nuevo ticket</a>
          <a href="profile.php">Editar perfil</a>
        </nav>
      </div>
      <div class="co-card" style="margin-top:12px">
        <h4>Consejos de seguridad</h4>
        <ul class="list-quick">
          <li>No compartas tu contraseña.</li>
          <li>Verificá el alias/CBU antes de transferir.</li>
          <li>Usá cierre de sesión en equipos compartidos.</li>
        </ul>
      </div>
    </aside>
  </section>

  <!-- MOVIMIENTOS -->
  <section>
    <h2 class="co-ttl">Movimientos recientes</h2>
    <div class="tx-list">
      <?php if (empty($txs)): ?>
        <div class="co-card"><p class="co-muted">Aún no hay movimientos.</p></div>
      <?php else: foreach($txs as $t): ?>
        <div class="co-card">
          <p><b><?php echo number_format($t['amount'],2,',','.').' '.$t['currency']; ?></b></p>
          <p class="co-muted">#<?php echo $t['account_from']; ?> → #<?php echo $t['account_to']; ?></p>
          <small class="co-muted"><?php echo $t['created_at']; ?></small>
        </div>
      <?php endforeach; endif; ?>
    </div>
  </section>
</main>

<footer class="co-footer">
  <div class="co-wrap co-footer__in">
    <div></div>
    <small class="co-copy">© <?php echo date('Y'); ?> CreditOrg</small>
  </div>
</footer>

<!-- Mini-tour (se mantiene igual que antes, si querés conservarlo) -->
<?php
$showTour = false;
try {
  $stmt=$mysqli->prepare("SELECT seen_tour FROM users WHERE id=?");
  $stmt->bind_param('i',$uid); $stmt->execute();
  $r=$stmt->get_result()->fetch_assoc();
  $showTour = $r && (int)$r['seen_tour']===0;
} catch(Throwable $e){ $showTour=false; }
?>
<?php if($showTour): ?>

<script>
document.getElementById('tour-gotit').addEventListener('click', () => {
  fetch('../api/tour_seen.php',{method:'POST'}).then(()=>document.getElementById('co-tour').remove());
});
document.getElementById('tour-skip').addEventListener('click', () => {
  document.getElementById('co-tour').remove();
});
</script>
<?php endif; ?>
</body>
</html>
