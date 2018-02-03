<?php

define('OK', 0);
define('DB_ERROR', 1); //数据库操作错误
define('EXCEPTION', 2); //异常
define('SERVER_ERROR', 3); //服务器错误，指网络请求时除200外的其他状态
define('VALID_ERROR', 4); //参数验证错误，经常需要反馈给客户端进行修正
define('REQUEST_INVALID', 5); //非法请求，主要指一些默认参数明显非法的情况，如要修改的数据根本不存在
define('BUSINESS_ERROR', 6); //业务逻辑错误,比如短信发送频次限制等

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
