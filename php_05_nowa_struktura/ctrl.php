<?php
require_once __DIR__ . '/init.php';

// Skrypt kontrolera głównego jako jedyny "punkt wejścia" inicjuje aplikację.

/**
 * Centralna ochrona dostępu – w jednym miejscu.
 * Nie zmieniamy zasad dostępu: wymagamy zalogowania dla akcji aplikacyjnych.
 */
function require_login_or_redirect(): void {
    if (!empty($_SESSION['role'])) return;

    $return = $_SERVER['REQUEST_URI'] ?? (getConf()->app_url . '/');
    redirect(getConf()->app_url . '/?action=login&return=' . urlencode($return));
}

// Default zależny od sesji
if ($action === null || $action === '') {
    $action = !empty($_SESSION['role']) ? 'home' : 'start';
}

// Whitelist przez switch
switch ($action) {
    default:
    case 'start': {
        if (!empty($_SESSION['role'])) {
            redirect(getConf()->app_url . '/?action=home');
        }

        $smarty = getSmarty();
        $smarty->assign('page_title', 'Welcome!');
        $smarty->assign('page_subtitle', 'Witaj w systemie kalkulatorów');
        $smarty->display('welcome.tpl');
        break;
    }

    case 'home': {
        require_login_or_redirect();
        $ctrl = new app\controllers\HomeCtrl();
        $ctrl->process();
        break;
    }

    case 'calc': {
        require_login_or_redirect();
        $ctrl = new app\controllers\CalcCtrl();
        $ctrl->process();
        break;
    }

    case 'kredyt': {
        require_login_or_redirect();
        $ctrl = new app\controllers\KredytCtrl();
        $ctrl->process();
        break;
    }

    case 'karta': {
        require_login_or_redirect();
        $ctrl = new app\controllers\KartaCtrl();
        $ctrl->process();
        break;
    }

    case 'login': {
        // Akcja publiczna – logowanie
        include __DIR__ . '/app/security/login.php';
        break;
    }

    case 'logout': {
        include __DIR__ . '/app/security/logout.php';
        break;
    }
}
