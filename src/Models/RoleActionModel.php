<?php
namespace JiugeTo\AuthAdminManager\Models;

class RoleActionModel extends Model
{
    /**
     * 角色、操作关联
     */

    protected $table = 'auth_role_action';
    protected $fillable = [
        'id','role','action','del','created_at','del_time',
    ];
}