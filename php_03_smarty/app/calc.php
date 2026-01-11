<?php
require_once __DIR__ . '/../config.php';

// Ochrona kontrolera (redirect do logowania gdy brak sesji)
require_once _ROOT_PATH . '/app/security/check.php';

// Smarty
require_once _ROOT_PATH . '/app/smarty_init.php';

// pobranie parametrów
function getParams(&$x, &$y, &$operation): void {
  // UI (templates_ui/calc.tpl) wysyła: liczba1, liczba2, operacja
  // Dla kompatybilności zostawiamy też stare nazwy: x, y, op
  $x = $_REQUEST['liczba1'] ?? ($_REQUEST['x'] ?? null);
  $y = $_REQUEST['liczba2'] ?? ($_REQUEST['y'] ?? null);
  $operation = $_REQUEST['operacja'] ?? ($_REQUEST['op'] ?? null);
}

// walidacja parametrów
function validate($x, $y, $operation, array &$messages): bool {

  if (!(isset($x) && isset($y) && isset($operation))) {
    return false; // pierwszy widok formularza bez komunikatów
  }

  if ($x === "") $messages[] = 'Nie podano liczby 1';
  if ($y === "") $messages[] = 'Nie podano liczby 2';
  if (!empty($messages)) return false;

  if (filter_var($x, FILTER_VALIDATE_INT) === false) {
    $messages[] = 'Pierwsza wartość nie jest liczbą całkowitą';
  }
  if (filter_var($y, FILTER_VALIDATE_INT) === false) {
    $messages[] = 'Druga wartość nie jest liczbą całkowitą';
  }

  if ($operation === 'div' && intval($y) === 0) {
    $messages[] = 'Nie wolno dzielić przez 0';
  }

  return empty($messages);
}

function process(int $x, int $y, string $operation, array &$messages, ?float &$result, string $role): void {

  $isAdmin = ($role === 'admin');

  // Blokada operacji dla roli user ("-" oraz dzielenie)
  if (!$isAdmin && ($operation === 'minus' || $operation === 'div')) {
    $messages[] = 'Tylko administrator może wykonywać tę operację.';
    return;
  }

  switch ($operation) {
    case 'minus':
      $result = $x - $y;
      break;
    case 'times':
      $result = $x * $y;
      break;
    case 'div':
      $result = $x / $y;
      break;
    case 'plus':
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
if ($operation === null || $operation === '') $operation = 'plus';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (validate($x, $y, $operation, $messages)) {
    // jawna konwersja na int PRZED obliczeniami
    $xInt = intval($x);
    $yInt = intval($y);
    process($xInt, $yInt, (string)$operation, $messages, $result, $role);
  }
}

// render Smarty
$smarty = get_smarty();
$smarty->assign('page_title', 'Zwykły kalkulator');

// templates_ui/calc.tpl
$smarty->assign('liczba1', $x ?? '');
$smarty->assign('liczba2', $y ?? '');
$smarty->assign('operacja', $operation ?? 'plus');
$smarty->assign('messages', $messages);
$smarty->assign('wynik', $result);
$smarty->assign('restriction_info', '');

$smarty->display('calc.tpl');
