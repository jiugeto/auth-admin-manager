<?php
namespace JiugeTo\AuthAdminManager\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Contracts\View\Factory as ViewFactory;
use JiugeTo\AuthAdminManager\Models\ActionModel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use JiugeTo\AuthAdminManager\Models\RoleActionModel;

class Controller extends BaseController
{
    /**
     * 基本控制器
     */

    protected static $limit = 10;//每页显示的记录数
    protected static $prefix;
    protected static $view;
    protected static $crumbs;

    /**
     * 返回错误格式
     * code：0操作成功，1没有数据
     */
    public static function toResponse($code=0)
    {
        if ($code==0) {
            return array(
                'code' => 0,
                'msg' => '操作成功！',
            );
        } else if ($code==-1) {
            return array(
                'code' => -1,
                'msg' => '数据必填！',
            );
        } else {
            return array(
                'code' => $code,
                'msg' => '未知错误！',
            );
        }
    }

    /**
     * 获取左侧菜单列表
     */
    public static function getLeftMenus()
    {
        $actionArr = array();
        $actions = ActionModel::where('pid',0)->where('del',0)->get();
        if (!count($actions)) { return '没有操作！'; }
        foreach ($actions as $k=>$action) {
            $actionArr[$k]['id'] = $action->id;
            $actionArr[$k]['name'] = $action->name;
            $actionArr[$k]['subs'] = $action->getSubsByPid();
        }
        return $actionArr;
    }

    /**
     * 判断登陆、权限
     */
    public static function isLogin()
    {
        if (!Session::has('admin')) {
            echo "<script>alert('没有登陆！');window.location.href='".config('jiuge.adminUrl')."/login"."';</script>";exit;
        }
        self::getAuthLimit();//权限开关
    }

    /**
     * 限制当前的操作
     */
    public static function getAuthLimit()
    {
        //获取当前控制器、方法
        $action = Route::current()->getActionName();
        list($class, $method) = explode('@', $action);
        $prefix = $class.'-'.$method;
        //获取当前操作列表
        $actions = self::getCurrAuths();
        $actionArr = array();
        if (count($actions)) {
            foreach ($actions as $action) {
                $actionArr[] = $action->namespace.'\\'.$action->controller.'-'.$action->action;
            }
        }
//        dd($prefix,$actionArr);
        if (!in_array($prefix,$actionArr)) {
            echo "<script>alert('你没有权限！');history.go(-1);</script>";exit;
        }
    }

    /**
     * 操作权限控制
     */
    public static function getCurrAuths()
    {
        $role = Session::get('admin.role');
        $roleActions = RoleActionModel::where('role',$role)
            ->where('del',0)
            ->get();
        $actionIdArr = array();
        foreach ($roleActions as $roleAction) {
            $actionIdArr[] = $roleActions->action;
        }
        return ActionModel::whereIn('id',$actionIdArr)
            ->where('del',0)
            ->get();
    }
}