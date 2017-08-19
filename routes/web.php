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


Route::group(['middleware'=>'web','prefix' => 'admin','namespace' => 'Admin'],function ()
{

    Route::get('/','Auth\LoginController@showLoginForm');
    Route::get('login', 'Auth\LoginController@showLoginForm');
    Route::post('login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout');
    Route::get('register', 'Auth\RegisterController@showRegisterForm');
    Route::post('register', 'Auth\RegisterController@register');
    Route::group(['middleware'=>'menu'],function ()
    {

        Route::get('/home', ['as' => 'admin.home', 'uses' => 'HomeController@index']);
        Route::group(['prefix'=>'user'],function(){
            Route::match(['get','post'],'/',['as'=>'user.list','uses'=>'UserController@index']);
            Route::match(['get','post'],'/store',['as'=>'user.store','uses'=>'UserController@usersStore']);
            Route::match(['get','post'],'/update/{id}',['as'=>'user.update','uses'=>'UserController@userUpdate']);
            Route::any('/lock/{id}/{state}',['as'=>'user.lock','uses'=>'UserController@userLock']);
        });

        Route::group(['prefix'=>'course'],function(){
            Route::match(['get','post'],'/',['as'=>'course.list','uses'=>'CourseController@index']);
            Route::match(['get','post'],'/store',['as'=>'course.store','uses'=>'CourseController@courseStore']);
            Route::match(['get','post'],'/update/{id}',['as'=>'course.update','uses'=>'CourseController@courseUpdate']);
            Route::any('/lock/{id}/{state}',['as'=>'course.lock','uses'=>'CourseController@courseLock']);

            Route::get('/type', ['as'=>'course.type.list','uses'=>'CourseController@courseTypeList']);
            Route::match(['get','post'],'/type/store',['as'=>'course.type.store','uses'=>'CourseController@typeStore']);
            Route::match(['get','post'],'/type/update/{id}',['as'=>'course.type.update','uses'=>'CourseController@typeUpdate']);
            Route::any('/type/del/{id}',['as'=>'course.type.del','uses'=>'CourseController@typeDel']);
        });
        Route::group(['prefix'  =>  '/config/menu'], function(){
            Route::get('/list', ['as'=>'menu.list','uses'=>'MenuController@getList']);
            Route::match(['get','post'],'/store', ['as'=>'menu.store','uses'=>'MenuController@menuStore']);
            Route::match(['get','post'],'/update/{id}', ['as'=>'menu.update','uses'=>'MenuController@menuUpdate']);
            Route::post('/del/{id}', ['as'=>'menu.del','uses'=>'MenuController@postDel']);
        });

        Route::group(['prefix'  =>  '/user/vip'], function(){
            Route::get('/list', ['as'=>'vip.list','uses'=>'VipController@getList']);
            Route::match(['get','post'],'/store', ['as'=>'vip.store','uses'=>'VipController@vipStore']);
            Route::match(['get','post'],'/update/{id}', ['as'=>'vip.update','uses'=>'VipController@vipUpdate']);
            Route::any('/del/{id}', ['as'=>'vip.del','uses'=>'VipController@vipDel']);
        });

        Route::group(['prefix'  =>  '/content'], function(){
            Route::get('/autoreply', ['as'=>'message.list','uses'=>'MessageController@index']);
            Route::match(['get','post'],'/store', ['as'=>'message.store','uses'=>'MessageController@messageStore']);
            Route::match(['get','post'],'/update/{id}', ['as'=>'message.update','uses'=>'MessageController@messageUpdate']);
            Route::any('/lock/{id}/{status?}', ['as'=>'message.lock','uses'=>'MessageController@messageLock']);
        });
    });
});



Route::group(['middleware'=>'wechat'],function(){
    Route::any('/wechat', 'WechatController@verifyToken');
    Route::any('/test', 'WechatController@serve');
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


