<?php
define('OK', 0);
define('DB_ERROR', 1);
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
    'db_error' => [
        'code' => DB_ERROR,
    ],
    'record_not_exists' => [
        'code' => DB_ERROR,
        'message' => '要操作的记录不存在',
        'type' => 'record_not_exists',
    ],
]);

