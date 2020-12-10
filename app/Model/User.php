<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //1.关联的数据表
    public $table = 'user';

    //2.主键
    public $primaryKey = 'user_id';

    //3.允许批量操作的字段
    //public $fillable = ['user_id','user_pass','email','phone'];
    //不允许的为空，意思是都允许
    public $guarded = [];

    //4.是否维护crated_at和updated_at字段
    public $timestamps = false;

    public function role()
    {
        //belongsToMany(被关联的模型，中间表名，当前模型在中间表中的主键， 被关联模型在中间表中的主键）
        return $this->belongsToMany('App\Model\Role','user_role','user_id','role_id');
    }
}
