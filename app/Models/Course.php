<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
	/**
	*   关联表名称
	*/
    protected $table = 'courses';


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
        'user_id', 'course_time', 'start_time', 'end_time'
    ];

    /**
     * 检测课程和创建新课程
     * @param  array  $option [description]
     * @return [type]         [description]
     */
    static public function createAndCheck($option = [])
    {
        $courseCheck = self::whereBetween('start_time',[$option['start_time'],$option['end_time']])->first();
        if($courseCheck){
            return false;
        }else{
            return self::create($option) ? true : false;
        }
    }

    /**
     * 获取课程列表
     * @param  string $start_time [description]
     * @param  string $end_time   [description]
     * @return [type]             [description]
     */
    static public function getCourseList($start_time='',$end_time='')
    {
        $list = self::with('user')->with('userCourse')->whereBetween('start_time',[$start_time,$end_time])->get();
        $content = '课程表';
        foreach ($list as $key => $value) {
            $content .= '开课时间:'.$value->start_time.',老师：'.$value->user->nick_name.',已报名：'.$value->userCourse->count().'人&#x0A;';
        }
        return $content;
    }

    /**
     * 关联用户表
     * @return [type] [description]
     */
    public function user()
    {
        return $this->belongsTo('App\Models\UserWechat','user_id','id');
    }

    /**
     * 关联报名表
     * @return [type] [description]
     */
    public function userCourse()
    {
        return $this->hasMany('App\Models\UserCourse','course_id','id');
    }
}
