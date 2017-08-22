<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWechat extends Model
{
    const teacherGroup = 2;

    /**
    *   关联表名称
    */
    protected $table = 'users_wechat';


    /**
    * 主键
    */
    protected $primaryKey = 'id';

    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'nick_name','vip_id', 'from_user','group_id','times','ex_time','status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * 通过from_user 查看是否是老师
     * @param  [type] $from_user [description]
     * @return [type]            [description]
     */
    public static function getTeachByFromUser($from_user)
    {
        $user = self::where('from_user',$from_user)->first();

        if($user){
            return $user->id;
        }
        return false;
    }

    /**
     * 通过FromUser获取用户信息
     * @return [type] [description]
     */
    public static function getUserByFromUser()
    {
        return self::with('vip')->with('userCourse')->where('from_user',$from_user)->first();
    }

    /**
     * 关联用户组表
     * @return [type] [description]
     */
    public function userGroup()
    {
        return $this->belongsTo('App\Models\UserGroups','group_id','id');
    }

    /**
     * 关联会员表
     * @return [type] [description]
     */
    public function vip()
    {
        return $this->belongsTo('App\Models\Vip','vip_id','id');
    }

    /**
     * 关联报名表
     * @return [type] [description]
     */
    public function userCourse()
    {
        return $this->hasMany('App\Models\UserCourse','id','user_id');
    }
}
