<?php
require_once __DIR__ . '/../config.php';

// Ochrona kontrolera (redirect do logowania gdy brak sesji)
require_once _ROOT_PATH . '/app/security/check.php';

// pobranie parametrów
function getParams(&$x, &$y, &$operation) {
  $x = isset($_REQUEST['x']) ? $_REQUEST['x'] : null;
  $y = isset($_REQUEST['y']) ? $_REQUEST['y'] : null;
  $operation = isset($_REQUEST['op']) ? $_REQUEST['op'] : null;
}

// walidacja parametrów
function validate(&$x, &$y, &$operation, &$messages) {

  if (!(isset($x) && isset($y) && isset($operation))) {
    return false;
  }

  if ($x === "") $messages[] = 'Nie podano liczby 1';
  if ($y === "") $messages[] = 'Nie podano liczby 2';
  if (count($messages) > 0) return false;

  if (filter_var($x, FILTER_VALIDATE_INT) === false) {
    $messages[] = 'Pierwsza wartość nie jest liczbą całkowitą';
  }
  if (filter_var($y, FILTER_VALIDATE_INT) === false) {
    $messages[] = 'Druga wartość nie jest liczbą całkowitą';
  }

  if ($operation === 'div' && intval($y) === 0) {
    $messages[] = 'Nie wolno dzielić przez 0';
  }

  return count($messages) === 0;
}

function process(&$x, &$y, &$operation, &$messages, &$result, $role) {

  // jawna konwersja na int
  $x = intval($x);
  $y = intval($y);

  $isAdmin = ($role === 'manager'); // admin/admin ustawia role=manager

  switch ($operation) {
    case 'minus':
      if ($isAdmin) {
        $result = $x - $y;
      } else {
        $messages[] = 'Tylko administrator może odejmować !';
      }
      break;

    case 'times':
      $result = $x * $y;
      break;

    case 'div':
      if ($isAdmin) {
        $result = $x / $y;
      } else {
        $messages[] = 'Tylko administrator może dzielić !';
      }
      break;

    default:
      $result = $x + $y;
      break;
  }
}

// zmienne kontrolera
$x = null;
$y = null;
$operation = null;
$result = null;
$messages = [];

// rola z sesji (check.php już ją sprawdził)
$role = $_SESSION['role'] ?? '';

getParams($x, $y, $operation);
if (validate($x, $y, $operation, $messages)) {
  process($x, $y, $operation, $messages, $result, $role);
}

include __DIR__ . '/calc_view.php';
