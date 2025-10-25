<?php

require_once dirname(__FILE__) . '/../config.php';

$kwota          = $_REQUEST['kwota'] ?? null;
$lata           = $_REQUEST['lata'] ?? null;
$oprocentowanie = $_REQUEST['oprocentowanie'] ?? null;
$messages = [];

if (!isset($kwota, $lata, $oprocentowanie)) {
  $messages[] = 'Błędne wywołanie aplikacji. Brak parametrów.';
} else {
  if ($kwota === '')          $messages[] = 'Nie podano kwoty kredytu';
  if ($lata === '')           $messages[] = 'Nie podano liczby lat';
  if ($oprocentowanie === '') $messages[] = 'Nie podano oprocentowania';
}

if (empty($messages)) {
  if (!is_numeric($kwota) || (float)$kwota <= 0)       $messages[] = 'Kwota musi być dodatnią liczbą';
  if (!is_numeric($lata)  || (float)$lata  <= 0)       $messages[] = 'Liczba lat musi być dodatnią liczbą';
  if (!is_numeric($oprocentowanie))                    $messages[] = 'Oprocentowanie musi być liczbą';
  elseif ((float)$oprocentowanie < 0 || (float)$oprocentowanie > 50)
                                                       $messages[] = 'Oprocentowanie musi być w przedziale 0–50%';
}

if (empty($messages)) {
  $P = (float)$kwota;
  $n = (int)round(((float)$lata) * 12);
  $r = ((float)$oprocentowanie) / 100.0 / 12.0;

  if ($r > 0) {
    $rata = $P * ($r * pow(1 + $r, $n)) / (pow(1 + $r, $n) - 1);
  } else {
    $rata = $P / $n;
  }
}

include dirname(__FILE__) . '/kredyt_view.php';
