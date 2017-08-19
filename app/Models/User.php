<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const rootId = 1;

    protected $table = 'admin_users';
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
     * 获取菜单
     * @param  [type] $userid [description]
     * @return [type]         [description]
     */
    public function getPerms($userid){
        if(self::rootId == $this->id){//超级管理员全部权限
            $perms = \App\Models\Permission::where('is_delete',0)->get();
        }else{
            $perms = $this->role->perms;
        }
        return $perms;
    }

    /**
     * 获取表单
     * @return [type] [description]
     */
    public function getMenuPerms(){
        if(self::rootId == $this->id){//超级管理员全部权限
            $perms = \App\Models\Permission::where('is_delete',0)->where('method','GET')->get();
        }else{
            $perms = $this->role->perms;
        }
        return $perms;
    }


    /**
     * 关联角色表
     * @return [type] [description]
     */
    public function role(){
        return $this->BelongsTo('App\Models\Role','role_id','id');
    }
}
