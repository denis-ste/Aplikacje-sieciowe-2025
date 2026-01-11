<?php
/**
 * Proste funkcje pomocnicze 
 */

function getFromRequest(string $param_name, $default = null) {
  return isset($_REQUEST[$param_name]) ? $_REQUEST[$param_name] : $default;
}

function redirect(string $url): void {
  header('Location: ' . $url);
  exit();
}

function is_logged_in(): bool {
  return !empty($_SESSION['role']);
}

function current_role(): string {
  return (string)($_SESSION['role'] ?? '');
}

function current_login(): string {
  return (string)($_SESSION['login'] ?? '');
}

function is_admin_role(): bool {
  return current_role() === 'admin';
}
