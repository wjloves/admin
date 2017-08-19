<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        //1.根据routes.php配置的路由重构权限
        //从路由集中取出所有的route路由项
        $routes = app('routes')->getRoutes();

        foreach ($routes as $route) {
            $methods = $route->methods();
            if(in_array('GET', $methods)){
                $method = 'GET';
            }elseif(in_array('POST', $methods)){
                $method = 'POST';
            }else{
                continue;
            }

            $uri = $route->uri();
            $uris = explode('/',$uri);
            if(isset($uris[0]) && 'admin'==$uris[0]){
                //$perm = \App\Model\Permission::findByUri($uri);
                $perm = \App\Models\Permission::findByMethodAndUri($method,$uri);

                if(!$perm){
                    $perm = \App\Models\Permission::create(['name'=>$uri,'desc'=>'desc','method'=>$method,'uri'=>$uri,
                        ]);

                }
            }
        }

        $perms = [];
        try{
            //2.获取用户的权限列表;
            $userId=  $request->user('admin')->id ? $request->user('admin')->id : Auth::guard('admin')->id;
            $user = \App\Models\User::find($userId);
            $perms = $user->getPerms($user->id);

        }catch(\Exception $e){
            return redirect()->guest('admin/login');
        }

        //3.检查用户是否具有访问权限
        $callback = $request->getRouteResolver();
        $router = $callback();//当前路由


        $auth = false;
        foreach ($perms as $perm) {
            if( in_array($perm->method, $router->methods()) && $perm->uri == $router->uri()){
                $auth = true;
                $current_perm = $perm;
                break;
            }
        }
        if(!$auth){
            abort(403,'对不起，您无权访问该页面！');
        }

        //4.根据菜单表 和 用户权限 构建 个人菜单;
        $top_menus = \App\Models\Menus::getTopMenus();
        $top_menus = $top_menus->toArray();


        $menus = array();
        $menus = array();
        foreach ($top_menus as $value) {
            $menus[$value['group']] = $value;
            foreach ($perms as $perm) {
                //子菜单的group属性为空
                if($perm->menu && $perm->menu->pid>0 && $perm->menu->pid == $value['id']){
                    $menus[$value['group']]['sub_menu'][] = ['name'=>$perm->menu['name'],'link'=>'/'.$perm->uri,'sort'=>$perm->menu['sort']];
                }
            }
        }

        foreach ($menus as $key => $value) {
            if(empty($menus[$key]['sub_menu'])){
                unset($menus[$key]);
            }else{
                $menus[$key]['sub_menu'] = multisort($menus[$key]['sub_menu'],'sort');
            }
        }

        //菜单列表,当前请求uri
        view()->share('menu_list', $menus);
        $path = $request->path();
        view()->share('request_path', '/'.$path);

        //当前组名,当前菜单名
        if($current_perm->menu){
            $menu_group = $current_perm->menu->parent_menu->group;

            $menu_group_name = admin_group_tag($menu_group);
            $menu_name = $current_perm->menu->name;

        }else{
            $menu_group = '';
            $menu_group_name = '';
            $menu_name = '';
        }
        $route = explode('/', $router->uri());
        if(count($route)>2){
            $current_route = $route[1];
        }else{
            $current_route = array_pop($route);
        }
        view()->share('current_route',$current_route);
        view()->share('menu_group', $menu_group);
        view()->share('menu_group_name', $menu_group_name);
        view()->share('menu_name', $menu_name);

        //当前权限菜单所在的组 决定 当前组, 而非根据当前请求;
        //这样role/list 可以放在user菜单组 而非role组;

        return $next($request);
    }
}
