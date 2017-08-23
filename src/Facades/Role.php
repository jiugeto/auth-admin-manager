<?php
namespace JiugeTo\AuthAdminManager\Facades;

use Illuminate\Support\Facades\Facade;
use JiugeTo\AuthAdminManager\Controllers\RoleController as JiugeRole;

class Role extends Facade
{
    /**
     * 操作
     */

    public static function index()
    {
        return JiugeRole::index();
    }

    public static function create()
    {
        return JiugeRole::create();
    }

    public static function store()
    {
        return JiugeRole::store();
    }

    public static function edit()
    {
        return JiugeRole::edit();
    }

    public static function update()
    {
        return JiugeRole::update();
    }

    public static function show()
    {
        return JiugeRole::show();
    }
}