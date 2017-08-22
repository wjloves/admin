<?php

namespace App\Console\Commands;

use DB;
use Log;
use Illuminate\Console\Command;
use Mrgoon\AliSms\AliSms;

class SendCourseSms extends Command
{

 	protected $sosPhoneNum = 'SOS_PHONE_NUMBER';

	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendsms:course';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send sms to user about sign up of course ';

	public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$this->line('sendsms:course start.');
    	ignore_user_abort(true);
        set_time_limit(0);

        $start_time = date('Y-m-d 00:00:00');
        $end_time = date('Y-m-d 23:59:59');
        $sendSecond = 7200;

        $course = DB::table('courses')->whereBetween('start_time',[$start_time,$end_time])->get();

        foreach ($course as  $value) {
        	//判断是否已发送
        	if(DB::table('console_log')->where('job_id',$value->id)->where('status',1)->first()){
        		continue;
        	}

        	$courseType = DB::table('course_type')->where('id',$value->course_id)->first();
        	//获取信息
        	if($user = DB::table('users_wechat')->where('id',$value->user_id)->first()){
        		//如果离开始还有两小时  发送短信至手机
	            if( (strtotime($value->start_time) - time()) < $sendSecond ){
	            	DB::table('console_log')->create(['job_id'=>$value->id,'created_at'=>date('Y-m-d H:i:s')]);
	            	$signNum = DB::table('user_course')->where('course_id',$value->id)->count();
	            	$option = [
	            		'sign_num'=>$signNum,
	            		'mobile'=> $user->mobile,
	            		'start_time'=>$value->start_time,
	            		'course_name'=>$courseType->full_name,
	            		'nick_name' =>$user->nick_name
	            	];
	            	$state = $this->sendSmsByType($option);
	            	Log::info('短信发送返回结果：'.json_encode($state));
	            }
        	}else{
        		$errorMsg = $value->start_time.' '.$courseType->full_name.' 未能找到用户信息';
        		Log::error('错误信息:'.$errorMsg);
        		$this->sendSmsByType('',,$errorMsg);
        	}
        }
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

    	if($option){
    		$message = $option['nick_name'].' 您在'.date('h:i A',strtotime($option['start_time'])).'时间的'.$option['course_name'].'的课程';
    		$message = $option['sign_num'] ? $message.'共有'.$option['sign_num'].'人报名' : $message.'未有人报名，取消课程';
    		$info = $sms->sendSms($option['mobile'],'SMS_CODE',['name'=>$message]);
    	}else{
    		$config = DB::table('configs')->where('alias_name',$this->sosPhoneNum)->first();
    		$info = $sms->sendSms(trim($config->content),'SMS_CODE',['name'=>$errorMsg]);
    	}

    	return $info;
    }
}