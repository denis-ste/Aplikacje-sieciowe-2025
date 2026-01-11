<?php

namespace app\controllers;

/**
 * Kontroler dla strony głównej (panel z menu).
 */
class HomeCtrl {

    public function process(): void {
        $smarty = \get_smarty();
        $smarty->assign('page_title', 'Witaj w systemie kalkulatorów!');
        $smarty->assign('page_subtitle', 'Wybierz rodzaj kalkulatora:');
        $smarty->display('panel.tpl');
    }
}
