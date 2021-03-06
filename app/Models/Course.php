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
        'user_id', 'course_time', 'start_time', 'end_time','course_id'
    ];

    /**
     * 检测课程和创建新课程
     * @param  array  $option [description]
     * @return [type]         [description]
     */
    static public function createAndCheck($option = [])
    {
        $courseCheck = self::where('status','!=',0)->whereBetween('start_time',[$option['start_time'],$option['end_time']])->first();
        if($courseCheck){
            return false;
        }else{
            return self::create($option);
        }
    }

    /**
     * 检测
     * @return [type] [description]
     */
    static public function check($start_time)
    {
        return self::whereBetween('start_time',[$start_time,date('Y-m-d H:i:s',strtotime($start_time)+3600)])->first();
    }

    /**
     * 获取课程列表
     * @param  string $start_time [description]
     * @param  string $end_time   [description]
     * @return [type]             [description]
     */
    static public function getCourseList($start_time='',$end_time='')
    {
        $list = self::with('user')->with('courseType')->whereBetween('start_time',[$start_time,$end_time])->get();

        $courses = [];
        foreach ($list as $key => $value) {
            $courses[$key]['id']         = $value->id;
            $courses[$key]['start_time'] = $value->start_time;
            $courses[$key]['teacher']    = $value->user->nick_name;
            $courses[$key]['course']     = $value->courseType->full_name;
        }
        return $courses;
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

    /**
     * 关联课程类别表
     * @return [type] [description]
     */
    public function courseType()
    {
        return $this->belongsTo('App\Models\CourseType','course_id','id');
    }
}
