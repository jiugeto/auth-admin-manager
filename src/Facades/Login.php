<?php
namespace JiugeTo\AuthAdminManager\Facades;

use Illuminate\Support\Facades\Facade;
use JiugeTo\AuthAdminManager\Controllers\LoginController as JiugeLogin;

class Login extends Facade
{
    /**
     * 登陆
     */

    public static function login()
    {
        return JiugeLogin::login();
    }

    public static function doLogin()
    {
        return JiugeLogin::doLogin();
    }

    public static function doLogout()
    {
        return JiugeLogin::doLogout();
    }
}