<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Methods extends Model
{
	/**
	*   关联表名称
	*/
    protected $table = 'methods';


    /**
    * 主键
    */
    protected $primaryKey = 'method_id';

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
        'lottery_id', 'mg_id', 'name', 'cname','description','is_lock', 'exp', 'can_input','status','sort'
    ];

    /**
     * 关联彩种
     * @return type
     */
    public function lottery()
    {
        return $this->belongsTo('App\Models\Lottery','lottery_id','lottery_id');
    }

}
