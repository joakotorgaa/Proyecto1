<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/app_config.php';

$is_logged = !empty($_SESSION['user_id']);
$is_admin  = !empty($_SESSION['is_admin']);
$full_name = trim(($_SESSION['first_name'] ?? '') . ' ' . ($_SESSION['last_name'] ?? ''));
$display   = $full_name !== '' ? $full_name : ($_SESSION['username'] ?? 'Invitado');
?>
<header class="co-header">
  <div class="co-wrap co-header__in">
    <a class="co-brand" href="<?php echo url_path('index.php'); ?>">
      <span class="co-brand__badge">CO</span><span class="co-brand__name">CreditOrg</span>
    </a>

    <nav class="co-nav" aria-label="Navegación principal">
      <button class="co-burger" aria-label="Abrir menú" onclick="document.body.classList.toggle('co-nav-open')">
        <span></span><span></span><span></span>
      </button>

      <ul class="co-nav__links">
        <li><a class="co-nav__link" href="<?php echo url_path('index.php'); ?>">Inicio</a></li>

        <?php if ($is_logged && !$is_admin): ?>
          <li><a class="co-nav__link" href="<?php echo url_path('user/index.php'); ?>">Panel</a></li>
          <li><a class="co-nav__link" href="<?php echo url_path('user/accounts.php'); ?>">Cuentas</a></li>
          <li><a class="co-nav__link" href="<?php echo url_path('user/transfer.php'); ?>">Transferir</a></li>
          <li><a class="co-nav__link" href="<?php echo url_path('user/cards.php'); ?>">Tarjetas</a></li>
          <li><a class="co-nav__link" href="<?php echo url_path('user/cheques.php'); ?>">Cheques</a></li>
          <li><a class="co-nav__link" href="<?php echo url_path('user/loans.php'); ?>">Préstamos</a></li>
          <li><a class="co-nav__link" href="<?php echo url_path('user/tickets.php'); ?>">Tickets</a></li>
          <li><a class="co-nav__link" href="<?php echo url_path('user/profile.php'); ?>">Perfil</a></li>
        <?php endif; ?>

        <?php if ($is_admin): ?>
          <li><a class="co-nav__link" href="<?php echo url_path('admin/index.php'); ?>">Admin</a></li>
          <li><a class="co-nav__link" href="<?php echo url_path('admin/users.php'); ?>">Usuarios</a></li>
          <li><a class="co-nav__link" href="<?php echo url_path('admin/user_accounts.php'); ?>">Cuentas</a></li>
          <li><a class="co-nav__link" href="<?php echo url_path('admin/tickets.php'); ?>">Tickets</a></li>
        <?php endif; ?>
      </ul>

      <div class="co-nav__cta">
        <?php if ($is_logged): ?>
          <span class="co-muted" style="white-space:nowrap">Hola, <?php echo htmlspecialchars($display); ?></span>
          <a class="co-btn" href="<?php echo url_path('logout.php'); ?>">Salir</a>
        <?php else: ?>
          <a class="co-btn" href="<?php echo url_path('login.php'); ?>">Ingresar</a>
          <a class="co-btn co-btn--primary" href="<?php echo url_path('register.php'); ?>">Crear cuenta</a>
        <?php endif; ?>
      </div>
    </nav>
  </div>
</header>
