<?php

$loader = new \Phalcon\Loader();

$loader->registerDirs(
    [
        $config->application->modelsDir
    ]
)->register();
