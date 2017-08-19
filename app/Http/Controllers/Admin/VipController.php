<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\UserWechat as UserModel;
use Validator;
use App\Models\UserGroups;
use App\Models\Vip;

/**
 *  用户管理
 *  @auther logen
 */
class VipController extends BaseController
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
     * Vip列表
     *
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {

        $vips = Vip::paginate(10);;

        return view('admin.vip.list',compact('vips'));
    }

    /**
     *  创建用户
     * @param Request $request
     * @return type html/json
     */
    public function vipStore(Request $request)
    {
        //执行操作
        if( $request->isMethod('post') )
        {
            $data = $request->toArray();

            //过滤数组
            unset($data['_token']);
            $status = Vip::create($data);

            if( $status )
            {
                return response()->json(array('errorCode' => 00000, 'message' => '创建成功', 'route' => route('vip.list')));
            }

            return response()->json(array('errorCode' => 60002, 'message' => '创建失败'));
        }

        return view('admin.vip.store');
    }

    /**
     * 修改用户
     * @param Request $request
     * @param type $mgId
     * @return type html/json
     */
    public function vipUpdate(Request $request, $id)
    {

        $data = $request->toArray();

        if( $request->isMethod('post') )
        {
            //过滤数组
            unset($data['_token']);
            $status = Vip::where('id', $id)->update($data);

            if( $status )
            {
                return response()->json(array('errorCode' => 00000, 'message' => '操作成功', 'route' => route('vip.list')));
            }

            return response()->json(array('errorCode' => 60002, 'message' => '操作失败'));
        }

        $vip = Vip::where('id', $id)->first();

        return view('admin.vip.update', compact('vip'));
    }

    /**
     * 禁用/启用/删除 用户
     * @param Request $request
     * @param type $lottery
     * @return type json
     */
    public function vipDel(Request $request, $id)
    {
        if( !$id )
        {
            return response()->json(['errorCode' => 60003, 'message' => '参数不能为空']);
        }

        $status = Vip::destroy($id);

        if( $status )
        {
            return response()->json(['errorCode' => 00000, 'message' => '操作成功']);
        }

        return response()->json(['errorCode' => 60002, 'message' => '操作失败']);
    }

}
