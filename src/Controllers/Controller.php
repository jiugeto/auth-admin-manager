<?php
namespace JiugeTo\AuthAdminManager\Controllers;

use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * 基本控制器
     */

    protected static $limit = 10;//每页显示的记录数
    protected static $prefix;
    protected static $view;

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
}