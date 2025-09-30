<?php require __DIR__ . '/../includes/guard_user.php'; require __DIR__ . '/../api/_conn.php';
$uid = (int)$_SESSION['user_id'];
$cheques = $mysqli->prepare("SELECT id,account_id,amount,payee,status,issued_at FROM cheques WHERE user_id=? ORDER BY id DESC");
$cheques->bind_param('i',$uid); $cheques->execute(); $cheques=$cheques->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Cheques</title>
  <link rel="stylesheet" href="../assets/main.css">
  <style>
    body.co-body { font-family: Arial, Helvetica, sans-serif !important; }
  </style>
</head>
<body class="co-body">
<?php include __DIR__ . '/../includes/global_nav.php'; ?>
<main class="co-wrap">
  <h1 class="co-ttl">Cheques</h1>
  <form class="co-card" method="post" action="../api/cheque_issue.php">
    <label class="co-label">Cuenta origen</label>
    <select name="account_id" class="co-input" required>
      <?php $stmt = $mysqli->prepare("SELECT id,alias,cbu FROM accounts WHERE user_id=?"); $stmt->bind_param('i',$uid); $stmt->execute(); $accs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
      foreach($accs as $a) echo "<option value=\"{$a['id']}\">#{$a['id']} - {$a['alias']}</option>";
      ?>
    </select>
    <label class="co-label">Beneficiario</label><input name="payee" class="co-input" required>
    <label class="co-label">Monto</label><input name="amount" type="number" step="0.01" class="co-input" required>
    <div style="margin-top:8px"><button class="co-btn co-btn--primary" type="submit">Emitir cheque</button></div>
  </form>

  <h2 class="co-ttl">Cheques emitidos</h2>
  <div class="co-grid co-grid--3">
    <?php if(empty($cheques)) echo '<div class="co-card"><p class="co-muted">No hay cheques.</p></div>'; else foreach($cheques as $c): ?>
      <div class="co-card">
        <p><b>Beneficiario:</b> <?php echo htmlspecialchars($c['payee']); ?></p>
        <p><b>Monto:</b> <?php echo $c['amount']; ?></p>
        <p class="co-muted">Estado: <?php echo htmlspecialchars($c['status']); ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</main></body></html>
