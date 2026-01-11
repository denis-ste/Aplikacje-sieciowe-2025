<?php
// Inicjacja systemu:
//  - konfiguracja (obiekt Config)
//  - ClassLoader (spl_autoload_register)
//  - messages (obiekt Messages)
//  - Smarty (lazy init)
//  - funkcje pomocnicze

require_once __DIR__ . '/core/Config.class.php';
$conf = new core\Config();

// config.php ustawia stałe (_ROOT_PATH, _APP_URL, ...). Nie zmieniamy istniejącego mechanizmu.
require_once __DIR__ . '/config.php';

// Obiekt konfiguracji na podstawie stałych (dla kompatybilności z przykładami)
$conf->root_path   = defined('_ROOT_PATH') ? _ROOT_PATH : __DIR__;
$conf->server_name = defined('_SERVER_NAME') ? _SERVER_NAME : ($_SERVER['HTTP_HOST'] ?? 'localhost');
$conf->server_url  = defined('_SERVER_URL') ? _SERVER_URL : '';
$conf->app_root    = defined('_APP_ROOT') ? _APP_ROOT : '';
$conf->app_url     = defined('_APP_URL') ? _APP_URL : '';
$conf->action_root = $conf->app_root;
$conf->action_url  = $conf->app_url;

function &getConf() {
    global $conf;
    return $conf;
}

// sesja (do ról/loginu)
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Loader klas (namespace -> folder) - przed tworzeniem obiektów klas autoloadowanych
require_once __DIR__ . '/core/ClassLoader.class.php';
$cloader = new core\ClassLoader();

function &getLoader() {
    global $cloader;
    return $cloader;
}

// Messages - ładowane przez autoloader (core\Messages -> /core/Messages.class.php)
$msgs = new core\Messages();

function &getMessages() {
    global $msgs;
    return $msgs;
}

// Smarty - tworzone leniwie (dopiero gdy potrzebne)
$smarty = null;

function &getSmarty() {
    global $smarty;

    if (!isset($smarty)) {
        require_once getConf()->root_path . '/lib/smarty/libs/Smarty.class.php';

        if (class_exists('Smarty\\Smarty')) {
            $smarty = new \Smarty\Smarty();
        } else {
            $smarty = new \Smarty();
        }

        // katalogi widoków (nowa struktura) + fallback do starej
        $viewMain = getConf()->root_path . '/app/views';
        $viewTpl  = getConf()->root_path . '/app/views/templates';
        $oldUI    = getConf()->root_path . '/templates_ui';
        $oldBase  = getConf()->root_path . '/templates';

        $smarty->setTemplateDir([$viewMain, $viewTpl, $oldUI, $oldBase]);

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

        // przypisz konfigurację i messages 
        $smarty->assign('conf', getConf());
        $smarty->assign('msgs', getMessages());

        // kompatybilność ze starymi widokami
        $smarty->assign('app_url', getConf()->app_url);
        $smarty->assign('app_root', getConf()->app_root);

        $smarty->assign('is_logged', !empty($_SESSION['role']));
        $smarty->assign('user_role', (string)($_SESSION['role'] ?? ''));
        $smarty->assign('user_login', (string)($_SESSION['login'] ?? ''));
    }

    return $smarty;
}

// Kompatybilnosc wsteczna:
function &get_smarty() {
    return getSmarty();
}

require_once __DIR__ . '/core/functions.php';

// wczytaj akcję do zmiennej $action 
$action = getFromRequest('action');
