<?php

namespace core;

/**
 * ClassLoader
 * -----------------------------------------------------------------------------
 * Prosty loader klas oparty o spl_autoload_register.
 *
 * Konwencja projektu:
 *  - klasa ma namespace zgodny ze strukturą folderów
 *  - pliki klas kończą się na *.class.php
 *
 * Przykład:
 *   namespace app\controllers;
 *   class HomeCtrl {}
 *   => plik: /app/controllers/HomeCtrl.class.php
 *
 * Zasada działania:
 *  - autoloader bazowy: szuka od katalogu root aplikacji (getConf()->root_path)
 *    po ścieżce wynikającej z pełnej nazwy klasy (FQCN)
 *  - autoloader „dodatkowy”: pozwala dołączyć inne prefiksy ścieżek poprzez addPath(...)
 *    (np. gdybyśmy trzymali klasy w innym podkatalogu)
 */
class ClassLoader {

    /**
     * Dodatkowe ścieżki (prefiksy) do przeszukania w drugim autoloaderze.
     * Przechowujemy w formie ".../" względnej do root_path.
     *
     * @var string[]
     */
    public $paths = array();

    public function __construct() {
        // 1) Autoloader bazowy – mapuje FQCN bezpośrednio na plik w root_path.
        //    To wystarcza dla klas w /core oraz /app.
        spl_autoload_register(function($class) {
            $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
            $fileName = \getConf()->root_path . DIRECTORY_SEPARATOR . $class . '.class.php';
            if (is_readable($fileName)) {
                require_once $fileName;
            }
        });
    }

    /**
     * Dodaj dodatkowy prefiks ścieżki do przeszukania.
     *
     * @param string $path np. '/vendor' (względnie do root_path)
     */
    public function addPath($path) {
        $this->paths[] = $path;

        // 2) Autoloader dodatkowy rejestrujemy tylko raz – przy pierwszym addPath(...)
        //    (żeby nie rejestrować wielu takich samych callbacków).
        if (count($this->paths) == 1) {
            spl_autoload_register(function($class) {
                $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
                foreach (\getLoader()->paths as $path) {
                    $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
                    $fileName = \getConf()->root_path . $path . DIRECTORY_SEPARATOR . $class . '.class.php';
                    if (is_readable($fileName)) {
                        require_once $fileName;
                        return;
                    }
                }
            });
        }
    }
}
