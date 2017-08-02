<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MethodGroups extends Model
{
	/**
	*   关联表名称
	*/
    protected $table = 'method_groups';


    /**
    * 主键
    */
    protected $primaryKey = 'mg_id';

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
        'lottery_id', 'name', 'description', 'sort'
    ];

    /**
     * 关联玩法表
     * @return type
     */
    public function method()
    {
        return $this->hasMany('App\Models\Methods');
    }

    /**
     * 关联彩种
     * @return type
     */
    public function lottery()
    {
        return $this->belongsTo('App\Models\Lottery','lottery_id','lottery_id');
    }



}
