<?php

namespace app\controllers;

use app\forms\LoginForm;
use app\results\User;

/**
 * LoginCtrl - obiektowy kontroler logowania (jak we wzorcu php_07)
 *
 * Wymagania:
 * - widok Smarty: LoginView.tpl
 * - logowanie/wylogowanie obsługiwane przez akcje w kontrolerze głównym
 * - sesja pozostaje kompatybilna z projektem (login/role w $_SESSION)
 */
class LoginCtrl {

    private $msgs;
    private $form;

    public function __construct() {
        // używamy wspólnego obiektu Messages z init.php (żeby komunikaty z guarda były widoczne)
        $this->msgs = \getMessages();
        $this->form = new LoginForm();
    }

    private function getParams(): void {
        $this->form->login = getFromRequest('login');
        $this->form->pass = getFromRequest('pass');
        $this->form->returnUrl = getFromRequest('return');

        // komunikat przekazany z guarda (redirect)
        $msg = getFromRequest('msg');
        if ($msg !== null && $msg !== '') {
            $this->msgs->addInfo($msg);
        }
    }

    private function sanitizeReturnUrl(string $url): string {
        $url = trim($url);
        if ($url === '') {
            return getConf()->app_url . '/?action=home';
        }

        // blokada zewnętrznych URL (open redirect)
        if (preg_match('#^https?://#i', $url)) {
            if (strpos($url, getConf()->app_url) !== 0) {
                return getConf()->app_url . '/?action=home';
            }
        }

        return $url;
    }

    private function validate(): bool {
        // walidujemy tylko przy POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }

        if ($this->form->login === null || trim((string)$this->form->login) === '') {
            $this->msgs->addError('Nie podano loginu.');
        }
        if ($this->form->pass === null || trim((string)$this->form->pass) === '') {
            $this->msgs->addError('Nie podano hasła.');
        }

        if ($this->msgs->isError()) {
            return false;
        }

        $login = (string)$this->form->login;
        $pass  = (string)$this->form->pass;

        // sprawdzenie par login/hasło - bez zmian funkcjonalnych
        $role = '';
        if ($login === 'admin' && $pass === 'admin') $role = 'admin';
        if ($login === 'user'  && $pass === 'user')  $role = 'user';

        if ($role === '') {
            $this->msgs->addError('Nieprawidłowy login lub hasło.');
            return false;
        }

        // ustaw sesję (role jak w php_07_role + kompatybilność ze starym 'role')
        $_SESSION['login'] = $login;
        resetRoles();
        addRole($role);

        // dodatkowo: obiekt użytkownika (wzorzec php_07) - nie jest wymagany przez resztę aplikacji
        $_SESSION['user'] = serialize(new User($login, $role));

        return true;
    }

    public function doLogin(): void {
        $this->getParams();

        // jeśli już zalogowany -> wróć na stronę docelową
        if (is_logged_in()) {
            $target = $this->sanitizeReturnUrl((string)($this->form->returnUrl ?? ''));
            redirect($target);
        }

        if ($this->validate()) {
            $target = $this->sanitizeReturnUrl((string)($this->form->returnUrl ?? ''));
            redirect($target);
        }

        $this->generateView();
    }

    public function doLogout(): void {
        // czyść sesję
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION = array();
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'], $params['secure'], $params['httponly']
            );
        }
        session_destroy();

        // przekieruj na login z komunikatem (bez dodatkowych mechanizmów flash)
        $msg = 'Poprawnie wylogowano z systemu.';
        redirect(getConf()->app_url . '/?action=login&msg=' . urlencode($msg));
    }

    private function generateView(): void {
        $smarty = \getSmarty();

        // domyślny return, gdy brak
        if ($this->form->returnUrl === null || $this->form->returnUrl === '') {
            $this->form->returnUrl = getConf()->app_url . '/?action=home';
        }

        $smarty->assign('page_title', 'Logowanie');
        $smarty->assign('msgs', $this->msgs);
        $smarty->assign('form', $this->form);
        $smarty->display('LoginView.tpl');
    }
}
