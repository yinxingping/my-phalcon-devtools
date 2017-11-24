<?php

$app->get('/', function () {

});

$app->notFound(function () use($app) {
    $app->response->setStatusCode(404, "Not Found")->send();
});

function sendContent($status, $messages=null) {
    global $app;

    $messages = $messages ?? STATUS[$status]['message'] ?? '无话可说';
    $app->response->setJsonContent([
        'code' => STATUS[$status]['code'],
        'status' => $status,
        'detail' => $messages,
    ]);

    $app->response->send();
}

