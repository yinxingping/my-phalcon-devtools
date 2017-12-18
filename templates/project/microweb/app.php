<?php

$app->get('/', function () {
    echo $this['view']->render('index');
});

$app->notFound(function () use($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo $app['view']->render('404');
});
