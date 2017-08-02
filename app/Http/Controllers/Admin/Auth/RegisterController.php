<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\AdminUsers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';

    protected $guard = 'admin';

    protected $loginView = 'admin.auth.register';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $salt = Str::random(6);
        return AdminUsers::create([
            'username' => $data['username'],
            'status' => 1,
            'password' => sha1($salt . $data['password']),
            'ip' => request()->ip(),
            'salt' => $salt
        ]);
    }

    /**
     * 重写注册视图页面
     * @return type
     */
    public function showRegisterForm()
    {
        return view('admin.auth.register');
    }


    /**
     * 自定义认证驱动
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }
}
