<?php
namespace JiugeTo\AuthAdminManager\Controllers;

class ViewController extends Controller
{
    /**
     * 视图：默认是 amazeUI
     */

    //表单类型：1input-text，2input-file，3input-submit，4input-button，5select，6textarea，
    private static $types = [
        1=>'text','file','submit','button','select','textarea',
    ];

    /**
     * 模板文件根目录
     */
    public static function getPubPath()
    {
        return config('jiuge.pub');
    }

    /**
     * 模板组合
     */
    public static function index($dataArr=array())
    {
        if (!$dataArr) { return '没有参数！'; }
        $main = '';
        $main .= self::mainTop();
        $main .= self::top();
        if ($dataArr['view']=='index') {
            $main .= self::indexlist($dataArr);
        } else if ($dataArr['view']=='create') {
            $main .= self::create($dataArr);
        } else if ($dataArr['view']=='edit') {
            $main .= self::edit($dataArr);
        } else if ($dataArr['view']=='show') {
            $main .= self::show($dataArr);
        } else {
            $main .= '没有信息';
        }
        $main .= self::mainBottom();
        $main .= '';
        return $main;
    }

    /**
     * head层
     */
    public static function mainTop()
    {
        $html = '';
        $html .= '<!doctype html>';
        $html .= '<html class="no-js fixed-layout">';
        $html .= '<head>';
        $html .= '<meta charset="utf-8">';
        $html .= '<title>'.config('jiuge.title').'</title>';
        $html .= '<link rel="icon" type="image/png" href="'.config('jiuge.icon').'">';
        $html .= '<link rel="stylesheet" href="'.self::getPubPath().'css/amazeui.min.css"/>';
        $html .= '<link rel="stylesheet" href="'.self::getPubPath().'css/admin.css">';
        $html .= '<link rel="stylesheet" href="'.self::getPubPath().'css/admin_cus.css">';
        $html .= '<link rel="stylesheet" type="text/css" href="'.self::getPubPath().'css/video.css">';
        $html .= '<script src="'.self::getPubPath().'js/jquery-1.10.2.min.js"></script>';
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
     * 左边菜单层
     */
    public static function leftMenu($leftMenus)
    {
        $html = '';
        $html .= '<ul class="am-list admin-sidebar-list">';
        $html .= '<li class="admin-parent">';
        if (count($leftMenus)) {
            foreach ($leftMenus as $leftMenu) {
                $html .= '<a class="am-cf" data-am-collapse="{target: \'#collapse-nav\'}" onclick="toggle('.$leftMenu['id'].')"><span class="am-icon-file"></span>  '.$leftMenu['name'].' <span class="am-icon-angle-right am-fr am-margin-right"></span></a>';
                if (count($leftMenu['subs'])) {
                    $html .= '<ul class="am-list am-collapse admin-sidebar-sub am-out" id="collapse-nav'.$leftMenu['id'].'">';
                    foreach ($leftMenu['subs'] as $sub) {
                        $html .= '<li><a href="'.$sub['url'].'" class="am-cf"><span class="am-icon-check"></span> '.$sub['name'].' <span class="am-icon-star am-fr am-margin-right admin-icon-yellow"></span></a></li>';
                    }
                    $html .= '</ul>';
                }
            }
        } else {
            $html .= '<a class="am-cf" data-am-collapse="{target: \'#collapse-nav\'}" onclick="toggle(0)"><span class="am-icon-file"></span>  权限管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>';
            $html .= '<ul class="am-list am-collapse admin-sidebar-sub am-out" id="collapse-nav0">';
            $html .= '<li><a href="/admin/admin" class="am-cf"><span class="am-icon-check"></span> 管理员 <span class="am-icon-star am-fr am-margin-right admin-icon-yellow"></span></a></li>';
            $html .= '<li><a href="/admin/role" class="am-cf"><span class="am-icon-check"></span> 角色列表 <span class="am-icon-star am-fr am-margin-right admin-icon-yellow"></span></a></li>';
            $html .= '<li><a href="/admin/action" class="am-cf"><span class="am-icon-check"></span> 操作列表 <span class="am-icon-star am-fr am-margin-right admin-icon-yellow"></span></a></li>';
            $html .= '<li><a href="/admin/roleaction" class="am-cf"><span class="am-icon-check"></span> 权限操作 <span class="am-icon-star am-fr am-margin-right admin-icon-yellow"></span></a></li>';
            $html .= '</ul>';
        }
        $html .= '</li>';
        $html .= '</ul>';
        $html .= '<script>';
        $html .= 'function toggle(id){';
        $html .= 'console.log(id);';
        $html .= '$("#collapse-nav"+id).toggle(200);';
        $html .= '}';
        $html .= '</script>';
        $html .= '<div class="am-panel am-panel-default admin-sidebar-panel"><div class="am-panel-bd"><p><span class="am-icon-bookmark"></span> 公告</p><p>时光静好，与君语；细水流年，与君同。—— Amaze UI</p></div></div>';
        $html .= '<div class="am-panel am-panel-default admin-sidebar-panel"><div class="am-panel-bd"><p><span class="am-icon-tag"></span> wiki</p><p>Welcome to the Amaze UI wiki!</p></div></div>';
        $html .= '';
        return $html;
    }

    /**
     * 动态内容列表层
     */
    public static function indexlist($dataArr)
    {
        $prefix = $dataArr['prefix'];
        $crumbs = $dataArr['crumbs'];
        $datas = $dataArr['datas'];
        $indexArr = $dataArr['indexArr'];
        $html = '';
        $html .= '<div class="am-cf admin-main">';
        $html .= '<div class="admin-sidebar am-offcanvas" id="admin-offcanvas">';
        $html .= '<div class="am-offcanvas-bar admin-offcanvas-bar">';
        $html .= self::leftMenu($dataArr['leftMenus']);
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="admin-content">';
        //面包屑
        $html .= '<div class="am-g"><div class="am-cf am-padding"><div class="am-fl am-cf"><strong class="am-text-primary am-text-lg"> '.$crumbs['module'].'</strong> / <strong class="am-text-primary am-text-lg"> '.$crumbs['func']['name'].'</strong></div></div></div><hr/>';
        //菜单
        if ($prefix != '/admin/roleaction') {
            $html .= '<div class="am-g"><div class="am-u-sm-12 am-u-md-6"><div class="am-btn-toolbar"><div class="am-btn-group am-btn-group-xs"><a href="' . $prefix . '/create"><button type="button" class="am-btn am-btn-default"> 添加' . $crumbs['func']['name'] . ' </button></a></div></div></div>';
        }
        //列表
        $html .= '<div class="am-g" id="list"><div class="am-u-sm-12">';
        $html .= '<table class="am-table am-table-striped am-table-hover table-main">';
        //标题栏
        $html .= '<thead>';
//        $html .= '<tr><th class="table-check"><input type="checkbox"/></th><th class="table-id">ID</th><th class="table-title">名称</th><th class="table-type">创建时间</th><th class="table-date am-hide-sm-only">操作</th></tr>';
        $html .= '<tr><th class="table-check"><input type="checkbox"/></th>';
        foreach ($indexArr as $key=>$value) {
            $html .= '<th class="table-type">'.$value.'</th>';
        }
        $html .= '<th class="table-date am-hide-sm-only">操作</th></tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        if (count($datas)) {
            foreach ($datas as $data) {
                $html .= '<tr>';
                $html .= '<td class="am-hide-sm-only"><input type="checkbox" /></td>';
                foreach ($indexArr as $k => $v) {
                    $html .= '<td class="am-hide-sm-only">' . $data[$k] . '</td>';
                }
                $html .= '<td class="am-hide-sm-only"><div class="am-btn-toolbar"><div class="am-btn-group am-btn-group-xs">';
                $html .= '<a href="' . $prefix . '/show?id=' . $data['id'] . '"><button class="am-btn am-btn-default am-btn-xs am-hide-sm-only"><img src="' . self::getPubPath() . 'images/show.png" class="icon"> 查看</button></a> ';
                if ($prefix != '/admin/roleaction') {
                    $html .= '<a href="' . $prefix . '/edit?id=' . $data['id'] . '"><button class="am-btn am-btn-default am-btn-xs am-hide-sm-only"><img src="' . self::getPubPath() . 'images/show.png" class="icon"> 编辑</button></a>';
                } else {
                }
                if ($prefix=='/admin/admin') {
                    $html .= ' <a href="' . $prefix . '/pwd?id=' . $data['id'] . '"><button class="am-btn am-btn-default am-btn-xs am-hide-sm-only"><img src="' . self::getPubPath() . 'images/show.png" class="icon"> 设置密码</button></a>';
                }
                $html .= '</div></div></td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr>';
            $html .= '<td class="am-hide-sm-only" colspan="20" style="text-align:center;">没有'.$crumbs['func']['name'].'</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div></div>';
        $html .= '</div>';
        //页脚
        $html .= '<footer><hr/><p class="am-padding-left list_center">你的页脚信息。</p></footer>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '';
        return $html;
    }

    /**
     * 动态内容添加层
     * $selArr：表单选项
     * $optionArr：下拉列表，二维数组
     */
    public static function create($dataArr)
    {
//        $selArr = $selArr ? $selArr : array(array(1,'名称','name','',1,array()));
//        if (!self::$action) {
//            if (!$selArr) { return "没有参数！"; }
//            foreach ($selArr as $v) {
//                echo self::setElement($v[0],$v[1],$v[2],$v[3],$v[4],$optionArr);
//            }
//            exit;
//        }
        $prefix = $dataArr['prefix'];
        $crumbs = $dataArr['crumbs'];
        $selArr = $dataArr['selArr'];
        $optionArr = $dataArr['optionArr'];
        $html = '';
        $html .= '<div class="am-cf admin-main">';
        $html .= '<div class="admin-sidebar am-offcanvas" id="admin-offcanvas">';
        $html .= '<div class="am-offcanvas-bar admin-offcanvas-bar">';
        $html .= self::leftMenu($dataArr['leftMenus']);
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
        $html .= "<form action='".$prefix."' class='am-form' method='POST' enctype='multipart/form-data'>";
        $html .= csrf_field();
        $html .= "<fieldset>";
        foreach ($selArr as $v) {
            $html = $html . self::setElement($v[0],$v[1],$v[2],$v[3],$v[4],$optionArr);
        }
        $html .= "<button type='button' class='am-btn am-btn-primary' onclick='history.go(-1);'>返回</button> <button type='submit' class='am-btn am-btn-primary'>保存添加</button>";
        $html .= "</fieldset>";
        $html .= "</form>";
        $html .= '</div>';
        $html .= '</div>';
        //页脚
        $html .= '<footer><hr/><p class="am-padding-left list_center">你的页脚信息。</p></footer>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '';
        return $html;
    }

    /**
     * 动态内容编辑层
     */
    public static function edit($dataArr)
    {
        $prefix = $dataArr['prefix'];
        $crumbs = $dataArr['crumbs'];
        $selArr = $dataArr['selArr'];
        $optionArr = $dataArr['optionArr'];
        $data = $dataArr['data'];
        $footer = config('jiuge.footer');
        $html = '';
        $html .= '<div class="am-cf admin-main">';
        $html .= '<div class="admin-sidebar am-offcanvas" id="admin-offcanvas">';
        $html .= '<div class="am-offcanvas-bar admin-offcanvas-bar">';
        $html .= self::leftMenu($dataArr['leftMenus']);
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
        $html .= '<form action="'.$prefix.'/modify?id='.$data['id'].'" class="am-form" method="POST" enctype="multipart/form-data">';
        $html .= csrf_field();
        $html .= '<input type="hidden" name="_method" value="POST"/>';
        $html .= '<input type="hidden" name="id" value="'.$data['id'].'"/>';
        $html .= "<fieldset>";
        foreach ($selArr as $v) {
            $html .= self::setElement($v[0],$v[1],$v[2],$v[3],$v[4],$optionArr,$data);
        }
        $html .= "<button type='button' class='am-btn am-btn-primary' onclick='history.go(-1);'>返回</button> <button type='submit' class='am-btn am-btn-primary'>保存修改</button>";
        $html .= "</fieldset>";
        $html .= "</form>";
        $html .= '</div>';
        $html .= '</div>';
        //页脚
        $html .= '<footer><hr/><p class="am-padding-left list_center">'.$footer.'</p></footer>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '';
        return $html;
    }

    /**
     * 动态内容详情层
     */
    public static function show($dataArr)
    {
        $crumbs = $dataArr['crumbs'];
        $data = $dataArr['data'];
        $showArr = $dataArr['showArr'];
        $html = '';
        $html .= '<div class="am-cf admin-main">';
        $html .= '<div class="admin-sidebar am-offcanvas" id="admin-offcanvas">';
        $html .= '<div class="am-offcanvas-bar admin-offcanvas-bar">';
        $html .= self::leftMenu($dataArr['leftMenus']);
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
        foreach ($showArr as $k=>$v) {
            $html .= "<tr><td class=\"am-hide-sm-only\">".$v." / ".ucfirst($k)."：</td>";
            $html .= "<td width='500'>".$data[$k]."</td></tr>";
        }
        $html .= "</tbody>";
        $html .= "</table>";
        $html .= "<button type=\"button\" class=\"am-btn am-btn-primary\" onclick=\"history.go(-1);\">返回</button>";
        $html .= '</div>';
        $html .= '</div>';
        $html .= '';
        return $html;
    }

    /**
     * 底部层
     */
    public static function mainBottom()
    {
        $html = '';
        $html .= '<script src="'.self::getPubPath().'js/jquery.js"></script>';
        $html .= '<script src="'.self::getPubPath().'js/amazeui.js"></script>';
        $html .= '<script src="'.self::getPubPath().'js/app.js"></script>';
        $html .= '</body>';
        $html .= '</html>';
        $html .= '';
        return $html;
    }

    /**
     * 表单元素
     * @param tinyint $type：
     *      1input-text，2input-file，3input-submit，4input-button，5select，6textarea，
     * @param string $nameCN 表单提交的中文属性
     * @param string $name 表单提交的属性
     * @param array $placeholder 表单提示
     * @param array $valite js字段验证
     * @param array $selData 下拉列表选项，二维数组
     * @param array $edit 模型的编辑记录
     */
    public static function setElement(
        $type=1,$nameCN='名称',$name='name',
        $placeholder='',$valite=0,$selData=array(),$edit=null)
    {
        if (!$name) { return '表单name参数未填！'; }
        //拼接表单
        $html = "<div class='am-form-group'>";
        $html .= "<label>".$nameCN.' '.ucfirst($name)."：</label>";
        if ($type==1) {
            $s = "<input type='".self::$types[$type]."' name='".$name."'";
            $s .= $edit ? " value='".$edit[$name]."'" : "";//编辑页面
            $s .= " placeholder='".$placeholder."'".self::getJsValite($valite);
            $s .= $valite==12 ? " readonly" : "";
            $s .= "/>";
        } else if ($type==2) {
            $s = "<input type='file' name='".$name."'/>";
//        } else if (in_array($type,[3,4])) {
//            $s = "<input type='".self::$types[$type]."' class=\"btn btn-success\"/>";
        } else if ($type==5) {
            $s = "<select name='".$name."'".self::getJsValite($valite).">";
            if ($edit && in_array($name,['area','cate','pid'])) {
                $s .= "<option value='0' ";
                $s .= $edit[$name]==0 ? " selected" : "";
                $s .= ">顶级/所有</option>";
            } else if (in_array($name,['area','cate','pid'])) {
                $s .= "<option value='0'>顶级/所有</option>";
            }
            foreach ($selData[$name] as $k=>$v) {
                $s .= "<option value='".$k."'";
                if ($edit) {
                    $s .= $edit[$name]==$k ? " selected" : "";
                }
                $s .= ">".$v."</option>";
            }
            $s .= "</select>";
        } else if ($type==6) {
            $s = "<textarea name='".$name."' placeholder='".$placeholder."'";
            $s .= self::getJsValite($valite)." rows='10'>";
            $s .= $edit ? $edit[$name] : "";
            $s .= "</textarea>";
        } else {
            $s = "";
        }
        $html2 = "</div>";
        return $html.$s.$html2;
    }

    /**
     * js验证
     * @param tinyint $n：1用户名，2手机号，3邮箱，4价格，5数字，6下拉框必选，7网络地址，8简介，
     *          9命名空间，10控制器，11路由，12日期，13文章，
     * 注：用户名2-20位必填，手机号11位必填，价格保留2位小数，
     */
    public static function getJsValite($n=1)
    {
        if ($n==1) {
            $valite = "minlength='2' maxlength='20' required";
        } else if ($n==2) {
            $valite = "pattern='^1[34578]\\d+{9}$' required";
        } else if ($n==3) {
            $valite = "pattern='^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+$' required";
        } else if ($n==4) {
            $valite = "pattern='(^[1-9]\\d*(\\.\\d{1,2})?$)|(^0(\\.\\d{1,2})?$)' required";
        } else if ($n==5) {
            $valite = "pattern='^\\d+$' required";
        } else if ($n==6) {
            $valite = "required";
        } else if ($n==7) {
            $valite = "minlength='5' required";
        } else if ($n==8) {
            $valite = "minlength='5' maxlength='255' required";
        } else if ($n==9) {
            $valite = "pattern='^App\\Http\\Controllers\\[0-9a-zA-Z]{2,20}$' required";
        } else if ($n==10) {
            $valite = "pattern='^[0-9a-zA-Z_]{2,20}$' required";
        } else if ($n==11) {
            $valite = "pattern='^/[0-9a-zA-Z_/]{2,20}$$' required";
        } else if ($n==12) {
            $valite = "class=\"doc-datepicker\" required";
        } else if ($n==13) {
            $valite = "minlength='5' maxlength='2000' required";
        } else {
            $valite = "";
        }
        return ' '.$valite;
    }
}