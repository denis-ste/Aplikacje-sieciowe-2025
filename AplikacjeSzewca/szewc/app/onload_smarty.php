<?php

/*
 * Executed when getSmarty() is called for the first time.
 */
function url($params, $smarty)
{
    $action = '';
    if (isset($params['action'])) {
        $action = $params['action'];
        unset($params['action']);
    }
    return \core\Utils::URL($action, $params);
}

function rel_url($params, $smarty)
{
    $action = '';
    if (isset($params['action'])) {
        $action = $params['action'];
        unset($params['action']);
    }
    return \core\Utils::relURL($action, $params);
}

\core\App::getSmarty()->registerPlugin("function", "url", "url");
\core\App::getSmarty()->registerPlugin("function", "rel_url", "rel_url");

\core\App::getSmarty()->assign('conf', \core\App::getConf());
\core\App::getSmarty()->assign('messages', \core\App::getMessages());

$mainRoute = 'main';

if (!empty(\core\App::getConf()->roles)) {
    if (\core\RoleUtils::inRole('ADMIN')) {
        $mainRoute = 'admin_services';
    } elseif (\core\RoleUtils::inRole('WORKER')) {
        $mainRoute = 'worker_orders';
    } elseif (\core\RoleUtils::inRole('CLIENT')) {
        $mainRoute = 'client_dashboard';
    }
}

\core\App::getSmarty()->assign('mainRoute', $mainRoute);
