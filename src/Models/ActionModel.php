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

    /**
     * 左侧是否显示
     */
    protected $leftShows = [
        '不显示','显示',
    ];

    public function getLeftShowName()
    {
       return array_key_exists($this->left_show,$this->leftShows) ?
           $this->leftShows[$this->left_show] : '';
    }

    /**
     * 获取父级名称
     */
    public function getParentName()
    {
        $model = ActionModel::find($this->pid);
        return $model ? $model->name : '';
    }
}