<?php
namespace JiugeTo\AuthAdminManager\Controllers;

class ViewController extends Controller
{
    /**
     * 视图
     */

    protected static $pub = '/public-admin-manager/';

    /**
     * 模板组合
     */
    public static function index()
    {
        $main = '';
        $main .= self::mainTop();
        $main .= self::top();
        $main .= self::bigContent();
        $main .= self::mainBottom();
        $main .= '';
        return $main;
    }

    /**
     * head层
     */
    public static function mainTop($title='后台主页',$icon='favicon.ico')
    {
        $html = '';
        $html .= '<!doctype html>';
        $html .= '<html class="no-js fixed-layout">';
        $html .= '<head>';
        $html .= '<meta charset="utf-8">';
        $html .= '<title>'.$title.'</title>';
        $html .= '<link rel="icon" type="image/png" href="'.$icon.'">';
        $html .= '<link rel="stylesheet" href="'.self::$pub.'css/amazeui.min.css"/>';
        $html .= '<link rel="stylesheet" href="'.self::$pub.'css/admin.css">';
        $html .= '<link rel="stylesheet" href="'.self::$pub.'css/admin_cus.css">';
        $html .= '<link rel="stylesheet" type="text/css" href="'.self::$pub.'css/video.css">';
        $html .= '<script src="'.self::$pub.'js/jquery-1.10.2.min.js"></script>';
        $html .= '</head>';
        $html .= '<body>';
        $html .= '';
        return $html;
    }

    /**
     * 顶部层
     */
    public static function top()
    {
        $html = '';
        $html .= '<header class="am-topbar admin-header">';
        $html .= '<div class="am-topbar-brand"><strong>您的后台</strong><small>管理中心</small></div>';
        $html .= '<button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: \'#topbar-collapse\'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>';
        $html .= '<div class="am-collapse am-topbar-collapse" id="topbar-collapse"><ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list">';
        $html .= '<li class="am-dropdown" data-am-dropdown>';
        $html .= '<a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;"><span class="am-icon-users"> 管理员名称 </span><span class="am-icon-caret-down"></span></a>';
        $html .= '<ul class="am-dropdown-content head"><li><a href="#"><span class="am-icon-user"></span> 资料</a></li><li><a href="#"><span class="am-icon-cog"></span> 设置</a></li><li><a href="javascript:;"><span class="am-icon-power-off"></span> 退出</a></li></ul>';
        $html .= '</li>';
        $html .= '<li class="am-hide-sm-only"><a href="javascript:;" id="admin-fullscreen"><span class="am-icon-arrows-alt"></span> <span class="admin-fullText">开启全屏</span></a></li>';
        $html .= '</ul></div>';
        $html .= '</header>';
        $html .= '<script>';
        $html .= '$(document).ready(function(){';
        $html .= '$(".am-dropdown-toggle").click(function(){';
        $html .= '$(".head").toggle();';
        $html .= '});';
        $html .= '});';
        $html .= '</script>';
        $html .= '';
        return $html;
    }

    /**
     * 内容层：菜单 + content + footer
     */
    public static function bigContent($view='index')
    {
        $html = '';
        $html .= '<div class="am-cf admin-main">';
        $html .= '<div class="admin-sidebar am-offcanvas" id="admin-offcanvas">';
        $html .= '<div class="am-offcanvas-bar admin-offcanvas-bar">';
        $html .= self::leftMenu();
        $html .= '<div class="am-panel am-panel-default admin-sidebar-panel"><div class="am-panel-bd"><p><span class="am-icon-bookmark"></span> 公告</p><p>时光静好，与君语；细水流年，与君同。—— Amaze UI</p></div></div>';
        $html .= '<div class="am-panel am-panel-default admin-sidebar-panel"><div class="am-panel-bd"><p><span class="am-icon-tag"></span> wiki</p><p>Welcome to the Amaze UI wiki!</p></div></div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="admin-content">';
        if ($view=='index') {
            $html .= self::indexlist();
        } else if ($view=='show') {
            $html .= self::show();
        } else if ($view=='create') {
            $html .= self::create();
        } else {
            $html .= '没有';
        }
        $html .= '<footer><hr><p class="am-padding-left list_center">你的页脚信息。</p></footer>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '';
        return $html;
    }

