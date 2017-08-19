#auth-admin-manager

# [auth-admin-manager](https://github.com/jiugeto/auth-admin-manager) for Laravel 5.4.*

# 默认当前数据库的几张表：
- auth_admin、auth_role、auth_action、auth_role_action、
- 在MySQL命令行：
- `use database database_name;`
- `source 项目的绝对路径/vendor/jiugeto/auth-server-laravel5/src/DataBases/auth_manager.sql;`

## 方法一：Composer安装
- 在composer.json的require中加上一行：`"jiugeto/auth-admin-manager" : "dev-master"`
- 然后在项目中，命令行执行：`composer update`
## 方法二：手动下载安装
- 假如默认更新的不是最新版，则会出错；那么，直接下载包，解压后，放在下面vendor/下面

## 使用方式