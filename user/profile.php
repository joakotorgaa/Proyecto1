<?php
require __DIR__ . '/../includes/guard_user.php';
require __DIR__ . '/../api/_conn.php';

$uid = (int)$_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT username,email,first_name,last_name,phone,birthdate FROM users WHERE id=? LIMIT 1");
$stmt->bind_param('i',$uid);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$ok  = isset($_GET['ok']);
$err = $_GET['error'] ?? null; // dup_user, dup_mail, bad, server
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Perfil — CreditOrg</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/main.css">
  <style>
    .two{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    @media(max-width:720px){.two{grid-template-columns:1fr}}
  </style>
</head>
<body class="co-body">

<?php include __DIR__ . '/../includes/global_nav.php'; ?>

<main class="co-wrap">
  <h1 class="co-ttl">Perfil</h1>

  <?php if($ok): ?>
    <div class="co-card co-msg co-msg--ok">Datos actualizados.</div>
  <?php endif; ?>
  <?php if($err): ?>
    <div class="co-card co-msg co-msg--error">
      <?php
        $map = [
          'dup_user'=>'Ese usuario ya existe.',
          'dup_mail'=>'Ese email ya está en uso.',
          'bad'=>'Datos incompletos o inválidos.',
          'server'=>'No pudimos guardar los cambios.'
        ];
        echo $map[$err] ?? 'Error al guardar.';
      ?>
    </div>
  <?php endif; ?>

  <form class="co-card co-form" method="post" action="../api/profile_update.php" autocomplete="off">
    <div class="two">
      <div>
        <label class="co-label">Nombre</label>
        <input class="co-input" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
      </div>
      <div>
        <label class="co-label">Apellido</label>
        <input class="co-input" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
      </div>
    </div>

    <div class="two">
      <div>
        <label class="co-label">Usuario</label>
        <input class="co-input" name="username" minlength="3" maxlength="30" value="<?php echo htmlspecialchars($user['username']); ?>" required>
      </div>
      <div>
        <label class="co-label">Email</label>
        <input class="co-input" type="email" name="email" maxlength="120" value="<?php echo htmlspecialchars($user['email']); ?>" required>
      </div>
    </div>

    <div class="two">
      <div>
        <label class="co-label">Teléfono</label>
        <input class="co-input" name="phone" maxlength="30" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
      </div>
      <div>
        <label class="co-label">Fecha de nacimiento</label>
        <input class="co-input" type="date" name="birthdate" value="<?php echo htmlspecialchars($user['birthdate'] ?? ''); ?>">
      </div>
    </div>

    <details class="co-card" style="margin-top:10px">
      <summary><b>Cambiar contraseña (opcional)</b></summary>
      <div class="two" style="margin-top:10px">
        <div>
          <label class="co-label">Nueva contraseña</label>
          <input class="co-input" type="password" name="password" minlength="6">
        </div>
        <div>
          <label class="co-label">Repetir contraseña</label>
          <input class="co-input" type="password" name="password2" minlength="6">
        </div>
      </div>
      <small class="co-muted">Si dejás esto vacío, tu contraseña no cambia.</small>
    </details>

    <div class="co-actions" style="margin-top:10px">
      <button class="co-btn co-btn--primary" type="submit">Guardar</button>
      <a class="co-btn co-btn--ghost" href="index.php">Cancelar</a>
    </div>
  </form>
</main>

<footer class="co-footer">
  <div class="co-wrap co-footer__in"><div></div><small class="co-copy">© <?php echo date('Y'); ?> CreditOrg</small></div>
</footer>
</body>
</html>
