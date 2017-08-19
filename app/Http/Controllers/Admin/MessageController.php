<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\UserWechat as UserModel;
use App\Models\Message;
use Illuminate\Support\Facades\Redis;

/**
 *  消息管理
 *  @auther logen
 */
class MessageController extends BaseController
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

        $messages = Message::with('admin')->where('status','!=',0)->paginate(10);;

        return view('admin.message.list',compact('messages'));
    }

    /**
     *  消息用户
     * @param Request $request
     * @return type html/json
     */
    public function messageStore(Request $request)
    {
        //执行操作
        if( $request->isMethod('post') )
        {
            $data = $request->toArray();

            if(!$data['keywords'])
            {
                return response()->json(['errorCode' => 60008, 'message' => '关键字不能为空']);
            }

            if(Message::where('keywords', $data['keywords'])->first() )
            {
                return response()->json(['errorCode' => 60007, 'message' => '关键字不能为空']);
            }


            //过滤数组
            unset($data['_token']);
            $data['admin_id'] = $request->user('admin')->id;
            $status = Message::create($data);

            if( $status )
            {
                Redis::hset('autoReply',$data['keywords'],json_encode(['keywords'=>$data['keywords'],'reply'=>$data['reply']]));
                return response()->json(array('errorCode' => 00000, 'message' => '创建成功', 'route' => route('message.list')));
            }

            return response()->json(array('errorCode' => 60002, 'message' => '创建失败'));
        }

        return view('admin.message.store');
    }

    /**
     * 修改消息
     * @param Request $request
     * @param type $mgId
     * @return type html/json
     */
    public function messageUpdate(Request $request, $id)
    {

        $data = $request->toArray();

        if( $request->isMethod('post') )
        {
            //过滤数组
            unset($data['_token']);
            unset($data['_wysihtml5_mode']);
            $option = [
                'keywords'=>$data['keywords'],
                'reply'   =>$data['reply']
            ];
            $status = Message::where('id', $id)->update($option);

            if( $status )
            {
                Redis::hset('autoReply',$data['keywords'],json_encode(['keywords'=>$data['keywords'],'reply'=>$data['reply']]));
                return response()->json(array('errorCode' => 00000, 'message' => '操作成功', 'route' => route('message.list')));
            }

            return response()->json(array('errorCode' => 60002, 'message' => '操作失败'));
        }

        $message = Message::where('id', $id)->first();

        return view('admin.message.update', compact('message'));
    }

    /**
     * 禁用/启用/删除
     * @param Request $request
     * @param type $lottery
     * @return type json
     */
    public function messageLock(Request $request, $id, $status=8)
    {
        if( !$id )
        {
            return response()->json(['errorCode' => 60003, 'message' => '参数不能为空']);
        }

        $status = Message::where('id',$id)->update(['status'=>$status]);

        $message = Message::find($id);
        if( $status )
        {
            Redis::hdel('autoReply',$message->keywords);
            return response()->json(['errorCode' => 00000, 'message' => '操作成功']);
        }

        return response()->json(['errorCode' => 60002, 'message' => '操作失败']);
    }

}
