<?php
namespace JiugeTo\AuthAdminManager\Controllers;

use JiugeTo\AuthAdminManager\Models\ActionModel;
use JiugeTo\AuthAdminManager\Models\RoleActionModel;
use JiugeTo\AuthAdminManager\Controllers\ViewController as View;
use Illuminate\Support\Facades\Input;
use JiugeTo\AuthAdminManager\Models\RoleModel;

class RoleActionController extends Controller
{
    /**
     * 权限分配
     */

    protected static $url = 'roleaction';//当前路由
    protected static $formElementArr = array(
        //array(表单选项,中文名称,name字段名称,表单提示,js验证规则)
        array(5,'角色组','role','角色组',6),
        array(5,'操作组','action','操作组',6),
    );
    protected static $formIndexArr = array(
        'id' => 'ID',
        'roleName' => '角色名称',
        'actionNum' => '操作数量',
        'createTime' => '创建时间',
    );
    protected static $formShowArr = array(
        'id' => 'ID',
        'roleName' => '角色名称',
        'actionArr' => '角色组',
        'createTime' => '创建时间',
    );

    public static function index()
    {
        $shares = self::getShare();
        $datas = array();
        $models = RoleActionModel::paginate(100);
        if (!$models->total()) {
            $datas = array(); $total = 0;
        } else {
            foreach ($models as $k=>$model) {
                $datas[$k] = self::getArrByModel($model);
            }
            $total = $models->total();
        }
        $dataArr = array(
            'prefix' => $shares['prefix'],
            'crumbs' => $shares['crumbs'],
            'leftMenus' => self::getLeftMenus(),
            'datas' => $datas,
            'indexArr' => self::$formIndexArr,
            'view' => 'index',
        );
        return View::index($dataArr);
    }

    public static function create()
    {
        $shares = self::getShare();
        $dataArr = array(
            'prefix' => $shares['prefix'],
            'crumbs' => $shares['crumbs'],
            'leftMenus' => self::getLeftMenus(),
            'selArr' => self::$formElementArr,
            'optionArr' => array(
                'role' => self::getRoles(),
                'action' => self::getActions(),
            ),
            'view' => 'create',
        );
        return View::index($dataArr);
    }

    public static function store()
    {
        $shares = self::getShare();
        $data = self::getData();
        $data['created_at'] = time();
//        dd($data);
        ActionModel::create($data);
        return redirect($shares['prefix']);
    }

    /**
     * 获取共享数据
     */
    public static function getShare()
    {
        self::$prefix = '/admin/'.self::$url;
        self::$crumbs['module'] = '权限管理';
        self::$crumbs['func']['url'] = self::$url;
        self::$crumbs['func']['name'] = '权限分配';
        return array(
            'prefix' => self::$prefix,
            'crumbs' => self::$crumbs,
        );
    }

    /**
     * 获取所有角色
     */
    public static function getRoles()
    {
        $roleArr = array();
        $roles = RoleModel::all();
        if (count($roles)) {
            foreach ($roles as $role) {
                $roleArr[$role->id] = $role->name;
            }
        }
        return $roleArr;
    }

    /**
     * 获取所有操作
     */
    public static function getActions()
    {
        $actionArr = array();
        $actions = ActionModel::where('pid','>',0)
            ->where('del',0)
            ->get();
        if (count($actions)) {
            foreach ($actions as $action) {
                $actionArr[$action->id] = $action->name;
            }
        }
        return $actionArr;
    }
}