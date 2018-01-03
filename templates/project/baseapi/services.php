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

$di->setShared('modelsManager', function () {
    return new \Phalcon\Mvc\Model\Manager();
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

    $connection->setEventsManager($this->getEventsManager());

    return $connection;
});

/**
 * metadata可用于model的自动验证
 * Memory生命周期为当次请求，用于开发
 */
$di->setShared('modelsMetadata', function () {
    if (getenv('APP_ENV') === 'production') {
        return new \Phalcon\Mvc\Model\Metadata\Redis($this->getConfig()->redis);
    } else {
        return new \Phalcon\Mvc\Model\MetaData\Memory();
    }
});

$di->setShared('eventsManager', function () {
    $eventManager = new \Phalcon\Events\Manager();
    $profiler = $this->getProfiler();

    // 记录sql详情
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

    return $eventManager;
});

$di->setShared('transactionManager', function () {
    return new \Phalcon\Mvc\Model\Transaction\Manager();
});

$di->setShared('profiler', function () {
    return new \Phalcon\Db\Profiler();
});

$di->setShared('logger', function () {
    return \Phalcon\Logger\Factory::load($this->getConfig()->logger);
});

//密码加密使用
$di->setShared('security', function () {
    $security = new \Phalcon\Security();

    $security->setWorkFactor(12);

    return $security;
});
