<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\Menus;
use App\Models\User;
use App\Models\Permission;

class MenuController extends BaseController
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

    public function getList(){
        $menus = Menus::getTopMenus();

        return view('admin.menu.list', compact('menus'));
    }


    /**
     * 创建
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function menuStore(Request $request){
        if( $request->isMethod('post') ){
            $data = $request->toArray();
            $option = [
                'name'=>$data['name'],
                'sort'=>$data['sort'],
                'pid' =>$data['pid']
            ];
            if(isset($data['group'])){
                $option['group'] = $data['group'];
            }
            if(isset($data['uri'])){
                $option['perm_id'] = Permission::insertGetId(['name'=>$data['uri'],'desc'=>$data['name'],'method'=>'GET','uri'=>$data['uri']]);
            }
            $status = Menus::create($option);
            if( $status )
            {
                return response()->json(array('errorCode' => 00000, 'message' => '操作成功', 'route' => route('menu.list')));
            }

            return response()->json(array('errorCode' => 60002, 'message' => '操作失败'));
        }

        $user = User::find($request->user('admin')->id);

        $perms = $user->getMenuPerms();

        $top_menus = Menus::getTopMenus();

        return view('admin.menu.store', compact('top_menus','perms'));
    }


    /**
     * 更新
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function menuUpdate(Request $request,$id){
         if( $request->isMethod('post') ){
                $option = [
                    'name'=>$data['name'],
                    'sort'=>$data['sort'],
                    'pid' =>$data['pid']
                ];
                if(isset($data['group'])){
                    $option['group'] = $data['group'];
                }
                if(isset($data['uri'])){
                    $option['perm_id'] = Permission::insertGetId(['name'=>$data['uri'],'desc'=>$data['name'],'method'=>'GET','uri'=>$data['uri']]);
                }
                Menus::where('id',$id)->update($option);
                $response = [
                    'status'    =>  00000,
                    'message'   =>  'OK'
                ];
            return response()->json($response);
        }

        $menu = Menus::with('perm')->find($id);
        $user = User::find($request->user('admin')->id);
        $perms = $user->getMenuPerms();

        $top_menus = Menus::getTopMenus();
        return view('admin.menu.update', compact('menu','top_menus','perms'));
    }

    /**
     * 删除
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postDel(Request $request,$id){
        try{
            $menu = Menus::find($id);
            foreach($menu->sub_menus as $smenu){
                $smenu->delete();
            }
            $menu->delete();
        }
        catch(\Exception $e){
            return response()->json(['status'   =>  60001]);
        }
        return response()->json(['status'   =>  00000]);
    }
}