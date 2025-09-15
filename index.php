<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>CreditOrg — Banco digital</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Importante: ahora TODO está en main.css -->
  <link rel="stylesheet" href="assets/main.css">
</head>
<body class="co-body" id="top">

<!-- HEADER -->
<header class="co-header">
  <div class="co-wrap co-header__in">
    <a class="co-brand" href="#top" aria-label="Inicio CreditOrg">
      <span class="co-brand__badge">CO</span>
      <span class="co-brand__name">CreditOrg</span>
    </a>

    <nav class="co-nav" aria-label="Navegación principal">
      <button class="co-burger" aria-label="Abrir menú" onclick="document.body.classList.toggle('co-nav-open')">
        <span></span><span></span><span></span>
      </button>

      <ul class="co-nav__links">
        <li class="co-nav__item has-sub">
          <a class="co-nav__link" href="#productos">Productos</a>
          <div class="co-sub">
            <a href="#productos">Cuentas</a>
            <a href="#productos">Tarjetas</a>
            <a href="#productos">Préstamos</a>
            <a href="#productos">Inversiones</a>
            <a href="#productos">Seguros</a>
          </div>
        </li>
        <li class="co-nav__item"><a class="co-nav__link" href="#canales">Canales</a></li>
        <li class="co-nav__item"><a class="co-nav__link" href="#beneficios">Beneficios</a></li>
        <li class="co-nav__item"><a class="co-nav__link" href="#faq">Ayuda</a></li>
        <li class="co-nav__item"><a class="co-nav__link" href="#contacto">Contacto</a></li>
      </ul>

      <div class="co-nav__cta">
        <a class="co-btn" href="login.php">Ingresar</a>
        <a class="co-btn co-btn--primary" href="register.php">Crear cuenta</a>
      </div>
    </nav>
  </div>
</header>