    /**
     * 左边菜单层
     */
    public static function leftMenu()
    {
        $html = '';
        $html .= '<ul class="am-list admin-sidebar-list">';
        $html .= '<li class="admin-parent">';
        $html .= '<a class="am-cf" data-am-collapse="{target: \'#collapse-nav\'}" onclick="toggle()"><span class="am-icon-file"></span>  权限管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>';
        $html .= '<ul class="am-list am-collapse admin-sidebar-sub am-out" id="collapse-nav">';
        $html .= '<li><a href="javascript:;" class="am-cf"><span class="am-icon-check"></span> 权限 <span class="am-icon-star am-fr am-margin-right admin-icon-yellow"></span></a></li>';
        $html .= '<li><a href="javascript:;" class="am-cf"><span class="am-icon-check"></span> 管理员 <span class="am-icon-star am-fr am-margin-right admin-icon-yellow"></span></a></li>';
        $html .= '</ul>';
        $html .= '</li>';
        $html .= '</ul>';
        $html .= '<script>';
        $html .= 'function toggle(){';
        $html .= '$("#collapse-nav").toggle(200);';
        $html .= '}';
        $html .= '</script>';
        $html .= '';
        return $html;
    }

    /**
     * 动态内容列表层
     */
    public static function indexlist()
    {
        $html = '';
        //面包屑
        $html .= '<div class="am-g"><div class="am-cf am-padding"><div class="am-fl am-cf">';
        $html .= '<strong class="am-text-primary am-text-lg"> XX管理 </strong>';
        $html .= '</div></div></div><hr/>';
        //菜单
        $html .= '<div class="am-g"><div class="am-u-sm-12 am-u-md-6"><div class="am-btn-toolbar"><div class="am-btn-group am-btn-group-xs"><a href="javascript:;"><button type="button" class="am-btn am-btn-default"> 添加XX </button></a></div></div></div>';
        //列表
        $html .= '<div class="am-g" id="list"><div class="am-u-sm-12">';
        $html .= '<table class="am-table am-table-striped am-table-hover table-main">';
        $html .= '<thead><tr><th class="table-check"><input type="checkbox"/></th><th class="table-id">ID</th><th class="table-title">名称</th><th class="table-type">创建时间</th><th class="table-date am-hide-sm-only">操作</th></tr></thead>';
        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<td class="am-hide-sm-only"><input type="checkbox" /></td>';
        $html .= '<td class="am-hide-sm-only"></td>';
        $html .= '<td class="am-hide-sm-only"></td>';
        $html .= '<td class="am-hide-sm-only"></td>';
        $html .= '<td class="am-hide-sm-only"><div class="am-btn-toolbar"><div class="am-btn-group am-btn-group-xs"><a href="javascript:;"><button class="am-btn am-btn-default am-btn-xs am-hide-sm-only"><img src="'.self::$pub.'images/show.png" class="icon"> 查看</button></a></div></div></td>';
        $html .= '</tr>';
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div></div>';
        $html .= '';
        return $html;
    }

    /**
     * 动态内容详情层
     */
    public static function show()
    {
        $html = '';
        $html .= '';
        $html .= '';
        $html .= '';
        $html .= '';
        return $html;
    }

    /**
     * 动态内容添加层
     */
    public static function create()
    {
        $html = '';
        $html .= '';
        $html .= '';
        $html .= '';
        $html .= '';
        return $html;
    }

    /**
     * 动态内容编辑层
     */
    public static function edit()
    {
        $html = '';
        $html .= '';
        $html .= '';
        $html .= '';
        $html .= '';
        return $html;
    }

    /**
     * 底部层
     */
    public static function mainBottom()
    {
        $html = '';
        $html .= '<script src="'.self::$pub.'js/jquery.js"></script>';
        $html .= '<script src="'.self::$pub.'js/amazeui.js"></script>';
        $html .= '<script src="'.self::$pub.'js/app.js"></script>';
        $html .= '</body>';
        $html .= '</html>';
        $html .= '';
        return $html;
    }
}