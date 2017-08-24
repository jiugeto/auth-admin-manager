<?php
namespace JiugeTo\AuthAdminManager\Controllers;

use JiugeTo\AuthAdminManager\Models\ActionModel;
use JiugeTo\AuthAdminManager\Controllers\ViewController as View;
use Illuminate\Support\Facades\Input;

class ActionController extends Controller
{
    /**
     * 操作
     */

    protected static $url = 'action';//当前路由
    protected static $formElementArr = array(
        //array(表单选项,中文名称,name字段名称,表单提示,js验证规则)
        array(1,'操作名称','name','操作名称',1),
        array(1,'命名空间','namespace','命名空间',8),
        array(1,'控制器','controller','控制器',1),
        array(1,'路由','url','请求路由',8),
        array(1,'方法','action','方法',1),
        array(5,'父级','pid','父级',6),
        array(5,'左侧显示','left_show','左侧显示',6),
    );
    protected static $formIndexArr = array(
        'id' => 'ID',
        'name' => '操作名称',
//        'namespace' => '命名空间',
        'controller' => '控制器',
        'url' => '路由',
        'action' => '方法',
        'parentName' => '父级名称',
        'leftShowName' => '左侧是否显示',
        'createTime' => '创建时间',
    );
    protected static $formShowArr = array(
        'id' => 'ID',
        'name' => '操作名称',
        'namespace' => '命名空间',
        'controller' => '控制器',
        'url' => '路由',
        'action' => '方法',
        'parentName' => '父级名称',
        'leftShowName' => '左侧是否显示',
        'createTime' => '创建时间',
    );

    public static function index()
    {
        self::getCommon();
        $shares = self::getShare();
        $datas = array();
        $models = ActionModel::paginate(self::$limit);
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
        self::getCommon();
        $shares = self::getShare();
        $dataArr = array(
            'prefix' => $shares['prefix'],
            'crumbs' => $shares['crumbs'],
            'leftMenus' => self::getLeftMenus(),
            'selArr' => self::$formElementArr,
            'optionArr' => array(
                'pid' => self::getParents(),
                'left_show' => self::getLeftShows(),
            ),
            'view' => 'create',
        );
        return View::index($dataArr);
    }

    public static function store()
    {
        self::getCommon();
        $shares = self::getShare();
        $data = self::getData();
        $data['created_at'] = time();
//        dd($data);
        ActionModel::create($data);
        return redirect($shares['prefix']);
    }

    public static function edit()
    {
        self::getCommon();
        $shares = self::getShare();
        $dataArr = array(
            'prefix' => $shares['prefix'],
            'crumbs' => $shares['crumbs'],
            'leftMenus' => self::getLeftMenus(),
            'selArr' => self::$formElementArr,
            'optionArr' => array(
                'pid' => self::getParents(),
                'left_show' => self::getLeftShows(),
                ),
            'data' => self::getModelById(Input::get('id')),
            'view' => 'edit',
        );
        return View::index($dataArr);
    }

    public static function update()
    {
        self::getCommon();
        $shares = self::getShare();
        $model = self::getModelById(Input::get('id'));
        $data = self::getData();
        $data['updated_at'] = time();
        ActionModel::where('id',Input::get('id'))->update($data);
        return redirect($shares['prefix']);
    }

    public static function show()
    {
        self::getCommon();
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
        if (!$data['name'] || !$data['namespace'] || !$data['controller'] ||
            !$data['url'] || !$data['action']) {
            return array('code'=> -1, 'msg'=> '管理员必填！');
        }
        return array(
            'name' => $data['name'],
            'namespace' => $data['namespace'],
            'controller' => $data['controller'],
            'url' => $data['url'],
            'action' => $data['action'],
            'pid' => $data['pid'],
            'left_show' => $data['left_show'],
        );
    }

    /**
     * 通过 ID 获取 Model
     */
    public static function getModelById($id)
    {
        $model = ActionModel::find($id);
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
            'namespace' => $model->namespace,
            'controller' => $model->controller,
            'url' => $model->url,
            'action' => $model->action,
            'pid' => $model->pid,
            'parentName' => $model->getParentName(),
            'left_show' => $model->left_show,
            'leftShowName' => $model->getLeftShowName(),
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
        self::$crumbs['func']['name'] = '操作';
        return array(
            'prefix' => self::$prefix,
            'crumbs' => self::$crumbs,
        );
    }

    /**
     * 获取顶级操作
     */
    public static function getParents()
    {
        $parentArr = array();
        $models = ActionModel::where('pid',0)->where('del',0)->get();
        if (count($models)) {
            foreach ($models as $model) {
                $parentArr[$model->id] = $model->name;
            }
        }
        return $parentArr;
    }

    /**
     * 获取是否显示
     */
    public static function getLeftShows()
    {
        $model = new ActionModel();
        return $model['leftShows'];
    }

    /**
     * 公用方法
     */
    public static function getCommon()
    {
        self::isLogin();
    }
}