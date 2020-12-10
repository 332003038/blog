<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Role;
use App\Model\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    //获取用户拥有的角色列表
    public function auth($id)
    {
        //1.根据用户id查询出的所有数据
        $user = User::find($id);
        //2.从角色表中，查询出所有角色列表
        $roles = Role::get();
        //3.根据用户id，从用户角色中间表中查找出用户有哪些角色
        $user_roles =$user->Role;
        //dd($user_roles);
        //4.遍历用户拥有的所有角色，获取拥有的角色id，放进arry()数组
        $user_role =[];
        foreach ($user_roles as $v) {
            //将循环出来的角色id($V->id)放入数组的值中
            $user_role[] = $v->id;
        }

        //返回用户授于角色列页面
        return view('admin.user.auth',compact('user','roles','user_role'));
    }

    //执行用户授于角色方法
    public function doAuth(Request $request)
    {
        //1.从表单获取到用户名称、用户id、角色id数组   
        $input = $request->except('_token');
        //dd($input);
        //2.删除用户已经拥有的角色
        //从中间表user_role中user_id字段中查询出表单提交过来的$input['user_id']，进行删除
        DB::table('user_role')->where('user_id',$input['user_id'])->delete();
        //3.循环表单提交过来的角色id数组，每次循环出一个角色id，则向中间表user_role中插入一条用户id对应的角色id
        if (!empty($input['role_id'])) {
            foreach($input['role_id'] as $v){
                DB::table('user_role')->insert(['user_id' => $input['user_id'],'role_id' => $v]);           
            }
        }
        return redirect('admin/user');
    }

    /**
     * Display a listing of the resource.
     *
     * 获取用户列表
     */
    public function index(Request $request)
    {
        //1.获取搜索框get提交的请求参数
/*         $input = $request->all();
        dd($input); */
        $user =User::orderBy('user_id','asc')
        ->where(function($query) use($request){
            $username = $request->input('username');
            $email = $request->input('email');
            if (!empty($username)) {
                $query->where('user_name','like','%'.$username.'%');
            }
            if (!empty($email)) {
                $query->where('email','like','%'.$email.'%');
            }
        })
        ->paginate($request->input('num')?$request->input('num'):3);

        //$user = User::paginate(3);
        return view('admin.user.list',compact('user','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     *返回用户添加页面
     */
    public function create()
    {
        return view('admin.user.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return 执行用户添加操作
     */
    public function store(Request $request)
    {
        //return 111111;
        //1.接收前台表单提交的数据:email、pass、repass
        $input = $request->all();
        //2.进行表单验证

        //3.添加到数据库的user表
        $username = $input['email'];
        $pass = Crypt::encrypt($input['pass']);
        $res = User::create(['user_name'=>$username,'user_pass'=>$pass,'email'=>$input['email']]);
        //4.根据添加是否成功，给客户端返回一个json格式的反馈
        if ($res) {
            $data = [
                'status'=>0,
                'message'=>'添加成功',
            ];
        }else {
            $data = [
                'status'=>1,
                'message'=>'添加失败',
            ];
        }
        //json_encode($data);
        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return 显示一条用户信息
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return 返回一个用户修改页面
     */
    public function edit($id)
    {
        //根据修改路由传递过来的用户id，查出对应的信息
        $user = User::find($id);
        //返回一个修改页面，将查出来的用户id对应的数据传递给模板
        return view('admin.user.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return 执行一个用户修改操作
     */
    public function update(Request $request, $id)
    {
        //1.根据id获取要修改的数据
        $user = User::find($id);
        //2.获取要修改成的用户名
        $username = $request->input('user_name');
        //3.执行修改操作
        $user->user_name = $username;
        //4.保存数据
        $res = $user->save();
        //5.根据保存结果，向客服端发送json数据
        if ($res) {
            $data = [
                'status'=>0,
                'message'=>'修改成功'
            ];
        }else{
            $data = [
                'status'=>1,
                'message'=>'修改失败'
            ];
        }
        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return 执行一个用户删除操作
     */
    public function destroy($id)
    {
        //根据删除路由传过来的用户id，查出对应的数据
        $user = User::find($id);
        //执行删除操作
        $res = $user->delete();
        //根据删除操作结果，给客户端返回json格式的数据
        if ($res) {
            $data = [
                'status'=>0,
                'message'=>'删除成功'
            ];
        }else{
            $data = [
                'status'=>1,
                'message'=>'删除失败'
            ];
        }
        return $data;        

    }

    //批量删除所有选中用户
    public function delAll(Request $request)
    {
        //获取到所有选中用户的ids
        $input = $request->input('ids');
        //执行删除操作
        $res = User::destroy($input);
        //根据删除操作结果，给客户端返回json格式的数据
        if ($res) {
            $data = [
                'status'=>0,
                'message'=>'删除成功'
            ];
        }else{
            $data = [
                'status'=>1,
                'message'=>'删除失败'
            ];
        }
        return $data;  
    }
}
