<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\UserWechat as UserModel;
use App\Models\Message;
use Illuminate\Support\Facades\Redis;
use App\Models\Config;

/**
 *  消息管理
 *  @auther logen
 */
class ConfigController extends BaseController
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
     * 消息列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $configs = Config::getConfig();

        return view('admin.config.list',compact('configs'));
    }

    /**
     * @param Request $request
     * @return type html/json
     */
    public function configStore(Request $request)
    {
        //执行操作
        if( $request->isMethod('post') )
        {
            $data = $request->toArray();

            if(!$data['alias_name'])
            {
                return response()->json(['errorCode' => 60008, 'message' => '别名不能为空']);
            }

            if(Config::where('alias_name', $data['alias_name'])->first() )
            {
                return response()->json(['errorCode' => 60007, 'message' => '关键字已存在']);
            }


            //过滤数组
            unset($data['_token']);
            unset($data['_wysihtml5_mode']);
            $data['admin_id'] = $request->user('admin')->id;
            $data['content']  = $data['content'];
            $status = Config::create($data);

            if( $status )
            {
                Redis::hset('config',$data['alias_name'],json_encode(['alias_name'=>$data['alias_name'],'content'=>$data['content']]));
                return response()->json(array('errorCode' => 00000, 'message' => '创建成功', 'route' => route('config.list')));
            }

            return response()->json(array('errorCode' => 60002, 'message' => '创建失败'));
        }

        return view('admin.config.store');
    }

    /**
     * @param Request $request
     * @param type $id
     * @return type html/json
     */
    public function configUpdate(Request $request, $id)
    {

        $data = $request->toArray();

        if( $request->isMethod('post') )
        {
            $option = [
                'alias_name'=>$data['alias_name'],
                'title'=>$data['title'],
                'description'=>$data['description'],
                'content'   => $data['content'],
                'modifier_id'=>  $request->user('admin')->id
            ];

            $status = Config::where('id', $id)->update($option);

            if( $status )
            {
                Redis::hset('config',$option['alias_name'],json_encode(['alias_name'=>$option['alias_name'],'content'=>$option['content']]));
                return response()->json(array('errorCode' => 00000, 'message' => '操作成功', 'route' => route('config.list')));
            }

            return response()->json(array('errorCode' => 60002, 'message' => '操作失败'));
        }
        $config = Config::where('id', $id)->first();

        return view('admin.config.update', compact('config'));
    }

    /**
     * 禁用/启用/删除
     * @param Request $request
     * @param type $lottery
     * @return type json
     */
    public function configLock(Request $request, $id, $status=8)
    {
        if( !$id )
        {
            return response()->json(['errorCode' => 60003, 'message' => '参数不能为空']);
        }

        $status = Config::where('id',$id)->update(['status'=>$status]);

        $message = Config::find($id);
        if( $status )
        {
            Redis::hdel('autoReply',$message->keywords);
            return response()->json(['errorCode' => 00000, 'message' => '操作成功']);
        }

        return response()->json(['errorCode' => 60002, 'message' => '操作失败']);
    }

}
