<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <title>Kalkulator kredytowy</title>
</head>
<body>
<h2>Kalkulator kredytowy</h2>

<form action="<?php echo _APP_URL; ?>/app/kredyt.php" method="post">
  <label>Kwota:</label>
  <input type="text" name="kwota" value="<?php echo htmlspecialchars($kwota ?? '', ENT_QUOTES); ?>"><br>

  <label>Lata:</label>
  <input type="text" name="lata" value="<?php echo htmlspecialchars($lata ?? '', ENT_QUOTES); ?>"><br>

  <label>Oprocentowanie (%):</label>
  <input type="text" name="oprocentowanie" value="<?php echo htmlspecialchars($oprocentowanie ?? '', ENT_QUOTES); ?>"><br>

  <button type="submit">Oblicz ratę</button>
</form>

<?php if (!empty($messages)): ?>
  <ol style="background:#f88;padding:10px;width:360px">
    <?php foreach ($messages as $m): ?>
      <li><?php echo htmlspecialchars($m, ENT_QUOTES); ?></li>
    <?php endforeach; ?>
  </ol>
<?php endif; ?>

<?php if ($rata !== null): ?>
  <div style="background:#ff0;padding:10px;width:360px">
    Miesięczna rata: <?php echo number_format(floatval($rata), 2, ',', ' '); ?> zł
  </div>
<?php endif; ?>

<p><a href="<?php echo _APP_URL; ?>/app/calc_view.php">Przejdź do kalkulatora przykładowego</a></p>
</body>
</html>
