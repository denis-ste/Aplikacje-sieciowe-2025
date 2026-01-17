<?php
namespace app\controllers;

use core\App;
use core\RoleUtils;

abstract class BaseCtrl {

    protected function prepareLayout() {

        App::getSmarty()->assign('isLogged', !empty(App::getConf()->roles));
        App::getSmarty()->assign('userLogin', $_SESSION['user_login'] ?? null);

        $mainRoute = 'main';
        if (RoleUtils::inRole('ADMIN')) {
            $mainRoute = 'admin_services';
        } elseif (RoleUtils::inRole('WORKER')) {
            $mainRoute = 'worker_orders';
        } elseif (RoleUtils::inRole('CLIENT')) {
            $mainRoute = 'client_dashboard';
        }

        App::getSmarty()->assign('mainRoute', $mainRoute);
    }
}
