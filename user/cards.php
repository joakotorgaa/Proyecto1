<?php
require __DIR__ . '/../includes/guard_user.php';
require __DIR__ . '/../api/_conn.php';
$uid = (int)$_SESSION['user_id'];

$cards = $mysqli->prepare("SELECT id,label,pan,last4,exp_month,exp_year,status FROM cards WHERE user_id = ?");
$cards->bind_param('i',$uid);
$cards->execute();
$cards = $cards->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"><title>Tarjetas — CreditOrg</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="../assets/main.css">
</head>
<body class="co-body">
<?php include __DIR__ . '/_nav.php'; /* simple nav include if lo querés */ ?>
<main class="co-wrap">
  <h1 class="co-ttl">Tarjetas</h1>
  <div style="display:flex;gap:12px;margin-bottom:12px">
    <form method="post" action="../api/cards_generate.php">
      <label>Etiqueta (opcional)</label>
      <input name="label" class="co-input" placeholder="Mi tarjeta virtual">
      <select name="type" class="co-input" style="margin-top:6px">
        <option value="debit">Débito</option>
        <option value="credit">Crédito</option>
      </select>
      <div style="margin-top:6px">
        <button class="co-btn co-btn--primary" type="submit">Generar tarjeta</button>
      </div>
    </form>
  </div>

  <div class="co-grid co-grid--3">
    <?php if(empty($cards)): ?>
      <div class="co-card"><p class="co-muted">No tenés tarjetas.</p></div>
    <?php else: foreach($cards as $c): ?>
      <div class="co-card">
        <h3><?php echo htmlspecialchars($c['label'] ?: 'Tarjeta'); ?> — <small><?php echo htmlspecialchars($c['status']); ?></small></h3>
        <p><b>**** **** **** <?php echo htmlspecialchars($c['last4']); ?></b></p>
        <p class="co-muted">Vence: <?php echo sprintf('%02d/%s',$c['exp_month'],$c['exp_year']); ?></p>
        <div style="display:flex;gap:8px;margin-top:8px">
          <form method="post" action="../api/card_action.php" style="display:inline">
            <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
            <button name="action" value="toggle" class="co-btn"><?php echo $c['status']==='active' ? 'Pausar' : 'Activar'; ?></button>
          </form>
          <form method="post" action="../api/card_action.php" style="display:inline">
            <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
            <button name="action" value="report" class="co-btn co-btn--outline">Denunciar</button>
          </form>
          <form method="post" action="../api/card_action.php" style="display:inline">
            <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
            <button name="action" value="delete" class="co-btn">Eliminar</button>
          </form>
        </div>
      </div>
    <?php endforeach; endif; ?>
  </div>
</main>
</body>
</html>
