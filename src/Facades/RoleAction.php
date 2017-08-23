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

    public static function show()
    {
        return JiugeRoleAction::show();
    }

    public static function check()
    {
        return JiugeRoleAction::check();
    }
}