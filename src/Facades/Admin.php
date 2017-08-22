<?php
namespace JiugeTo\AuthAdminManager\Facades;

use Illuminate\Support\Facades\Facade;
use JiugeTo\AuthAdminManager\Controllers\AdminController as JiugeAdmin;
use Illuminate\Http\Request;

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

    public static function store(Request $request)
    {
        return JiugeAdmin::store($request);
    }

    public static function edit($id)
    {
        return JiugeAdmin::edit($id);
    }

    public static function update(Request $request,$id)
    {
        return JiugeAdmin::update($request,$id);
    }
}