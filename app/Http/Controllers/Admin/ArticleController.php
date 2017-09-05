<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\UserWechat as UserModel;
use App\Models\Article;
use Illuminate\Support\Facades\Redis;

/**
 *  文章管理
 *  @auther logen
 */
class ArticleController extends BaseController
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

        $articles = Article::with('cadmin')->with('uadmin')->where('status','!=',0)->paginate(10);;

        return view('admin.article.list',compact('articles'));
    }

    /**
     *  消息用户
     * @param Request $request
     * @return type html/json
     */
    public function articleStore(Request $request)
    {
        //执行操作
        if( $request->isMethod('post') )
        {
            $data = $request->toArray();

            if(!$data['title'])
            {
                return response()->json(['errorCode' => 60008, 'message' => '标题不能为空']);
            }

            if(Article::where('title', $data['title'])->first() )
            {
                return response()->json(['errorCode' => 60007, 'message' => '标题已存在']);
            }

            //过滤数组
            $option = [
                'title'=>$data['title'],
                'thumb_img_url'   =>$data['thumb_img_url'],
                'content'   =>$data['content'],
                'tags'   =>$data['tags'],
                'creater_id'=>$request->user('admin')->id
            ];
            $status = Article::create($option);

            if( $status )
            {
                return response()->json(array('errorCode' => 00000, 'message' => '创建成功', 'route' => route('article.list')));
            }

            return response()->json(array('errorCode' => 60002, 'message' => '创建失败'));
        }

        return view('admin.article.store');
    }

    /**
     * 修改消息
     * @param Request $request
     * @param type $mgId
     * @return type html/json
     */
    public function articleUpdate(Request $request, $id)
    {

        $data = $request->toArray();

        if( $request->isMethod('post') )
        {
            //过滤数组
            $option = [
                'title'=>$data['title'],
                'thumb_img_url'   =>$data['thumb_img_url'],
                'content'   =>$data['content'],
                'tags'   =>$data['tags'],
                'updater_id'=>$request->user('admin')->id
            ];
            $status = Article::where('id', $id)->update($option);

            if( $status )
            {
                return response()->json(array('errorCode' => 00000, 'message' => '操作成功', 'route' => route('article.list')));
            }

            return response()->json(array('errorCode' => 60002, 'message' => '操作失败'));
        }

        $article = Article::where('id', $id)->first();

        return view('admin.article.update', compact('article'));
    }

    /**
     * 禁用/启用/删除
     * @param Request $request
     * @param type $lottery
     * @return type json
     */
    public function articleLock(Request $request, $id, $status=8)
    {
        if( !$id )
        {
            return response()->json(['errorCode' => 60003, 'message' => '参数不能为空']);
        }

        $status = Article::where('id',$id)->update(['status'=>$status]);

        if( $status )
        {
            return response()->json(['errorCode' => 00000, 'message' => '操作成功']);
        }

        return response()->json(['errorCode' => 60002, 'message' => '操作失败']);
    }


    /**
     * 上传
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function articleUpload(Request $request)
    {

        $file = $request->file('Filedata');
        if($file->isValid()){
            $dir = 'uploads/images/';
            $filename = time() . mt_rand(100000, 999999) . '.' . $file ->getClientOriginalExtension();
            $file->move($dir, $filename);
            $path = $dir . $filename;
            return response()->json(['errorCode' => 0, 'message' => url($path)]);
        }

        return response()->json(['errorCode' => 60002, 'message' => '操作失败']);
    }

}
