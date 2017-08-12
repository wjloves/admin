<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();
Route::get('/', function () {
    return redirect('admin/login');
});
// Auth::routes();
// Route::group(['middleware' => 'auth'], function () {
//     Route::get('/game','GameController@index');
//     Route::any('/game/play','GameController@play')->name('play');
//     Route::get('/home', 'HomeController@index')->name('home');
// });



Route::group(['middleware'=>'web','prefix' => 'admin','namespace' => 'Admin'],function ()
{
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::get('login', 'Auth\LoginController@showLoginForm');
    Route::post('login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout');
    Route::get('register', 'Auth\RegisterController@showRegisterForm');
    Route::post('register', 'Auth\RegisterController@register');

    Route::get('/home', ['as' => 'admin.home', 'uses' => 'HomeController@index']);

    Route::group(['prefix'=>'user'],function(){
        Route::match(['get','post'],'/',['as'=>'user.list','uses'=>'UserController@index']);
        Route::match(['get','post'],'/store',['as'=>'user.store','uses'=>'UserController@usersStore']);
        Route::match(['get','post'],'/update',['as'=>'user.update','uses'=>'UserController@userUpdate']);
        Route::any('/lock/{id}/{state}',['as'=>'user.lock','uses'=>'UserController@userLock']);
    });

});



Route::group(['middleware'=>'wechat'],function(){
    Route::any('/wechat', 'WechatController@verifyToken');
});

Route::group(['middleware' => ['web', 'wechat.oauth']], function () {
        Route::get('/user', function () {
            $user = session('wechat.oauth_user'); // 拿到授权用户资料

            dd($user);
        });
});

Route::group(['prefix' => 'wechat'], function () {
    //服务端
    Route::any('server', ['as' => 'api.wechat.server', 'uses' => 'DemoController@server']);
    //oauth callback
    Route::get('callback', ['as' => 'api.wechat.callback', 'uses' => 'DemoController@callback']);
    //oauth userinfo
    Route::get('profile', ['as' => 'api.wechat.profile', 'uses' => 'DemoController@profile']);
});


