<?php require __DIR__ . '/../includes/guard_user.php'; require __DIR__ . '/../api/_conn.php';
$uid=(int)$_SESSION['user_id'];
$offers = [
  ['name'=>'Personal 12 meses','months'=>12,'rate'=>0.04],
  ['name'=>'Personal 24 meses','months'=>24,'rate'=>0.05],
  ['name'=>'Préstamo corto 6m','months'=>6,'rate'=>0.035],
];
?>
<!doctype html><html><head><meta charset="utf-8"><title>Préstamos</title><link rel="stylesheet" href="../assets/main.css"></head><body>
<main class="co-wrap">
  <h1 class="co-ttl">Préstamos</h1>
  <div class="co-grid co-grid--3">
    <?php foreach($offers as $o): ?>
      <div class="co-card">
        <h3><?php echo $o['name']; ?></h3>
        <p class="co-muted">Tasa mensual aproximada: <?php echo ($o['rate']*100).' %'; ?></p>
        <form method="post" action="../api/loan_apply.php">
          <input type="hidden" name="months" value="<?php echo $o['months']; ?>">
          <input type="hidden" name="rate" value="<?php echo $o['rate']; ?>">
          <label class="co-label">Monto</label><input name="amount" class="co-input" required>
          <div style="margin-top:8px"><button class="co-btn co-btn--primary">Simular / Solicitar</button></div>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</main></body></html>
