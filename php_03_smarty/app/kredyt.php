<?php
require_once __DIR__ . '/../config.php';
require_once _ROOT_PATH . '/app/security/check.php';
require_once _ROOT_PATH . '/app/smarty_init.php';

$smarty = get_smarty();

$kwota = $_POST['kwota'] ?? '';
$lata  = $_POST['lata'] ?? '';
$oprocentowanie = $_POST['oprocentowanie'] ?? '';

$messages = [];
$rata = null;

const MAX_KWOTA_USER = 100000;
const MIN_APR_MANAGER = 3.00;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if ($kwota === '') $messages[] = 'Nie podano kwoty kredytu.';
  if ($lata === '') $messages[] = 'Nie podano liczby lat.';
  if ($oprocentowanie === '') $messages[] = 'Nie podano oprocentowania.';

  if (empty($messages)) {
    if (filter_var($kwota, FILTER_VALIDATE_FLOAT) === false || floatval($kwota) <= 0) {
      $messages[] = 'Kwota musi być dodatnią liczbą.';
    }
    if (filter_var($lata, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 50]]) === false) {
      $messages[] = 'Liczba lat musi być liczbą całkowitą (1–50).';
    }
    if (filter_var($oprocentowanie, FILTER_VALIDATE_FLOAT) === false) {
      $messages[] = 'Oprocentowanie musi być liczbą.';
    } else {
      $apr = floatval($oprocentowanie);
      if ($apr < 0 || $apr > 50) $messages[] = 'Oprocentowanie musi być w zakresie 0–50%.';
    }
  }

  if (empty($messages)) {
    $role = $_SESSION['role'] ?? 'user';

    $kwotaFloat = floatval($kwota);
    $lataInt = intval($lata);
    $aprFloat = floatval($oprocentowanie);

    if ($role !== 'manager') {
      if ($kwotaFloat > MAX_KWOTA_USER) {
        $messages[] = 'Kwota > ' . MAX_KWOTA_USER . ' zł: obliczenia może wykonać wyłącznie manager.';
      }
      if ($aprFloat < MIN_APR_MANAGER) {
        $messages[] = 'Oprocentowanie < ' . number_format(MIN_APR_MANAGER, 2) . '%: obliczenia może wykonać wyłącznie manager.';
      }
    }

    if (empty($messages)) {
      $n = $lataInt * 12;
      $r = $aprFloat / 100.0 / 12.0;

      if ($n <= 0) {
        $messages[] = 'Nieprawidłowy okres kredytowania.';
      } else if ($r > 0) {
        $rata = $kwotaFloat * ($r * pow(1 + $r, $n)) / (pow(1 + $r, $n) - 1);
      } else {
        $rata = $kwotaFloat / $n;
      }
    }
  }
}

$smarty->assign('page_title', 'Przykład 04');
$smarty->assign('page_subtitle', 'Profesjonalne szablonowanie oparte na bibliotece Smarty');

$smarty->assign('kwota', $kwota);
$smarty->assign('lata', $lata);
$smarty->assign('oprocentowanie', $oprocentowanie);
$smarty->assign('messages', $messages);
$smarty->assign('rata', $rata);

$smarty->display('kredyt.tpl');
