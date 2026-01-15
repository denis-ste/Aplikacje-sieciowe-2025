<?php

namespace core;

/**
 * Route
 * -----------------------------------------------------------------------------
 * Prosty „DTO” (Data Transfer Object) opisujący jedną trasę routingu.
 *
 * Router przechowuje mapę:
 *   action (string) -> Route
 *
 * a Route mówi Routerowi:
 *   - jaki kontroler i metodę wywołać
 *   - jaka jest lista dozwolonych ról
 *
 * Uwaga:
 *  - roles = null => trasa publiczna (bez logowania)
 *  - roles = 'admin' lub ['user','admin'] => trasa chroniona
 */
class Route {
    /** @var string|null Namespace kontrolera, np. 'app\\controllers' */
    public $namespace = null;
    /** @var string Nazwa klasy kontrolera, np. 'HomeCtrl' */
    public $controller = '';
    /** @var string Nazwa metody w kontrolerze, np. 'process' */
    public $method = '';
    /** @var array|string|null Dozwolone role (null = public) */
    public $roles = null;

    /**
     * @param string|null $namespace
     * @param string $controller
     * @param string $method
     * @param array|string|null $roles
     */
    public function __construct($namespace, $controller, $method, $roles = null) {
        $this->namespace  = $namespace;
        $this->controller = $controller;
        $this->method     = $method;
        $this->roles      = $roles;
    }
}
