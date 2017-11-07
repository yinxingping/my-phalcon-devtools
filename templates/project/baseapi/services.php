<?php

$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

$di->setShared('profiler', function () {
    return new Phalcon\Db\Profiler();
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

$di->setShared('logger', function () {
    return Phalcon\Logger\Factory::load($this->getConfig()->logger);
});

/**
 * metadata可用于model的自动验证
 * Memory生命周期为当次请求，用于开发
 */
$di->setShared('modelsMetadata', function () {
    if (getenv('APP_ENV') === 'production') {
        return new Phalcon\Mvc\Model\Metadata\Redis($this->getConfig()->redis);
    } else {
        return new Phalcon\Mvc\Model\MetaData\Memory();
    }
});

