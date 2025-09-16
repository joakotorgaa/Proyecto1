<?php
require __DIR__ . '/../includes/guard_admin.php';
require __DIR__ . '/../api/_conn.php';

$q = trim($_GET['q'] ?? '');
if ($q !== '') {
  $like = "%$q%";
  $stmt = $mysqli->prepare("SELECT id, username, email, is_admin FROM users WHERE username LIKE ? OR email LIKE ? ORDER BY id DESC LIMIT 200");
  $stmt->bind_param('ss', $like, $like);
  $stmt->execute();
  $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
  $users = $mysqli->query("SELECT id, username, email, is_admin FROM users ORDER BY id DESC LIMIT 200")->fetch_all(MYSQLI_ASSOC);
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8"><title>Usuarios — Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/main.css">
</head>
<body class="co-body">
<header class="co-header">
  <div class="co-wrap co-header__in">
    <a class="co-brand" href="index.php"><span class="co-brand__badge">CO</span><span class="co-brand__name">CreditOrg</span></a>
    <nav class="co-nav"><div class="co-nav__cta"><a class="co-btn" href="../logout.php">Salir</a></div></nav>
  </div>
</header>

<main class="co-wrap">
  <h1 class="co-ttl">Usuarios</h1>
  <form class="co-card co-grid co-grid--2" method="get">
    <div>
      <label class="co-label">Buscar</label>
      <input class="co-input" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Usuario o email">
    </div>
    <div class="co-actions">
      <button class="co-btn co-btn--primary" type="submit">Buscar</button>
      <a class="co-btn co-btn--ghost" href="users.php">Limpiar</a>
    </div>
  </form>

  <div class="co-grid co-grid--3" style="margin-top:12px">
    <?php foreach($users as $u): ?>
      <div class="co-card">
        <h3>#<?php echo $u['id']; ?> — <?php echo htmlspecialchars($u['username']); ?></h3>
        <p><?php echo htmlspecialchars($u['email']); ?></p>
        <p class="co-muted">Rol: <?php echo $u['is_admin'] ? 'Admin' : 'Usuario'; ?></p>
        <div class="co-actions">
          <a class="co-btn co-btn--outline" href="user_edit.php?id=<?php echo $u['id']; ?>">Editar</a>
          <a class="co-btn" href="user_accounts.php?user_id=<?php echo $u['id']; ?>">Cuentas</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</main>

<footer class="co-footer">
  <div class="co-wrap co-footer__in"><div></div><small class="co-copy">© <?php echo date('Y'); ?> CreditOrg</small></div>
</footer>
</body>
</html>
