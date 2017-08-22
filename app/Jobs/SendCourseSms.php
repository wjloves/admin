<?php

namespace App\Jobs;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendCourseSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $times;

    protected $sendSecond;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->$times = date('Y-m-d');
        $this->sendSecond = 7200;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $courses = Course::getCourseList($this->$times.' 00:00:00',$this->$times.' 23:59:59');
        foreach ($courses as $course) {
                //如果离开课还有两小时
                if( (strtotime($course['start_time']) - time()) < $this->sendSecond ){

                }
        }
    }
}
