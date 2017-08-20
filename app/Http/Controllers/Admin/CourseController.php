<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;
use App\Models\UserWechat as UserModel;
use Illuminate\Support\Str;
use App\Models\UserGroups;
use App\Models\Course;
use App\Models\CourseType;

/**
 *  用户管理
 *  @auther logen
 */
class CourseController extends BaseController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 课程列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $courses = Course::with('user')->with('userCourse')->with('courseType')->where('status','!=',0)->paginate(10);

        return view('admin.course.list',compact('courses'));
    }

 /**
     *  新建课程
     * @param Request $request
     * @return type html/json
     */
    public function courseStore(Request $request)
    {
        //执行操作
        if( $request->isMethod('post') )
        {
            $data = $request->toArray();
            $this->couseValidator($data);

            //检查教师是否存在
            if( !$user = UserModel::where('nick_name',$data['nick_name'])->where('group_id',2)->first()){
                return response()->json(['errorCode' => 60008, 'message' => '查询不到老师信息，请重新输入昵称']);
            }

            $option=[];
            $option['course_time'] = $data['start_time'];
            $option['start_time']  = $data['start_time'].' '.$data['start_time_exp'].':00';
            $option['end_time']    = date('Y-m-d H:i:s',strtotime($option['start_time'])+3600);
            //检查课程是否冲突
            if( Course::check($option['start_time']) )
            {
                return response()->json(['errorCode' => 60007, 'message' => '课程时间冲突']);
            }

            $option['course_id'] = $data['course_id'];
            $option['user_id'] = $user->id;

            $status = Course::create($option);

            if( $status )
            {
                $week = getWeek($option['start_time']);
                $searchTime = date('Y-m-d',strtotime($option['start_time']));
                $courses = Course::getCourseList($searchTime.' 00:00:00',$searchTime.' 23:59:59');
                //放入缓存
                Redis::hset('courses',getWeekToEn($week),json_encode($courses));
                return response()->json(array('errorCode' => 00000, 'message' => '创建成功', 'route' => route('course.list')));
            }

            return response()->json(array('errorCode' => 60002, 'message' => '创建失败'));
        }
        $courseType = CourseType::get();
        return view('admin.course.store',compact('courseType'));
    }

    /**
     * 修改用户
     * @param Request $request
     * @param type $mgId
     * @return type html/json
     */
    public function courseUpdate(Request $request, $id)
    {

        if( $request->isMethod('post') )
        {
            $data = $request->toArray();
            $this->couseValidator($data);

            //检查教师是否存在
            if( !$user = UserModel::where('nick_name',$data['nick_name'])->where('group_id',2)->first()){
                return response()->json(['errorCode' => 60008, 'message' => '查询不到老师信息，请重新输入昵称']);
            }

            $option=[];
            $option['course_time'] = $data['start_time'];
            $option['start_time']  = $data['start_time'].' '.$data['start_time_exp'].':00';
            $option['end_time']    = date('Y-m-d H:i:s',strtotime($option['start_time'])+3600);
            //检查课程是否冲突
            if( Course::check($option['start_time']) )
            {
                return response()->json(['errorCode' => 60007, 'message' => '课程时间冲突']);
            }

            $option['course_id'] = $data['course_id'];
            $option['user_id'] = $user->id;

            //过滤数组
            unset($data['_token']);
            $status = Course::where('id', $id)->update($option);

            if( $status )
            {
                return response()->json(array('errorCode' => 00000, 'message' => '操作成功', 'route' => route('course.list')));
            }

            return response()->json(array('errorCode' => 60002, 'message' => '操作失败'));
        }

        $course = Course::where('id', $id)->first();
        $courseType = CourseType::getList();

        return view('admin.course.update', compact('course','courseType'));
    }


    /**
     * 类型验证
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    private function couseValidator($data)
    {
        $rule = [
            "nick_name" =>  'required',
            "course_id"  =>  'required',
            "start_time"   =>  'required',
            "hour" =>  'required',
            "minute"=>'required',
            "meridian"=>'required',

        ];

        $validator = Validator::make($data,$rule);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $message = join(',',array_keys($errors->toArray())) . '不可为空';

            $response = [

                'errorCode'    =>  60003,

                'message'   =>  $message

            ];

            return response()->json($response);

        }
    }

    /**
     * 禁用/启用/删除 课程
     * @param Request $request
     * @param type $lottery
     * @return type json
     */
    public function courseLock(Request $request, $id, $state = 1)
    {
        if( !$id )
        {
            return response()->json(['errorCode' => 60003, 'message' => '参数不能为空']);
        }

        $status = Course::where('id', $id)->update(['status' => $state]);

        if( $status )
        {
            return response()->json(['errorCode' => 00000, 'message' => '操作成功']);
        }

        return response()->json(['errorCode' => 60002, 'message' => '操作失败']);
    }


    /**
     * 课程类型列表
     * @return [type] [description]
     */
    public function courseTypeList()
    {
       $courseTypes  =  CourseType::with('admin')->where('status',8)->paginate(10);

       return view('admin.course.type.list',compact('courseTypes'));
    }


    /**
     * 添加类型
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function typeStore(Request $request)
    {
        //执行操作
        if( $request->isMethod('post') )
        {
            $data = $request->toArray();

            $option=[];
            $option['full_name']   = $data['full_name'];
            $option['alias_name']  = $data['alias_name'];
            $option['admin_id']    = $request->user('admin')->id;
            //检查课程类型别名是否存在
            if( CourseType::checkByAlias($option['alias_name']) )
            {
                return response()->json(['errorCode' => 60007, 'message' => '课程别名已存在']);
            }

            $courseTypeId = CourseType::insertGetId($option);

            if( $courseTypeId )
            {
                return response()->json(array('errorCode' => 00000, 'message' => '创建成功', 'route' => route('course.type.list')));
            }

            return response()->json(array('errorCode' => 60002, 'message' => '创建失败'));
        }

        return view('admin.course.type.store');
    }


    /**
     * 修改类型
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function typeUpdate(Request $request,$id)
    {
        if( $request->isMethod('post') ) {
            $data = $request->toArray();

            $option=[];
            $option['full_name']   = $data['full_name'];
            $option['alias_name']  = $data['alias_name'];
            $option['admin_id']    = $request->user('admin')->id;
            //检查课程类型别名是否存在
            if( CourseType::checkByAlias($option['alias_name']) )
            {
                return response()->json(['errorCode' => 60007, 'message' => '课程别名已存在']);
            }

            $status = CourseType::where('id', $id)->update($option);

            if( $status )
            {
                return response()->json(array('errorCode' => 00000, 'message' => '操作成功', 'route' => route('course.type.list')));
            }

            return response()->json(array('errorCode' => 60002, 'message' => '操作失败'));
        }

        $courseType = CourseType::where('id', $id)->first();;

        return view('admin.course.type.update', compact('courseType'));
    }


    /**
     * 删除操作
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function typeDel(Request $request,$id)
    {
        $status = CourseType::where('id', $id)->update(['status'=>0]);
        if( $status )
        {
            return response()->json(array('errorCode' => 00000, 'message' => '操作成功', 'route' => route('course.type.list')));
        }

        return response()->json(array('errorCode' => 60002, 'message' => '操作失败'));
    }

}