<main>

  <!-- HERO -->
  <section class="co-hero">
    <div class="co-wrap co-hero__in">
      <div class="co-hero__copy">
        <h1>Un banco digital claro y cercano</h1>
        <p>Gestioná tu dinero con una experiencia moderna: cuentas, tarjetas, préstamos e inversiones, con soporte cuando lo necesitás.</p>
        <div class="co-hero__cta">
          <a class="co-btn co-btn--primary" href="register.php">Abrí tu cuenta</a>
          <a class="co-btn co-btn--outline" href="#productos">Conocé nuestros productos</a>
        </div>
        <ul class="co-hero__bullets">
          <li>Transparencia y simplicidad</li>
          <li>Experiencia 100% online</li>
          <li>Seguridad y acompañamiento</li>
        </ul>
      </div>

      <div class="co-hero__panel">
        <article class="co-card co-kpi">
          <h3>Seguridad</h3>
          <p class="co-muted">Buenas prácticas y verificación de identidad.</p>
        </article>
        <article class="co-card co-kpi">
          <h3>Claridad</h3>
          <p class="co-muted">Información simple y costos visibles.</p>
        </article>
        <article class="co-card co-kpi">
          <h3>Disponibilidad</h3>
          <p class="co-muted">Tu banco, donde estés.</p>
        </article>
      </div>
    </div>
  </section>

  <!-- PRODUCTOS (tipo banco real) -->
  <section id="productos" class="co-wrap">
    <h2 class="co-ttl">Productos</h2>
    <div class="co-grid co-grid--prod">
      <article class="co-card co-prod">
        <h3>Cuentas</h3>
        <p>Cajas de ahorro y cuentas corrientes para tu día a día. Apertura online en minutos.</p>
        <a class="co-btn co-btn--outline" href="register.php">Abrir cuenta</a>
      </article>
      <article class="co-card co-prod">
        <h3>Tarjetas</h3>
        <p>Tarjetas de débito y crédito con control desde la web. Gestión simple y segura.</p>
        <a class="co-btn co-btn--outline" href="login.php">Conocer más</a>
      </article>
      <article class="co-card co-prod">
        <h3>Préstamos</h3>
        <p>Opciones de financiación con cuotas claras y simulador orientativo.</p>
        <a class="co-btn co-btn--outline" href="login.php">Solicitar</a>
      </article>
      <article class="co-card co-prod">
        <h3>Inversiones</h3>
        <p>Plazos fijos y alternativas básicas para planificar tu ahorro.</p>
        <a class="co-btn co-btn--outline" href="login.php">Invertir</a>
      </article>
      <article class="co-card co-prod">
        <h3>Seguros</h3>
        <p>Protección para vos y tus bienes con coberturas esenciales.</p>
        <a class="co-btn co-btn--outline" href="login.php">Cotizar</a>
      </article>
    </div>
  </section>

  <!-- CANALES -->
  <section id="canales" class="co-wrap">
    <h2 class="co-ttl">Canales</h2>
    <div class="co-grid co-grid--3">
      <article class="co-card">
        <h3>Banca Online</h3>
        <p>Acceso web para operar desde cualquier dispositivo.</p>
      </article>
      <article class="co-card">
        <h3>App Móvil</h3>
        <p>Tu banco en el bolsillo, con notificaciones clave.</p>
      </article>
      <article class="co-card">
        <h3>Sucursales y Cajeros</h3>
        <p>Red para extracciones y gestiones presenciales esenciales.</p>
      </article>
    </div>
  </section>

  <!-- BENEFICIOS / SEGURIDAD -->
  <section id="beneficios" class="co-band">
    <div class="co-wrap co-band__in">
      <div class="co-band__col">
        <h3>Compromiso con la seguridad</h3>
        <p class="co-muted">Educación financiera, alertas y medidas para cuidar tus datos.</p>
      </div>
      <div class="co-band__col">
        <h3>Atención cuando la necesitás</h3>
        <p class="co-muted">Centro de ayuda, preguntas frecuentes y contacto directo.</p>
      </div>
      <div class="co-band__col">
        <h3>Transparencia</h3>
        <p class="co-muted">Información clara para decidir con tranquilidad.</p>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section id="faq" class="co-wrap">
    <h2 class="co-ttl">Preguntas frecuentes</h2>
    <div class="co-faq">
      <details class="co-card"><summary>¿La apertura de cuenta es online?</summary><p class="co-muted">Sí, 100% digital con validación de identidad.</p></details>
      <details class="co-card"><summary>¿Puedo operar desde el celular?</summary><p class="co-muted">La experiencia está optimizada para móvil y escritorio.</p></details>
      <details class="co-card"><summary>¿Cómo recibo ayuda?</summary><p class="co-muted">Tenés centro de ayuda y canales de contacto.</p></details>
    </div>
  </section>

  <!-- CONTACTO -->
  <section id="contacto" class="co-wrap">
    <h2 class="co-ttl">Contacto</h2>
    <form class="co-card co-contact" method="post" action="api/contact.php">
      <div class="co-grid co-grid--2">
        <div>
          <label class="co-label">Nombre y apellido</label>
          <input class="co-input" name="name" required>
        </div>
        <div>
          <label class="co-label">Email</label>
          <input class="co-input" type="email" name="email" required>
        </div>
      </div>
      <div>
        <label class="co-label">Motivo</label>
        <select class="co-input" name="topic" required>
          <option value="info">Quiero más información</option>
          <option value="soporte">Necesito soporte</option>
          <option value="sugerencia">Tengo una sugerencia</option>
        </select>
      </div>
      <div>
        <label class="co-label">Mensaje</label>
        <textarea class="co-input" name="message" rows="5" required></textarea>
      </div>
      <div class="co-actions">
        <button class="co-btn co-btn--primary" type="submit">Enviar</button>
        <a class="co-btn co-btn--ghost" href="#top">Cancelar</a>
      </div>
      <p class="co-muted">Al enviar, aceptás ser contactadx por el equipo.</p>
    </form>
  </section>

</main>

<!-- FOOTER -->
<footer class="co-footer">
  <div class="co-wrap co-footer__in">
    <div class="co-footer__links">
      <a href="#productos">Productos</a>
      <a href="#canales">Canales</a>
      <a href="#beneficios">Beneficios</a>
      <a href="#faq">Ayuda</a>
      <a href="#contacto">Contacto</a>
    </div>
    <small class="co-copy">© <?php echo date('Y'); ?> CreditOrg</small>
  </div>
</footer>

</body>
</html>
