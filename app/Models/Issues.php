<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issues extends Model
{
	/**
	*   关联表名称
	*/
    protected $table = 'issues';


    /**
    * 主键
    */
    protected $primaryKey = 'issue_id';

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
        'lottery_id', 'code', 'issue', 'belong_date','start_sale_time','end_sale_time', 'earliest_input_time', 'input_admin_id','input_time','verify_admin_id','verify_time','rank','status_fetch','status_code','status_check_prize','status_send_prize'
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
