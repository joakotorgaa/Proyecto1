<?php
session_start();
$is_logged = !empty($_SESSION['user_id']);
$is_admin  = !empty($_SESSION['is_admin']);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CreditOrg — Banco digital simple y claro</title>
  <link rel="stylesheet" href="assets/home.css">
</head>
<body class="home">

<!-- HEADER con navbar -->
<header class="home__header">
  <div class="home__container home__header-in">
    <a class="home__logo" href="#top">
      <span class="home__logo-badge">CO</span>
      <span>CreditOrg</span>
    </a>

    <nav class="home__nav">
      <div class="home__nav-group">
        <a class="home__nav-item" href="#somos">Quiénes somos</a>
        <div class="home__nav-item has-menu">Productos
          <div class="home__menu">
            <a href="#ofrecemos">Qué ofrecemos</a>
            <a href="#tarjeta">Generar tarjeta</a>
            <a href="#faq">Ayuda / Preguntas</a>
          </div>
        </div>
        <a class="home__nav-item" href="#contacto">Contacto</a>
      </div>

      <div class="home__cta">
        <?php if ($is_logged): ?>
          <?php if ($is_admin): ?>
            <a class="btn" href="admin/">Panel Admin</a>
          <?php else: ?>
            <a class="btn" href="user/">Mi Panel</a>
          <?php endif; ?>
          <a class="btn btn--outline" href="logout.php">Cerrar sesión</a>
        <?php else: ?>
          <a class="btn" href="login.php">Ingresar</a>
          <a class="btn btn--primary" href="register.php">Crear cuenta</a>
        <?php endif; ?>
      </div>

      <button class="home__burger" aria-label="Menú" onclick="document.body.classList.toggle('nav-open')">
        <span></span><span></span><span></span>
      </button>
    </nav>
  </div>
</header>

