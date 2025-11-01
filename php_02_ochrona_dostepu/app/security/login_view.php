<?php require_once dirname(__FILE__).'/../../config.php'; ?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
  <meta charset="utf-8" />
  <title>Logowanie</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid"><a class="navbar-brand" href="#">Kalkulatory</a></div>
</nav>

<div class="container mt-5" style="max-width:400px;">
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="card-title text-center mb-4">Logowanie</h4>
      <form action="<?php print(_APP_ROOT); ?>/app/security/login.php" method="post">
        <div class="mb-3">
          <label class="form-label">Login:</label>
          <input type="text" class="form-control" name="login" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Hasło:</label>
          <input type="password" class="form-control" name="pass" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Zaloguj</button>
      </form>

      <?php if (!empty($messages ?? [])) {
        echo '<div class="alert alert-danger mt-3"><ul class="mb-0">';
        foreach ($messages as $msg) echo "<li>$msg</li>";
        echo '</ul></div>';
      } ?>

      <p class="mt-3 small text-muted">Test: <code>user/user</code> lub <code>manager/manager</code></p>
    </div>
  </div>
</div>
</body>
</html>
