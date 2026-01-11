<?php
// Kompatybilność wsteczna – stary entrypoint.
// Docelowo cała aplikacja działa przez front controller: /?action=kredyt
require_once __DIR__ . '/../config.php';
header('Location: ' . _APP_URL . '/?action=kredyt');
exit();
