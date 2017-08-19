<?php
namespace JiugeTo\AuthAdminManager\Models;

class ActionModel extends Model
{
    /**
     * 操作
     */

    protected $table = 'auth_action';
    protected $fillable = [
        'id','name','namespace','controller','url','action',
        'pid','left_show','del','created_at','updated_at',
    ];
}