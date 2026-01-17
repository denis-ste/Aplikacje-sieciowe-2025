<?php
require_once 'init.php';
require_once 'routing.php';

use core\App;
use core\ParamUtils;
use core\SessionUtils;

$action = ParamUtils::getFromRequest(App::getConf()->action_param, false);

SessionUtils::loadMessages();
App::getRouter()->setAction($action);
App::getRouter()->go();