<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\Lottory\MethodsService;
use Auth;
use App\Models\AdminUsers;

/**
 * 前台首页
 * @auther logen
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = AdminUsers::get();
        var_dump($data);
        try{
        } catch(Exception $e){
        }
        echo 1111;
        // return view('home');
    }
}
