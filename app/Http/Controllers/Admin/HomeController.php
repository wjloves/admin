<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;

/**
 *  后台首页
 *  @auther logen
 */
class HomeController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.home',['user'=>Auth::guard('admin')->user()]);
    }
}
