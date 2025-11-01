<?php require_once dirname(__FILE__).'/../config.php'; ?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <title>Kalkulator</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Kalkulator</a>
    <div class="d-flex">
      <a class="nav-link text-white" href="<?php print(_APP_URL); ?>/app/menu.php">Menu główne</a>
      <a class="nav-link text-white" href="<?php print(_APP_URL); ?>/app/security/logout.php">Wyloguj</a>
    </div>
  </div>
</nav>

<div class="container mt-4" style="max-width: 420px;">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-3">Zwykły kalkulator</h4>
      <form action="<?php print(_APP_URL);?>/app/calc.php" method="post">
        <div class="mb-3">
          <label>Liczba 1:</label>
          <input type="text" class="form-control" name="x" value="<?php out($x); ?>">
        </div>
        <div class="mb-3">
          <label>Operacja:</label>
          <select name="op" class="form-select">
            <option value="plus">+</option>
            <option value="minus">-</option>
            <option value="times">*</option>
            <option value="div">/</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Liczba 2:</label>
          <input type="text" class="form-control" name="y" value="<?php out($y); ?>">
        </div>
        <button type="submit" class="btn btn-primary w-100">Oblicz</button>
      </form>

      <?php
      if (!empty($messages)) {
          echo '<div class="alert alert-danger mt-3"><ul class="mb-0">';
          foreach ($messages as $msg) echo '<li>'.$msg.'</li>';
          echo '</ul></div>';
      }
      if (isset($result)) {
          echo '<div class="alert alert-success mt-3">Wynik: '.$result.'</div>';
      }
      ?>
    </div>
  </div>
</div>
</body>
</html>
