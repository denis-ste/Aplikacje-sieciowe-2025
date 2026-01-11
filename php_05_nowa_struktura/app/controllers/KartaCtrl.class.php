<?php

namespace app\controllers;

/**
 * Kontroler karty chronionej.
 * Zachowuje dotychczasową logikę: dostęp (admin/manager), user widzi komunikat.
 */
class KartaCtrl {

    public function process(): void {
        $role = $_SESSION['role'] ?? '';
        $isAdmin = in_array($role, ['admin','manager'], true);

        $smarty = \get_smarty();
        $smarty->assign('page_title', 'Karta chroniona');
        $smarty->assign('is_admin', $isAdmin);

        if (!$isAdmin) {
            $smarty->assign('messages', ['Nie masz uprawnień do tej strony (tylko admin).']);
        } else {
            $smarty->assign('messages', []);
        }

        $smarty->display('karta_chroniona.tpl');
    }
}
