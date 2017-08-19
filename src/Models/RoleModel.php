<?php
namespace JiugeTo\AuthAdminManager\Models;

class RoleModel extends Model
{
    /**
     * 角色
     */
    protected $table = 'auth_role';
    protected $fillable = [
        'id','name','created_at','updated_at',
    ];
}