<main id="top">

  <!-- HERO informativo -->
  <section class="hero">
    <div class="home__container hero__grid">
      <div class="hero__copy card">
        <h1>Un banco digital pensado para vos</h1>
        <p>Operá fácil: cuentas en ARS y USD, tarjetas, transferencias internas, recargas SUBE y móvil, y soporte por tickets.</p>
        <div class="hero__cta">
          <a class="btn btn--primary" href="<?php echo $is_logged ? ($is_admin ? 'admin/' : 'user/') : 'register.php'; ?>">
            <?php echo $is_logged ? 'Ir a mi panel' : 'Abrí tu cuenta'; ?>
          </a>
          <a class="btn btn--outline" href="#ofrecemos">Conocé nuestros productos</a>
        </div>
        <ul class="hero__badges">
          <li>💳 Gestión completa de tarjetas</li>
          <li>🔁 Transferencias internas</li>
          <li>🚉 Recargas SUBE y móvil</li>
        </ul>
      </div>

      <div class="hero__promo card">
        <h3>Todo en un mismo lugar</h3>
        <ul class="promo__list">
          <li>• Cuentas (caja de ahorro ARS/USD y cuenta corriente)</li>
          <li>• CBU/CVU y Alias configurables</li>
          <li>• Generar / pausar / denunciar / eliminar tarjetas</li>
          <li>• Transferencias entre usuarios del banco</li>
          <li>• Soporte por tickets y configuración de perfil</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- QUIÉNES SOMOS -->
  <section id="somos" class="home__container">
    <h2 class="section-title">Quiénes somos</h2>
    <div class="card somos__grid">
      <div>
        <p>Somos CreditOrg, un banco 100% digital. Diseñamos una experiencia clara y moderna para que puedas administrar tu dinero sin vueltas, desde cualquier dispositivo.</p>
      </div>
      <div>
        <p>Nuestro foco está en la simpleza: procesos directos, información transparente y herramientas para que tengas control de tus cuentas en todo momento.</p>
      </div>
      <div>
        <p>Operaciones internas al instante, gestión integral de tarjetas y recargas. Si necesitás ayuda, nuestro sistema de tickets te acompaña.</p>
      </div>
    </div>
  </section>

  <!-- QUÉ OFRECEMOS -->
  <section id="ofrecemos" class="home__container">
    <h2 class="section-title">Qué ofrecemos</h2>
    <div class="prod__grid">
      <div class="prod card">
        <h3>Cuentas</h3>
        <p>Abrí caja de ahorro en ARS y USD o una cuenta corriente. Alias y CBU automáticos para empezar a operar ya mismo.</p>
        <a class="btn btn--outline" href="<?php echo $is_logged ? 'user/accounts.php' : 'register.php'; ?>">Abrir cuenta</a>
      </div>
      <div class="prod card">
        <h3>Tarjetas</h3>
        <p>Generá tarjetas en segundos y administrá su estado: pausar, denunciar o eliminar, todo desde tu panel.</p>
        <a class="btn btn--outline" href="<?php echo $is_logged ? 'user/cards.php' : 'login.php'; ?>">Gestionar</a>
      </div>
      <div class="prod card">
        <h3>Pagos y recargas</h3>
        <p>Recargá SUBE y tu línea móvil con acreditación básica y comprobante simple.</p>
        <a class="btn btn--outline" href="<?php echo $is_logged ? 'user/topup_sube.php' : 'login.php'; ?>">Ver opciones</a>
      </div>
      <div class="prod card">
        <h3>Transferencias internas</h3>
        <p>Enviá y recibí dinero entre cuentas de CreditOrg. Rápido, directo y sin complicaciones.</p>
        <a class="btn btn--outline" href="<?php echo $is_logged ? 'user/transfer.php' : 'login.php'; ?>">Transferir</a>
      </div>
    </div>
  </section>

  <!-- CÓMO GENERAR TU TARJETA (paso a paso visible en la bienvenida) -->
  <section id="tarjeta" class="home__container">
    <h2 class="section-title">Cómo generar tu tarjeta</h2>
    <div class="pasos card">
      <div class="paso">
        <div class="paso__num">1</div>
        <div class="paso__copy">
          <h4>Ingresá</h4>
          <p><?php echo $is_logged ? 'Entrá a tu panel y ' : 'Iniciá sesión y '; ?>andá a <b>Tarjetas</b>.</p>
        </div>
      </div>
      <div class="paso">
        <div class="paso__num">2</div>
        <div class="paso__copy">
          <h4>Generá tu tarjeta</h4>
          <p>Elegí la marca y hacé clic en <b>“Generar tarjeta”</b>. La emitimos al instante.</p>
        </div>
      </div>
      <div class="paso">
        <div class="paso__num">3</div>
        <div class="paso__copy">
          <h4>Administrala</h4>
          <p>Desde el mismo módulo podés <b>pausar, denunciar o eliminar</b> cuando lo necesites.</p>
        </div>
      </div>
      <div class="paso">
        <div class="paso__num">4</div>
        <div class="paso__copy">
          <h4>Listo para usar</h4>
          <p>Tu tarjeta queda lista para operar dentro de CreditOrg.</p>
        </div>
      </div>
      <div class="pasos__cta">
        <a class="btn btn--primary" href="<?php echo $is_logged ? 'user/cards.php' : 'login.php'; ?>">Ir a Tarjetas</a>
      </div>
    </div>
  </section>

  <!-- FAQ / AYUDA -->
  <section id="faq" class="home__container">
    <h2 class="section-title">Preguntas frecuentes</h2>
    <div class="faq__grid">
      <details class="card"><summary>¿Puedo transferir a otros bancos?</summary><p>De momento, solo transferencias internas dentro de CreditOrg.</p></details>
      <details class="card"><summary>¿Cómo edito mi Alias?</summary><p>Desde “CBU / Alias” podés verlo y actualizarlo.</p></details>
      <details class="card"><summary>¿Dónde veo mis cuentas?</summary><p>En “Abrir cuentas” podés crear y consultar tus cuentas disponibles.</p></details>
    </div>
  </section>

  <!-- CONTACTO -->
  <section id="contacto" class="home__container">
    <h2 class="section-title">Contacto</h2>
    <form class="card contacto__form" method="post" action="api/contact.php">
      <div class="grid2">
        <div>
          <label>Nombre y apellido</label>
          <input class="input" name="name" required>
        </div>
        <div>
          <label>Email</label>
          <input class="input" type="email" name="email" required>
        </div>
      </div>
      <div>
        <label>Motivo</label>
        <select class="input" name="topic" required>
          <option value="info">Quiero más información</option>
          <option value="soporte">Necesito soporte</option>
          <option value="sugerencia">Tengo una sugerencia</option>
        </select>
      </div>
      <div>
        <label>Mensaje</label>
        <textarea class="input" name="message" rows="5" required></textarea>
      </div>
      <div class="form__actions">
        <button class="btn btn--primary" type="submit">Enviar</button>
        <a class="btn btn--outline" href="#top">Cancelar</a>
      </div>
      <p class="muted">Al enviar, aceptás ser contactadx por nuestro equipo.</p>
    </form>
  </section>

</main>

<!-- FOOTER -->
<footer class="home__footer">
  <div class="home__container home__footer-in">
    <div class="home__links">
      <a href="#somos">Quiénes somos</a>
      <a href="#ofrecemos">Qué ofrecemos</a>
      <a href="#tarjeta">Generar tarjeta</a>
      <a href="#contacto">Contacto</a>
    </div>
    <div class="home__copy">© <?php echo date('Y'); ?> CreditOrg</div>
  </div>
</footer>

</body>
</html>
