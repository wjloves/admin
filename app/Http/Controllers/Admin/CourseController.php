<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
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
        $courses = Course::with('user')->with('userCourse')->with('courseType')->where('status',8)->get();
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

            if( Course::check($data['start_time']) )
            {
                return response()->json(['errorCode' => 60007, 'message' => '课程时间冲突']);
            }

            //过滤数组
            unset($data['_token']);
            $status = Course::create($data);

            if( $status )
            {
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

        $data = $request->toArray();

        if( $request->isMethod('post') )
        {
            $this->couseValidator($data);

            //过滤数组
            unset($data['_token']);
            $status = Course::where('id', $id)->update($data);

            if( $status )
            {
                return response()->json(array('errorCode' => 00000, 'message' => '操作成功', 'route' => route('course.list')));
            }

            return response()->json(array('errorCode' => 60002, 'message' => '操作失败'));
        }

        $user = Course::where('id', $id)->first();

        return view('admin.user.userupdate', compact('user'));
    }

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
     * 禁用/启用/删除 用户
     * @param Request $request
     * @param type $lottery
     * @return type json
     */
    public function userLock(Request $request, $id, $state = 1)
    {
        if( !$id )
        {
            return response()->json(['errorCode' => 60003, 'message' => '参数不能为空']);
        }

        $status = UserModel::where('id', $id)->update(['status' => $state]);

        if( $status )
        {
            return response()->json(['errorCode' => 00000, 'message' => '操作成功']);
        }

        return response()->json(['errorCode' => 60002, 'message' => '操作失败']);
    }

}
