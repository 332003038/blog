<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //1.关联的数据表
    public $table = 'role';

    //2.主键
    public $primaryKey = 'id';

    //3.允许批量操作的字段
    //不允许的为空，意思是都允许
    public $guarded = [];

    //4.是否维护crated_at和updated_at字段
    public $timestamps = false;


    //添加动态属性，关联权限模型
    public function permission()
    {
        //belongsToMany(被关联的模型，被关联的数据表，当前模型在关联表中的外键， 被关联模型在关联表中的主键 
        //return $this->belongsToMany(related:'App\Model\Permission',table:'role_permission',foreignPivoKey:'role_id',relatedPivoKey:'permission_id');
        return $this->belongsToMany('App\Model\Permission','role_permission','role_id','permission_id');

    }
}
