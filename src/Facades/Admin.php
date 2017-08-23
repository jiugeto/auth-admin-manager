<?php
namespace JiugeTo\AuthAdminManager\Facades;

use Illuminate\Support\Facades\Facade;
use JiugeTo\AuthAdminManager\Controllers\AdminController as JiugeAdmin;

class Admin extends Facade
{
    /**
     * 管理员操作
     */

    public static function index()
    {
        return JiugeAdmin::index();
    }

    public static function create()
    {
        return JiugeAdmin::create();
    }

    public static function store()
    {
        return JiugeAdmin::store();
    }

    public static function edit()
    {
        return JiugeAdmin::edit();
    }

    public static function update()
    {
        return JiugeAdmin::update();
    }

    public static function show()
    {
        return JiugeAdmin::show();
    }

    public static function pwd()
    {
        return JiugeAdmin::pwd();
    }

    public static function setPwd()
    {
        return JiugeAdmin::setPwd();
    }
}