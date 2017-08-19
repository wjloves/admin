<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
    *   关联表名称
    */
    protected $table = 'messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'keywords', 'reply','admin_id','status'
    ];



    /**
     * 关联管理员表
     * @return [type] [description]
     */
    public function admin()
    {
        return $this->belongsTo('App\Models\User','admin_id','id');
    }

}
