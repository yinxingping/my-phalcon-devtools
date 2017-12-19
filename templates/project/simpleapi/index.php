<?php

use Phalcon\Di;
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

$appName = getenv('APP_NAME', 'appname');

try {

    $di = new Di();

    $di->setShared('logger', function () use ($appName) {
        return Phalcon\Logger\Factory::load([
            'adapter' => 'file',
            'name'    => LOG_PATH . '/' . $appName . '_info_' . date('Ymd') . '.log',
        ]);
    });

    $di->setShared('request', function () {
        return new \Phalcon\Http\Request();
    });

    $di->setShared('response', function () {
        return new \Phalcon\Http\Response();
    });

    $di->setShared('router', function () {
        return new \Phalcon\Mvc\Router();
    });

    include APP_PATH . '/config/status.php';

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

