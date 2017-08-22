<?php

/**
 * 后台权限管理路由
 */


use JiugeTo\AuthAdminManager\Facades\Admin;
use JiugeTo\AuthAdminManager\Facades\Action;
use Illuminate\Support\Facades\Input;

Route::group(['prefix'=>'admin'],function(){
    //管理员路由
    Route::get('admin',function(){ return Admin::index(); });
    Route::get('admin/create',function(){ return Admin::create(); });
    Route::post('admin',function(){ return Admin::store(Input::all()); });
    Route::get('admin/edit',function(){ return Admin::edit(Input::all()); });
    Route::get('admin/modify',function(){ return Admin::update(Input::all()); });
    //角色路由
    Route::get('role',function(){ return Role::index(); });
    Route::get('role/create',function(){ return Role::create(); });
    Route::post('role',function(){ return Role::store(Input::all()); });
    Route::get('role/edit',function(){ return Role::edit(Input::all()); });
    Route::post('role/modify',function(){ return Role::modify(Input::all()); });
    //操作路由
    Route::get('action',function(){ return Action::index(); });
    Route::get('action/create',function(){ return Action::create(); });
    Route::post('action',function(){ return Action::store(Input::all()); });
    Route::get('action/edit',function(){ return Action::edit(Input::all()); });
    Route::post('action/modify',function(){ return Action::modify(Input::all()); });
});