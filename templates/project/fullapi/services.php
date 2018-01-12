<?php

$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

$di->setShared('request', function () {
    return new \Phalcon\Http\Request();
});

$di->setShared('response', function () {
    return new \Phalcon\Http\Response();
});

$di->setShared('router', function () {
    return new \Phalcon\Mvc\Router();
});

$di->setShared('session', function () {
    if (getenv('APP_ENV') === 'production') {
        $session = new \Phalcon\Session\Adapter\Redis($this->getConfig()->session);
    } else {
        $session = new \Phalcon\Session\Adapter\Files();
    }
    $session->start();

    return $session;
});

$di->setShared('dispatcher', function () {
    return new \Phalcon\Mvc\Dispatcher();
});

$di->setShared('logger', function () {
    return \Phalcon\Logger\Factory::load($this->getConfig()->logger);
});

$di->setShared('security', function () {
    $security = new \Phalcon\Security();

    $security->setWorkFactor(12);

    return $security;
});

