<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
    *   关联表名称
    */
    protected $table = 'articles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'thumb_img_url','content','tags','status','is_push','creater_id','updater_id'
    ];



    /**
     * 关联管理员表
     * @return [type] [description]
     */
    public function cadmin()
    {
        return $this->belongsTo('App\Models\User','creater_id','id');
    }

     /**
     * 关联管理员表
     * @return [type] [description]
     */
    public function uadmin()
    {
        return $this->belongsTo('App\Models\User','updater_id','id');
    }

}
