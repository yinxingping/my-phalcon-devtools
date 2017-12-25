<?php

define('OK', 0);
define('EXCEPTION', 2);
define('NETWORK_ERROR', 3);

define('STATUS',[
    'ok' => [
        'code' => OK,
    ],
    'exception' => [
        'code' => EXCEPTION,
    ],
    'network_error' => [
        'code' => NETWORK_ERROR,
    ],
]);

return new \Phalcon\Config([
    'version' => '1.0',
    'appName' => $appName,

    'logger' => [
        'adapter' => 'file',
        'name'    => LOG_PATH . '/' . $appName . '_info_' . date('Ymd') . '.log',
    ],

]);

