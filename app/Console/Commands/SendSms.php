<?php

namespace App\Console\Commands;

use DB;
use Log;
use Illuminate\Console\Command;
use Mrgoon\AliSms\AliSms;
use Yunpian\Sdk\YunpianClient;

class SendSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coursesms:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send sms to user about sign up of course ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->yunKey = env('API_YUNPIAN_KEY');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
     public function handle()
    {
        $this->info('sms start');
        Log::info('sms start');
        ignore_user_abort(true);
        set_time_limit(0);

        $start_time = date('Y-m-d 00:00:00');
        $end_time = date('Y-m-d 23:59:59');
        $sendSecond = 72000;

        $course = DB::table('courses')->whereBetween('start_time',[$start_time,$end_time])->get();
        $course = $course ? $course : false;

        if(count($course) > 0){
            foreach ($course as  $value) {
                //$this->info($value->id);
                //判断是否已发送
                if($courselist = DB::table('console_log')->where('job_id',$value->id)->where('status',1)->first()){
                    continue;
                }

                $courseType = DB::table('course_type')->where('id',$value->course_id)->first();
                //获取信息
                if($user = DB::table('users_wechat')->where('id',$value->user_id)->first()){
                    //$this->info($sendSecond);
                    //如果离开始还有两小时  发送短信至手机
                    $nowSecond = (strtotime($value->start_time) - time());
                    if( $nowSecond < $sendSecond ){
                        //$this->info($value->id);
                        DB::table('console_log')->insert(['job_id'=>$value->id,'created_at'=>date('Y-m-d H:i:s',time())]);
                        $signNum = DB::table('user_course')->where('course_id',$value->id)->count();
                        $option = [
                            'sign_num'=>$signNum,
                            'mobile'=> $user->mobile,
                            'start_time'=>$value->start_time,
                            'course_name'=>$courseType->full_name,
                            'nick_name' =>$user->nick_name
                        ];
                        $info = $this->sendSmsByType($option);
                        Log::info('短信发送返回结果：'.json_encode([$info]));
                    }
                }else{
                    $errorMsg = $value->start_time.' '.$courseType->full_name.' 未能找到用户信息';
                    Log::error('错误信息:'.$errorMsg);
                    $info = $this->sendSmsByType('',$errorMsg);
                    Log::info('短信发送返回结果：'.json_encode([$info]));
                }
            }
        }else{
            Log::error(date('Y-m-d').'没有课程');
        }

        $this->info('sms end');
        Log::info('sms end');
    }

    /**
     * 不同情况发送不同信息
     * @param  [type] $option [description]
     * @param  [type] $state  [description]
     * @return [type]         [description]
     */
    private function sendSmsByType($option=[],$state=false,$errorMsg='')
    {
        $sms = new AliSms();
        $client = YunpianClient::create($this->yunKey);

        if($option){
            $message = $option['nick_name'].' 您在'.date('h:i A',strtotime($option['start_time'])).'时间的'.$option['course_name'].'的课程';
            $message = $option['sign_num'] ? $message.'共有'.$option['sign_num'].'人报名' : $message.'未有人报名，课程已取消';
            return $message;
            $param = [YunpianClient::MOBILE => $option['mobile'],YunpianClient::TEXT => $message];
            $info = $client->sms()->single_send($param);
            // $info = $sms->sendSms($option['mobile'],'SMS_CODE',['name'=>$message]);
        }else{
            $config = DB::table('configs')->where('alias_name',$this->sosPhoneNum)->first();
            $param = [YunpianClient::MOBILE => trim($config->content),YunpianClient::TEXT => $errorMsg];
            $info = $client->sms()->single_send($param);
           // $info = $sms->sendSms(trim($config->content),'SMS_CODE',['name'=>$errorMsg]);
        }

        return $info;
    }
}
