<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Auth;

/**
 * BaseController
 * @auther logen
 */
class BaseController extends Controller
{

    public function __construct(){
    	$this->middleware('admin');
    }

}
