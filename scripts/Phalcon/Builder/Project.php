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

namespace Phalcon\Builder;

use Phalcon\Builder\Project\Cli;
use Phalcon\Builder\Project\Micro;
use Phalcon\Builder\Project\Simple;
use Phalcon\Builder\Project\Modules;
use Phalcon\Builder\Project\BaseApi;
use Phalcon\Builder\Project\SimpleApi;
use Phalcon\Builder\Project\Api;
use Phalcon\Utils\FsUtils;
use SplFileInfo;

/**
 * Project Builder
 *
 * Builder to create application skeletons
 *
 * @package  Phalcon\Builder
 */
class Project extends Component
{
    CONST TYPE_MICRO   = 'micro';
    CONST TYPE_SIMPLE  = 'simple';
    CONST TYPE_MODULES = 'modules';
    CONST TYPE_CLI     = 'cli';
    CONST TYPE_BASEAPI    = 'baseapi';
    CONST TYPE_SIMPLEAPI= 'simpleapi';
    CONST TYPE_API= 'api';

    /**
     * Current Project Type
     * @var string
     */
    private $currentType = self::TYPE_SIMPLE;

    /**
     * Available Project Types
     * @var array
     */
    private $_types = [
        self::TYPE_MICRO   => Micro::class,
        self::TYPE_SIMPLE  => Simple::class,
        self::TYPE_MODULES => Modules::class,
        self::TYPE_CLI     => Cli::class,
        self::TYPE_BASEAPI    => BaseApi::class,
        self::TYPE_SIMPLEAPI  => SimpleApi::class,
        self::TYPE_API  => Api::class,
    ];

    /**
     * Project build
     *
     * @return mixed
     * @throws \Phalcon\Builder\BuilderException
     */
    public function build()
    {
        if ($this->options->contains('directory')) {
            $this->path->setRootPath($this->options->get('directory'));
        }

        $templatePath = str_replace('scripts/' . str_replace('\\', DIRECTORY_SEPARATOR, __CLASS__) . '.php', '', __FILE__) . 'templates';
        if ($this->options->contains('templatePath')) {
            $templatePath = $this->options->get('templatePath');
        }

        if ($this->path->hasPhalconDir()) {
            throw new BuilderException('Projects cannot be created inside Phalcon projects.');
        }

        $this->currentType = $this->options->get('type');

        if (!isset($this->_types[$this->currentType])) {
            throw new BuilderException(sprintf(
                'Type "%s" is not a valid type. Choose among [%s] ',
                $this->currentType,
                implode(', ', array_keys($this->_types))
            ));
        }

        $builderClass = $this->_types[$this->currentType];

        if ($this->options->contains('name')) {
            $this->path->appendRootPath($this->options->get('name'));
        }

        if (file_exists($this->path->getRootPath())) {
            throw new BuilderException(sprintf('Directory %s already exists.', $this->path->getRootPath()));
        }

        if (!mkdir($this->path->getRootPath(), 0777, true)) {
            throw new BuilderException(sprintf('Unable create project directory %s', $this->path->getRootPath()));
        }

        if (!is_writable($this->path->getRootPath())) {
            throw new BuilderException(sprintf('Directory %s is not writable.', $this->path->getRootPath()));
        }

        $this->options->offsetSet('templatePath', $templatePath);
        $this->options->offsetSet('projectPath', $this->path->getRootPath());

        /** @var \Phalcon\Builder\Project\ProjectBuilder $builder */
        $builder = new $builderClass($this->options);

        $success = $builder->build();

        $root = new SplFileInfo($this->path->getRootPath('public'));
        $fsUtils = new FsUtils();

        //项目类别名称中带api时，不生成css和js目录
        if (strpos($this->currentType, 'api') === false) {
            $fsUtils->setDirectoryPermission($root, ['css' => 0777, 'js' => 0777]);
        }

        //安装指定的程序包
        if ($composer=exec('which composer') && file_exists($this->path->getRootPath() . 'composer.json')) {
            $cmd = "cd " . $this->path->getRootPath() . ";composer install";
            `$cmd`;
        }

        if ($success === true) {
            $this->notifySuccess(sprintf(
                "Project '%s' was successfully created.",
                $this->options->get('name')
            ));
        }

        return $success;
    }
}
