<?php
// config.php jest już dołączany w pliku uruchomieniowym (app/kredyt.php).

require_once _ROOT_PATH . '/app/smarty_init.php';
require_once _ROOT_PATH . '/lib/Messages.class.php';
require_once _ROOT_PATH . '/app/KredytForm.class.php';
require_once _ROOT_PATH . '/app/KredytResult.class.php';

class KredytCtrl {

    private $msgs;
    private $form;
    private $res;

    // Zachowujemy dotychczasową logikę ograniczeń ról z projektu bazowego
    private const MAX_KWOTA_USER   = 100000;
    private const MIN_APR_MANAGER  = 3.00;

    public function __construct() {
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

        $kwota = $this->form->kwota;
        $lata  = $this->form->lata;
        $apr   = $this->form->oprocentowanie;

        if (trim((string)$kwota) === '') $this->msgs->addError('Nie podano kwoty kredytu.');
        if (trim((string)$lata)  === '') $this->msgs->addError('Nie podano liczby lat.');
        if (trim((string)$apr)   === '') $this->msgs->addError('Nie podano oprocentowania.');

        if ($this->msgs->isError()) return false;

        // kwota: float > 0
        if (filter_var($kwota, FILTER_VALIDATE_FLOAT) === false || (float)$kwota <= 0) {
            $this->msgs->addError('Kwota musi być dodatnią liczbą.');
        }

        // lata: int 1..50 (jak w wersji bazowej)
        if (filter_var($lata, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => 50))) === false) {
            $this->msgs->addError('Liczba lat musi być liczbą całkowitą (1–50).');
        }

        // oprocentowanie: float 0..50
        if (filter_var($apr, FILTER_VALIDATE_FLOAT) === false) {
            $this->msgs->addError('Oprocentowanie musi być liczbą.');
        } else {
            $aprFloat = (float)$apr;
            if ($aprFloat < 0 || $aprFloat > 50) {
                $this->msgs->addError('Oprocentowanie musi być w zakresie 0–50%.');
            }
        }

        if ($this->msgs->isError()) return false;

        // Ograniczenia zależne od roli (zachowujemy dotychczasową funkcjonalność)
        $role = $_SESSION['role'] ?? 'user';

        $kwotaFloat = (float)$kwota;
        $aprFloat   = (float)$apr;

        if ($role !== 'manager') {
            if ($kwotaFloat > self::MAX_KWOTA_USER) {
                $this->msgs->addError('Kwota > ' . self::MAX_KWOTA_USER . ' zł: obliczenia może wykonać wyłącznie manager.');
            }
            if ($aprFloat < self::MIN_APR_MANAGER) {
                $this->msgs->addError('Oprocentowanie < ' . number_format(self::MIN_APR_MANAGER, 2) . '%: obliczenia może wykonać wyłącznie manager.');
            }
        }

        return !$this->msgs->isError();
    }

    /** Wykonanie obliczeń */
    private function doCalc() {
        // Jawne rzutowania PRZED obliczeniami
        $kwota = (float)$this->form->kwota;
        $lata  = (int)$this->form->lata;
        $apr   = (float)$this->form->oprocentowanie;

        $n = $lata * 12;
        $r = $apr / 100.0 / 12.0;

        if ($n <= 0) {
            $this->msgs->addError('Nieprawidłowy okres kredytowania.');
            return;
        }

        if ($r > 0) {
            $pow = pow(1 + $r, $n);
            // rata annuitetowa
            $this->res->rata = $kwota * ($r * $pow) / ($pow - 1);
        } else {
            // 0% APR: prosto w ratach
            $this->res->rata = $kwota / $n;
        }
    }

    /** Wyświetlenie widoku */
    private function generateView() {
        $smarty = get_smarty();

        $smarty->assign('page_title', 'Kalkulator kredytowy');
        $smarty->assign('msgs', $this->msgs);
        $smarty->assign('form', $this->form);
        $smarty->assign('res', $this->res);

        $smarty->display('KredytView.tpl');
    }

    /** Metoda główna */
    public function process() {
        $this->getParams();

        if ($this->validate()) {
            $this->doCalc();
        }

        $this->generateView();
    }
}
