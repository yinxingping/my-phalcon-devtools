<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Session\Adapter\Redis as SessionAdapter;
use Phalcon\Flash\Direct as Flash;

$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

$di->setShared('profiler', function () {
    return new Phalcon\Db\Profiler();
});

$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ]);

            return $volt;
        },
        '.phtml' => PhpEngine::class
    ]);

    return $view;
});

$di->setShared('db', function () {
    $config = $this->getConfig();

    // 记录sql详情
    $eventManager = new \Phalcon\Events\Manager();
    $profiler = $this->getProfiler();

    $eventManager->attach(
        'db',
        function ($event, $connection) use ($profiler) {
            if ($event->getType() === 'beforeQuery') {
                $profiler->startProfile($connection->getSQLStatement());
            }
            if ($event->getType() === 'afterQuery') {
                $profiler->stopProfile();
            }
        }
    );

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    $connection = new $class($params);

    $connection->setEventsManager($eventManager);

    return $connection;
});

/*
 * Memory仅当次请求时起作用，适合于开发环境
 */
$di->setShared('modelsMetadata', function () {
    if (getenv('APP_ENV') === 'production') {
        return new Phalcon\Mvc\Model\Metadata\Redis($this->getConfig()->redis);
    } else {
        return new Phalcon\Mvc\Model\MetaData\Memory();
    }
});

$di->setShared('logger', function () {
    return Phalcon\Logger\Factory::load($this->getConfig()->logger);
});

/*
 * bootstrap4.x
 */
$di->set('flash', function () {
    return new Flash([
        'error'   => 'text-danger',
        'success' => 'text-success',
        'notice'  => 'text-info',
        'warning' => 'text-warning'
    ]);
});

$di->setShared('session', function () {
    $session = new SessionAdapter($this->getConfig()->redis);
    $session->start();

    return $session;
});

