<?php
require_once dirname(__FILE__).'/../config.php';
include _ROOT_PATH.'/app/security/check.php';

// 1) pobranie
$kwota = $_REQUEST['kwota'] ?? null;             // całkowita
$lata  = $_REQUEST['lata']  ?? null;             // całkowita
$oprocentowanie = $_REQUEST['oprocentowanie'] ?? null; // ułamek możliwy

$messages = [];
$rata = null;

// 2) walidacja po POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($kwota === '' || $lata === '' || $oprocentowanie === '') {
        if ($kwota === '') $messages[] = 'Nie podano kwoty kredytu.';
        if ($lata === '') $messages[] = 'Nie podano okresu (lata).';
        if ($oprocentowanie === '') $messages[] = 'Nie podano oprocentowania.';
    }

    if (empty($messages)) {
        if (filter_var($kwota, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1]]) === false)
            $messages[] = 'Kwota musi być dodatnią liczbą całkowitą.';
        if (filter_var($lata, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1]]) === false)
            $messages[] = 'Okres w latach musi być dodatnią liczbą całkowitą.';
        if (filter_var($oprocentowanie, FILTER_VALIDATE_FLOAT) === false)
            $messages[] = 'Oprocentowanie musi być liczbą.';
        elseif ((float)$oprocentowanie < 0 || (float)$oprocentowanie > 50)
            $messages[] = 'Oprocentowanie w przedziale 0–50%.';
    }

    // 3) zasady ról (user ograniczony, manager bez limitów)
    if (empty($messages)) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $role = $_SESSION['role'] ?? 'user';
        $k = (int)$kwota; $years = (int)$lata; $apr = (float)$oprocentowanie;

        if ($role === 'user') {
            if ($k > 100000)  $messages[] = 'Kwota > 100 000 wymaga uprawnień manager.';
            if ($years > 30)  $messages[] = 'Okres > 30 lat wymaga uprawnień manager.';
            if ($apr < 3.00)  $messages[] = 'Oprocentowanie < 3.00% wymaga uprawnień manager.';
        }

        if (empty($messages)) {
            $P = (float)$k;
            $n = $years * 12;
            $r = $apr / 100.0 / 12.0;

            if ($r > 0) $rata = $P * ($r * pow(1 + $r, $n)) / (pow(1 + $r, $n) - 1);
            else        $rata = $P / $n;
        }
    }
}

// 4) widok
include _ROOT_PATH.'/app/kredyt_view.php';
