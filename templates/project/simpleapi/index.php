<?php

use Phalcon\Di;
use Phalcon\Mvc\Micro;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('LOG_PATH', BASE_PATH . '/logs');

include(BASE_PATH . '/vendor/autoload.php');
$dotenv = new Dotenv\Dotenv(BASE_PATH);
$dotenv->load();

$appName = getenv('APP_NAME', 'appname');

if (getenv('APP_ENV') == 'production') {
    ini_set('display_errors', 'off');
    ini_set('error_log', LOG_PATH . '/' . $appName . '_error_' . date('Ymd') . '.log');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
} else {
    error_reporting(E_ALL);
}
ini_set('date.timezone', 'Asia/Shanghai');


try {

    $di = new Di();

    $di->setShared('request', function () {
        return new \Phalcon\Http\Request();
    });
    $di->setShared('response', function () {
        return new \Phalcon\Http\Response();
    });
    $di->setShared('router', function () {
        return new \Phalcon\Mvc\Router();
    });
    $di->setShared('config', function () use ($appName) {
        return include APP_PATH . "/config/config.php";
    });

    $config = $di->getConfig();
    $di->setShared('logger', function () use ($config) {
        return \Phalcon\Logger\Factory($config->logger);
    });

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

