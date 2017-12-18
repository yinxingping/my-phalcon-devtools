# 关于My Phalcon Devtools

本项目基于Phalcon官方开发工具Phalcon devtools二次开发，根据实际开发需要，对原有模版进行了改进，并增加了若干常用API模版，帮你大幅提升开发效率。

一、整体变动
---
#### 1、增加.env配置

官方提供的模版没有将开发、测试、生产环境的配置文件分开，对于利用github公开方式托管代码的个人和小团队来说配置文件更涉及安全性，my-phalcon-devtools借助<code>vlucas/phpdotenv</code>实现配置和代码分开，开发环境的.env开发人员自己管理，测试和生产环境的.env由专门的运维人员管理和发布。

#### 2、增加默认时区

时区默认设置为"中国-上海"，避免时间混乱问题

#### 3、增加日志处理

根据.env中的APP_ENV设置（dev,test,production），production仅输出重要的日志，且日志默认输出到项目根目录下的logs文件夹，名称按APP_NAME和日期定义

#### 4、项目增加默认的README.md

可以利用README.md对项目功能、环境、部署等进行说明

#### 5、取消的功能
* 取消webtools工具
* 取消ini支持

#### 6、顶层命名空间的修改
官方开发工具项目名称为project_name，选择"modules"应用类型时，顶层命名空间为Project_name，my-phalcon-devtools中改为ProjectName


二、数据库类项目模版
---
#### 1、增加了数据库访问日志

通过这个日志，你可以观察并统计数据请求类型以及耗时状况，帮助你随时发现数据库请求瓶颈并及时进行优化

#### 2、利用metadata缓存提高数据库访问性能

默认生产环境使用Redis缓存metadata，其他环境则使用Memcache（仅当次请求周期内有效，Phalcon自带，不需要单独安装）

#### 3、关于数据表中的created_at和updated_at字段
 
* 这两个字段在每个数据表中都必须存在，且按如下定义
    ```
    created_at datetime comment '创建时间',
    updated_at timestamp comment '更新时间', 
    ```   
* phalcon命令执行时.env还没有生效，故用phalcon命令创建model前需修改config.php中数据库设置默认值为当前开发环境实际值,如下面的代码片段
    ``` 
    'database' => [
        'adapter'    => 'Mysql',
        'host'     => getenv('DB_HOST') ?: 'localhost',
        'username' => getenv('DB_USERNAME') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: 'test123456',
        'dbname'   => getenv('DB_DATABASE') ?: '',
        'charset'    => 'utf8',
    ],
    ```
* 使用phalcon命令创建模型时，一定要使用 --excludefields=udated_at 参数
* 按照如上设置后，插入数据时created_at和updated_at将自动生成，created_at是通过工具生成model的以下代码实现
    ``` 
   public function initialize()
    {
        $this->setSchema("qin_user");
        $this->setSource("user");
        $this->hasMany('id', 'Child', 'user_id', ['alias' => 'Child']);

        // created_at必须为nullable=true
        $this->addBehavior(
            new \Phalcon\Mvc\Model\Behavior\Timestampable(
                [
                    'beforeCreate' => [
                        'field' => 'created_at',
                        'format'=> 'Y-m-d H:i:s',
                    ]
                ]
            )
        );
    }
    ```
#### 4、关于Mysql

鉴于Mysql流行程度以及为了代码简洁度，项目模版仅绑定了Mysql。但开发者随时可以更换为自己喜欢的其他数据库。

#### 5、目前支持数据库的项目模版
* cli
* microweb
* web
* baseapi
* fullapi

三、API类项目模版
---
#### 1、紧凑的结构和高效的性能

API类项目模版都采用了Phalcon提供的MVC微应用框架（Phalcon/Mvc/Micro），适合于瘦应用，很少的代码即可实现强大的功能。

#### 2、精简的代码

去除了所有与前端有关的代码，包括css, js和 view 相关的代码，并根据需求提供了多个不同用途的API项目模版供选用，最简单的项目你仅需添加几行代码即可实现

#### 3、提供了统一的API输出

提供了统一的json输出方法（包括错误和异常状况下的输出）和输出状态配置文件。输出格式为：

```
{
    code: 0,
    status: 'ok',
    detail: [
        {
            'user_id': 1,
            'user_name': 'david',
            'sex': 1,
            'age': 13
        },
        {
            'user_id': 2,
            'user_name': 'tom',
            'sex': 2,
            'age': 13
        }
    ]
}
```

#### 4、目前提供的API类项目模版
* simpleapi
* baseapi
* fullapi

四、网站类项目模版
---
#### 1、前端依赖模块
* jQuery 升级为3.2.1
* Bootstrap 升级为4.0.0-beta2

#### 2、PHP模版支持
* VoltEngine
* PhpEngine
* Smarty （后续增加）

#### 3、目前提供的网站类项目模版
* microweb
* web
* modules

五、my-phalcon-devtools项目模版介绍
---
#### 1、cli
适合开发命令行应用，如爬虫、后台处理等

#### 2、microweb
适合开发微网站，如官网、微信小程序等

#### 3、web
适合开发功能完整的网站

#### 4、simpleapi
适合开发不使用数据库的简单API，如文件请求、搜索引擎等的封装接口

#### 5、baseapi
适合开发封装底层数据库访问的基础API

#### 6、fullapi
适合开发直接为客户端提供服务的中间API，可以通过数据库和集成其他API扩展功能

#### 7、modules
适合开发多模块的复杂应用，目前提供一个CLI接口和一个前端接口，可以通过phalcon的模块命令添加新模块

六、系统要求
---
* PHP >= 7.0
* Phalcon >= 3.2.0
* Composer

七、推荐环境配置
---
* 操作系统：Linux
* Web服务器：Nginx + PHP-FPM 7.0+
* Phalcon >= 3.2.0
* 数据库：MySQL
* 缓存和Session：Redis

八、安装和配置
---

``` 
# 以下配置可用于linux和macOS

# 第一步：下载my-phalcon-devtools到指定目录，如/home/myname/public
cd /home/myname/public;
git clone git@github.com:yinxingping/my-phalcon-devtools.git

# 第二步：配置~/.bashrc,添加以下项
export PTOOLSPATH=/home/myname/public/my-phalcon-devtools
export PATH=$PTOOLSPATH:$PATH

# 第三步：验证
cd /home/myname/Workspace;
phalcon project my_first_phalcon microweb

```

看到绿色的"Success: Project 'my_first_phalcon' was successfully created..."即表示成功。

九、相关链接
---

phalcon-devtools原项目地址：
https://github.com/phalcon/phalcon-devtools

phalcon官网：
https://phalconphp.com
