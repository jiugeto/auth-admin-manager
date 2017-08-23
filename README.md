#auth-admin-manager

# [auth-admin-manager](https://github.com/jiugeto/auth-admin-manager) for Laravel 5.4.*

# 默认当前数据库的几张表：
- auth_admin、auth_role、auth_action、auth_role_action、
- 在MySQL命令行：
- `use database database_name;`
- `source 项目的绝对路径/vendor/jiugeto/auth-server-laravel5/src/DataBases/auth_manager.sql;`

## 方法一：Composer安装
- 安装_ide_helper.php，在项目根目录，命令行执行：`composer require barryvdh/laravel-ide-helper`
- 然后在config/app.php的providers中添加：Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
- 在项目根目录，命令行执行：
```sql
php artisan clear-compiled
php artisan ide-helper:generate
php artisan optimize
```
- 安装jiugeto/auth-admin-manager：
- 在composer.json的require中加上：`"jiugeto/auth-admin-manager" : "dev-master"`
- 然后在项目中，命令行执行：`composer update`
## 方法二：手动下载安装
- 假如默认更新的不是最新版，则会出错；那么，直接下载包，解压后，放在下面vendor/下面

## 使用方式
- 在路由中加上：
`include_once('../vendor/jiugeto/auth-admin-manager/src/Route/AdminManagerRoute.php');`
- 将 /vendor/jiugeto/auth-admin-manager/config/jiuge.php 拷贝到 /config/ 下面
- 将 /vendor/jiugeto/auth-admin-manager/public-admin-manager 拷贝到 /public 下面