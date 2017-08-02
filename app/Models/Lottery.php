<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lottery extends Model
{
	/**
	*   关联表名称
	*/
    protected $table = 'lottery';


    /**
    * 主键
    */
    protected $primaryKey = 'lottery_id';

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
        'name', 'cname', 'web_site', 'lottery_type','property_id','description', 'settings', 'yearly_start_closed','yearly_end_closed','status','sort'
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
     * 关联玩法组表
     * @return type
     */
    public function methodGroups()
    {
        return $this->hasMany('App\Models\MethodGroups');
    }
}
