<?php
namespace JiugeTo\AuthAdminManager\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Contracts\View\Factory as ViewFactory;
use JiugeTo\AuthAdminManager\Models\ActionModel;

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
}