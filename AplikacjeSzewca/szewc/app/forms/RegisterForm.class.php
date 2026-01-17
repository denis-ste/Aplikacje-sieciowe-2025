<?php

namespace app\forms;

use core\ParamUtils;
use core\Message;
use core\App;

class RegisterForm {
    public $login;
    public $email;
    public $password;
    public $passwordRepeat;

    public function loadFromRequest() {
        $this->login = trim(ParamUtils::getFromRequest('login'));
        $this->email = trim(ParamUtils::getFromRequest('email'));
        $this->password = ParamUtils::getFromRequest('password');
        $this->passwordRepeat = ParamUtils::getFromRequest('password_repeat');
    }

    public function validate() {
        if (empty($this->login)) {
            App::getMessages()->addMessage(
                new Message('Login jest wymagany', Message::ERROR)
            );
        }
        if (empty($this->email)) {
            App::getMessages()->addMessage(
                new Message('Email jest wymagany', Message::ERROR)
            );
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            App::getMessages()->addMessage(
                new Message('Niepoprawny email', Message::ERROR)
            );
        }
        if (empty($this->password)) {
            App::getMessages()->addMessage(
                new Message('Hasło jest wymagane', Message::ERROR)
            );
        }
        if ($this->password !== $this->passwordRepeat) {
            App::getMessages()->addMessage(
                new Message('Hasła nie są identyczne', Message::ERROR)
            );
        }

        return !App::getMessages()->isError();
    }
}
