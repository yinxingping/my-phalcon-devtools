<?php
use Phalcon\Di\FactoryDefault;

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
    include APP_PATH . '/config/router.php';
    include APP_PATH . '/config/services.php';
    $config = $di->getConfig();
    include APP_PATH . '/config/loader.php';
    $application = new \Phalcon\Mvc\Application($di);

    echo str_replace(["\n","\r","\t"], '', $application->handle()->getContent());

} catch (\Throwable $e) {
    $di->get('logger')->error($e->getMessage() . $e->getTraceAsString());
    $application->response->setStatusCode(500, 'Server error')->send();
}
