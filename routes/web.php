<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    //后台登陆路由
    Route::get('login', 'LoginController@login');
    //验证码路由
    Route::get('code', 'LoginController@code');
    // 使用composer包下载的验证码路由
    // Route::get('/code/captcha/{tmp}', 'Admin\LoginController@captcha');
    //后台登陆表单提交路由
    Route::post('doLogin', 'LoginController@doLogin');
    //加密算法
    Route::get('jiami', 'LoginController@jiami');
});

Route::get('noaccess','Admin\LoginController@noaccess');

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['hasRole','isLogin']], function () {
    //后台首页路由
    Route::get('index', 'LoginController@index');
    //后台欢迎页路由
    Route::get('welcome', 'LoginController@welcome');
    //后台退出登陆路由
    Route::get('logout', 'LoginController@logout');

    //批量删除用户路由
    Route::get('user/del', 'UserController@delAll');
    //后台用户模块相关路由
    Route::resource('user', 'UserController');

    //用户授于角色路由
    Route::get('user/auth/{id}', 'UserController@auth');
   //处理用户授于角色路由
    Route::post('user/doauth', 'UserController@doAuth'); 

    //角色授权路由
    Route::get('role/auth/{id}', 'RoleController@auth');
    //处理角色授权路由
    Route::post('role/doauth', 'RoleController@doAuth');
    //后台角色模块相关路由
    Route::resource('role', 'RoleController');

    //后台权限模块相关路由
    Route::resource('permission', 'PermissionController');
});
