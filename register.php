<?php
session_start();
if (!empty($_SESSION['user_id'])) {
  header('Location: ' . (!empty($_SESSION['is_admin']) ? 'admin/' : 'user/'));
  exit;
}
$err = $_GET['error'] ?? null;   // ?error=1 si backend devuelve error
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Crear cuenta — CreditOrg</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/main.css">
</head>
<body class="co-body co-auth">

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

    <div class="co-auth__intro">
      <h1>Creá tu cuenta</h1>
      <p class="co-muted">Registrate en minutos y empezá a operar online con seguridad y claridad.</p>
      <ul class="co-auth__bullets">
        <li>🪪 Validación básica</li>
        <li>⚙️ Perfil configurable</li>
        <li>📲 Acceso desde cualquier dispositivo</li>
      </ul>
    </div>

    <div class="co-card co-auth__card">
      <h2 class="co-auth__title">Registro</h2>

      <?php if ($err): ?>
        <div class="co-msg co-msg--error" role="alert">No pudimos crear la cuenta. Probá nuevamente.</div>
      <?php endif; ?>

      <form action="api/register.php" method="post" class="co-form" autocomplete="off" onsubmit="return coValidateRegister(event)">
        <div class="co-field">
          <label class="co-label" for="username">Usuario</label>
          <input class="co-input" id="username" name="username" minlength="3" maxlength="30" required>
        </div>

        <div class="co-field">
          <label class="co-label" for="email">Email</label>
          <input class="co-input" id="email" type="email" name="email" required>
        </div>

        <div class="co-field co-field--pwd">
          <label class="co-label" for="password">Contraseña</label>
          <input class="co-input" id="password" type="password" name="password" minlength="6" required>
          <button class="co-eye" type="button" aria-label="Mostrar u ocultar contraseña"></button>
        </div>

        <div class="co-field">
          <label class="co-label" for="password2">Repetir contraseña</label>
          <input class="co-input" id="password2" type="password" minlength="6" required>
          <small class="co-muted" id="pwdHelp">Debe coincidir con la contraseña.</small>
        </div>

        <div class="co-auth__actions">
          <button class="co-btn co-btn--primary" type="submit">Crear cuenta</button>
          <a class="co-btn co-btn--ghost" href="login.php">Ya tengo usuario</a>
        </div>
      </form>
    </div>

  </section>
</main>

<footer class="co-auth__footer">
  <small class="co-muted">© <?php echo date('Y'); ?> CreditOrg</small>
</footer>

<script>
  // Toggle de contraseña
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

  // Validación simple en el cliente
  function coValidateRegister(e){
    const p1 = document.getElementById('password');
    const p2 = document.getElementById('password2');
    if(p1.value !== p2.value){
      e.preventDefault();
      document.getElementById('pwdHelp').textContent = 'Las contraseñas no coinciden.';
      // efecto shake suave
      const card = document.querySelector('.co-auth__card');
      card.classList.remove('shake'); void card.offsetWidth; card.classList.add('shake');
      p2.focus();
      return false;
    }
    return true;
  }
</script>
</body>
</html>
