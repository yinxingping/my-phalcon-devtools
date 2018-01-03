<?php
define('OK', 0);
define('DB_ERROR', 1);
define('EXCEPTION', 2);
define('VALID_ERROR', 3);

define('STATUS',[
    'ok' => [
        'code' => OK,
    ],
    'db_error' => [
        'code' => DB_ERROR,
    ],
    'exception' => [
        'code' => EXCEPTION,
    ],
    'valid_error' => [
        'code' => VALID_ERROR,
    ],
    'record_not_exists' => [
        'code' => DB_ERROR,
        'message' => '要操作的记录不存在',
    ],
]);

