<?php
require_once __DIR__ . '/../config.php';
require_once _ROOT_PATH . '/app/security/check.php';
require_once _ROOT_PATH . '/app/smarty_init.php';

$smarty = get_smarty();
$smarty->assign('page_title', 'Witaj w systemie kalkulatorów!');
$smarty->assign('page_subtitle', 'Wybierz rodzaj kalkulatora:');
$smarty->display('panel.tpl');
