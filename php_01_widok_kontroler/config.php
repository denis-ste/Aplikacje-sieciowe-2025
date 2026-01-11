<?php
$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
$protocol = $https ? 'https' : 'http';

$serverName = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('_SERVER_NAME', $serverName);
define('_SERVER_URL', $protocol . '://' . _SERVER_NAME);

$docRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
$appPath = str_replace('\\', '/', __DIR__);

$appRoot = '';
if ($docRoot !== '' && strpos($appPath, $docRoot) === 0) {
  $appRoot = substr($appPath, strlen($docRoot));
}
if ($appRoot === '') {
  // awaryjnie: katalog skryptu (czasem kończy się na /app)
  $appRoot = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
}
define('_APP_ROOT', $appRoot);
define('_APP_URL', _SERVER_URL . _APP_ROOT);
define('_ROOT_PATH', __DIR__);
?>
