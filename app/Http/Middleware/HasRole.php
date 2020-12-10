<?php

namespace App\Http\Middleware;

use App\Model\User;
use App\Model\Role;
use App\Model\Permission;
use Closure;
use Illuminate\Support\Facades\Route;

class HasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //1.获取当前请求的路由，对应的控制器方法名
        //"App\Http\Controllers\Admin\LoginController@index"
        $route = Route::current()->getActionName();
        //dd($route);

        //2.获取当前用户的权限组
        //(1)根据session中用户的id，查询出对应的用户信息
        $user = User::find(session()->get('user')->user_id);
        //(2)通过用户模型关联到角色模型，获取用户对应的所有角色
        $roles = $user->role;
        //dd($roles);
        //(3)循环用户对应的所有角色，获取所有角色对应的所有权限
        //存放权限对应的per_url字段值,就是权限列表
        $arr = [];
        foreach ($roles as $v) {
            //每次循环出一个角色，再从角色模型关联到权限模型，获取每个角色对应的所有权限
            $perms = $v->Permission;
            //循环每个角色对应的权限，
            foreach ($perms as $perm) {
                //(4)将循环出来的所有权限对应的per_url，放进$arr数组中，就是用户拥有的所有权限
                $arr[] = $perm->per_url;
            }
        }
        //dd($arr);
        //(5)去掉用户拥有的所有角色的所有权限重复的部分
        $arr = array_unique($arr);

        //3.判断当前请求的路由对应的控制器方法名$route是否在当前用户拥有的所有权限中(也就是$arry中)
        if (in_array($route, $arr)) {
            return $next($request);
        } else {
            return redirect('noaccess');
        }
    }
}
