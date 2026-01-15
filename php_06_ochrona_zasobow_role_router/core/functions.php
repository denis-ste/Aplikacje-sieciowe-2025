<?php
/**
 * Funkcje pomocnicze (rozszerzone jak we wzorcu php_07_role):
 * - pobieranie parametrów z różnych źródeł (z opcją required + komunikat)
 * - przekierowania i forward na akcje
 * - role w sesji (zapis/odczyt) + sprawdzenie uprawnień
 * - routing i automatyczne wywołanie kontrolerów jest realizowane przez obiekt core\Router
 *
 * UWAGA: zachowujemy kompatybilność wstecz z dotychczasowym kodem php_06_ochrona_zasobow.
 */

// ------------------------
// Parametry (GET/POST/REQUEST/SESSION/COOKIES)
// ------------------------

function getFrom(array &$source, string $idx, bool $required = false, ?string $required_message = null, $default = null) {
  if (isset($source[$idx])) {
    return $source[$idx];
  }
  if ($required) {
    $msg = $required_message ?? ('Brak parametru: ' . $idx);
    getMessages()->addError($msg);
  }
  return $default;
}

function getFromRequest(string $param_name, $default = null) {
  // kompatybilność: stary podpis (nazwa, default)
  return isset($_REQUEST[$param_name]) ? $_REQUEST[$param_name] : $default;
}

// Nowe, zgodne z wzorcem (required + komunikat)
function getParam(string $param_name, bool $required = false, $default = null, ?string $required_message = null) {
  return getFrom($_REQUEST, $param_name, $required, $required_message, $default);
}

function getFromGet(string $param_name, bool $required = false, ?string $required_message = null, $default = null) {
  return getFrom($_GET, $param_name, $required, $required_message, $default);
}

function getFromPost(string $param_name, bool $required = false, ?string $required_message = null, $default = null) {
  return getFrom($_POST, $param_name, $required, $required_message, $default);
}

function getFromCookies(string $param_name, bool $required = false, ?string $required_message = null, $default = null) {
  return getFrom($_COOKIE, $param_name, $required, $required_message, $default);
}

function getFromSession(string $param_name, bool $required = false, ?string $required_message = null, $default = null) {
  return getFrom($_SESSION, $param_name, $required, $required_message, $default);
}

function getInt(string $param_name, bool $required = false, ?string $required_message = null, $default = null): ?int {
  $v = getParam($param_name, $required, $default, $required_message);
  if ($v === null || $v === '') return $default === null ? null : (int)$default;
  if (!is_numeric($v)) {
    getMessages()->addError(($required_message ?? ('Nieprawidłowa liczba całkowita: ' . $param_name)));
    return null;
  }
  return (int)$v;
}

function getFloat(string $param_name, bool $required = false, ?string $required_message = null, $default = null): ?float {
  $v = getParam($param_name, $required, $default, $required_message);
  if ($v === null || $v === '') return $default === null ? null : (float)$default;
  if (is_string($v)) $v = str_replace(',', '.', $v);
  if (!is_numeric($v)) {
    getMessages()->addError(($required_message ?? ('Nieprawidłowa liczba rzeczywista: ' . $param_name)));
    return null;
  }
  return (float)$v;
}

// ------------------------
// Redirect / Forward
// ------------------------

function redirect(string $url): void {
  header('Location: ' . $url);
  exit();
}

function buildActionUrl(string $action_name, array $params = []): string {
  $base = rtrim(getConf()->app_url, '/') . '/?action=' . urlencode($action_name);
  if (!empty($params)) {
    $qs = http_build_query($params);
    $base .= '&' . $qs;
  }
  return $base;
}

function redirectTo(string $action_name, array $params = []): void {
  redirect(buildActionUrl($action_name, $params));
}

function forwardTo(string $action_name, array $params = []): void {
  // forward w ramach tego samego requestu (jak w php_07_role)
  foreach ($params as $k => $v) {
    $_REQUEST[$k] = $v;
  }
  global $action;
  $action = $action_name;
  include getConf()->root_path . '/ctrl.php';
  exit;
}

// ------------------------
// Role w sesji (jak w php_07_role) + kompatybilność ze starym 'role'
// ------------------------

function resetRoles(): void {
  getConf()->roles = [];
  unset($_SESSION['_roles']);
}

function addRole(string $role): void {
  if (!isset(getConf()->roles) || !is_array(getConf()->roles)) {
    getConf()->roles = [];
  }
  getConf()->roles[$role] = true;
  $_SESSION['_roles'] = serialize(getConf()->roles);
  // kompatybilność: "główna" rola jako string
  $_SESSION['role'] = $role;
}

function inRole(string $role): bool {
  return isset(getConf()->roles) && is_array(getConf()->roles) && isset(getConf()->roles[$role]);
}

function is_logged_in(): bool {
  // kompatybilność: stary kod sprawdzał $_SESSION['role']
  if (!empty($_SESSION['role'])) return true;
  return isset(getConf()->roles) && is_array(getConf()->roles) && !empty(getConf()->roles);
}

function current_role(): string {
  return (string)($_SESSION['role'] ?? '');
}

function current_login(): string {
  return (string)($_SESSION['login'] ?? '');
}

function is_admin_role(): bool {
  return current_role() === 'admin' || inRole('admin');
}

function logoutUser(): void {
  resetRoles();
  unset($_SESSION['role'], $_SESSION['login'], $_SESSION['user']);
}

// UWAGA:
// Poprzednia funkcja "control()" (dispatcher) została zastąpiona przez core\Router.
