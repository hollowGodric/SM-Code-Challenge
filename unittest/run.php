<?php
/**
 * Run unit tests
 *
 * Date: 24/08/2015
 * Time: 22:59
 */
require_once 'TestCase.php';
define('DS', DIRECTORY_SEPARATOR);
define('TEST_DIR', __DIR__ . '/tests');
define('JSON_DIR', __DIR__ . '/json');
define('MOCK_DIR', __DIR__ . '/mocks');
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

spl_autoload_register(function ($className)
{
    require_once MOCK_DIR . "/$className.php";
});

function processFiles($dirName, callable $function) {
    $dir = dir($dirName);
    while ($file = $dir->read()) {
        if ($file[0] === '.') {
            continue;
        } elseif (is_dir($file)) {
            processFiles($file, $function);
        } else {
            $function($dirName . DS . $file);
        }
    }
}
processFiles(TEST_DIR, function ($filename) {
    include_once $filename;
});

$errors = [];
foreach (get_declared_classes() as $testCase) {
    if (!in_array('TestCase', class_parents($testCase))) {
        continue;
    }
    /** @var TestCase $test */
    $test = new $testCase;
    $test->run();

    $errors = array_merge($errors, $test->errors);
}

if (count($errors)) {
    // super sophisticated formatting
    print_r($errors);
} else {
    echo 'All tests have passed! Hopefully that means the code is working.';
}