<?php

return new \Phalcon\Config([
    'version' => '1.0',
    'appName' => $appName,

    'logger' => [
        'adapter' => 'file',
        'name'    => LOG_PATH . '/' . $appName . '_info_' . date('Ymd') . '.log',
    ],

]);

