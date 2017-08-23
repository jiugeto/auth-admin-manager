<?php
namespace JiugeTo\AuthAdminManager\Controllers;

use Illuminate\Support\Facades\Input;
use JiugeTo\AuthAdminManager\Controllers\ViewController as View;
use JiugeTo\AuthAdminManager\Models\RoleModel;

class RoleController extends Controller
{
    /**
     * 角色
     */

    protected static $url = 'role';//当前路由
    protected static $formElementArr = array(
        //array(表单选项,中文名称,name字段名称,表单提示,js验证规则)
        array(1,'角色名称','name','角色名称',1),
    );
    protected static $formIndexArr = array(
        'id' => 'ID',
        'name' => '角色名称',
        'createTime' => '创建时间',
    );
    protected static $formShowArr = array(
        'id' => 'ID',
        'name' => '角色名称',
        'createTime' => '创建时间',
    );

    public static function index()
    {
        $shares = self::getShare();
        $datas = array();
        $models = RoleModel::paginate(self::$limit);
        if (!$models->total()) {
            $datas = array(); $total = 0;
        } else {
            foreach ($models as $k=>$model) {
                $datas[$k] = self::getArrByModel($model);
            }
            $total = $models->total();
        }
//        $datas->limit = self::$limit;
        $dataArr = array(
            'prefix' => $shares['prefix'],
            'crumbs' => $shares['crumbs'],
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
            'selArr' => self::$formElementArr,
            'optionArr' => array(),
            'view' => 'create',
        );
        return View::index($dataArr);
    }

    public static function store()
    {
        $shares = self::getShare();
        $data = self::getData();
        $data['created_at'] = time();
        RoleModel::create($data);
        return redirect($shares['prefix']);
    }

    public static function edit()
    {
        $shares = self::getShare();
        $dataArr = array(
            'prefix' => $shares['prefix'],
            'crumbs' => $shares['crumbs'],
            'leftMenus' => self::getLeftMenus(),
            'selArr' => self::$formElementArr,
            'optionArr' => array(),
            'data' => self::getModelById(Input::get('id')),
            'view' => 'edit',
        );
        return View::index($dataArr);
    }

    public static function update()
    {
        $shares = self::getShare();
        $model = self::getModelById(Input::get('id'));
        $data = self::getData();
        $data['updated_at'] = time();
        RoleModel::where('id',Input::get('id'))->update($data);
        return redirect($shares['prefix']);
    }

    public static function show()
    {
        $shares = self::getShare();
        $dataArr = array(
            'prefix' => $shares['prefix'],
            'crumbs' => $shares['crumbs'],
            'leftMenus' => self::getLeftMenus(),
            'showArr' => self::$formShowArr,
            'data' => self::getModelById(Input::get('id')),
            'view' => 'show',
        );
        return View::index($dataArr);
    }

    /**
     * 收集、验证
     */
    public static function getData()
    {
        $data = Input::all();
        if (!$data['name']) {
            return array('code'=> -1, 'msg'=> '信息必填！');
        }
        return array(
            'name' => $data['name'],
        );
    }

    /**
     * 通过 ID 获取 Model
     */
    public static function getModelById($id)
    {
        $model = RoleModel::find($id);
        if (!$model) { return array('code'=> -1, 'msg'=> '记录不存在！'); }
        return self::getArrByModel($model);
    }

    /**
     * Model 转化为 Array
     */
    public static function getArrByModel($model)
    {
        return array(
            'id' => $model->id,
            'name' => $model->name,
            'createTime' => $model->createTime(),
        );
    }

    /**
     * 获取共享数据
     */
    public static function getShare()
    {
        self::$prefix = '/admin/'.self::$url;
        self::$crumbs['module'] = '权限管理';
        self::$crumbs['func']['url'] = self::$url;
        self::$crumbs['func']['name'] = '角色';
        return array(
            'prefix' => self::$prefix,
            'crumbs' => self::$crumbs,
        );
    }
}