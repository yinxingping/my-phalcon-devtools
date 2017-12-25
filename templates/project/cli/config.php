<?php
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    /*
     * model生成时还不支持.env，所以只能以系统getenv方式，且项目生成后这里的默认值要设置成开发环境的相应值
     * ??支持null, ?:支持false
     */
    'database' => [
        'adapter'    => 'Mysql',
        'host'     => getenv('DB_HOST') ?: 'localhost',
        'username' => getenv('DB_USERNAME') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: 'test123456',
        'dbname'   => getenv('DB_DATABASE') ?: '',
        'charset'    => 'utf8',
    ],

    'version' => '1.0',

    'printNewLine' => true
]);
