<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'content', 'creator_id','modifier_id','status','rank','alias_name'
    ];


    /**
     * 获取配置列表
     * @return [type] [description]
     */
    public static function getConfig()
    {
        $data = self::where('status', '!=',0)->orderBy('rank','asc')->orderBy('id','desc')->paginate(10);
        return $data;
    }



    /**
     * 关联admin表
     * @return [type] [description]
     */
    public function admin()
    {
        return $this->belongsTo('\App\Models\User','creator_id');
    }

}
