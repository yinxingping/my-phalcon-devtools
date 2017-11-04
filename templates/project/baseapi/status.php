<?php
define('OK', 0);
define('DB_ERROR', 1);
define('EXCEPTION', 2);

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
    'password_invalid' => [
        'code' => DB_ERROR,
        'message' => '密码必须位6-16位英文字母、数字或特殊字符',
        'field' => 'password',
        'type' => 'password_invalid',
    ],
]);

