<?php
namespace JiugeTo\AuthAdminManager\Facades;

use Illuminate\Support\Facades\Facade;
use JiugeTo\AuthAdminManager\Controllers\RoleActionController as JiugeRoleAction;

class RoleAction extends Facade
{
    /**
     * 操作
     */

    public static function index()
    {
        return JiugeRoleAction::index();
    }

    public static function create()
    {
        return JiugeRoleAction::create();
    }

    public static function store()
    {
        return JiugeRoleAction::store();
    }

    public static function edit()
    {
        return JiugeRoleAction::edit();
    }

    public static function update()
    {
        return JiugeRoleAction::update();
    }

    public static function show()
    {
        return JiugeRoleAction::show();
    }
}