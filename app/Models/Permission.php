<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
	/**
	*   关联表名称
	*/
    protected $table = 'permissions';

    /**
    *  可批量修改字段
    */
    protected $fillable = [
        'name','desc','method','uri','ismenu','pid','group','sort'
    ];


    /**
     * 获取路由
     * @param  [type] $uri [description]
     * @return [type]      [description]
     */
    public static function findByUri($uri){
        return self::where('uri',$uri)->where('is_delete',0)->first();
    }

    /**
     * 获取路由 By 请求方式和路由
     * @param  [type] $method [description]
     * @param  [type] $uri    [description]
     * @return [type]         [description]
     */
    public static function findByMethodAndUri($method,$uri){
        return self::where('method',$method)->where('uri',$uri)->first();
    }

    /**
     * 关联菜单表
     * @return [type] [description]
     */
    public function menu()
    {
        return $this->belongsTo('App\Models\Menus','id','perm_id');
    }

    //获取admin路由组的路由表
    public static function getTree(){
        $perms = self::where('is_delete',0)->get();
        $tree = [];
        foreach($perms as $perm){
            $uri = $perm->uri;
            $uris = explode("/",$uri);
            if($uris[0] == 'admin' && count($uris) == 3){
                $group = $uris[1];             //routes.php的路由不能随便加,admin路由组后面一段uri是权限组;
                $tree[$group][] = $perm;
            }

        }
        return $tree;
    }

}
