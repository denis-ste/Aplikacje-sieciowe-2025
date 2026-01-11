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

// PRODUKCYJNE USTAWIENIA BŁĘDÓW (LAB) — brak ścieżek i warningów w HTML
if (!defined('APP_DEBUG')) define('APP_DEBUG', false);

ini_set('display_errors', APP_DEBUG ? '1' : '0');
ini_set('display_startup_errors', APP_DEBUG ? '1' : '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

// log do pliku w projekcie (Windows/XAMPP ma prawo zapisu w katalogu projektu)
$logDir = _ROOT_PATH . '/logs';
if (!is_dir($logDir)) @mkdir($logDir, 0777, true);
ini_set('error_log', $logDir . '/php_errors.log');

// helper używany w widokach
function out($param) {
  if (isset($param)) {
    echo $param;
  }
}
?>
