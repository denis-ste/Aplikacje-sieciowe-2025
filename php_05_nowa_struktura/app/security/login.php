<?php
require_once __DIR__ . '/../../init.php';

$returnUrl = $_GET['return'] ?? '';
if ($returnUrl === '') $returnUrl = getConf()->app_url . '/?action=home';

// blokada zewnętrznych URL
if (strpos($returnUrl, 'http://') === 0 || strpos($returnUrl, 'https://') === 0) {
  if (strpos($returnUrl, getConf()->app_url) !== 0) $returnUrl = getConf()->app_url . '/?action=home';
}

$messages = [];
$login = $_POST['login'] ?? '';

if (!empty($_SESSION['role'])) {
  redirect($returnUrl);
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
      redirect($returnUrl);
    }
  }
}

$smarty = getSmarty();
$smarty->assign('page_title', 'Logowanie');
$smarty->assign('login', $login);
$smarty->assign('messages', $messages);
$smarty->display('login.tpl');
