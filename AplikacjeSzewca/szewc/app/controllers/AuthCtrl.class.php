<?php

namespace app\controllers;

use core\App;
use core\Message;
use core\RoleUtils;
use app\forms\LoginForm;
use app\forms\RegisterForm;

class AuthCtrl {

    private LoginForm $form;
    private RegisterForm $registerForm;

    public function __construct() {
        $this->form = new LoginForm();
        $this->registerForm = new RegisterForm();
    }

    private function redirectLoggedUser(): void {
        if (RoleUtils::inRole('ADMIN')) {
            App::getRouter()->redirectTo('admin_services');
            return;
        }
        if (RoleUtils::inRole('WORKER')) {
            App::getRouter()->redirectTo('worker_orders');
            return;
        }
        if (RoleUtils::inRole('CLIENT')) {
            App::getRouter()->redirectTo('client_dashboard');
            return;
        }
        App::getRouter()->redirectTo('main');
    }

    public function action_login(): void {

        if (!empty(App::getConf()->roles)) {
            $this->redirectLoggedUser();
            return;
        }

        if (!isset($_POST['login'])) {
            $this->generateLoginView();
            return;
        }

        $this->form->load();

        if (!$this->form->validate()) {
            $this->generateLoginView();
            return;
        }

        try {
            $user = App::getDB()->get(
                'users',
                ['id', 'username', 'password_hash', 'role_id', 'is_active'],
                ['username' => $this->form->login]
            );

            if (!$user || $user['is_active'] !== 'Y') {
                App::getMessages()->addMessage(new Message('Niepoprawny login lub konto nieaktywne', Message::ERROR));
                $this->generateLoginView();
                return;
            }

            if (!password_verify($this->form->pass, $user['password_hash'])) {
                App::getMessages()->addMessage(new Message('Niepoprawny login lub hasło', Message::ERROR));
                $this->generateLoginView();
                return;
            }

            $roleName = App::getDB()->get(
                'roles',
                'name',
                ['id' => $user['role_id'], 'is_active' => 'Y']
            );

            if (!$roleName) {
                App::getMessages()->addMessage(new Message('Rola użytkownika jest nieaktywna', Message::ERROR));
                $this->generateLoginView();
                return;
            }

            RoleUtils::addRole($roleName);
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['user_login'] = $user['username'];

            $this->redirectLoggedUser();

        } catch (\PDOException $e) {
            App::getMessages()->addMessage(new Message('Błąd bazy danych', Message::ERROR));
            $this->generateLoginView();
        }
    }

    public function action_register(): void {

        if (!isset($_POST['login'])) {
            $this->generateRegisterView();
            return;
        }

        $this->registerForm->loadFromRequest();

        if (!$this->registerForm->validate()) {
            $this->generateRegisterView();
            return;
        }

        // unikalność
        $exists = App::getDB()->has('users', [
            'OR' => [
                'username' => $this->registerForm->login,
                'email' => $this->registerForm->email
            ]
        ]);

        if ($exists) {
            App::getMessages()->addMessage(new Message('Login lub email już istnieje', Message::ERROR));
            $this->generateRegisterView();
            return;
        }

        // rola CLIENT
        $clientRoleId = App::getDB()->get('roles', 'id', ['name' => 'CLIENT']);
        if (!$clientRoleId) {
            App::getMessages()->addMessage(new Message('Brak roli CLIENT w bazie (seed)', Message::ERROR));
            $this->generateRegisterView();
            return;
        }

        $hash = password_hash($this->registerForm->password, PASSWORD_DEFAULT);

        App::getDB()->insert('users', [
            'username' => $this->registerForm->login,
            'email' => $this->registerForm->email,
            'password_hash' => $hash,
            'role_id' => $clientRoleId,
            'is_active' => 'Y'
        ]);

        App::getMessages()->addMessage(new Message('Rejestracja OK – możesz się zalogować', Message::INFO));
        App::getRouter()->redirectTo('login');
    }

    public function action_logout(): void {
        App::getConf()->roles = [];
        unset($_SESSION['_amelia_roles']);
        session_destroy();
        App::getRouter()->redirectTo('main');
    }

    private function generateLoginView(): void {
        App::getSmarty()->assign('form', $this->form);
        App::getSmarty()->display('Login.tpl');
    }

    private function generateRegisterView(): void {
        App::getSmarty()->assign('form', $this->registerForm);
        App::getSmarty()->display('Rejestracja.tpl');
    }
}
