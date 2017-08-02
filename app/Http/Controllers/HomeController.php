<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\Lottory\MethodsService;
use Auth;

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
        try{
        } catch(Exception $e){
        }
        echo 1111;
        return view('home');
    }
}
