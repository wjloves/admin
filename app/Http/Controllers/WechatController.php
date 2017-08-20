<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use EasyWeChat\Foundation\Application;
use App\Services\Help\HelpService;
use App\Models\UserWechat;
use App\Models\Course;
use App\Models\Message;
use App\Models\CourseType;
use EasyWeChat\Message\text;

class WechatController extends Controller
{

    const Token = 'nwszkcskmvpaejc4qsg2ashbsisntwlq';

    const EncodingAESKey = 'cNhGgZdfqvE9ZzCuq42J2ZAizy7dieEdtbBZSFgqEcd';

    public $userId = 0;

    public $fromUserName ='';

    public $maycUser = 'user';
    /**
     * 基本验证
     */
    public function __construct()
    {
        //检测access_token 过期自动获取并缓存
        if(!Redis::get('wechat_access_token')){
            // $client = new Client();
            // $res = $client->request('GET', 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx0f131099096c80d1&secret=4e01f36d1569607d4d301f68a8d07a1a'
            // );
            // $access_token =  json_decode($res->getBody(),true);
            // Redis::set('wechat_access_token',$access_token['access_token']);
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
        $wechat = app('wechat');
        $userService = $wechat->user;
        $server = $wechat->server;

        //$this->maycUser = 'teacher';
        //return $this->textMessage('0950am 周日 jazz');
        $message = $server->getMessage();
        Log::info($message['FromUserName']);
        $this->fromUserName = $message['FromUserName'] ? $message['FromUserName'] : '1';

        //获取用户状态
        if($this->userId = UserWechat::getTeachByFromUser($this->fromUserName)){
            $this->maycUser = 'teacher';
        }

       // Log::info($message);
        $server->setMessageHandler(function($message){
            //$user = $userService->get($message->FromUserName);
        //    Log::info($user);
        //    Log::info($message);
          //  return "您好！欢迎关注我!".$user['nickname'];
            switch ($message->MsgType) {
                case 'event':
                    return '收到事件消息';
                    break;
                case 'text':
                    return $this->textMessage($message['Content']);
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });


        $response = $server->serve();
        Log::info($response);
        return $response;
    }

    /**
     * 文本消息类型判断
     * @param  string $content [description]
     * @return [type]          [description]
     */
    private function textMessage($content = '')
    {
        $noticeText = "请输入关键字，如：‘周一’,’周二‘,‘我的会员’,‘今日老师’";
        if(!$content){
            return  $noticeText;
        }
        $contents [] = ["Title" =>"欢迎关注多多工作室","Description" =>"", "PicUrl" =>"", "Url" =>""];
        $contents [] = ["Title" =>"【1】新闻 天气 空气 股票 彩票 星座\n"."【2】快递 人品 算命 解梦 附近 苹果\n"."【3】公交 火车 汽车 航班 路况 违章\n"."【4】翻译 百科 双语 听力 成语 历史\n"."【5】团购 充值 菜谱 贺卡 景点 冬吴\n"."【6】情侣相 夫妻相 亲子相 女人味\n"."【7】相册 游戏 笑话 答题 点歌 树洞\n"."【8】微社区 四六级 华强北 世界杯\n\n". "更多精彩，即将亮相，敬请期待！;", "Description" =>"", "PicUrl" =>"", "Url" =>""];
$contents[] = ["Title" =>"回复对应数字查看使用方法\n发送 0 返回本菜单", "Description" =>"", "PicUrl" =>"", "Url" =>""];
return $contents;
        //检测是否是老师并且是否是上报课程
        if($this->maycUser == 'teacher'){
            $temp = explode(' ', strtoupper($content));
            if(count($temp) >2){
                //检查录入信息格式是否正确
                if($courseTime =  isDatetime($temp)){
                    //判断录入类型是否存在
                    if(!$courseType = CourseType::checkByAlias($temp[2])){
                        return '暂未查询到课程类型，请重新录入';
                    }

                    $option = [
                            'course_time' => $courseTime,
                            'start_time'  => $courseTime,
                            'end_time'    => date('Y-m-d H:i:s',strtotime($courseTime)+3600),
                            'user_id'     => $this->userId,
                            'course_id'   => $courseType->id
                    ];

                    return $this->addCourse($option);
                }
            }
        }

        if($content == trim('我的会员')){
            $user   = UserWechat::getUserByFromUser($this->userId);
            $notice = "尊贵的".$user->vip->name."会员,您好!\n 您的会员信息如下:\n 剩余次数:".$user->times."\n 已使用次数:".$user->userCourse->count();
            return $notice;
        }else{
            //获取类型缓存
            if($textType = Redis::hget('autoReply',$content)){
                $message = json_decode($textType,true);
                return $message['reply'];
            }else{
                $message = Message::where('keywords',trim($content))->first();
                if($message){
                    return $message->reply;
                }
            }

             return $noticeText;
        }

    }

    /**
     * 获取今日课程表
     * @return [type] [description]
     */
    private function couresList($start_time,$end_time)
    {
        $list = Course::getCourseList($start_time,$end_time);
        return $list;
    }

    /**
     * 添加课程
     * @param [type] $content [description]
     */
    private function addCourse($option){

        //检查和创建
        $state = Course::createAndCheck($option);
        if($state){
            //存入缓存
            $redisKey = getWeek($option['start_time']);
           // Redis::hset($redisKey,$option['start_time']);
           // return response('恭喜，课程添加成功，课程开始时间:'.$option['start_time'].';请提前10分钟到教室，并注意短信提示课程报名人员');
            return '恭喜，课程添加成功，课程开始时间:'.$option['start_time'].';请提前10分钟到教室，并注意短信提示课程报名人员';
        }else{
           // return response('课程时间冲突或录入失败，请输入’今日课程‘查询本日课程表');
            return '课程时间冲突或录入失败，请输入’今日课程‘查询本日课程表';
        }
    }
}