<?php
session_start();
if (!empty($_SESSION['user_id'])) {
  // Ya logueado: enviá al panel correspondiente
  header('Location: ' . (!empty($_SESSION['is_admin']) ? 'admin/' : 'user/'));
  exit;
}
$err = $_GET['error'] ?? null;   // ?error=1 (credenciales inválidas) p.ej.
$ok  = $_GET['ok'] ?? null;      // ?ok=1 (mensaje info, ej. luego de register)
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Ingresar — CreditOrg</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/main.css">
</head>
<body class="co-body co-auth">

<!-- Fondo animado (sutil) -->
<div class="co-auth__bg">
  <span class="co-auth__blob co-auth__blob--1"></span>
  <span class="co-auth__blob co-auth__blob--2"></span>
  <span class="co-auth__blob co-auth__blob--3"></span>
</div>

<header class="co-auth__header">
  <a class="co-brand" href="index.php" aria-label="Volver al inicio">
    <span class="co-brand__badge">CO</span>
    <span class="co-brand__name">CreditOrg</span>
  </a>
</header>

<main class="co-auth__wrap">
  <section class="co-auth__grid">

    <!-- Lado izquierdo -->
    <div class="co-auth__intro">
      <h1>¡Bienvenido!</h1>
      <p class="co-muted">Ingresá para operar con tus cuentas, tarjetas, préstamos e inversiones.</p>
      <ul class="co-auth__bullets">
        <li>🔒 Sesión segura</li>
        <li>⚡ Acceso rápido</li>
        <li>💬 Soporte cuando lo necesitás</li>
      </ul>
    </div>

    <!-- Formulario -->
    <div class="co-card co-auth__card">
      <h2 class="co-auth__title">Ingresar</h2>

      <?php if ($ok): ?>
        <div class="co-msg co-msg--ok" role="status">Tu cuenta fue creada. Ingresá con tus credenciales.</div>
      <?php endif; ?>

      <?php if ($err): ?>
        <div class="co-msg co-msg--error" role="alert">Usuario o contraseña incorrectos.</div>
      <?php endif; ?>

      <form action="api/login.php" method="post" class="co-form" autocomplete="on">
        <div class="co-field">
          <label class="co-label" for="username">Usuario</label>
          <input class="co-input" id="username" name="username" required autofocus>
        </div>

        <div class="co-field co-field--pwd">
          <label class="co-label" for="password">Contraseña</label>
          <input class="co-input" id="password" type="password" name="password" required>
          <button class="co-eye" type="button" aria-label="Mostrar u ocultar contraseña"></button>
        </div>

        <div class="co-auth__actions">
          <button class="co-btn co-btn--primary" type="submit">Ingresar</button>
          <a class="co-btn co-btn--ghost" href="index.php">Cancelar</a>
        </div>
      </form>

      <p class="co-auth__hint">¿No tenés cuenta? <a class="co-link" href="register.php">Creá tu cuenta</a></p>
    </div>
  </section>
</main>

<footer class="co-auth__footer">
  <small class="co-muted">© <?php echo date('Y'); ?> CreditOrg</small>
</footer>

<script>
  // Mostrar/ocultar contraseña (sin dependencias)
  (function(){
    const btn = document.querySelector('.co-eye');
    const inp = document.getElementById('password');
    if(btn && inp){
      btn.addEventListener('click', () => {
        const show = inp.type === 'password';
        inp.type = show ? 'text' : 'password';
        btn.classList.toggle('is-on', show);
        inp.focus();
      });
    }
  })();
</script>
</body>
</html>
