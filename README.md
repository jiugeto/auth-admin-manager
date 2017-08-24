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
- 安装 laravel-ide-helper 后，在后台每个自定义控制器：`use JiugeTo\AuthAdminManager\Controllers\ViewController as View;`
- 这样就可以在构造方法中共享后台左侧菜单的数据了： `view()->share('leftMenus',View::getLeftMenus());`
- 默认账户：admin；密码：123456，登陆后请自行修改。
- 请添加登陆权限中间件，路由组中：`'middleware' => ['web','admin'],`
- 在 /app/Http/Middleware/ 目录下：`AdminMiddle.php`
```sql
<?php
namespace App\Http\Middleware;

use Closure;
use Session;
use JiugeTo\AuthAdminManager\Models\ActionModel;
use Illuminate\Support\Facades\Session;
use JiugeTo\AuthAdminManager\Models\RoleActionModel;

class AdminMiddle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //登陆判断
        if (!Session::has('admin')) {
            return redirect(env('DOMAIN').'/culadmin/login');
        }
        $this->getAuthLimit();//权限开关
        return $next($request);
    }

    /**
     * 限制当前的操作
     */
    public function getAuthLimit()
    {
        //获取当前控制器、方法
        $action = \Route::current()->getActionName();
        list($class, $method) = explode('@', $action);
        $prefix = $class.'-'.$method;
        //获取当前操作列表
        $actions = $this->getCurrAuths();
        $actionArr = array();
        if (count($actions)) {
            foreach ($actions as $action) {
                $actionArr[] = $action->namespace.'\\'.$action->controller.'-'.$action->action;
            }
        }
        if (!in_array($prefix,$actionArr)) {
            echo "<script>alert('你没有权限！');history.go(-1);</script>";exit;
        }
    }

    /**
     * 操作权限控制
     */
    public function getCurrAuths()
    {
        $role = Session::get('admin.role');
        $roleModels = RoleActionModel::where('role',$role)
            ->where('del',0)
            ->get();
        $actionIdArr = array();
        foreach ($roleModels as $roleModel) {
            $actionIdArr[] = $roleModel->action;
        }
        return ActionModel::whereIn('id',$actionIdArr)
            ->where('del',0)
            ->get();
    }
}
```
- 然后在 /app/Http/Kernel.php 中，$routeMiddleware 下面添加：`'admin' => \App\Http\Middleware\AdminMiddle::class,`