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
        return $model ? $model->name : '顶级操作';
    }

    /**
     * 获取所有子操作
     */
    public function getSubsByPid()
    {
        $subArr = array();
        $models = ActionModel::where('pid',$this->id)
            ->where('del',0)
            ->get();
        if (!count($models)) { return $subArr; }
        foreach ($models as $k=>$model) {
            $subArr[$k]['id'] = $model->id;
            $subArr[$k]['name'] = $model->name;
            $subArr[$k]['url'] = $model->url;
        }
        return $subArr;
    }
}