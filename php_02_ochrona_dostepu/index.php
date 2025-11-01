<?php
require_once dirname(__FILE__).'/config.php';
include _ROOT_PATH.'/app/security/check.php';

header("Location: "._APP_URL."/app/menu.php");
exit();
