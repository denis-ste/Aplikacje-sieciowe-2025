<?php

namespace app\controllers;

use core\App;

class MainCtrl extends BaseCtrl {

    public function action_main() {
        
        $this->prepareLayout();
        App::getSmarty()->display('Main.tpl');
    }
}
