<?php

namespace App\Http\Middleware;

use Closure;

class IsLogin
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
        //如果session中有用户信息，则继续进行下一步请求
        if (session()->get('user')) {
            return $next($request);
        //否则session中没有用户信息，则重定向到登陆页面，给错误提示信息
        }else{
            return redirect('admin/login')->with('errors','请先登陆用户');
        }

    }
}
