<?php
session_start(); 
if(empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])){ header('Location: ../login.php'); exit; }
require_once __DIR__ . '/../api/_conn.php';
$id = (int)($_GET['id'] ?? 0);
if(!$id){ header('Location: users.php'); exit; }
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $username=trim($_POST['username']??''); $email=trim($_POST['email']??''); $is_admin=isset($_POST['is_admin'])?1:0; $password=trim($_POST['password']??'');
  if($username!=='' && $email!==''){
    if($password!==''){
      $stmt=$mysqli->prepare("UPDATE users SET username=?, email=?, is_admin=?, password=? WHERE id=?");
      $stmt->bind_param('ssisi',$username,$email,$is_admin,$password,$id);
    }else{
      $stmt=$mysqli->prepare("UPDATE users SET username=?, email=?, is_admin=? WHERE id=?");
      $stmt->bind_param('ssii',$username,$email,$is_admin,$id);
    }
    $stmt->execute(); $msg='Guardado.';
  }
}
$stmt=$mysqli->prepare("SELECT id,username,email,is_admin,created_at FROM users WHERE id=?");
$stmt->bind_param('i',$id); $stmt->execute(); $u=$stmt->get_result()->fetch_assoc();
if(!$u){ header('Location: users.php'); exit; }
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | Editar usuario</title>
  <link rel="stylesheet" href="../assets/main.css">
</head>
<body class="co-body">
<header class="co-header">
  <div class="co-wrap co-header__in">
    <a class="co-brand" href="users.php"><span class="co-brand__badge">CO</span><span class="co-brand__name">Admin</span></a>
    <nav class="co-nav">
      <ul class="co-nav__links">
        <li><a class="co-nav__link" href="users.php">Usuarios</a></li>
        <li><a class="co-nav__link" href="user_accounts.php">Cuentas por usuario</a></li>
      </ul>
      <div class="co-nav__cta">
        <span class="co-muted">Administrador</span>
        <a class="co-btn" href="../logout.php">Salir</a>
      </div>
    </nav>
  </div>
</header>
<main class="co-wrap">
  <div class="co-card" style="max-width:640px;margin:0 auto;">
    <h2 class="co-ttl">Editar usuario #<?php echo $u['id']; ?></h2>
    <?php if($msg) echo '<div class="co-msg co-msg--ok" style="margin-bottom:10px">'.$msg.'</div>'; ?>
    <form method="post" class="co-form">
      <div class="co-field">
        <label class="co-label">Usuario</label>
        <input class="co-input" name="username" value="<?php echo htmlspecialchars($u['username']); ?>" required>
      </div>
      <div class="co-field">
        <label class="co-label">Email</label>
        <input class="co-input" type="email" name="email" value="<?php echo htmlspecialchars($u['email']); ?>" required>
      </div>
      <div class="co-field">
        <label class="co-label"><input type="checkbox" name="is_admin" <?php echo $u['is_admin']?'checked':''; ?>> Admin</label>
      </div>
      <div class="co-field">
        <label class="co-label">Nueva contraseña (opcional)</label>
        <input class="co-input" name="password" placeholder="Dejar vacío para no cambiar">
      </div>
      <div class="co-actions" style="margin-top:10px;display:flex;gap:8px;">
        <button class="co-btn co-btn--primary">Guardar</button>
        <a class="co-btn co-btn--ghost" href="users.php">Cancelar</a>
      </div>
    </form>
  </div>
</main>
<footer class="co-footer">
  <div class="co-wrap co-footer__in"><div></div><small class="co-copy">© <?php echo date('Y'); ?> CreditOrg</small></div>
</footer>
</body>
</html>
