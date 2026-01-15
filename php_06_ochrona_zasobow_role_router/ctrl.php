<?php
require_once __DIR__ . '/init.php';

/**
 * ctrl.php (Front Controller)
 * -----------------------------------------------------------------------------
 * JEDYNY punkt wejścia aplikacji (poza publicznymi zasobami CSS/IMG/JS).
 *
 * Dlaczego to jest ważne?
 *  - użytkownik nie powinien wywoływać bezpośrednio plików w /app oraz /core
 *    (blokuje to .htaccess)
 *  - cała kontrola dostępu (logowanie/role) i routing są w tym jednym miejscu
 *
 * Przepływ żądania:
 *   przeglądarka -> index.php -> ctrl.php -> Router::go() -> Ctrl->metoda -> Smarty
 */

// Akcja logowania na potrzeby helperów (np. redirectToLogin w LoginCtrl)
// (nie zmieniamy nazewnictwa - tylko porządkujemy w jednym miejscu)
getConf()->login_action = 'login';

// --- WYZNACZENIE AKCJI ---
// Default zależy od tego, czy użytkownik jest zalogowany.
// Jeżeli ktoś wejdzie bez action, to:
//  - niezalogowany zobaczy stronę startową
//  - zalogowany trafi na panel/home
if ($action === null || $action === '') {
    $action = is_logged_in() ? 'home' : 'start';
}

// --- KOMPATYBILNOŚĆ ---
// Wcześniejsze etapy mogły używać akcji "credit" – mapujemy ją na obecną "kredyt".
if ($action === 'credit') {
    $action = 'kredyt';
}

// --- KONFIGURACJA ROUTERA ---
$router = getRouter();
$router->setAction($action);

// Gdzie router ma wysyłać przy braku uprawnień (forwardTo('login'))
$router->setLoginRoute('login');

// Bezpieczny fallback, gdy action jest pusta lub nieznana
$router->setDefaultRoute(is_logged_in() ? 'home' : 'start');

// Role „zalogowane” (lista pozostaje jak w projekcie – nie zmieniamy polityk)
$rolesLogged = ['user', 'admin', 'manager'];

/**
 * REJESTR TRAS: action -> (kontroler, metoda, role)
 *
 * Zasada:
 *  - public: roles = null
 *  - chronione: roles = lista ról, które mają mieć dostęp
 */

// Publiczne (dostęp bez logowania)
$router->addRouteEx('start',  'app\\controllers', 'StartCtrl', 'process');
$router->addRouteEx('login',  'app\\controllers', 'LoginCtrl', 'doLogin');

// Chronione (wymaga roli w sesji)
$router->addRouteEx('logout', 'app\\controllers', 'LoginCtrl',  'doLogout', $rolesLogged);
$router->addRouteEx('home',   'app\\controllers', 'HomeCtrl',   'process',  $rolesLogged);
$router->addRouteEx('calc',   'app\\controllers', 'CalcCtrl',   'process',  $rolesLogged);
$router->addRouteEx('kredyt', 'app\\controllers', 'KredytCtrl', 'process',  $rolesLogged);
$router->addRouteEx('karta',  'app\\controllers', 'KartaCtrl',  'process',  $rolesLogged);

// Start aplikacji – Router uruchamia dokładnie jedną trasę i kończy skrypt.
$router->go();
