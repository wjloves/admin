<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lottery extends Model
{
	/**
	*   关联表名称
	*/
    protected $table = 'order';


    /**
    * 主键
    */
    protected $primaryKey = 'order_id';

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
        'user_id', 'lottery_id', 'issue', 'amount','codes','check_prize_status', 'send_prize_status', 'send_prize_time','site_no','status','frm','user_ip'
    ];
    
    /**
     * 关联彩种表
     * @return type
     */
    public function lottery()
    {
        return $this->belongsToMany('App\Models\Lottery');
    }
    
}
