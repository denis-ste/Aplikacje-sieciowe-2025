<?php

namespace app\results;

/**
 * Prosty obiekt użytkownika (wzorzec z php_07), trzymany opcjonalnie w sesji.
 */
class User {
    public $login;
    public $role;

    public function __construct(string $login = '', string $role = '') {
        $this->login = $login;
        $this->role = $role;
    }
}
