<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    protected $table =  'menus';
    protected $fillable = ['pid','name','group','sort','perm_id'];
    public $timestamps = true;

    /**
     * 获取顶级菜单
     * @return [type] [description]
     */
    public static function getTopMenus(){
        return self::with('parent_menu')->with('sub_menus')->with('perm')->where('pid',0)->orderBy('sort','asc')->get();
    }

    public function sub_menus(){
        //Relationship method must return an object of type Illuminate\Database\Eloquent\Relations\Relation
        //return $this->belongsTo('\App\Model\Menu','id','pid');//都OK;
        //return $this->hasMany('\App\Model\Menu','pid','id');
        $relation = $this->hasMany('\App\Models\Menus','pid','id');
        $relation->orderBy('group','asc')->orderBy('sort','asc');//Relation的魔术方法调内置的query对象的相应方法;
        return $relation;
    }

    public function parent_menu(){
        //return $this->hasOne('\App\Model\Menu','id','pid');//ok
        return $this->belongsTo('\App\Models\Menus','pid','id');
    }

    public function isTop(){
        retrun ($this->pid == 0);
    }

    /**
     * 关联路由表
     * @return [type] [description]
     */
    public function perm(){
        return $this->belongsTo('\App\Models\Permission','perm_id','id');
    }

    /*
    public function roles()
    {
        return $this->hasMany(Role::class);
    }
    */

}
