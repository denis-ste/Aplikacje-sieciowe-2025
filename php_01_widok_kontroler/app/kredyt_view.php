<?php require_once dirname(__FILE__) . '/../config.php'; ?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
<meta charset="utf-8" />
<title>Kalkulator kredytowy</title>
</head>
<body>
<h2>Kalkulator kredytowy</h2>

<form action="<?php print(_APP_URL); ?>/app/kredyt.php" method="post">
  <label>Kwota kredytu: </label>
  <input type="text" name="kwota" value="<?php if (isset($kwota)) echo htmlspecialchars($kwota); ?>" /><br/>

  <label>Liczba lat: </label>
  <input type="text" name="lata" value="<?php if (isset($lata)) echo htmlspecialchars($lata); ?>" /><br/>

  <label>Oprocentowanie (% rocznie): </label>
  <input type="text" name="oprocentowanie" value="<?php if (isset($oprocentowanie)) echo htmlspecialchars($oprocentowanie); ?>" /><br/>

  <input type="submit" value="Oblicz ratę" />
</form>

<?php
if (!empty($messages ?? [])) {
  echo '<ol style="background:#f88;padding:10px;width:320px">';
  foreach ($messages as $m) echo '<li>' . htmlspecialchars($m) . '</li>';
  echo '</ol>';
}
if (isset($rata)) {
  echo '<div style="background:#ff0;padding:10px;width:320px">';
  echo 'Miesięczna rata: ' . number_format((float)$rata, 2, ',', ' ') . ' zł';
  echo '</div>';
}
?>

<p><a href="<?php print(_APP_URL); ?>/app/calc_view.php">Wróć do kalkulatora podstawowego</a></p>
</body>
</html>
