<?php

$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
$scheme = $https ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

define('_SERVER_NAME', $host);
define('_SERVER_URL', $scheme . '://' . _SERVER_NAME);

// Wyznacz _APP_ROOT na podstawie położenia katalogu projektu względem DOCUMENT_ROOT
$docRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
$appDir  = rtrim(str_replace('\\', '/', __DIR__), '/');

$appRoot = '';
if ($docRoot !== '' && strpos($appDir, $docRoot) === 0) {
  $appRoot = substr($appDir, strlen($docRoot));
}
if ($appRoot === '') {
  // awaryjnie: katalog skryptu
  $appRoot = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
}
if ($appRoot === '') $appRoot = '/';

define('_APP_ROOT', $appRoot);
define('_APP_URL', _SERVER_URL . _APP_ROOT);
define('_ROOT_PATH', __DIR__);

function out($param) {
  if (isset($param)) {
    echo $param;
  }
}
?>
