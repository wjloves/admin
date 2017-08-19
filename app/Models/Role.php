<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	//protected $table =  'roles';
    protected $fillable = ['name','display_name'];
    public $timestamps = true;

    /*
    public function users()
    {
        return $this->belongsToMany('User', 'user_roles');
    }
    */

    public function perms()
    {
        return $this->belongsToMany('\App\Model\Permission');
    }

    public static function getList(){
    	return self::orderBy('id','asc')->paginate(10);
    }

    public function user()
    {
        return $this->hasMany('\App\Models\User','role_id','id');
    }

}
