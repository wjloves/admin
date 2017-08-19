<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCourse extends Model
{
	/**
	*   关联表名称
	*/
    protected $table = 'user_course';


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
        'user_id', 'course_id'
    ];


    /**
     * 关联用户表
     * @return [type] [description]
     */
    public function user()
    {
        return $this->belongsTo('App\Models\UserWechat','user_id','id');
    }

    /**
     * 关联课程表
     * @return [type] [description]
     */
    public function course()
    {
        return $this->belongsTo('App\Models\Course','course_id','id');
    }
}
