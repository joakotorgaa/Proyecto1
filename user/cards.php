<?php
require __DIR__ . '/../includes/guard_user.php';
require __DIR__ . '/../api/_conn.php';
$uid = (int)$_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT id,label,last4,exp_month,exp_year,type,status FROM cards WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param('i',$uid); $stmt->execute();
$cards = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8"><title>Tarjetas — CreditOrg</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/main.css">
</head>
<body class="co-body">
<?php include __DIR__ . '/../includes/global_nav.php'; ?>
<main class="co-wrap">
  <h1 class="co-ttl">Tarjetas</h1>

  <div class="co-card" style="margin-bottom:12px">
    <form method="post" action="../api/cards_generate.php" class="co-grid co-grid--2">
      <div>
        <label class="co-label">Etiqueta (opcional)</label>
        <input name="label" class="co-input" placeholder="Mi tarjeta virtual">
      </div>
      <div>
        <label class="co-label">Tipo</label>
        <select name="type" class="co-input">
          <option value="debit">Débito</option>
          <option value="credit">Crédito</option>
        </select>
      </div>
      <div class="co-actions">
        <button class="co-btn co-btn--primary" type="submit">Generar tarjeta</button>
      </div>
    </form>
  </div>

  <div class="co-grid co-grid--3">
    <?php if(empty($cards)): ?>
      <div class="co-card"><p class="co-muted">No tenés tarjetas.</p></div>
    <?php else: foreach($cards as $c): ?>
      <div class="co-card">
        <h3><?php echo htmlspecialchars($c['label'] ?: 'Tarjeta'); ?> — <small><?php echo htmlspecialchars($c['status'] ?: 'active'); ?></small></h3>
        <p><b>**** **** **** <?php echo htmlspecialchars($c['last4']); ?></b></p>
        <p class="co-muted">Vence: <?php echo sprintf('%02d/%s', (int)$c['exp_month'], (int)$c['exp_year']); ?> · <?php echo htmlspecialchars($c['type'] ?: 'debit'); ?></p>
        <div class="co-actions">
          <form method="post" action="../api/card_action.php">
            <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
            <button name="action" value="toggle" class="co-btn"><?php echo ($c['status']==='paused'?'Activar':'Pausar'); ?></button>
            <button name="action" value="report" class="co-btn co-btn--outline">Denunciar</button>
            <button name="action" value="delete" class="co-btn">Eliminar</button>
          </form>
        </div>
      </div>
    <?php endforeach; endif; ?>
  </div>
</main>
</body>
</html>
