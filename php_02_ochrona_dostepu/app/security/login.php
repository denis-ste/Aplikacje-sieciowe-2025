<?php
require_once __DIR__ . '/../../config.php';

// parametry
function getParamsLogin(&$form) {
  $form['login'] = isset($_REQUEST['login']) ? trim($_REQUEST['login']) : null;
  $form['pass']  = isset($_REQUEST['pass'])  ? trim($_REQUEST['pass'])  : null;
}

// walidacja + ustawienie sesji przy sukcesie
function validateLogin(&$form, &$messages) {

  if (!(isset($form['login']) && isset($form['pass']))) {
    return false; // wejście bez formularza
  }

  if ($form['login'] === '') $messages[] = 'Nie podano loginu';
  if ($form['pass'] === '')  $messages[] = 'Nie podano hasła';
  if (count($messages) > 0) return false;

  if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
  }

  // admin/admin -> rola uprzywilejowana (u Ciebie: manager)
  if ($form['login'] === 'admin' && $form['pass'] === 'admin') {
    session_regenerate_id(true);
    $_SESSION['role']  = 'manager';
    $_SESSION['login'] = 'admin';
    return true;
  }

  // user/user -> zwykły użytkownik
  if ($form['login'] === 'user' && $form['pass'] === 'user') {
    session_regenerate_id(true);
    $_SESSION['role']  = 'user';
    $_SESSION['login'] = 'user';
    return true;
  }

  // (opcjonalny alias – jeśli chcesz zostawić dawny login)
  if ($form['login'] === 'manager' && $form['pass'] === 'manager') {
    session_regenerate_id(true);
    $_SESSION['role']  = 'manager';
    $_SESSION['login'] = 'manager';
    return true;
  }

  $messages[] = 'Niepoprawny login lub hasło';
  return false;
}

// init
$form = [];
$messages = [];

// jeżeli już zalogowany – nie pokazuj logowania
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if (!empty($_SESSION['role'])) {
  header("Location: " . _APP_URL);
  exit();
}

getParamsLogin($form);

if (!validateLogin($form, $messages)) {
  include _ROOT_PATH . '/app/security/login_view.php';
} else {
  header("Location: " . _APP_URL);
  exit();
}
