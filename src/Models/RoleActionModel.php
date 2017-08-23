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

    public function getRoleName()
    {
        $model = RoleModel::find($this->role);
        return $model ? $model->name : '';
    }

    public function getActionName()
    {
        $model = ActionModel::find($this->role);
        return $model ? $model->name : '';
    }
}