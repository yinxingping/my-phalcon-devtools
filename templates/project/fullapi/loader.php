<?php

$loader = new \Phalcon\Loader();

$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->libraryDir,
    ]
)->register();
