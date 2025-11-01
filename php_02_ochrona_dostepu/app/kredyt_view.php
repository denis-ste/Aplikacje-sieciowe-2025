<?php require_once dirname(__FILE__).'/../config.php'; include _ROOT_PATH.'/app/security/check.php'; ?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <title>Kalkulator kredytowy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Kalkulator kredytowy</a>
    <div class="d-flex">
      <a class="nav-link text-white" href="<?php print(_APP_URL); ?>/app/menu.php">Menu główne</a>
      <a class="nav-link text-white" href="<?php print(_APP_URL); ?>/app/security/logout.php">Wyloguj</a>
    </div>
  </div>
</nav>

<div class="container mt-4" style="max-width: 520px;">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-3">Oblicz ratę</h4>

      <form action="<?php print(_APP_URL);?>/app/kredyt.php" method="post">
        <div class="mb-3">
          <label class="form-label">Kwota kredytu (PLN):</label>
          <input type="number" class="form-control" name="kwota" step="1" min="1" value="<?php out($kwota); ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Okres (lata):</label>
          <input type="number" class="form-control" name="lata" step="1" min="1" value="<?php out($lata); ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Oprocentowanie roczne (%):</label>
          <input type="number" class="form-control" name="oprocentowanie" step="0.01" min="0" value="<?php out($oprocentowanie); ?>">
        </div>
        <button type="submit" class="btn btn-success w-100">Oblicz</button>
      </form>

      <?php
      if (!empty($messages)) {
          echo '<div class="alert alert-danger mt-3"><ul class="mb-0">';
          foreach ($messages as $msg) echo "<li>$msg</li>";
          echo '</ul></div>';
      }
      if (isset($rata)) {
          echo '<div class="alert alert-success mt-3 text-center">';
          echo 'Miesięczna rata: <strong>'.number_format($rata, 2, ',', ' ').' PLN</strong>';
          echo '</div>';
      }
      ?>

      <hr>
      <p class="small text-muted mb-0">
        <strong>Reguły ról:</strong> user ≤ 100 000 PLN, ≤ 30 lat, ≥ 3.00% | manager – bez ograniczeń.
      </p>
    </div>
  </div>
</div>
</body>
</html>
