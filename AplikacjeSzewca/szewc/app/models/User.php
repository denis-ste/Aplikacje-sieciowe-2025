<?php

namespace app\models;

class User {

    public string $login;
    public string $role;

    public function __construct(string $login, string $role) {
        $this->login = $login;
        $this->role  = $role;
    }
}

