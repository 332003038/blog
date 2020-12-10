<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Permission;
use App\Model\Role;

class RoleController extends Controller
{

    //获取授权页面
    public function auth($id)
    {
        //获取当前角色
        $role = Role::find($id);
        //获取所有的权限列表
        $perms = Permission::get();

        //获取当前角色拥有的权限
        $own_perms = $role->Permission;
        //dd($own_perms);
        //获取当前角色拥有的权限的id
        $own_pers = [];
        foreach ($own_perms as $v) {
            $own_pers[] = $v->id;
        }
        //返回视图，将当前角色，所有的权限列表，当前角色拥有的权限id传给模板
        return view('admin.role.auth', compact('role', 'perms', 'own_pers'));
    }

    //处理授权方法
    public function doAuth(Request $request)
    {
        //1.获取到表单提交过来的角色id，角色名称，拥有的权限id数组
        $input = $request->except('_token');
        //dd($input);

        //2.删除当前角色已有的权限
        //从中间表中role_permission取出字段为role_id(数据表中角色id)对应的输入的input['role_id'](输入的角色id)删掉
        \DB::table('role_permission')->where('role_id', $input['role_id'])->delete();

        //3.添加新授予的权限到中间表role_permission
        if (!empty($input['permission_id'])) {
            //遍历角色拥有的权限id数组
            foreach ($input['permission_id'] as $v) {
                //每次获取到的权限id：$v，向中间表role_permission插入数据
                //role_id字段对应的是输入的$input['role_id']，permission_id字段对应的是循环出来的$v
                \DB::table('role_permission')->insert(['role_id' => $input['role_id'], 'permission_id' => $v]);
            }
        }
        return redirect('admin/role');
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //1.获取所有的角色数据
        $role = Role::get();
        //2.返回角色视图，传递数据
        return view('admin.role.list', compact('role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.role.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //1.获取表单传过来的值，除了token
        $input = $request->except('_token');
        //dd($input);
        //2.进行表单验证

        //3.将数据添加到role表中
        $res = Role::create($input);
        //4.判断添加结果，成功了返回列表页，失败了返回上一页
        if ($res) {
            return redirect('admin/role')->with('msg', '添加角色成功');
        } else {
            return back()->with('msg', '添加角色失败，请稍后重试');
        }
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
        //
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
        //
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
