<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseType extends Model
{
	/**
	*   关联表名称
	*/
    protected $table = 'course_type';


    /**
    *  可批量修改字段
    */
    protected $fillable = [
        'full_name', 'alias_name'
    ];


    /**
     * 关联课程表
     * @return type
     */
    public function course()
    {
        return $this->hasMany('App\Models\Course','course_type_id','id');
    }

}
