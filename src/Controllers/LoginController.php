<?php
namespace JiugeTo\AuthAdminManager\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use JiugeTo\AuthAdminManager\Models\AdminModel;
use Illuminate\Hashing\BcryptHasher as Hash;

class LoginController extends Controller
{
    /**
     * 登陆
     */

    public static function login()
    {
        $title = config('jiuge.title');
        $icon = config('jiuge.icon');
        $pub = config('jiuge.pub');
        $loginUserName = config('jiuge.loginUserName');
        $loginPwd = config('jiuge.loginPwd');
        $adminUrl = config('jiuge.adminUrl');
        $html = '';
        //head
        $html .= '<!DOCTYPE html><html lang="en">';
        $html .= '<head><meta charset="utf-8"><title>'.$title.'登录</title>';
        $html .= '<link rel="icon" type="image/png" href="'.$icon.'">';
        $html .= '<link href="'.$pub.'css/app.css" rel="stylesheet">';
        $html .= '<link href="'.$pub.'css/amazeui.min.css" rel="stylesheet">';
        $html .= '<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>';
        $html .= '<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>';
        $html .= '<script type="text/javascript" src="'.$pub.'js/jquery.min.js"></script>';
        $html .= '</head><body>';
        //顶部
        $html .= '<nav class="navbar navbar-default"><div class="container-fluid">';
        $html .= '<div class="navbar-header"><button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"><span class="icon-bar"></span></button></div>';
        $html .= '<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1"><ul class="nav navbar-nav" style="position:relative;top:10px;"><li>'.Session::get('admin.name').' 后台登陆</li></ul></div>';
        $html .= '</div></nav>';
        //主体
        $html .= '<div class="container-fluid"><div class="row">
        <div class="col-md-8 col-md-offset-2"><div class="panel panel-default"><div class="panel-heading">登录</div><div class="panel-body">';
        //表单
        $html .= '<form class="form-horizontal" role="form" method="POST" action="'.$adminUrl.'/dologin"><input type="hidden" name="_token" value="'.csrf_token().'"><input type="hidden" name="_method" value="POST">';
        $html .= '<div class="form-group"><label class="col-md-4 control-label">'.$loginUserName.'</label><div class="col-md-6"><input type="text" class="form-control" placeholder="5-10字母数字组合" pattern="^[0-9a-zA-Z]{5,10}$" required name="name"></div></div>';
        $html .= '<div class="form-group"><label class="col-md-4 control-label">'.$loginPwd.'</label><div class="col-md-6"><input type="password" class="form-control" placeholder="5-20字母数字组合" pattern="^[0-9a-zA-Z]{5,20}$" required name="pwd"></div></div>';
        $html .= '<div class="form-group"><div class="col-md-6 col-md-offset-4"><button type="submit" class="btn btn-primary"> 登 录 </button></div></div>';
        $html .= '</form>';
        $html .= '</div></div></div></div></div></body></html>';
        $html .= '';
        return $html;
    }

    public static function doLogin()
    {
        $adminUrl = config('jiuge.adminUrl');
        $data = Input::all();
        if (!$data['name'] || !$data['pwd']) {
            echo "<script>alert('信息未填写完整！');history.go(-1);</script>";exit;
        }
        $model = AdminModel::where('name',$data['name'])->first();
        if (!$model) {
            echo "<script>alert('该管理员不存在！');history.go(-1);</script>";exit;
        }
        $hash = new Hash();
//        dd($hash->check('123456',$hash->make('123456')));
        if (!($hash->check($data['pwd'],$model->password))) {
            echo "<script>alert('密码错误！');history.go(-1);</script>";exit;
        }
        Session::put('admin',array(
            'id' => $model->id,
            'name' => $model->name,
            'role' => $model->role,
            'roleName' => $model->getRoleName(),
            'createTime' => $model->createTime(),
        ));
        return redirect(adminUrl);
    }

    public static function doLogout()
    {
        $adminUrl = config('jiuge.adminUrl');
        Session::forget('admin');
        return redirect($adminUrl.'/login');
    }
}