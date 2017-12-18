<?php

use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Flash\Direct as Flash;

$di->setShared('router', function () {
    $router = new Router();

    $router->setDefaultModule('frontend');

    return $router;
});

$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

$di->set('flash', function () {
    return new Flash([
        'error'   => 'text-danger',
        'success' => 'text-success',
        'notice'  => 'text-info',
        'warning' => 'text-warning'
    ]);
});

$di->setShared('dispatcher', function() {
    $dispatcher = new Dispatcher();
    $dispatcher->setDefaultNamespace('@@namespace@@\Modules\Frontend\Controllers');
    return $dispatcher;
});

