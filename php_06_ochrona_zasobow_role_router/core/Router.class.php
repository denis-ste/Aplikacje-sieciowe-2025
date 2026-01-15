<?php

namespace core;

use Exception;

/**
 * Router
 * -----------------------------------------------------------------------------
 * Obiektowy odpowiednik wcześniejszej funkcji „dispatch/autoCall/control()”.
 *
 * Cel: zachować identyczną ideę działania, ale w formie czytelnej i rozwojowej.
 *
 *  action -> kontroler -> metoda -> (kontroler sam renderuje widok w Smarty)
 *
 * Główne cechy:
 *  - whitelist (biała lista) akcji: uruchamiamy TYLKO trasy zarejestrowane w Routerze
 *  - centralna kontrola ról (per-trasa): zanim wywołamy kontroler, sprawdzamy uprawnienia
 *  - bezpieczny fallback (default route): gdy action jest pusta lub błędna, odpalamy trasę domyślną
 *
 * Ważne:
 *  - Router nie blokuje zasobów publicznych (CSS/IMG/JS). To robi .htaccess.
 *  - Router dotyczy wyłącznie wejścia aplikacji przez ctrl.php.
 */
class Router {

    /**
     * Wybrana akcja (np. "home", "calc", "kredyt").
     * Ustawiana w init.php (z requestu) i może być nadpisana/aliasowana w ctrl.php.
     *
     * @var string|null
     */
    public $action = null;

    /**
     * Zarejestrowane trasy: action => Route
     *
     * @var array<string, Route>
     */
    public $routes = [];

    /** @var string|null action domyślna (fallback) */
    private $default = null;

    /** @var string action logowania (gdzie forwardujemy, gdy brak uprawnień) */
    private $login = 'login';

    /**
     * Ustaw akcję (zwykle: z GET/POST param "action").
     */
    public function setAction($action): void {
        $this->action = ($action === null || $action === '') ? null : (string)$action;
    }

    public function getAction(): ?string {
        return $this->action;
    }

    /**
     * Dodaje trasę w formie jawnej (pełna kontrola):
     *
     * @param string $action     np. 'home'
     * @param string|null $namespace np. 'app\\controllers' (lub null -> domyślny app\\controllers)
     * @param string $controller np. 'HomeCtrl'
     * @param string $method     np. 'process'
     * @param array|string|null $roles
     *   - null: trasa publiczna (bez logowania)
     *   - string: jedna rola (np. 'admin')
     *   - array: lista ról (np. ['user','admin'])
     */
    public function addRouteEx(string $action, ?string $namespace, string $controller, string $method, $roles = null): void {
        $this->routes[$action] = new Route($namespace, $controller, $method, $roles);
    }

    /**
     * Dodaje trasę z konwencją nazwy metody: action_<action>.
     * Przykład:
     *   addRoute('login','LoginCtrl') -> wywoła LoginCtrl::action_login().
     */
    public function addRoute(string $action, string $controller, $roles = null): void {
        $this->routes[$action] = new Route(null, $controller, 'action_' . $action, $roles);
    }

    /** Ustaw trasę domyślną (fallback), np. 'start' lub 'home'. */
    public function setDefaultRoute(string $route): void {
        $this->default = $route;
    }

    /** Ustaw trasę logowania, gdzie forwardujemy przy braku uprawnień. */
    public function setLoginRoute(string $route): void {
        $this->login = $route;
    }

    /**
     * Sprawdza, czy aktualny użytkownik ma JAKĄKOLWIEK z wymaganych ról.
     *
     * Uwaga: inRole(...) jest helperem z core/functions.php.
     */
    private function hasAnyAllowedRole($roles): bool {
        if ($roles === null) {
            return true; // trasa publiczna
        }

        if (is_array($roles)) {
            foreach ($roles as $role) {
                if (inRole((string)$role)) {
                    return true;
                }
            }
            return false;
        }

        return inRole((string)$roles);
    }

    /**
     * Wykonuje pojedynczą trasę:
     *  1) sprawdza role (jeśli trasa chroniona)
     *  2) buduje nazwę klasy kontrolera (FQCN)
     *  3) tworzy obiekt kontrolera i wywołuje metodę
     *  4) kończy działanie skryptu (exit) – jedna prośba = jedna trasa
     */
    private function runRoute(Route $r): void {
        // --- ROLE CHECK (CENTRALNIE) ---
        if (!$this->hasAnyAllowedRole($r->roles)) {
            // Zachowujemy dotychczasowe zachowanie: komunikat + forward do login.
            // return=... pozwala potencjalnie wrócić po zalogowaniu.
            $return = $_SERVER['REQUEST_URI'] ?? buildActionUrl('home');
            getMessages()->addInfo('Zaloguj się, aby uzyskać dostęp do tej strony.');
            forwardTo($this->login, ['return' => $return]);
        }

        // --- BUDOWA NAZWY KLASY KONTROLERA ---
        // Jeśli namespace niepodany, przyjmujemy konwencję: app\controllers\<Ctrl>
        if (empty($r->namespace)) {
            $fqcn = 'app\\controllers\\' . $r->controller;
        } else {
            $fqcn = rtrim($r->namespace, '\\') . '\\' . $r->controller;
        }

        // --- WYWOŁANIE KONTROLERA ---
        $ctrl = new $fqcn();
        if (!method_exists($ctrl, $r->method)) {
            throw new Exception('Method "' . $r->method . '" does not exist in "' . $fqcn . '"');
        }

        $method = $r->method;
        $ctrl->$method();

        // Każda trasa kończy request – nie dopuszczamy do „dalszego wykonywania”.
        exit;
    }

    /**
     * Uruchom routing.
     *
     * Zachowanie:
     *  - jeżeli action pasuje do zarejestrowanej trasy -> uruchom ją
     *  - w przeciwnym wypadku -> uruchom default route (jeśli skonfigurowana)
     *  - jeśli nie ma default -> wyjątek (błąd konfiguracji)
     */
    public function go(): void {
        $action = $this->action;

        if ($action !== null && isset($this->routes[$action])) {
            $this->runRoute($this->routes[$action]);
        }

        if ($this->default !== null && isset($this->routes[$this->default])) {
            $this->runRoute($this->routes[$this->default]);
        }

        throw new Exception('Route for "' . (string)$action . '" is not defined');
    }
}
