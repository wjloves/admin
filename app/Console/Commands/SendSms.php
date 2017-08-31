<?php

namespace App\Console\Commands;

use DB;
use Log;
use Illuminate\Console\Command;
//use Mrgoon\AliSms\AliSms;
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

    protected $yunkey  = '';

    //yunkey =  5cda9afb2f6d1512a7755941b7b062b1
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
        date_default_timezone_set('PRC');
        set_time_limit(0);

        $start_time = date('Y-m-d 00:00:00');
        $end_time = date('Y-m-d 23:59:59');
        $sendSecond = 7200;

        $course = DB::table('courses')
                ->leftJoin('console_log','courses.id','=','console_log.job_id')
                ->select('courses.id','courses.user_id','courses.course_id','courses.start_time','console_log.job_id','console_log.status')
                ->whereBetween('courses.start_time',[$start_time,$end_time])
                ->get();
        $course = $course ? $course : false;

        if(count($course) > 0){
            foreach ($course as  $value) {
                //$this->info($value->id);
                //判断是否已发送
                if($value->job_id && $value->status == 1 ){
                    continue;
                }elseif(!$value->job_id){
                    DB::table('console_log')->insert(['job_id'=>$value->id,'created_at'=>date('Y-m-d H:i:s',time())]);
                }

                $courseType = DB::table('course_type')->where('id',$value->course_id)->first();
                $info = [];
                //获取信息
                if($user = DB::table('users_wechat')->where('id',$value->user_id)->first()){
                    //如果离开始还有两小时, 发送短信至手机（开课前半小时不发送）
                    $nowSecond = (strtotime($value->start_time) - time());
                    if( $nowSecond < $sendSecond && $nowSecond > 1800){
                        $signNum = DB::table('user_course')->where('course_id',$value->id)->count();
                        $option = [
                            'sign_num'=>$signNum,
                            'mobile'=> $user->mobile,
                            'start_time'=>$value->start_time,
                            'course_name'=>$courseType->full_name,
                            'nick_name' =>$user->nick_name
                        ];
                        $info = $this->sendSmsByType($option);
                        Log::info('短信发送返回结果：'.$info);
                    }
                }else{
                    $errorMsg = $value->start_time.' '.$courseType->full_name.' 课程未能找到开课老师信息';
                    Log::error('错误信息:'.$errorMsg);
                    $info = $this->sendSmsByType('',$errorMsg);
                    Log::info('短信发送返回结果：'.$info);
                }

                if( count($info) > 0 ){
                    if($info->code() == 0){
                        DB::table('console_log')->where('job_id',$value->id)->update(['status'=>1,'updated_at'=>date('Y-m-d H:i:s',time())]);
                    }
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
     * @return [type]         [description]
     */
    private function sendSmsByType($option=[],$errorMsg='')
    {
       // $sms = new AliSms();
        $client = YunpianClient::create(env('API_YUNPIAN_KEY'));


        if($option){
            $message = '【MayC工作室】'.$option['nick_name'].',您在'.date('h:i A',strtotime($option['start_time'])).'时间的课程，';
            $message = $option['sign_num'] ? $message.'共有'.$option['sign_num'].'人报名' : $message.'未有人报名，课程已取消';
            $param = [YunpianClient::MOBILE => $option['mobile'],YunpianClient::TEXT => $message];
            $info = $client->sms()->single_send($param);
            // $info = $sms->sendSms($option['mobile'],'SMS_CODE',['name'=>$message]);
        }else{
            $config = DB::table('configs')->where('alias_name','SOS_PHONE_NUMBER')->first();

            $param = [YunpianClient::MOBILE => trim($config->content),YunpianClient::TEXT => $errorMsg];
            $info = $client->sms()->single_send($param);

           // $info = $sms->sendSms(trim($config->content),'SMS_CODE',['name'=>$errorMsg]);
        }

        return $info;
    }
}
