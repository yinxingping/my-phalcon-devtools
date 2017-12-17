### 主要功能

用于封装底层数据库访问的基础API

### 具体环境要求

1. 操作系统：Linux
2. WEB服务器：Nginx + PHP-FPM(7.1)
3. PHP框架：Phalcon(3.2)
4. 数据库：Mysql
5. 缓存服务：Redis

### 注意事项

1. 用工具生成model时要使用参数：--excludefields=updated_at
2. 用工具生成model时.env部分没有生效，所以需要在config.php中修改数据库连接相关默认参数为实际开发环境的参数
3. 为了使用自动添加created_at的功能，数据库字段created_at必须设置默认为null

