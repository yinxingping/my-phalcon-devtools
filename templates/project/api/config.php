<?php

defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');
defined('LOG_PATH') || define('LOG_PATH', BASE_PATH . '/logs');

include APP_PATH . '/config/status.php';

$appName = getenv('APP_NAME', 'appname');

return new \Phalcon\Config([
    'version' => '1.0',
    'appName' => $appName,

    'sessionRedis' => [
        'host'       => getenv('REDIS_HOST') ?: '127.0.0.1',
        'port'       => getenv('REDIS_PORT') ?: '6379',
        'uniqueId'   => getenv('SESSION_UNIQUEID') ?: 'qin-session',
        'persistent' => false,
        'lifetime'   => 3600,
        'prefix'     => 'qin',
        'index'      => 1,
    ],

    'application' => [
        'controllersDir' => APP_PATH . '/controllers/',
        'baseUri'        => '/',
    ],

    'logger' => [
        'adapter' => 'file',
        'name'    => LOG_PATH . '/' . $appName . '_info_' . date('Ymd') . '.log',
    ],
]);

