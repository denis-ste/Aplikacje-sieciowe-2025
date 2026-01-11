<?php
require_once __DIR__ . '/../config.php';
include _ROOT_PATH . '/app/security/check.php';

// zabezpieczenie przed bezpośrednim wejściem w widok
$kwota = $kwota ?? '';
$lata = $lata ?? '';
$oprocentowanie = $oprocentowanie ?? '';
$messages = $messages ?? [];
$rata = $rata ?? null;
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
  <meta charset="utf-8" />
  <title>Kalkulator kredytowy</title>
  <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
</head>
<body>

<div style="width:90%; margin: 2em auto;">
  <a href="<?php print(_APP_ROOT); ?>/app/calc.php" class="pure-button">Powrót do kalkulatora</a>
  <a href="<?php print(_APP_ROOT); ?>/app/security/logout.php" class="pure-button pure-button-active">Wyloguj</a>
</div>

<div style="width:90%; margin: 2em auto;">
<form action="<?php print(_APP_ROOT); ?>/app/kredyt.php" method="post" class="pure-form pure-form-stacked">
  <legend>Kalkulator kredytowy</legend>
  <fieldset>
    <label>Kwota:</label>
    <input type="text" name="kwota" value="<?php echo htmlspecialchars($kwota, ENT_QUOTES); ?>" />

    <label>Lata:</label>
    <input type="text" name="lata" value="<?php echo htmlspecialchars($lata, ENT_QUOTES); ?>" />

    <label>Oprocentowanie (%):</label>
    <input type="text" name="oprocentowanie" value="<?php echo htmlspecialchars($oprocentowanie, ENT_QUOTES); ?>" />
  </fieldset>
  <input type="submit" value="Oblicz ratę" class="pure-button pure-button-primary" />
</form>

<?php if (!empty($messages)): ?>
  <ol style="margin-top: 1em; padding: 1em 1em 1em 2em; border-radius: 0.5em; background-color: #f88; width:25em;">
    <?php foreach ($messages as $m): ?>
      <li><?php echo htmlspecialchars($m, ENT_QUOTES); ?></li>
    <?php endforeach; ?>
  </ol>
<?php endif; ?>

<?php if ($rata !== null): ?>
  <div style="margin-top: 1em; padding: 1em; border-radius: 0.5em; background-color: #ff0; width:25em;">
    Miesięczna rata: <b><?php echo number_format(floatval($rata), 2, ',', ' '); ?> zł</b>
  </div>
<?php endif; ?>

</div>
</body>
</html>
