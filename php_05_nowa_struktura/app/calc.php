<?php
// Kompatybilność wsteczna – stary entrypoint.
// Docelowo cała aplikacja działa przez front controller: /?action=calc
require_once __DIR__ . '/../config.php';
header('Location: ' . _APP_URL . '/?action=calc');
exit();
