<?php
// Punkt wejścia: kalkulator zwykły (bez logiki obliczeń w tym pliku)

require_once __DIR__ . '/../config.php';

// Ochrona (redirect do logowania gdy brak sesji)
require_once _ROOT_PATH . '/app/security/check.php';

// Kontroler
require_once _ROOT_PATH . '/app/CalcCtrl.class.php';

$ctrl = new CalcCtrl();
$ctrl->process();
