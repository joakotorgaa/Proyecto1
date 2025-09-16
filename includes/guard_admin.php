<?php
// includes/guard_admin.php
session_start();
if (empty($_SESSION['user_id'])) {
  header('Location: ../login.php'); exit;
}
if (empty($_SESSION['is_admin'])) {
  header('Location: ../user/'); exit;
}
