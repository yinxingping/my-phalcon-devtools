<?php

use Phalcon\Mvc\Micro\Collection as MicroCollection;

$index = new MicroCollection();
$index->setHandler('IndexController', true);
$index->setPrefix('/');
$index->get('/', 'indexAction');
$app->mount($index);

//这里添加你的路由

$app->notFound(
    function () use ($app) {
        $app->response->setStatusCode(404, 'Not Found');
        $app->response->send();
    }
);


