<?php

defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');
defined('LOG_PATH') || define('LOG_PATH', BASE_PATH . '/logs');

include APP_PATH . '/config/status.php';

$appName = getenv('APP_NAME', 'appname');

return new \Phalcon\Config([
    'version' => '1.0',
    'appName' => $appName,

    'application' => [
        'controllersDir' => APP_PATH . '/controllers/',
        'libraryDir'     => APP_PATH . '/library/',
    ],

    'session' => [
        'host'     => getenv('SESSION_REDIS_HOST') ?: '127.0.0.1',
        'port'     => getenv('SESSION_REDIS_PORT') ?: '6379',
        'lifetime' => 86400,//1天
        'prefix'   => ':' .$appName . ':',
        'adapter'  => 'redis',
    ],

    'logger' => [
        'adapter' => 'file',
        'name'    => LOG_PATH . '/' . $appName . '_info_' . date('Ymd') . '.log',
    ],
]);

