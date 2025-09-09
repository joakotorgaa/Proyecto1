<?php
session_start();
if(empty($_SESSION['user_id'])){ header('Location: ../login.php'); exit; }
?>
<!doctype html><html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Mi panel - CreditOrg</title>
<link rel="stylesheet" href="../assets/main.css">
</head><body>
<header><div class="header-inner">
  <a class="logo" href="../index.php"><span class="badge">CO</span> CreditOrg</a>
  <nav class="nav">
    <?php if(!empty($_SESSION['is_admin'])): ?><a class="btn" href="../admin/">Admin</a><?php endif; ?>
    <a class="btn btn-outline" href="../logout.php">Salir</a>
  </nav>
</div></header>
<main class="container grid grid-3">
  <a class="card" href="deposit.php"><h3>Ingresar dinero</h3><p>Depósito simple a tu cuenta.</p></a>
  <a class="card" href="cards.php"><h3>Tarjetas</h3><p>Generar, pausar, eliminar, denunciar.</p></a>
  <a class="card" href="tickets.php"><h3>Tickets</h3><p>Soporte y consultas.</p></a>
  <a class="card" href="transfer.php"><h3>Transferencias</h3><p>Entre cuentas del banco.</p></a>
  <a class="card" href="topup_sube.php"><h3>Recarga SUBE</h3><p>Crédito para transporte.</p></a>
  <a class="card" href="topup_mobile.php"><h3>Recarga móvil</h3><p>Tu línea.</p></a>
  <a class="card" href="cbu.php"><h3>CBU / Alias</h3><p>Ver y modificar alias.</p></a>
  <a class="card" href="accounts.php"><h3>Abrir cuentas</h3><p>Pesos, dólares, corrientes.</p></a>
  <a class="card" href="settings.php"><h3>Configuración</h3><p>Perfil y ajustes.</p></a>
</main>
</body></html>
