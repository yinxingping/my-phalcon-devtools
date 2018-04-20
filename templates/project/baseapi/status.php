<?php

define('OK', 0);
define('EXCEPTION', 1);
define('DB_ERROR', 2);
define('REDIS_ERROR', 3);
define('VALID_ERROR', 4);
define('UNIQUE_ERROR', 5);
define('INTERFACE_ERROR', 6);

define('STATUS',[
    'ok' => [
        'code' => OK,
    ],
    'exception' => [
        'code' => EXCEPTION,
    ],
    'db_error' => [
        'code' => DB_ERROR,
    ],
    'redis_error' => [
        'code' => REDIS_ERROR,
    ],
    'valid_error' => [
        'code' => VALID_ERROR,
    ],
    'unique_error' => [
        'code' => UNIQUE_ERROR,
    ],
    'interface_error' => [
        'code' => INTERFACE_ERROR,
    ],
    'record_not_exists' => [
        'code' => VALID_ERROR,
        'message' => '要操作的记录不存在',
        'field' => 'id',
    ],
    'id_invalid' => [
        'code' => VALID_ERROR,
        'message' => 'ID必须为正整数值',
    ],
]);

