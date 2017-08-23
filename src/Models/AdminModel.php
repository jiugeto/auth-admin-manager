<?php
namespace JiugeTo\AuthAdminManager\Models;

class AdminModel extends Model
{
    /**
     * 管理员
     */

    protected $table = 'auth_admin';
    protected $fillable = [
        'id','name','role','password','created_at','updated_at',
    ];

    public function getRoleName()
    {
        $roleModel = RoleModel::find($this->role);
        return $roleModel ? $roleModel->name : '';
    }
}