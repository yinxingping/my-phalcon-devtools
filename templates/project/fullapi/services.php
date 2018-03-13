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
    $sessionConfig = $this->getConfig()->session;
    $session = new \Phalcon\Session\Adapter\Redis((Array)$sessionConfig);
    $session->start();

    return $session;
});

$di->setShared('dispatcher', function () {
    return new \Phalcon\Mvc\Dispatcher();
});

$di->setShared('logger', function () {
    $logger = \Phalcon\Logger\Factory::load($this->getConfig()->logger);
    $logger->setFormatter(new \Phalcon\Logger\Formatter\Line('%type%|%date%|%message%'));

    return $logger;
});

$di->setShared('security', function () {
    $security = new \Phalcon\Security();

    $security->setWorkFactor(12);

    return $security;
});

$di->setShared('api', function () {
    return new \API();
});
