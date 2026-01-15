<?php

namespace core;

/**
 * Obiekt konfiguracji.
 */
class Config {
    public $root_path;
    public $server_name;
    public $server_url;
    public $app_root;
    public $app_url;
    public $action_root;
    public $action_url;

    /**
     * Nazwa akcji logowania (fallback przy braku uprawnień).
     * Ustawiane w ctrl.php.
     */
    public $login_action;

    /**
     * Role użytkownika wczytane z sesji (mapa: rola => true).
     */
    public $roles;
}
