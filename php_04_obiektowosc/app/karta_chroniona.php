<?php
require_once __DIR__ . '/../config.php';
require_once _ROOT_PATH . '/app/security/check.php';
require_once _ROOT_PATH . '/app/smarty_init.php';

$role = $_SESSION['role'] ?? '';
$isAdmin = in_array($role, ['admin','manager'], true);

$smarty = get_smarty();
$smarty->assign('page_title', 'Karta chroniona');
$smarty->assign('is_admin', $isAdmin);

if (!$isAdmin) {
  $smarty->assign('messages', ['Nie masz uprawnień do tej strony (tylko admin).']);
} else {
  $smarty->assign('messages', []);
}

$smarty->display('karta_chroniona.tpl');
