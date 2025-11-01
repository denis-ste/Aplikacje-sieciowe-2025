<?php
require_once dirname(__FILE__).'/../config.php';
include _ROOT_PATH.'/app/security/check.php';
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <title>Menu główne</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Aplikacja</a>
    <a class="nav-link text-white" href="<?php print(_APP_URL); ?>/app/security/logout.php">Wyloguj</a>
  </div>
</nav>

<div class="container text-center mt-5">
  <h2>Dostępne kalkulatory!</h2>
  <p>Wybierz moduł:</p>
  <a href="<?php print(_APP_URL); ?>/app/calc.php"   class="btn btn-primary m-2">Zwykły kalkulator</a>
  <a href="<?php print(_APP_URL); ?>/app/kredyt.php" class="btn btn-success m-2">Kalkulator kredytowy</a>
  <a href="<?php print(_APP_URL); ?>/app/inna_chroniona.php" class="btn btn-outline-secondary m-2">Inna chroniona strona</a>
</div>
</body>
</html>
