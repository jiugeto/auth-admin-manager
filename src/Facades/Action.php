<?php
namespace JiugeTo\AuthAdminManager\Facades;

use Illuminate\Support\Facades\Facade;
use JiugeTo\AuthAdminManager\Controllers\ActionController as JiugeAction;

class Action extends Facade
{
    /**
     * 操作
     */

    public static function index()
    {
        return JiugeAction::index();
    }

    public static function create()
    {
        return JiugeAction::create();
    }

    public static function store()
    {
        return JiugeAction::store();
    }

    public static function edit()
    {
        return JiugeAction::edit();
    }

    public static function update()
    {
        return JiugeAction::update();
    }

    public static function show()
    {
        return JiugeAction::show();
    }
}