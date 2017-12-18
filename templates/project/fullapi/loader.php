<?php

$loader = new \Phalcon\Loader();

$loader->registerDirs(
    [
        $config->application->modelsDir,
        $config->application->controllersDir,
        $config->application->migrationsDir,
    ]
)->register();
