<?php
namespace JiugeTo\AuthAdminManager\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    /**
     * 基础 Model
     */

    public $timestamps = false;

    public function createTime()
    {
        return $this->created_at ? date('Y-m-d H:i',$this->created_at) : '';
    }

    public function updateTime()
    {
        return $this->updated_at ? date('Y-m-d H:i',$this->updated_at) : '';
    }
}