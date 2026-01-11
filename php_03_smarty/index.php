<?php
require_once __DIR__ . '/config.php';
require_once _ROOT_PATH . '/app/smarty_init.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if (!empty($_SESSION['role'])) {
  header("Location: " . _APP_URL . "/app/home.php");
  exit();
}

$smarty = get_smarty();
$smarty->assign('page_title', 'Welcome!');
$smarty->assign('page_subtitle', 'Witaj w systemie kalkulatorów');
$smarty->display('welcome.tpl');
