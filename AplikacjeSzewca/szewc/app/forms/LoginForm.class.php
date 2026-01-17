<?php

namespace app\forms;

use core\ParamUtils;
use core\Message;
use core\App;

class LoginForm {

    public $login;
    public $pass;

    // pobranie danych z requestu (POST/GET)
    public function load() {
        $this->login = ParamUtils::getFromRequest('login');
        $this->pass  = ParamUtils::getFromRequest('pass');
    }

    // walidacja formularza
    public function validate(): bool {

        if (empty($this->login)) {
            App::getMessages()->addMessage(
                new Message('Nie podano loginu', Message::ERROR)
            );
        }

        if (empty($this->pass)) {
            App::getMessages()->addMessage(
                new Message('Nie podano hasła', Message::ERROR)
            );
        }

        return !App::getMessages()->isError();
    }
}

