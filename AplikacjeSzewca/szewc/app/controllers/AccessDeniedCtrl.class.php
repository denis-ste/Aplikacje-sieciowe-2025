<?php

namespace app\controllers;

use core\App;
use core\RoleUtils;

class AccessDeniedCtrl extends BaseCtrl {

    public function action_accessdenied(): void {

        $backAction = 'main';

        if (RoleUtils::inRole('ADMIN')) {
            $backAction = 'admin_services';
        } elseif (RoleUtils::inRole('WORKER')) {
            $backAction = 'worker_orders';
        } elseif (RoleUtils::inRole('CLIENT')) {
            $backAction = 'client_dashboard';
        }

        App::getSmarty()->assign('backAction', $backAction);
        App::getSmarty()->display('AccessDenied.tpl');
    }
}
