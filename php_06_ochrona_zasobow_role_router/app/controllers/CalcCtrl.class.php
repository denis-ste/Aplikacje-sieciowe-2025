<?php

namespace app\controllers;

use core\Messages;
use app\forms\CalcForm;
use app\results\CalcResult;

/**
 * CalcCtrl
 * -----------------------------------------------------------------------------
 * Kontroler „zwykłego” kalkulatora.
 *
 * Wejście użytkownika:
 *  - POST: liczba1, liczba2, operacja (dla kompatybilności także x, y, op)
 *
 * Wyjście:
 *  - widok Smarty: CalcView.tpl
 *
 * Uwaga dot. inicjalizacji:
 *  - config.php / init.php są ładowane przez ctrl.php, więc ten kontroler nie robi już include'ów.
 *
 * WAŻNE: Nie zmieniamy logiki obliczeń – tylko komentujemy i porządkujemy czytelność.
 */
class CalcCtrl {

    /** @var Messages Komunikaty walidacji/guardów dla tego kontrolera */
    private $msgs;
    /** @var CalcForm Dane wejściowe formularza */
    private $form;
    /** @var CalcResult Wynik obliczeń */
    private $res;

    public function __construct() {
        // Uwaga: tu używamy niezależnego obiektu Messages (historycznie tak było w tym projekcie).
        // Mechanizm globalnych Messages (getMessages()) nadal istnieje dla komunikatów z guarda/routera.
        $this->msgs = new Messages();
        $this->form = new CalcForm();
        $this->res  = new CalcResult();
    }

    /**
     * Pobranie parametrów z requestu.
     *
     * UI wysyła: liczba1, liczba2, operacja
     * Zostawiamy też stare nazwy: x, y, op
     */
    private function getParams() {
        $this->form->x  = $_REQUEST['liczba1'] ?? ($_REQUEST['x'] ?? null);
        $this->form->y  = $_REQUEST['liczba2'] ?? ($_REQUEST['y'] ?? null);
        $this->form->op = $_REQUEST['operacja'] ?? ($_REQUEST['op'] ?? null);

        if ($this->form->op === null || $this->form->op === '') {
            $this->form->op = 'plus';
        }
    }

    /** Walidacja danych wejściowych (zachowana 1:1) */
    private function validate() {
        // Pierwszy widok: bez komunikatów, bez obliczeń
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }

        $x  = $this->form->x;
        $y  = $this->form->y;
        $op = $this->form->op;

        if ($x === null || trim((string)$x) === '') $this->msgs->addError('Nie podano liczby 1');
        if ($y === null || trim((string)$y) === '') $this->msgs->addError('Nie podano liczby 2');

        if ($this->msgs->isError()) return false;

        if (filter_var($x, FILTER_VALIDATE_INT) === false) {
            $this->msgs->addError('Pierwsza wartość nie jest liczbą całkowitą');
        }
        if (filter_var($y, FILTER_VALIDATE_INT) === false) {
            $this->msgs->addError('Druga wartość nie jest liczbą całkowitą');
        }

        // Dozwolone operacje (default = plus)
        $allowed = array('plus', 'minus', 'times', 'div');
        if (!in_array($op, $allowed, true)) {
            $this->form->op = 'plus';
        }

        // Dzielenie przez 0
        if ($this->form->op === 'div' && filter_var($y, FILTER_VALIDATE_INT) !== false) {
            if ((int)$y === 0) $this->msgs->addError('Nie wolno dzielić przez 0');
        }

        return !$this->msgs->isError();
    }

    /** Wykonanie obliczeń (zachowane 1:1) */
    private function doCalc() {
        // Jawna konwersja na typy liczbowe PRZED obliczeniami
        $x  = (int)$this->form->x;
        $y  = (int)$this->form->y;
        $op = (string)$this->form->op;

        $role    = $_SESSION['role'] ?? '';
        $isAdmin = ($role === 'admin');

        // Blokada operacji dla roli user ("-" oraz dzielenie) - zachowujemy istniejącą logikę
        if (!$isAdmin && ($op === 'minus' || $op === 'div')) {
            $this->msgs->addError('Tylko administrator może wykonywać tę operację.');
            return;
        }

        switch ($op) {
            case 'minus':
                $this->res->op_name = 'Odejmowanie';
                $this->res->result  = $x - $y;
                break;
            case 'times':
                $this->res->op_name = 'Mnożenie';
                $this->res->result  = $x * $y;
                break;
            case 'div':
                $this->res->op_name = 'Dzielenie';
                $this->res->result  = $x / $y;
                break;
            case 'plus':
            default:
                $this->res->op_name = 'Dodawanie';
                $this->res->result  = $x + $y;
                break;
        }
    }

    /** Render widoku Smarty */
    private function generateView() {
        $smarty = \get_smarty();

        $smarty->assign('page_title', 'Zwykły kalkulator');
        $smarty->assign('msgs', $this->msgs);
        $smarty->assign('form', $this->form);
        $smarty->assign('res', $this->res);

        $smarty->display('CalcView.tpl');
    }

    /** Metoda routowana przez Router: action=calc */
    public function process() {
        $this->getParams();

        if ($this->validate()) {
            $this->doCalc();
        }

        $this->generateView();
    }
}
