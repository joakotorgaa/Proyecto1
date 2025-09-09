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
<!doctype html><html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin | Editar usuario</title>
<link rel="stylesheet" href="../assets/main.css"></head><body>
<header><div class="header-inner">
  <a class="logo" href="users.php"><span class="badge">CO</span> Admin</a>
  <nav class="nav"><a class="btn" href="users.php">Volver</a></nav>
</div></header>
<main class="container">
  <div class="card" style="max-width:640px;margin:0 auto;">
    <h2>Editar usuario #<?php echo $u['id']; ?></h2>
    <?php if($msg) echo '<p style="color:green">'.$msg.'</p>'; ?>
    <form method="post">
      <label>Usuario</label><input class="input" name="username" value="<?php echo htmlspecialchars($u['username']); ?>" required>
      <label>Email</label><input class="input" type="email" name="email" value="<?php echo htmlspecialchars($u['email']); ?>" required>
      <label><input type="checkbox" name="is_admin" <?php echo $u['is_admin']?'checked':''; ?>> Admin</label>
      <label>Nueva contraseña (opcional)</label><input class="input" name="password" placeholder="Dejar vacío para no cambiar">
      <div style="margin-top:10px;display:flex;gap:8px;">
        <button class="btn btn-primary">Guardar</button>
        <a class="btn btn-outline" href="users.php">Cancelar</a>
      </div>
    </form>
  </div>
</main>
</body></html>
