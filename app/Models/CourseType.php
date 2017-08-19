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
        'full_name', 'alias_name','admin_id','status'
    ];

    /**
     * 通过别名检测
     * @param  [type] $aliasname [description]
     * @return [type]            [description]
     */
    public static function checkByAlias($aliasname)
    {
        return self::where('alias_name',$aliasname)->where('status',8)->first();
    }

    /**
     * 获取列表
     * @return [type] [description]
     */
    public static function getList()
    {
        return self::where('status',8)->get();
    }

    /**
     * 关联课程表
     * @return type
     */
    public function course()
    {
        return $this->hasMany('App\Models\Course','course_type_id','id');
    }

    /**
     * 关联管理员表
     * @return [type] [description]
     */
    public function admin()
    {
        return $this->belongsTo('App\Models\User','admin_id','id');
    }

}
