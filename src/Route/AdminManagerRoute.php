<?php

/**
 * 后台权限管理路由
 */


use JiugeTo\AuthAdminManager\Facades\Admin;
use JiugeTo\AuthAdminManager\Facades\Action;
use JiugeTo\AuthAdminManager\Facades\Role;
use JiugeTo\AuthAdminManager\Facades\RoleAction;

Route::group(['prefix'=>'admin'],function(){
    //管理员路由
    Route::get('admin',function(){ return Admin::index(); });
    Route::get('admin/create',function(){ return Admin::create(); });
    Route::post('admin',function(){ return Admin::store(); });
    Route::get('admin/show',function(){ return Admin::show(); });
    Route::get('admin/edit',function(){ return Admin::edit(); });
    Route::post('admin/modify',function(){ return Admin::update(); });
    //角色路由
    Route::get('role',function(){ return Role::index(); });
    Route::get('role/create',function(){ return Role::create(); });
    Route::post('role',function(){ return Role::store(); });
    Route::get('role/show',function(){ return Role::show(); });
    Route::get('role/edit',function(){ return Role::edit(); });
    Route::post('role/modify',function(){ return Role::update(); });
    //操作路由
    Route::get('action',function(){ return Action::index(); });
    Route::get('action/create',function(){ return Action::create(); });
    Route::post('action',function(){ return Action::store(); });
    Route::get('action/show',function(){ return Action::show(); });
    Route::get('action/edit',function(){ return Action::edit(); });
    Route::post('action/modify',function(){ return Action::update(); });
    //权限分配
    Route::get('roleaction',function(){ return RoleAction::index(); });
    Route::post('roleaction',function(){ return RoleAction::store(); });
    Route::get('roleaction/show',function(){ return RoleAction::show(); });
    Route::get('roleaction/check',function(){ return RoleAction::check(); });
});