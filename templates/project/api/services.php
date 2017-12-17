<?php

use Phalcon\Session\Adapter\Redis as Session;

$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

$di->setShared('logger', function () {
    return Phalcon\Logger\Factory::load($this->getConfig()->logger);
});

$di->setShared('session', function () {
    $session = new Session($this->getConfig()->sessionRedis);
    $session->start();

    return $session;
});

