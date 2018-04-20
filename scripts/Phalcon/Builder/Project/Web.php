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
  |          Serghei Iakovlev <serghei@phalconphp.com>                     |
  +------------------------------------------------------------------------+
*/

namespace Phalcon\Builder\Project;

use Phalcon\Builder\Controller as ControllerBuilder;
use Phalcon\Web\Tools;

/**
 * Web
 *
 * Builder to create Web application skeletons
 *
 * @package Phalcon\Builder\Project
 */
class Web extends ProjectBuilder
{
    use ProjectAware;

    /**
     * Project directories
     * @var array
     */
    protected $projectDirectories = [
        'app',
        'app/views',
        'app/config',
        'app/models',
        'app/controllers',
        'app/library',
        'app/migrations',
        'app/views/index',
        'app/views/layouts',
        'cache',
        'schemas',
        'logs',
        'public',
        'public/img',
        'public/css',
        'public/temp',
        'public/files',
        'public/js',
        '.phalcon'
    ];

    /**
     * Create indexController file
     *
     * @return $this
     */
    private function createControllerFile()
    {
        $builder = new ControllerBuilder([
            'name'           => 'index',
            'directory'      => $this->options->get('projectPath') ,
            'controllersDir' => $this->options->get('projectPath')  . 'app/controllers',
            'baseClass'      => 'ControllerBase'
        ]);

        $builder->build();

        return $this;
    }

    /**
     * Create .htaccess files by default of application
     *
     * @return $this
     */
    private function createHtaccessFiles()
    {
        if (file_exists($this->options->get('projectPath') . '.htaccess') == false) {
            $code = '<IfModule mod_rewrite.c>'.PHP_EOL.
                "\t".'RewriteEngine on'.PHP_EOL.
                "\t".'RewriteRule  ^$ public/    [L]'.PHP_EOL.
                "\t".'RewriteRule  (.*) public/$1 [L]'.PHP_EOL.
                '</IfModule>';
            file_put_contents($this->options->get('projectPath').'.htaccess', $code);
        }

        if (file_exists($this->options->get('projectPath') . 'public/.htaccess') == false) {
            file_put_contents(
                $this->options->get('projectPath').'public/.htaccess',
                file_get_contents($this->options->get('templatePath') . '/project/web/htaccess')
            );
        }

        if (file_exists($this->options->get('projectPath').'index.html') == false) {
            $code = '<html><body><h1>Mod-Rewrite is not enabled</h1><p>Please enable rewrite module on your web server to continue</body></html>';
            file_put_contents($this->options->get('projectPath').'index.html', $code);
        }

        return $this;
    }

    /**
     * Create view files by default
     *
     * @return $this
     */
    private function createIndexViewFiles()
    {
        $engine = $this->options->get('templateEngine') == 'volt' ? 'volt' : 'phtml';

        $getFile = $this->options->get('templatePath') . '/project/web/views/index.' . $engine;
        $putFile = $this->options->get('projectPath').'app/views/index.' . $engine;
        $this->generateFile($getFile, $putFile);

        $getFile = $this->options->get('templatePath') . '/project/web/views/index/index.' . $engine;
        $putFile = $this->options->get('projectPath').'app/views/index/index.' . $engine;
        $this->generateFile($getFile, $putFile);

        return $this;
    }

    /**
     * Creates the configuration
     *
     * @return $this
     */
    private function createConfig()
    {
        $type = $this->options->contains('useConfigIni') ? 'ini' : 'php';

        $getFile = $this->options->get('templatePath') . '/project/web/config.' . $type;
        $putFile = $this->options->get('projectPath') . 'app/config/config.' . $type;
        $this->generateFile($getFile, $putFile, $this->options->get('name'));

        $getFile = $this->options->get('templatePath') . '/project/web/loader.php';
        $putFile = $this->options->get('projectPath') . 'app/config/loader.php';
        $this->generateFile($getFile, $putFile, $this->options->get('name'));

        $getFile = $this->options->get('templatePath') . '/project/web/services.php';
        $putFile = $this->options->get('projectPath') . 'app/config/services.php';
        $this->generateFile($getFile, $putFile, $this->options->get('name'));

        $getFile = $this->options->get('templatePath') . '/project/web/router.php';
        $putFile = $this->options->get('projectPath') . 'app/config/router.php';
        $this->generateFile($getFile, $putFile, $this->options->get('name'));

        $getFile = $this->options->get('templatePath') . '/project/web/composer.json';
        $putFile = $this->options->get('projectPath') . 'composer.json';
        $this->generateFile($getFile, $putFile);

        $getFile = $this->options->get('templatePath') . '/project/web/env.example';
        $putFile = $this->options->get('projectPath') . '.env';
        $this->generateFile($getFile, $putFile);

        $getFile = $this->options->get('templatePath') . '/project/web/env.example';
        $putFile = $this->options->get('projectPath') . 'env.example';
        $this->generateFile($getFile, $putFile);

        $getFile = $this->options->get('templatePath') . '/project/web/README.md';
        $putFile = $this->options->get('projectPath') . 'README.md';
        $this->generateFile($getFile, $putFile);

        return $this;
    }

    /**
     * Create ControllerBase
     *
     * @return $this
     */
    private function createControllers()
    {
        $getFile = $this->options->get('templatePath') . '/project/web/ControllerBase.php';
        $putFile = $this->options->get('projectPath') . 'app/controllers/ControllerBase.php';
        $this->generateFile($getFile, $putFile, $this->options->get('name'));

        $getFile = $this->options->get('templatePath') . '/project/web/IndexController.php';
        $putFile = $this->options->get('projectPath') . 'app/controllers/IndexController.php';
        $this->generateFile($getFile, $putFile);

        return $this;
    }

    /**
     * Create Bootstrap file by default of application
     *
     * @return $this
     */
    private function createBootstrapFiles()
    {
        $getFile = $this->options->get('templatePath') . '/project/web/index.php';
        $putFile = $this->options->get('projectPath') . 'public/index.php';
        $this->generateFile($getFile, $putFile);

        return $this;
    }

    /**
     * create model base
     */
    private function createModelBase()
    {
        $getFile = $this->options->get('templatePath') . '/project/baseapi/ModelBase.php';
        $putFile = $this->options->get('projectPath') . 'app/models/ModelBase.php';
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
            // ->getVariableValues()
            ->createConfig()
            ->createBootstrapFiles()
            // ->createHtaccessFiles()
            ->createControllers()
            ->createIndexViewFiles()
            // ->createControllerFile()
            ->createControllers()
            ->createModelBase()
            /* ->createHtrouterFile()*/;

        $this->options->contains('enableWebTools') && Tools::install($this->options->get('projectPath'));

        return true;
    }
}
