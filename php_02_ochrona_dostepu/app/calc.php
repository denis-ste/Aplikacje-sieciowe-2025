<?php
require_once dirname(__FILE__).'/../config.php';

// 1. pobranie parametrów
$x = $_REQUEST['x'] ?? null;
$y = $_REQUEST['y'] ?? null;
$operation = $_REQUEST['op'] ?? null;

// zainicjuj zmienne widoku
$messages = [];
$result = null;

// 2. walidacja tylko po POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($x, $y, $operation)) {
        $messages[] = 'Błędne wywołanie aplikacji. Brak jednego z parametrów.';
    } else {
        if ($x === '') $messages[] = 'Nie podano liczby 1';
        if ($y === '') $messages[] = 'Nie podano liczby 2';

        if (empty($messages)) {
            if (!is_numeric($x)) $messages[] = 'Pierwsza wartość nie jest liczbą';
            if (!is_numeric($y)) $messages[] = 'Druga wartość nie jest liczbą';
        }
    }

    if (empty($messages)) {
        $x = floatval($x);
        $y = floatval($y);

        switch ($operation) {
            case 'minus': $result = $x - $y; break;
            case 'times': $result = $x * $y; break;
            case 'div':
                if ($y == 0) $messages[] = 'Nie wolno dzielić przez zero!';
                else $result = $x / $y;
                break;
            case 'plus':
            default: $result = $x + $y; break;
        }
    }
}

// 4. widok
include 'calc_view.php';
