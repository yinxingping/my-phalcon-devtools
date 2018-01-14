<?php

/*
  +------------------------------------------------------------------------+
  | Phalcon Developer Tools                                                |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2016 Phalcon Team (https://www.phalconphp.com)      |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file LICENSE.txt.                             |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconphp.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Authors: Andres Gutierrez <andres@phalconphp.com>                      |
  |          Eduar Carvajal <eduar@phalconphp.com>                         |
  +------------------------------------------------------------------------+
*/

namespace Phalcon\Builder\Project;

/**
 * FullApi
 *
 * Builder to create FullApi application skeletons
 *
 * @package Phalcon\Builder\Project
 */
class FullApi extends ProjectBuilder
{
    /**
     * Project directories
     * @var array
     */
    protected $projectDirectories = [
        'app',
        'app/config',
        'app/controllers',
        'logs',
        'public',
        '.phalcon'
    ];

    /**
     * Creates the configuration
     *
     * @return $this
     */
    private function createConfig()
    {
        //仅支持config.php
        $type = 'php';

        $getFile = $this->options->get('templatePath') . '/project/fullapi/config.' . $type;
        $putFile = $this->options->get('projectPath') . 'app/config/config.' . $type;
        $this->generateFile($getFile, $putFile, $this->options->get('name'));

        $getFile = $this->options->get('templatePath') . '/project/fullapi/services.php';
        $putFile = $this->options->get('projectPath') . 'app/config/services.php';
        $this->generateFile($getFile, $putFile, $this->options->get('name'));

        $getFile = $this->options->get('templatePath') . '/project/fullapi/loader.php';
        $putFile = $this->options->get('projectPath') . 'app/config/loader.php';
        $this->generateFile($getFile, $putFile, $this->options->get('name'));

        $getFile = $this->options->get('templatePath') . '/project/fullapi/app.php';
        $putFile = $this->options->get('projectPath') . 'app/app.php';
        $this->generateFile($getFile, $putFile, $this->options->get('name'));

        $getFile = $this->options->get('templatePath') . '/project/fullapi/status.php';
        $putFile = $this->options->get('projectPath') . 'app/config/status.php';
        $this->generateFile($getFile, $putFile);

        $getFile = $this->options->get('templatePath') . '/project/fullapi/composer.json';
        $putFile = $this->options->get('projectPath') . 'composer.json';
        $this->generateFile($getFile, $putFile);

        $getFile = $this->options->get('templatePath') . '/project/fullapi/env.example';
        $putFile = $this->options->get('projectPath') . '.env';
        $this->generateFile($getFile, $putFile);

        $getFile = $this->options->get('templatePath') . '/project/fullapi/env.example';
        $putFile = $this->options->get('projectPath') . 'env.example';
        $this->generateFile($getFile, $putFile);

        $getFile = $this->options->get('templatePath') . '/project/fullapi/README.md';
        $putFile = $this->options->get('projectPath') . 'README.md';
        $this->generateFile($getFile, $putFile);

        return $this;
    }

    /**
     * Create Bootstrap file by default of application
     *
     * @return $this
     */
    private function createBootstrapFile()
    {
        $getFile = $this->options->get('templatePath') . '/project/fullapi/index.php';
        $putFile = $this->options->get('projectPath') . 'public/index.php';
        $this->generateFile($getFile, $putFile);

        return $this;
    }

    /**
     * create controllers
     */
    private function createControllers()
    {
        $getFile = $this->options->get('templatePath') . '/project/fullapi/ControllerBase.php';
        $putFile = $this->options->get('projectPath') . 'app/controllers/ControllerBase.php';
        $this->generateFile($getFile, $putFile);

        $getFile = $this->options->get('templatePath') . '/project/fullapi/IndexController.php';
        $putFile = $this->options->get('projectPath') . 'app/controllers/IndexController.php';
        $this->generateFile($getFile, $putFile);

        return $this;
    }

    /**
     * Build project
     *
     * @return bool
     */
    public function build()
    {
        $this
            ->buildDirectories()
            ->createConfig()
            ->createBootstrapFile()
            ->createControllers();

        return true;
    }
}

