<?php
require_once __DIR__ . '/../../config.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if (empty($_SESSION['role'])) {
  $return = $_SERVER['REQUEST_URI'] ?? (_APP_URL . '/');
  header("Location: " . _APP_URL . "/?action=login&return=" . urlencode($return));
  exit();
}
