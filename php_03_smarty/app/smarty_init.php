<?php
require_once __DIR__ . '/../config.php';

function ensure_dir(string $path): void {
  if (!is_dir($path)) { @mkdir($path, 0777, true); }
}

function require_smarty_class(): void {
  $root = _ROOT_PATH;
  $candidates = [
    $root . '/lib/smarty/libs/Smarty.class.php',
    $root . '/libs/Smarty/libs/Smarty.class.php',
    $root . '/vendor/smarty/smarty/libs/Smarty.class.php',
  ];
  foreach ($candidates as $p) {
    if (file_exists($p)) { require_once $p; return; }
  }
  header('Content-Type: text/plain; charset=utf-8');
  echo "BŁĄD: Nie znaleziono Smarty.class.php\n";
  foreach ($candidates as $p) echo " - $p\n";
  exit();
}

function get_smarty() {
  static $smarty = null;
  if ($smarty) return $smarty;

  require_smarty_class();

  if (class_exists('Smarty\\Smarty')) $smarty = new \Smarty\Smarty();
  else $smarty = new \Smarty();

  $templatesUI   = _ROOT_PATH . '/templates_ui';
  $templatesBase = _ROOT_PATH . '/templates';

  $compileDir = _ROOT_PATH . '/templates_c';
  $cacheDir   = _ROOT_PATH . '/cache';
  $configsDir = _ROOT_PATH . '/configs';

  ensure_dir($templatesUI);
  ensure_dir($templatesBase);
  ensure_dir($compileDir);
  ensure_dir($cacheDir);
  ensure_dir($configsDir);

  $smarty->setTemplateDir([$templatesUI, $templatesBase]);
  $smarty->setCompileDir($compileDir);
  $smarty->setCacheDir($cacheDir);
  $smarty->setConfigDir($configsDir);
  $smarty->caching = false;

  $role  = (session_status() === PHP_SESSION_ACTIVE) ? ($_SESSION['role'] ?? '') : '';
  $login = (session_status() === PHP_SESSION_ACTIVE) ? ($_SESSION['login'] ?? '') : '';

  $smarty->assign('app_url', _APP_URL);
  $smarty->assign('app_root', _APP_ROOT);

  $smarty->assign('conf', [
    'app_url' => _APP_URL,
    'app_root' => _APP_ROOT
  ]);

  $smarty->assign('is_logged', $role !== '');
  $smarty->assign('user_role', $role);
  $smarty->assign('user_login', $login);

  return $smarty;
}
