<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
	/**
	*   关联表名称
	*/
    protected $table = 'prize';


    /**
    * 主键
    */
    protected $primaryKey = 'prize_id';

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
        'lottery_id', 'method_id', 'odds', 'prize'
    ];
    
}
