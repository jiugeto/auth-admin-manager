<?php

/**
 * 后台权限管理路由
 */


use JiugeTo\AuthAdminManager\Facades\Admin;
use Illuminate\Http\Request;
Route::group(['prefix'=>'admin'],function(){
    Route::get('admin',function(){ return Admin::index(); });
    Route::get('admin/create',function(){ return Admin::create(); });
    Route::get('admin/store',function(){ return Admin::store(new Request()); });
});