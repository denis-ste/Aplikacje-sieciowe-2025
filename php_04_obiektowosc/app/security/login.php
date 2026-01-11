<?php
require_once __DIR__ . '/../../config.php';
require_once _ROOT_PATH . '/app/smarty_init.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

$returnUrl = $_GET['return'] ?? '';
if ($returnUrl === '') $returnUrl = _APP_URL . '/app/home.php';

// blokada zewnętrznych URL
if (strpos($returnUrl, 'http://') === 0 || strpos($returnUrl, 'https://') === 0) {
  if (strpos($returnUrl, _APP_URL) !== 0) $returnUrl = _APP_URL . '/app/home.php';
}

$messages = [];
$login = $_POST['login'] ?? '';

if (!empty($_SESSION['role'])) {
  header("Location: " . $returnUrl);
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $pass = $_POST['pass'] ?? '';

  if ($login === '' || $pass === '') {
    $messages[] = 'Podaj login i hasło.';
  } else {
    $role = '';
    if ($login === 'admin' && $pass === 'admin') $role = 'admin';
    if ($login === 'user'  && $pass === 'user')  $role = 'user';

    if ($role === '') {
      $messages[] = 'Nieprawidłowy login lub hasło.';
    } else {
      $_SESSION['login'] = $login;
      $_SESSION['role']  = $role;
      header("Location: " . $returnUrl);
      exit();
    }
  }
}

$smarty = get_smarty();
$smarty->assign('page_title', 'Logowanie');
$smarty->assign('login', $login);
$smarty->assign('messages', $messages);
$smarty->display('login.tpl');
