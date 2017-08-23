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
//        'id' => 'ID',
        'roleName' => '角色名称',
        'actionNum' => '操作数量',
        'createTime' => '创建时间',
    );

    public static function index()
    {
        $shares = self::getShare();
        $datas = array();
        $roles = RoleModel::all();
        if (count($roles)) {
            foreach ($roles as $k=>$role) {
                $models = RoleActionModel::where('role',$role->id)
                    ->where('del',0)
                    ->get();
                $datas[$k]['id'] = $role->id;
                $datas[$k]['roleName'] = $role->name;
                $datas[$k]['actionNum'] = count($models);
                $datas[$k]['createTime'] = $role->createTime();
            }
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

    public static function show()
    {
        $shares = self::getShare();
        $role = RoleModel::find(Input::get('id'));
        if (!$role) { return '不存在当前角色！'; }
        $models = RoleActionModel::where('role',Input::get('id'))
            ->where('del',0)
            ->get();
        $roleActionArr = array();
        if (count($models)) {
            foreach ($models as $model) {
                $roleActionArr[$model->id] = $model->action;
            }
        }
        $dataArr = array(
            'prefix' => $shares['prefix'],
            'crumbs' => $shares['crumbs'],
            'leftMenus' => self::getLeftMenus(),
            'role' => array(
                'id' => $role->id,
                'name' => $role->name,
            ),
            'actions' => self::getActions(),
            'roleActionArr' => $roleActionArr,
        );
        return self::getShowHtml($dataArr);
    }

    /**
     * 权限详情页面
     */
    public static function getShowHtml($dataArr)
    {
        $crumbs = $dataArr['crumbs'];
        $role = $dataArr['role'];
        $actions = $dataArr['actions'];
        $roleActionArr = $dataArr['roleActionArr'];
        $html = '';
        $html .= View::mainTop();
        $html .= View::top();
        $html .= '<div class="am-cf admin-main">';
        $html .= '<div class="admin-sidebar am-offcanvas" id="admin-offcanvas">';
        $html .= '<div class="am-offcanvas-bar admin-offcanvas-bar">';
        $html .= View::leftMenu($dataArr['leftMenus']);
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="admin-content">';
        //面包屑
        $html .= '<div class="am-g"><div class="am-cf am-padding"><div class="am-fl am-cf"><strong class="am-text-primary am-text-lg"> '.$crumbs['module'].'</strong> / <strong class="am-text-primary am-text-lg"> '.$crumbs['func']['name'].'</strong></div></div></div><hr/>';
        //管理员信息
        $html .= '<div class="am-g">';
        $html .= '<div class="am-u-sm-12 am-u-md-4 am-u-md-push-8"><div class="am-panel am-panel-default"><div class="am-panel-bd"><div class="user-info"><p class="user-info-order">管理员名称：<strong>管理员</strong></p><p class="user-info-order">所在角色组：<strong>角色</strong></p><p class="user-info-order">最近登陆时间：<strong>时间</strong></p></div></div></div></div>';
        //拼接详情页面
        $html .= "<div class=\"am-u-sm-12 am-u-md-8 am-u-md-pull-4\">";
        $html .= "<table class=\"am-table am-table-striped am-table-hover table-main\">";
        $html .= "<tbody id=\"tbody-alert\">";
        $html .= "<tr><td class=\"am-hide-sm-only\" width='200'>当前角色 ：</td>";
        $html .= "<td>".$role['name']."</td></tr>";
        $html .= "<tr><td class=\"am-hide-sm-only\">权限列表 ：</td>";
        $html .= "<td>";
        foreach ($actions as $key=>$action) {
            $html .= '<label style="font-size:18px;cursor:pointer;">';
            $html .= '<input type="checkbox" value="';
            if (count($roleActionArr) && array_key_exists($key,array_flip($roleActionArr))) {
                $roleActionId = array_flip($roleActionArr)[$key];
            } else {
                $roleActionId = 0;
            }
            $html .= $roleActionId.'-'.$role['id'].'-'.$key;
            $html .= '"';
            if (count($roleActionArr) && in_array($key,$roleActionArr)) {
                $html .= 'checked';
            }
            $html .= ' onclick="getCheck(this.value)"';
            $html .= '/> '.$action;
            $html .= '</label>'.str_repeat('&nbsp;',10);
        }
        $html .= '</td></tr>';
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '<button type="button" class="am-btn am-btn-primary" onclick="history.go(-1);">返回</button>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= View::mainBottom();
        $html .= '<script>';
        $html .= 'function getCheck(value){';
        $html .= 'window.location.href="'.self::$prefix.'/check?value="+value;';
        $html .= '}';
        $html .= '</script>';
        $html .= '';
        return $html;
    }

    /**
     * 判断角色、操作有无关联：有则去掉，无则插入
     */
    public static function check()
    {
        $value = Input::get('value');
        $datas = explode('-',$value);
        $id = $datas[0];
        $role = $datas[1];
        $action = $datas[2];
        if (!$id) {
            //新添记录
            if (!self::add($role,$action)) {
                echo "<script>alert('添加失败！');history.go(-1);</script>";exit;
            }
        } else {
            //更新记录
            if (!self::modify($id,$role,$action)) {
                echo "<script>alert('更新失败！');history.go(-1);</script>";exit;
            }
        }
        $shares = self::getShare();
        return redirect($shares['prefix'].'/show?id='.$role);
    }

    /**
     * 新加记录
     */
    public static function add($role,$action)
    {
        if (!$role || !$action) {
            echo "<script>alert('参数错误！');history.go(-1);</script>";exit;
        }
        $roleModel = RoleModel::find($role);
        $actionModel = ActionModel::find($action);
        if (!$roleModel || !$actionModel) {
            echo "<script>alert('角色或操作不存在！');history.go(-1);</script>";exit;
        }
        $roleActionModel = RoleActionModel::where('role',$role)
            ->where('action',$action)
            ->where('del',0)
            ->first();
        if ($roleActionModel) {
            echo "<script>alert('权限已存在！');history.go(-1);</script>";exit;
        }
        RoleActionModel::create(array(
            'role' => $role,
            'action' => $action,
            'created_at' => time(),
        ));
        return true;
    }

    /**
     * 更新记录
     */
    public static function modify($id,$role,$action)
    {
        $model = RoleActionModel::where('id',$id)
            ->where('role',$role)
            ->where('action',$action)
//            ->where('del',0)
            ->first();
        if (!$model) {
            echo "<script>alert('权限不存在！');history.go(-1);</script>";exit;
        }
        if ($model->del==0) {
            RoleActionModel::where('id',$id)
                ->update(array('del'=>1,'del_time'=>time()));
        } else {
            RoleActionModel::where('id',$id)
                ->update(array('del'=>0,'del_time'=>time()));
        }
        return true;
    }

    /**
     * 通过 ID 获取 Model
     */
    public static function getModelById($id)
    {
        $model = RoleActionModel::find($id);
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
            'role' => $model->role,
            'roleName' => $model->getRoleName(),
            'action' => $model->action,
            'actionName' => $model->getActionName(),
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