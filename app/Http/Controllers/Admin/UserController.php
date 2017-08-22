<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\UserWechat as UserModel;
use Auth;
use Validator;
use Illuminate\Support\Str;
use App\Models\UserGroups;
use App\Models\Vip;

/**
 *  用户管理
 *  @auther logen
 */
class UserController extends BaseController
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
     * 用户列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $users = UserModel::with('userGroup')->with('vip')->where('status','!=',0)->paginate(10);;

        return view('admin.user.userslist',compact('users'));
    }

    /**
     *  创建用户
     * @param Request $request
     * @return type html/json
     */
    public function usersStore(Request $request)
    {
        //执行操作
        if( $request->isMethod('post') )
        {
            $data = $request->toArray();
            if(!$data['username'])
            {
                return response()->json(['errorCode' => 60008, 'message' => '用户名不能为空']);
            }

            if(UserModel::where('username', $data['username'])->first() )
            {
                return response()->json(['errorCode' => 60007, 'message' => '用户名不能重复']);
            }


            //过滤数组
            unset($data['_token']);
            $status = UserModel::create($data);

            if( $status )
            {
                return response()->json(array('errorCode' => 00000, 'message' => '创建成功', 'route' => route('user.list')));
            }

            return response()->json(array('errorCode' => 60002, 'message' => '创建失败'));
        }
        $vipType =  Vip::get();
        $userGroups = UserGroups::get();
        return view('admin.user.userstore',compact('vipType','userGroups'));
    }

    /**
     * 修改用户
     * @param Request $request
     * @param type $mgId
     * @return type html/json
     */
    public function userUpdate(Request $request, $id)
    {

        $data = $request->toArray();

        if( $request->isMethod('post') )
        {
            //过滤数组
            unset($data['_token']);
            $status = UserModel::where('id', $id)->update($data);

            if( $status )
            {
                return response()->json(array('errorCode' => 00000, 'message' => '操作成功', 'route' => route('user.list')));
            }

            return response()->json(array('errorCode' => 60002, 'message' => '操作失败'));
        }

        $user = UserModel::where('id', $id)->first();
        $vipType =  Vip::get();
        $userGroups = UserGroups::get();
        return view('admin.user.userupdate', compact('user','vipType','userGroups'));
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
