# My Phalcon Devtools

## 与phalcon官方devtools的区别 

根据自身项目需求，对原开发工具做了若干小改动，并增加了新的项目模版
1. 小改动
    * 修正了即使新增的项目模版不需要css和js，都会自动创建相关目录的问题；
    * 出于安全和部署便利考虑，参考laravel增加了.env环境参数设置文件
2. 新增项目模版
    * baseapi
    * simpleapi

## baseapi项目模版介绍

1. 基于micro项目模版修改，适用于紧贴数据库的基础API项目；
2. 去掉了所有与前端相关的处理（如css,js等）；
3. 路由处理替换为controller模式，app.php功能更专一；
4. 提供了统一的json输出方法（包括错误和异常状况下的输出）；
5. 增加了统一的输出状态配置文件: config/status.php；
6. production与其他环境采用不同的metadata缓存方式，提高性能；
7. 增加了错误日志和SQL日志；

## simpleapi项目模版介绍

1. 基于micro项目模版修改，适用于不使用数据库，仅提供简单功能的API；
2. 去掉了所有与前端相关的处理（如css,js等）；
3. 提供了统一的json输出方法（包括错误和异常状况下的输出）；
4. 增加了统一的输出状态配置文件: config/status.php；
5. production与其他环境采用不同的metadata缓存方式，提高性能；
6. 增加了错误日志；

## 其余注意事项
1. 用工具生成model时要使用参数：--excludefields=updated_at
2. 用工具生成model时.env部分没有生效，所以需要在config.php中修改数据库连接相关默认参数为实际开发环境的参数
3. 为了使用自动添加created_at的功能，数据库字段created_at必须设置默认为null

## 系统要求

* PHP >= 7.0
* Phalcon >= 3.2.0
* Composer

## 相关链接

phalcon-devtools原项目地址：
https://github.com/phalcon/phalcon-devtools

phalcon官网：
https://phalconphp.com
