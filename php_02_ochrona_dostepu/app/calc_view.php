<?php
require_once __DIR__ . '/../config.php';
require_once _ROOT_PATH . '/app/security/check.php';

$role = $_SESSION['role'] ?? '';
$x = $x ?? '';
$y = $y ?? '';
$messages = $messages ?? [];
$result = $result ?? null;
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
  <meta charset="utf-8" />
  <title>Kalkulator</title>
  <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
</head>
<body>

<div style="width:90%; margin: 2em auto;">
  <a href="<?php print(_APP_ROOT); ?>/app/inna_chroniona.php" class="pure-button">kolejna chroniona strona</a>
  <a href="<?php print(_APP_ROOT); ?>/app/kredyt.php" class="pure-button">kalkulator kredytowy</a>
  <a href="<?php print(_APP_ROOT); ?>/app/security/logout.php" class="pure-button pure-button-active">Wyloguj</a>
</div>

<div style="width:90%; margin: 2em auto;">

<?php if ($role === 'user'): ?>
  <div style="margin: 1em 0; padding: 1em; border-radius: 0.5em; background-color: #eef; width: 28em;">
    Uwaga: jako <b>user</b> nie możesz wykonywać operacji <b>-</b> oraz <b>/</b>.
  </div>
<?php endif; ?>

<form action="<?php print(_APP_ROOT); ?>/app/calc.php" method="post" class="pure-form pure-form-stacked">
  <legend>Kalkulator</legend>
  <fieldset>
    <label for="id_x">Liczba 1: </label>
    <input id="id_x" type="text" name="x" value="<?php out($x); ?>" />

    <label for="id_op">Operacja: </label>
    <select name="op">
      <option value="plus">+</option>
      <option value="minus">-</option>
      <option value="times">*</option>
      <option value="div">/</option>
    </select>

    <label for="id_y">Liczba 2: </label>
    <input id="id_y" type="text" name="y" value="<?php out($y); ?>" />
  </fieldset>

  <input type="submit" value="Oblicz" class="pure-button pure-button-primary" />
</form>

<?php
if (count($messages) > 0) {
  echo '<ol style="margin-top: 1em; padding: 1em 1em 1em 2em; border-radius: 0.5em; background-color: #f88; width:25em;">';
  foreach ($messages as $msg) {
    echo '<li>'.htmlspecialchars($msg, ENT_QUOTES).'</li>';
  }
  echo '</ol>';
}
?>

<?php if ($result !== null): ?>
  <div style="margin-top: 1em; padding: 1em; border-radius: 0.5em; background-color: #ff0; width:25em;">
    <?php echo 'Wynik: ' . htmlspecialchars((string)$result, ENT_QUOTES); ?>
  </div>
<?php endif; ?>

</div>
</body>
</html>
