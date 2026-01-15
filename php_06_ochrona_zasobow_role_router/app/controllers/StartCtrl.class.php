<?php

namespace app\controllers;

/**
 * StartCtrl - ekran startowy (welcome) dla niezalogowanych.
 *
 * Przeniesienie logiki z wcześniejszego case 'start' w ctrl.php.
 */
class StartCtrl {

    public function process(): void {
        if (is_logged_in()) {
            redirectTo('home');
        }

        $smarty = \getSmarty();
        $smarty->assign('page_title', 'Welcome!');
        $smarty->assign('page_subtitle', 'Witaj w systemie kalkulatorów');
        $smarty->display('welcome.tpl');
    }
}
