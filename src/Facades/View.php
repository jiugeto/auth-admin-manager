<?php
namespace JiugeTo\AuthAdminManager\Facades;

use Illuminate\Support\Facades\Facade;
use JiugeTo\AuthAdminManager\Controllers\ViewController as JiugeView;
use JiugeTo\AuthAdminManager\Controllers\Controller as JiugeView2;

class View extends Facade
{
    /**
     * 视图
     */

    public static function getPubPath()
    {
        return JiugeView::getPubPath();
    }

    public static function index()
    {
        return JiugeView::index();
    }

    public static function getLeftMenus()
    {
        return JiugeView2::getLeftMenus();
    }

    public static function isLogin()
    {
        return JiugeView2::isLogin();
    }
}