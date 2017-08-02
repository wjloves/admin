<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'register_ip','salt','money', 'frozenMoney', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 覆盖Laravel中默认的getAuthPassword方法, 返回用户的password和salt字段
     * @return type
     */
    public function getAuthPassword()
    {
        return ['password' => $this->attributes['password'], 'salt' => $this->attributes['salt']];
    }

    /**
     * 关联奖金表
     * @return type
     */
    public function prize()
    {
       // return $this->hasOne('App\Models\Prize');
    }
}
