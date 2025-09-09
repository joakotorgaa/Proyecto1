<?php
session_start();
if(empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])){ header('Location: ../login.php'); exit; }
require_once __DIR__ . '/../api/_conn.php';
$uid = (int)($_GET['id'] ?? 0);
if(!$uid){ header('Location: users.php'); exit; }
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $type=$_POST['type']??'caja_ahorro'; $currency=$_POST['currency']??'ARS';
  $alias = strtolower('alias.'.$uid.'.'.bin2hex(random_bytes(3)));
  $cbu = substr(str_shuffle(str_repeat('0123456789',22)),0,22);
  $stmt=$mysqli->prepare("INSERT INTO accounts (user_id,type,currency,cbu,alias) VALUES (?,?,?,?,?)");
  $stmt->bind_param('issss',$uid,$type,$currency,$cbu,$alias); $stmt->execute();
  $msg='Cuenta creada.';
}
$user=$mysqli->query("SELECT id,username FROM users WHERE id=".$uid)->fetch_assoc();
$acc=$mysqli->query("SELECT * FROM accounts WHERE user_id=".$uid." ORDER BY id DESC");
?>
<!doctype html><html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin | Cuentas de usuario</title>
<link rel="stylesheet" href="../assets/main.css"></head><body>
<header><div class="header-inner">
  <a class="logo" href="users.php"><span class="badge">CO</span> Admin</a>
  <nav class="nav"><a class="btn" href="users.php">Volver</a></nav>
</div></header>
<main class="container">
  <div class="card">
    <h2>Cuentas de <?php echo htmlspecialchars($user['username']); ?></h2>
    <?php if($msg) echo '<p style="color:green">'.$msg.'</p>'; ?>
    <form method="post" style="display:flex;gap:8px;align-items:end">
      <div><label>Tipo</label><select class="input" name="type"><option value="caja_ahorro">Caja de ahorro</option><option value="cuenta_corriente">Cuenta corriente</option></select></div>
      <div><label>Moneda</label><select class="input" name="currency"><option value="ARS">ARS</option><option value="USD">USD</option></select></div>
      <button class="btn btn-primary">Crear cuenta</button>
    </form>
    <table class="table" style="margin-top:12px;">
      <thead><tr><th>ID</th><th>Tipo</th><th>Moneda</th><th>CBU</th><th>Alias</th><th>Saldo</th><th>Estado</th><th>Creado</th></tr></thead>
      <tbody>
        <?php while($a=$acc->fetch_assoc()): ?>
          <tr>
            <td><?php echo $a['id']; ?></td>
            <td><?php echo $a['type']; ?></td>
            <td><?php echo $a['currency']; ?></td>
            <td><?php echo $a['cbu']; ?></td>
            <td><?php echo $a['alias']; ?></td>
            <td><?php echo number_format($a['balance'],2,',','.'); ?></td>
            <td><?php echo $a['status']; ?></td>
            <td><?php echo $a['created_at']; ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</main>
</body></html>
