<?php

namespace Mladenov;

class Autoload {

    static private $loaderPaths = [];

    public static function register(array $loaderPaths)
    {
        self::$loaderPaths = $loaderPaths;

        spl_autoload_register(function ($className)
        {
            if (preg_match('/\\\\/', $className)) {
                $path = str_replace('\\', DIRECTORY_SEPARATOR, $className);
            } else {
                $path = str_replace('_', DIRECTORY_SEPARATOR, $className);
            }

            foreach (self::$loaderPaths as $loaderPath) {
                if (file_exists($loaderPath . DIRECTORY_SEPARATOR . $path . '.php')) {
                    require_once $loaderPath . DIRECTORY_SEPARATOR . $path . '.php';
                }
            }
        });
    }
}
