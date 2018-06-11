<?php

use BKTest\Models\Application;

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', realpath(__DIR__ . DS . '..'));

spl_autoload_register(function ($class) {
    $prefix = 'BKTest\\';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file           = ROOT_DIR . DS . str_replace('\\', DS, $relative_class) . '.php';

    require_once $file;
});


try {
    $app = new Application();
    $app->bootstrap();
    $result = $app->run();

    print $result;
} catch (Exception $e) {
    error_log($e);
}

