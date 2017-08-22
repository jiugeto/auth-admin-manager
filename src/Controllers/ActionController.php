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
    );

    public static function index()
    {
        $shares = self::getShare();
        $datas = ActionModel::paginate(self::$limit);
        $datas->limit = self::$limit;
        $dataArr = array(
            'prefix' => $shares['prefix'],
            'crumbs' => $shares['crumbs'],
            'datas' => $datas,
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
            'optionArr' => array(
                'pid' => self::getParents(),
            ),
            'view' => 'create',
        );
        return View::index($dataArr);
    }

    public static function store()
    {
        $shares = self::getShare();
        $data = self::getData($request);
        $data['created_at'] = time();
        ActionModel::create($data);
        return redirect($shares['prefix']);
    }

    public static function edit($id)
    {
        $shares = self::getShare();
        $dataArr = array(
            'prefix' => $shares['prefix'],
            'crumbs' => $shares['crumbs'],
            'selArr' => self::$formElementArr,
            'optionArr' => array(
                'pid' => self::getParents(),
                ),
            'data' => self::getModelById($id),
            'view' => 'edit',
        );
        return View::index($dataArr);
    }

    public static function update(Request $request,$id)
    {
        $shares = self::getShare();
        $model = self::getModelById($id);
        $data = self::getData($request);
        $data['updated_at'] = time();
        ActionModel::where('id',$id)->update($data);
        return redirect($shares['prefix']);
    }

    public static function show($id)
    {
        $shares = self::getShare();
        $dataArr = array(
            'prefix' => $shares['prefix'],
            'crumbs' => $shares['crumbs'],
            'view' => 'show',
            'data' => self::getModelById($id),
        );
        return View::index($dataArr);
    }

    /**
     * 收集、验证
     */
    public static function getData(Request $request)
    {
        if (!$request->name || !$request->namespace || !$request->controller ||
            !$request->url || !$request->action) {
            return array('code'=> -1, 'msg'=> '管理员必填！');
        }
        return array(
            'name' => $request->name,
            'namespace' => $request->namespace,
            'controller' => $request->controller,
            'url' => $request->url,
            'action' => $request->action,
            'pid' => $request->pid,
            'left_show' => $request->left_show,
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
            'name' => $model->id,
            'namespace' => $model->namespace,
            'controller' => $model->controller,
            'url' => $model->url,
            'action' => $model->action,
            'pid' => $model->pid,
            'left_show' => $model->left_show,
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
}