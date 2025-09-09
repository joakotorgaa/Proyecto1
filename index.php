<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Banco Aurora — Bienvenido</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- IMPORTANTE: solo este CSS, no main.css ni register.css -->
  <link rel="stylesheet" href="assets/home.css">
</head>
<body class="home">

<header class="home__header">
  <div class="home__container">
    <a href="#top" class="home__logo">Banco Aurora</a>
    <nav class="home__nav">
      <a href="#somos">Quiénes somos</a>
      <a href="#productos">Productos</a>
      <a href="#tarjeta">Tarjeta</a>
      <a href="#faq">Ayuda</a>
      <a href="#contacto">Contacto</a>
    </nav>
    <div class="home__cta">
      <a class="btn" href="login.php">Ingresar</a>
      <a class="btn btn--primary" href="register.php">Crear cuenta</a>
    </div>
  </div>
</header>

<main>
  <section class="home__hero">
    <div class="home__container">
      <h1>Un banco digital claro y cercano</h1>
      <p>Operá online con seguridad y simplicidad: cuentas, tarjetas y pagos.</p>
      <div class="hero__actions">
        <a class="btn btn--primary" href="register.php">Abrí tu cuenta</a>
        <a class="btn btn--outline" href="#productos">Conocé más</a>
      </div>
    </div>
  </section>

  <section id="somos" class="home__container">
    <h2>Quiénes somos</h2>
    <p>Somos un banco digital pensado para simplificar tu vida financiera.</p>
  </section>

  <section id="productos" class="home__container">
    <h2>Qué ofrecemos</h2>
    <div class="home__grid">
      <div class="card"><h3>Cuentas</h3><p>Abiertas online en minutos.</p></div>
      <div class="card"><h3>Tarjetas</h3><p>Control total desde la web.</p></div>
      <div class="card"><h3>Pagos</h3><p>Pagá y recargá fácil y rápido.</p></div>
    </div>
  </section>

  <section id="tarjeta" class="home__container">
    <h2>Cómo obtener tu tarjeta</h2>
    <ol class="steps">
      <li>Abrí tu cuenta digital.</li>
      <li>Solicitá tu tarjeta online.</li>
      <li>Activala en minutos.</li>
      <li>Gestioná todo desde tu panel.</li>
    </ol>
  </section>

  <section id="faq" class="home__container">
    <h2>Preguntas frecuentes</h2>
    <details><summary>¿La apertura es online?</summary><p>Sí, 100% digital.</p></details>
    <details><summary>¿Hay costos ocultos?</summary><p>No, todo está detallado claramente.</p></details>
  </section>

  <section id="contacto" class="home__container">
    <h2>Contacto</h2>
    <form method="post" action="api/contact.php" class="contact-form">
      <input type="text" name="name" placeholder="Nombre" required>
      <input type="email" name="email" placeholder="Email" required>
      <textarea name="message" placeholder="Escribí tu mensaje..." required></textarea>
      <button type="submit" class="btn btn--primary">Enviar</button>
    </form>
  </section>
</main>

<footer class="home__footer">
  <div class="home__container">
    <p>© <?php echo date('Y'); ?> Banco Aurora</p>
  </div>
</footer>

</body>
</html>
