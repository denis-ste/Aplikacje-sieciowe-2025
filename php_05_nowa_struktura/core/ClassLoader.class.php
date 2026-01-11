<?php

namespace core;

/**
 * ClassLoader - automatyczne ładowanie klas na podstawie pełnej nazwy (namespace)
 * i konwencji plików: <Klasa>.class.php.
 *
 */
class ClassLoader {

    
    public $paths = array();

    public function __construct() {
        // Loader bazowy - szuka w strukturze zgodnej z namespace od katalogu root aplikacji.
        spl_autoload_register(function($class) {
            $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
            $fileName = \getConf()->root_path . DIRECTORY_SEPARATOR . $class . '.class.php';
            if (is_readable($fileName)) {
                require_once $fileName;
            }
        });
    }

    
    public function addPath($path) {
        $this->paths[] = $path;

        // Drugi autoloader rejestrujemy tylko raz (przy pierwszym addPath),
        // aby nie dublować kolejnych rejestracji.
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
