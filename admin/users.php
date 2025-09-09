<?php
session_start();
if(empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])){ header('Location: ../login.php'); exit; }
require_once __DIR__ . '/../api/_conn.php';
$q = trim($_GET['q'] ?? '');
$where = ''; $param=''; $types='';
if($q!==''){ $where = "WHERE username LIKE CONCAT('%',?,'%') OR email LIKE CONCAT('%',?,'%')"; $param=$q; $types='ss'; }
$sql = "SELECT id,username,email,is_admin,created_at FROM users " . ($where ? $where : "") . " ORDER BY id DESC LIMIT 200";
$stmt = $mysqli->prepare($sql);
if($where){ $stmt->bind_param($types,$param,$param); }
$stmt->execute(); $res = $stmt->get_result();
?>
<!doctype html><html lang="es"><head> 
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin | Usuarios</title>
<link rel="stylesheet" href="../assets/main.css"></head><body>
<header><div class="header-inner">
  <a class="logo" href="index.php"><span class="badge">CO</span> Admin</a>
  <nav class="nav">
    <a class="btn" href="../index.php">Inicio</a>
    <a class="btn btn-outline" href="../logout.php">Salir</a>
  </nav>
</div></header>
<main class="container">
  <div class="card">
    <h2>Gestión de usuarios</h2>
    <form method="get" style="display:flex;gap:8px;margin:10px 0;">
      <input class="input" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Buscar usuario o email">
      <button class="btn btn-primary">Buscar</button>
      <a class="btn" href="users.php">Limpiar</a>
    </form>
    <table class="table">
      <thead><tr><th>ID</th><th>Usuario</th><th>Email</th><th>Admin</th><th>Creado</th><th>Acciones</th></tr></thead>
      <tbody>
      <?php while($u=$res->fetch_assoc()): ?>
        <tr>
          <td><?php echo $u['id']; ?></td>
          <td><?php echo htmlspecialchars($u['username']); ?></td>
          <td><?php echo htmlspecialchars($u['email']); ?></td>
          <td><?php echo $u['is_admin'] ? 'Sí':'No'; ?></td>
          <td><?php echo $u['created_at']; ?></td>
          <td>
            <a class="btn btn-outline" href="user_edit.php?id=<?php echo $u['id']; ?>">Editar</a>
            <a class="btn" href="user_accounts.php?id=<?php echo $u['id']; ?>">Cuentas</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</main>
</body></html>
