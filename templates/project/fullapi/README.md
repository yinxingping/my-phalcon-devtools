# 主要功能

适合于直接为客户端提供服务的API，可以访问数据库，也可以请求其他后端API

## 具体环境要求

0. PHP-FPM >= 7.0
1. PHP框架：Phalcon >= 3.2
2. 开发工具：[my-phalcon-devtools](https://github.com/yinxingping/my-phalcon-devtools)

## 注意事项

1. 用工具生成model时要使用参数：--excludefields=updated_at
2. 用工具生成model时.env部分没有生效，所以需要在config.php中修改数据库连接相关默认参数为实际开发环境的参数
3. 为了使用自动添加created_at的功能，数据库字段created_at必须设置默认为null

