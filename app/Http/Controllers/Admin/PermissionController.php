<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //1.从权限模型中获取所有数据
        $permission = Permission::get();
        //2.将获取的所有权限数据，返回给权限列表模板
        return view('admin.permission.list',compact('permission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //1.返回添加权限页
        return view('admin.permission.add');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //1.获取表单提交过来的数据
        $input = $request->all();

        //2.进行表单验证

        //3.向数据库中插入数据
        $res = Permission::create($input);
        //4.判断插入是否成功
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //根据修改路由传递过来的权限id，查出对应的信息
        $permission = Permission::find($id);
        //返回一个修改页面，将查出来的权限id对应的数据传递给模板
        return view('admin.permission.edit',compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //1.根据id获取要修改的数据
        $permission = Permission::find($id);
        //2.获取要修改成的数据
        $input = $request->all();
        //3.执行修改操作
        $permission->per_name = $input['per_name'];
        $permission->per_url = $input['per_url'];

        //4.保存数据
        $res = $permission->save();
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
