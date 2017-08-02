<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWechat extends Model
{
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
        'username', 'wechatID', 'card_type', 'times','ex_time','status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];


    /**
     *
     * @return type
     */
    public function prize()
    {
       // return $this->hasOne('App\Models\Prize');
    }
}
