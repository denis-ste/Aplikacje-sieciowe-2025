<?php

namespace app\controllers;

use core\Messages;
use app\forms\KredytForm;
use app\results\KredytResult;

/**
 * KredytCtrl
 * -----------------------------------------------------------------------------
 * Kontroler kalkulatora kredytowego.
 *
 * Wejście użytkownika (formularz):
 *  - kwota
 *  - lata
 *  - oprocentowanie
 *
 * Wyjście do widoku:
 *  - msgs (Messages)
 *  - form (KredytForm)
 *  - res  (KredytResult)
 *
 * Ważne:
 *  - logika ograniczeń ról i walidacji jest zachowana z wcześniejszych etapów
 *    (nie zmieniamy obliczeń – tylko dokumentujemy)
 */
class KredytCtrl {

    private $msgs;
    private $form;
    private $res;

    // Zachowujemy dotychczasową logikę ograniczeń ról z projektu bazowego
    private const MAX_KWOTA_USER   = 100000;
    private const MIN_APR_MANAGER  = 3.00;

    public function __construct() {
        // Tu celowo tworzymy lokalny Messages – kontroler wyświetla go w swoim widoku.
        // (Komunikaty z guarda/routera idą przez globalne getMessages(), ale to inny kanał.)
        $this->msgs = new Messages();
        $this->form = new KredytForm();
        $this->res  = new KredytResult();
    }

    /** Pobranie parametrów z żądania */
    private function getParams() {
        $this->form->kwota          = $_REQUEST['kwota'] ?? '';
        $this->form->lata           = $_REQUEST['lata'] ?? '';
        $this->form->oprocentowanie = $_REQUEST['oprocentowanie'] ?? '';
    }

    /** Walidacja danych */
    private function validate() {
        // Pierwszy widok: bez komunikatów, bez obliczeń
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }

        if ($this->form->kwota === '' || trim((string)$this->form->kwota) === '') {
            $this->msgs->addError('Nie podano kwoty');
        }
        if ($this->form->lata === '' || trim((string)$this->form->lata) === '') {
            $this->msgs->addError('Nie podano liczby lat');
        }
        if ($this->form->oprocentowanie === '' || trim((string)$this->form->oprocentowanie) === '') {
            $this->msgs->addError('Nie podano oprocentowania');
        }

        if ($this->msgs->isError()) return false;

        if (filter_var($this->form->kwota, FILTER_VALIDATE_FLOAT) === false) {
            $this->msgs->addError('Kwota nie jest liczbą');
        }
        if (filter_var($this->form->lata, FILTER_VALIDATE_INT) === false) {
            $this->msgs->addError('Lata nie są liczbą całkowitą');
        }
        if (filter_var($this->form->oprocentowanie, FILTER_VALIDATE_FLOAT) === false) {
            $this->msgs->addError('Oprocentowanie nie jest liczbą');
        }

        // Zasady ograniczeń ról (zachowane):
        //  - user: maksymalna kwota kredytu
        //  - manager: minimalne oprocentowanie
        $role = $_SESSION['role'] ?? '';
        if ($role === 'user') {
            $kwota = (float)$this->form->kwota;
            if ($kwota > self::MAX_KWOTA_USER) {
                $this->msgs->addError('Dla roli user maksymalna kwota to '.self::MAX_KWOTA_USER);
            }
        }
        if ($role === 'manager') {
            $apr = (float)$this->form->oprocentowanie;
            if ($apr < self::MIN_APR_MANAGER) {
                $this->msgs->addError('Dla roli manager minimalne oprocentowanie to '.self::MIN_APR_MANAGER);
            }
        }

        return !$this->msgs->isError();
    }

    /** Wykonanie obliczeń */
    private function doCalc() {
        $kwota = (float)$this->form->kwota;
        $lata  = (int)$this->form->lata;
        $apr   = (float)$this->form->oprocentowanie;

        // Prosty wzór raty miesięcznej (annuitet):
        //   r = apr/100/12
        //   n = lata*12
        //   rata = kwota * r * (1+r)^n / ((1+r)^n - 1)
        $r = $apr / 100.0 / 12.0;
        $n = $lata * 12;

        if ($r == 0.0) {
            $this->res->rata = $kwota / $n;
        } else {
            $pow = pow(1 + $r, $n);
            $this->res->rata = $kwota * $r * $pow / ($pow - 1);
        }
    }

    /** Wyświetlenie widoku */
    private function generateView() {
        $smarty = \get_smarty();

        $smarty->assign('page_title', 'Kalkulator kredytowy');
        $smarty->assign('msgs', $this->msgs);
        $smarty->assign('form', $this->form);
        $smarty->assign('res', $this->res);

        $smarty->display('KredytView.tpl');
    }

    /** Metoda routowana przez Router: action=credit */
    public function process() {
        $this->getParams();

        if ($this->validate()) {
            $this->doCalc();
        }

        $this->generateView();
    }
}
