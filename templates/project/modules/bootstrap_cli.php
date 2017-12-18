<?php

use Phalcon\Di\FactoryDefault\Cli as FactoryDefault;
use Phalcon\Cli\Console as ConsoleApp;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

include(BASE_PATH . '/vendor/autoload.php');
$dotenv = new Dotenv\Dotenv(BASE_PATH);
$dotenv->load();

ini_set('date.timezone', 'Asia/Shanghai');

$di = new FactoryDefault();
include APP_PATH . '/config/services.php';
include APP_PATH . '/config/services_cli.php';
include APP_PATH . '/config/loader.php';
$config = $di->getConfig();

$console = new ConsoleApp($di);

$console->registerModules([
    'cli' => ['className' => '@@namespace@@\Modules\Cli\Module']
]);

$arguments = ['module' => 'cli'];
foreach ($argv as $k => $arg) {
    if ($k == 1) {
        $arguments['task'] = $arg;
    } elseif ($k == 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

try {

    $console->handle($arguments);
    if (isset($config["printNewLine"]) && $config["printNewLine"]) {
        echo PHP_EOL;
    }

} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
    exit(255);
}

