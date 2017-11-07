<?php

defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');
defined('LOG_PATH') || define('LOG_PATH', BASE_PATH . '/logs');

include APP_PATH . '/config/status.php';

$appName = getenv('APP_NAME', 'appname');

return new \Phalcon\Config([
    'version' => '1.0',
    'appName' => $appName,

    /*
     * model生成时还不支持.env，所以只能以系统getenv方式，且项目生成后这里的默认值要设置成开发环境的相应值
     * ??支持null, ?:支持false
     */
    'database' => [
        'adapter'    => 'Mysql',
        'host'     => getenv('DB_HOST') ?: 'localhost',
        'username' => getenv('DB_USERNAME') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: 'test123456',
        'dbname'   => getenv('DB_DATABASE') ?: 'qin_user',
        'charset'    => 'utf8',
    ],

    'redis' => [
        'host'       => getenv('REDIS_HOST') ?: '127.0.0.1',
        'port'       => getenv('REDIS_PORT') ?: '6379',
    ],

    'application' => [
        'modelsDir'      => APP_PATH . '/models/',
        'controllersDir' => APP_PATH . '/controllers/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'baseUri'        => '/',
    ],

    'logger' => [
        'adapter' => 'file',
        'name'    => LOG_PATH . '/' . $appName . '_info_' . date('Ymd') . '.log',
    ],

    'dbLogger' => [
        'adapter' => 'file',
        'name'    => LOG_PATH . '/' . $appName . '_db_' . date('Ymd') . '.log',
    ],
]);

