<?php
// Kompatybilność wsteczna – stary entrypoint.
// Docelowo cała aplikacja działa przez front controller: /?action=karta
require_once __DIR__ . '/../config.php';
header('Location: ' . _APP_URL . '/?action=karta');
exit();
