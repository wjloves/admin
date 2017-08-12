<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use EasyWeChat\Foundation\Application;

class WechatController extends Controller
{

    const Token = 'nwszkcskmvpaejc4qsg2ashbsisntwlq';

    const EncodingAESKey = 'cNhGgZdfqvE9ZzCuq42J2ZAizy7dieEdtbBZSFgqEcd';
    /**
     * 基本验证
     */
    public function __construct()
    {
        //检测access_token 过期自动获取并缓存
        if(!Redis::get('wechat_access_token')){
            $client = new Client();
            $res = $client->request('GET', 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx0f131099096c80d1&secret=4e01f36d1569607d4d301f68a8d07a1a'
            );
            $access_token =  json_decode($res->getBody(),true);
            Redis::set('wechat_access_token',$access_token['access_token']);
        }
    }

    /**
     * 验证开发者
     * @return [type] [description]
     */
    public function verifyToken(Request $request)
    {
        $echostr = $request->input('echostr');
        Log::info($echostr);
        return response($echostr, 200);
    }

    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve(Request $request)
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $wechat = app('wechat');
        $userService = $wechat->user;
        $server = $wechat->server;

       // $message = $server->getMessage();

       // Log::info($message);
        $server->setMessageHandler(function($message){
            $user = $userService->get($message->FromUserName);
            return "您好！欢迎关注我!".$user['nickname'];
        });


        $response = $server->serve();
        Log::info($response);
        return $response;
    }
}