<?php
require_once dirname(__FILE__).'/../config.php';
include _ROOT_PATH.'/app/security/check.php';
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
  <meta charset="utf-8" />
  <title>Chroniona strona</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <p>To jest inna **chroniona** strona aplikacji.</p>
  <a href="<?php print(_APP_URL); ?>/app/menu.php" class="btn btn-secondary">Powrót do menu</a>
  <a href="<?php print(_APP_URL); ?>/app/security/logout.php" class="btn btn-outline-danger">Wyloguj</a>
</div>
</body>
</html>
