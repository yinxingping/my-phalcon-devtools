<?php

use Phalcon\Mvc\View\Simple as View;
use Phalcon\Mvc\Url as UrlResolver;

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

$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    $connection = new $class($params);

    return $connection;
});

$di->setShared('modelsMetadata', function () {
    return new \Phalcon\Mvc\Model\MetaData\Memory();
});

$di->setShared('session', function () {
    if (getenv('APP_ENV') === 'production') {
        $session = new \Phalcon\Session\Adapter\Redis($this->getConfig()->redis);
    } else {
        $session = new \Phalcon\Session\Adapter\Files();
    }
    $session->start();

    return $session;
});

$di->setShared('cookies', function () {
    return new \Phalcon\Http\Response\Cookies();
});

$di->setShared('dispatcher', function () {
    return new \Phalcon\Mvc\Dispatcher();
});

$di->setShared('logger', function () {
    return \Phalcon\Logger\Factory::load($this->getConfig()->logger);
});

$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setViewsDir($config->application->viewsDir);
    return $view;
});

$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);
    return $url;
});

