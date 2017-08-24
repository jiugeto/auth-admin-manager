<?php
namespace JiugeTo\AuthAdminManager\Controllers;

use JiugeTo\AuthAdminManager\Models\AdminModel;
use JiugeTo\AuthAdminManager\Models\RoleModel;
use Illuminate\Hashing\BcryptHasher as Hash;
use JiugeTo\AuthAdminManager\Controllers\ViewController as View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /**
     * 管理员
     */

    protected static $url = 'admin';//当前路由
    protected static $formElementArr = array(
        //array(表单选项,中文名称,name字段名称,表单提示,js验证规则)
        array(1,'管理员名称','name','管理员名称',1),
        array(5,'角色组','role','角色组',6),
    );
    protected static $formIndexArr = array(
        'id' => 'ID',
        'name' => '管理员名称',
        'roleName' => '角色组',
        'createTime' => '创建时间',
    );
    protected static $formShowArr = array(
        'id' => 'ID',
        'name' => '管理员名称',
        'roleName' => '角色组',
        'createTime' => '创建时间',
    );

    public static function index()
    {
        self::getCommon();
        $shares = self::getShare();
        $datas = array();
        $models = AdminModel::paginate(self::$limit);
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
                'role' => self::getRoles(),
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
        $data['password'] = Hash::make('123456');//默认密码
        AdminModel::create($data);
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
                'role' => self::getRoles(),
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
        AdminModel::where('id',Input::get('id'))->update($data);
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

    public static function pwd()
    {
        self::getCommon();
        $shares = self::getShare();
        $prefix = $shares['prefix'];
        $crumbs = $shares['crumbs'];
        $data = self::getModelById(Input::get('id'));
        $footer = config('jiuge.footer');
        $html = '';
        $html .= View::mainTop();
        $html .= View::top();
        $html .= '<div class="am-cf admin-main">';
        $html .= '<div class="admin-sidebar am-offcanvas" id="admin-offcanvas">';
        $html .= '<div class="am-offcanvas-bar admin-offcanvas-bar">';
        $html .= View::leftMenu(self::getLeftMenus());
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="admin-content">';
        //面包屑
        $html .= '<div class="am-g"><div class="am-cf am-padding"><div class="am-fl am-cf"><strong class="am-text-primary am-text-lg"> '.$crumbs['module'].'</strong> / <strong class="am-text-primary am-text-lg"> '.$crumbs['func']['name'].'</strong></div></div></div><hr/>';
        //管理员信息
        $html .= '<div class="am-g">';
        $html .= '<div class="am-u-sm-12 am-u-md-4 am-u-md-push-8"><div class="am-panel am-panel-default"><div class="am-panel-bd"><div class="user-info"><p class="user-info-order">管理员名称：<strong>管理员</strong></p><p class="user-info-order">所在角色组：<strong>角色</strong></p><p class="user-info-order">最近登陆时间：<strong>时间</strong></p></div></div></div></div>';
        //拼接表单
        $html .= "<div class=\"am-u-sm-12 am-u-md-8 am-u-md-pull-4\">";
        $html .= '<form action="'.$prefix.'/setpwd?id='.$data['id'].'" class="am-form" method="POST" enctype="multipart/form-data">';
        $html .= csrf_field();
        $html .= '<input type="hidden" name="_method" value="POST"/>';
        $html .= '<input type="hidden" name="id" value="'.$data['id'].'"/>';
        $html .= '<fieldset>';
        $html .= '<label>原密码</label><input type="text" name="pwd" placeholder="原密码" pattern="^[0-9a-zA-Z]{5,20}$" required/>';
        $html .= '<label>新密码</label><input type="password" name="pwd2" placeholder="新密码" pattern="^[0-9a-zA-Z]{5,20}$" required/><br>';
        $html .= '<button type="button" class="am-btn am-btn-primary" onclick="history.go(-1);">返回</button> <button type="submit" class="am-btn am-btn-primary">保存修改</button>';
        $html .= '</fieldset>';
        $html .= '</form>';
        $html .= '</div>';
        $html .= '</div>';
        //页脚
        $html .= '<footer><hr/><p class="am-padding-left list_center">'.$footer.'</p></footer>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= View::mainBottom();
        $html .= '';
        return $html;
    }

    public static function setPwd()
    {
        self::getCommon();
        $data = Input::all();
        $model = AdminModel::find($data['id']);
        if (!$model) {
            echo "<script>alert('记录不存在！');history.go(-1);</script>";exit;
        }
        $hash = new Hash();
        if (!($hash->check($data['pwd'],$model->password))) {
            echo "<script>alert('密码错误！');history.go(-1);</script>";exit;
        }
        AdminModel::where('id',$data['id'])
            ->update(array(
                'password' => $hash->make($data['pwd2']),
                'updated_at' => time(),
            ));
        $shares = self::getShare();
        return redirect($shares['prefix']);
    }

    /**
     * 收集、验证
     */
    public static function getData()
    {
        $data = Input::all();
        if (!$data['name']) {
            return array('code'=> -1, 'msg'=> '管理员必填！');
        }
        $roleModel = RoleModel::find($data['role']);
        if (!$roleModel) {
            return array('code'=> -2, 'msg'=> '该角色不存在！');
        }
        return array(
            'name' => $data['name'],
            'role' => $data['role'],
        );
    }

    /**
     * 通过 ID 获取 Model
     */
    public static function getModelById($id)
    {
        $model = AdminModel::find($id);
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
            'role' => $model->role,
            'roleName' => $model->getRoleName(),
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
        self::$crumbs['func']['name'] = '管理员';
        return array(
            'prefix' => self::$prefix,
            'crumbs' => self::$crumbs,
        );
    }

    /**
     * 获取角色
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
     * 公用方法
     */
    public static function getCommon()
    {
        self::isLogin();
    }
}