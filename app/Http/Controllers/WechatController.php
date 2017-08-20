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
use EasyWeChat\Message\News;

class WechatController extends Controller
{

    const Token = 'nwszkcskmvpaejc4qsg2ashbsisntwlq';

    const EncodingAESKey = 'cNhGgZdfqvE9ZzCuq42J2ZAizy7dieEdtbBZSFgqEcd';

    public $userId = 1;

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

      // $this->maycUser = 'teacher';
      //  return $this->textMessage('周一');
        $message = $server->getMessage();
        Log::info($message['FromUserName']);
        $this->fromUserName = $message['FromUserName'] ? $message['FromUserName'] : '1';

        //获取用户状态
        if($this->userId = UserWechat::getTeachByFromUser($this->fromUserName)){
            $this->maycUser = 'teacher';
        }

        $server->setMessageHandler(function($message){
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
        $noticeText = Redis::get('noticeMessage');
        $noticeText = $noticeText ? $noticeText : "请输入关键字，如：‘周一’,’周二‘,‘我的会员’,‘今日老师’";
        if(!$content){
            return  $noticeText;
        }

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

                    if(strtotime($courseTime) < time()){
                        return '时间错误，请重新输入';
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
        }

        $notice = $this->replyByType($content);
        return $notice ? $notice : $noticeText;

    }

    /**
     * 通过类型回复消息
     * @param  [type] $content [description]
     * @return [type]          [description]
     */
    private function replyByType($content)
    {
        $toweeks = ['周日','周一','周二','周三','周四','周五','周六',];
        if(in_array($content, $toweeks)){
            $title = $content."课程表";
            $description = '';
            $week = getWeekToEn($content);
            $courses = Redis::hget('courses',$week);
            if(!$courses){
                $temp = '0000am '.$content;
                $searchTime = isDatetime(explode(' ', strtoupper($temp));
                $searchTime = date('Y-m-d',$searchTime);
                $courses = Course::getCourseList($searchTime.' 00:00:00',$searchTime.' 23:59:59');
                //放入缓存
                Redis::hset('courses',$week,json_encode($courses));
            }else{
                $courses  = json_decode($courses,true);
            }

            $description .= "编号       时间        课程         老师\n";
            foreach ($courses as $key => $value) {
                 $description .= "  ".$value['id']."      ".date('h:iA',strtotime($value['start_time']))."     ".$value['course']."    (".$value['teacher'].") \n";
            }
            $description .= "注意事项：\n"."1、期卡学员每月保证来三次，不满三次按三次计算（确保进度）\n"."2、请假需提前三小时通知 \n"."NG、请各位同学提前做好安排😆 \n\n"."回复：“报名”+课程编号进行报名";
            $news = new News(["title" =>$title,"description" =>$description]);
            return $news;

        }else{
            if($textType = Redis::hget('autoReply',$content)){
                $message = json_decode($textType,true);
                return $message['reply'];
            }else{
                $message = Message::where('keywords',trim($content))->first();
                if($message){
                    return $message->reply;
                }
            }
        }

        return false;
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
            $week = getWeek($option['start_time']);
            $searchTime = date('Y-m-d',strtotime($option['start_time']));

            $courses = Course::getCourseList($searchTime.' 00:00:00',$searchTime.' 23:59:59');

            //放入缓存
            Redis::hset('courses',getWeekToEn($week),json_encode($courses));
           // return response('恭喜，课程添加成功，课程开始时间:'.$option['start_time'].';请提前10分钟到教室，并注意短信提示课程报名人员');
            return '恭喜，课程添加成功，课程开始时间:'.$option['start_time'].';请提前10分钟到教室，并注意短信提示课程报名人员';
        }else{
           // return response('课程时间冲突或录入失败，请输入’今日课程‘查询本日课程表');
            return '课程时间冲突或录入失败，请输入日期如：“周一”，查询课程表';
        }
    }
}