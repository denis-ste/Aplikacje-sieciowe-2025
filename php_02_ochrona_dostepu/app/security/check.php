<?php
require_once __DIR__ . '/../../config.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

$role = $_SESSION['role'] ?? '';
if ($role === '') {
  header("Location: " . _APP_URL . "/app/security/login.php");
  exit();
}
