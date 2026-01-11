<?php
// Punkt wejścia: kalkulator kredytowy (bez logiki obliczeń w tym pliku)

require_once __DIR__ . '/../config.php';

// Ochrona (redirect do logowania gdy brak sesji)
require_once _ROOT_PATH . '/app/security/check.php';

// Kontroler
require_once _ROOT_PATH . '/app/KredytCtrl.class.php';

$ctrl = new KredytCtrl();
$ctrl->process();
