<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWechat extends Model
{
    const teacherGroup = 1;

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
        'username', 'nick_name','wechat', 'openid','card_type', 'from_user','user_group','times','ex_time','status'
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
    static public function getTeachByFromUser($from_user)
    {
        $user = self::where('user_group',self::teacherGroup)->where('from_user',$from_user)->first();

        if($user){
            return $user->id;
        }

        return false;
    }

    /**
     * 关联用户组表
     * @return [type] [description]
     */
    public function userGroup()
    {
        return $this->belongsTo('App\Models\UserGroups','user_group','id');
    }

    /**
     * 关联会员表
     * @return [type] [description]
     */
    public function vip()
    {
        return $this->belongsTo('App\Models\Vip','vip_id','id');
    }
}
