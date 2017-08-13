<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroups extends Model
{
	/**
	*   关联表名称
	*/
    protected $table = 'user_groups';


    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;


    /**
    *  可批量修改字段
    */
    protected $fillable = [
        'group_name'
    ];


    /**
     * 关联用户表
     * @return [type] [description]
     */
    public function user()
    {
        return $this->hasMany('App\Models\UserWechat','user_group','id');
    }
}
