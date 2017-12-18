<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('LOG_PATH', BASE_PATH . '/logs');

include(BASE_PATH . '/vendor/autoload.php');
$dotenv = new Dotenv\Dotenv(BASE_PATH);
$dotenv->load();

if (getenv('APP_ENV') == 'production') {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
} else {
    error_reporting(E_ALL);
}
ini_set('date.timezone', 'Asia/Shanghai');
ini_set('display_errors', 'off');
ini_set('error_log', LOG_PATH . '/' .getenv('APP_NAME', 'appname') . '_error_' . date('Ymd') . '.log');

try {

    $di = new FactoryDefault();
    include APP_PATH . '/config/services.php';
    $config = $di->getConfig();
    include APP_PATH . '/config/loader.php';
    $app = new Micro($di);
    include APP_PATH . '/app.php';

    $app->handle();

} catch (\Throwable $t) {
    $app->response->setJsonContent([
        'code'   => STATUS['exception']['code'],
        'status' => 'exception',
        'detail' => [
            'file' => $t->getFile(),
            'line' => $t->getLine(),
            'code' => $t->getCode(),
            'message' => $t->getMessage(),
        ]
    ]);
    $app->response->send();
}

