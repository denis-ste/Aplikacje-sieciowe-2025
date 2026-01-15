<?php
/**
 * init.php
 * -----------------------------------------------------------------------------
 * Jeden, centralny punkt inicjalizacji aplikacji (MVC + Router + role + Smarty).
 *
 * Ten plik NIE wykonuje routingu ani logiki biznesowej.
 * On tylko przygotowuje środowisko do działania:
 *
 * 1) Konfiguracja:
 *    - config.php ustawia stałe (_ROOT_PATH, _APP_URL, ...)
 *    - tutaj mapujemy je na obiekt core\Config (getConf())
 * 2) Sesja:
 *    - start sesji
 *    - role i login w sesji (kompatybilność z wcześniejszymi etapami)
 * 3) ClassLoader:
 *    - autoload klas wg namespace + konwencji plików "*.class.php"
 * 4) Messages:
 *    - wspólny obiekt komunikatów (info/error) używany w całej aplikacji
 * 5) Smarty:
 *    - inicjalizowane leniwie (dopiero gdy kontroler wywoła getSmarty())
 *
 * Uwaga: ten plik jest dołączany przez ctrl.php (front controller).
 */

require_once __DIR__ . '/core/Config.class.php';
$conf = new core\Config();

// config.php ustawia stałe (_ROOT_PATH, _APP_URL, ...). Zostawiamy ten mechanizm bez zmian.
require_once __DIR__ . '/config.php';

// Budowa obiektu konfiguracji na podstawie stałych (kompatybilność z przykładami z zajęć).
$conf->root_path   = defined('_ROOT_PATH') ? _ROOT_PATH : __DIR__;
$conf->server_name = defined('_SERVER_NAME') ? _SERVER_NAME : ($_SERVER['HTTP_HOST'] ?? 'localhost');
$conf->server_url  = defined('_SERVER_URL') ? _SERVER_URL : '';
$conf->app_root    = defined('_APP_ROOT') ? _APP_ROOT : '';
$conf->app_url     = defined('_APP_URL') ? _APP_URL : '';

// action_* w tym projekcie idzie przez front controller (index.php/ctrl.php), więc "root" akcji = app_root.
$conf->action_root = $conf->app_root;
$conf->action_url  = rtrim($conf->app_url, '/') . '/?action=';

function &getConf() {
	global $conf;
	return $conf;
}

// --- SESJA ---
// Role/login trzymamy w sesji, więc uruchamiamy ją możliwie wcześnie.
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

// --- ROLE (kompatybilność wsteczna) ---
//
// W tym projekcie rola jest przechowywana:
//  - w "nowym" stylu jako zserializowana tablica w $_SESSION['_roles']
//  - w "starym" stylu jako string w $_SESSION['role']
//
// Utrzymujemy oba, żeby nie psuć istniejących kontrolerów/templ.../helperów.
$conf->roles = isset($_SESSION['_roles']) ? @unserialize($_SESSION['_roles']) : array();
if (!is_array($conf->roles)) {
	$conf->roles = array();
}
if (empty($conf->roles) && !empty($_SESSION['role'])) {
	$conf->roles[(string)$_SESSION['role']] = true;
	$_SESSION['_roles'] = serialize($conf->roles);
}

// --- AUTOLOAD KLAS ---
//
// ClassLoader rejestruje spl_autoload_register i ładuje klasy na podstawie namespace:
// np. "app\controllers\HomeCtrl" -> /app/controllers/HomeCtrl.class.php
require_once __DIR__ . '/core/ClassLoader.class.php';
$cloader = new core\ClassLoader();

function &getLoader() {
	global $cloader;
	return $cloader;
}

// --- ROUTER ---
// Router jest obiektem (zamiast funkcji dispatch) i uruchamia kontroler na podstawie action.
require_once __DIR__ . '/core/Router.class.php';
$router = new core\Router();

function &getRouter(): core\Router {
	global $router;
	return $router;
}

// --- MESSAGES ---
// Uwaga: core\Messages jest ładowane autoloadem (plik /core/Messages.class.php).
$msgs = new core\Messages();

function &getMessages() {
	global $msgs;
	return $msgs;
}

// --- SMARTY (lazy init) ---
//
// Smarty jest tworzone dopiero w momencie, gdy kontroler wywoła getSmarty().
// Dzięki temu proste akcje (np. redirect) nie inicjalizują silnika szablonów.
$smarty = null;

function &getSmarty() {
	global $smarty;

	if (!isset($smarty)) {
		require_once getConf()->root_path . '/lib/smarty/libs/Smarty.class.php';

		// W zależności od wersji Smarty w projekcie może istnieć namespace Smarty\Smarty.
		if (class_exists('Smarty\\Smarty')) {
			$smarty = new \Smarty\Smarty();
		} else {
			$smarty = new \Smarty();
		}

		// Katalogi szablonów (templateDir)
		//
		// UWAGA "clean-up": w tej paczce usuwamy stare, nieużywane katalogi widoków.
		// Żeby nie powodować błędów na serwerze, dokładamy do Smarty tylko te katalogi,
		// które realnie istnieją.
		$dirs = [];
		$candidates = [
			getConf()->root_path . '/app/views',
			getConf()->root_path . '/app/views/templates',
			getConf()->root_path . '/templates_ui',
			getConf()->root_path . '/templates',
		];
		foreach ($candidates as $d) {
			if (is_dir($d)) {
				$dirs[] = $d;
			}
		}
		$smarty->setTemplateDir($dirs);

		// Katalogi robocze Smarty (generowane runtime)
		$compileDir = getConf()->root_path . '/templates_c';
		$cacheDir   = getConf()->root_path . '/cache';
		$configsDir = getConf()->root_path . '/configs';

		if (!is_dir($compileDir)) @mkdir($compileDir, 0777, true);
		if (!is_dir($cacheDir))   @mkdir($cacheDir, 0777, true);
		if (!is_dir($configsDir)) @mkdir($configsDir, 0777, true);

		$smarty->setCompileDir($compileDir);
		$smarty->setCacheDir($cacheDir);
		$smarty->setConfigDir($configsDir);
		$smarty->caching = false;

		// Dane globalne dostępne w widokach
		$smarty->assign('conf', getConf());
		$smarty->assign('msgs', getMessages());

		// Kompatybilność wsteczna w szablonach:
		$smarty->assign('app_url', getConf()->app_url);
		$smarty->assign('app_root', getConf()->app_root);

		// Proste flagi dla layoutu/nawigacji
		$smarty->assign('is_logged', !empty($_SESSION['role']));
		$smarty->assign('user_role', (string)($_SESSION['role'] ?? ''));
		$smarty->assign('user_login', (string)($_SESSION['login'] ?? ''));
	}

	return $smarty;
}

// Alias dla starszego kodu (część kontrolerów używała get_smarty()).
function &get_smarty() {
	return getSmarty();
}

// Funkcje pomocnicze (parametry, role, redirect/forward). Router korzysta z tych helperów.
require_once __DIR__ . '/core/functions.php';

// --- ACTION ---
// Wczytujemy action z żądania i ustawiamy ją w Routerze.
// ctrl.php może nadpisać (np. aliasy lub default zależny od sesji).
$action = getFromRequest('action');
getRouter()->setAction($action);
