<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    //指定表名
    protected $table='users';
    //指定主键
    protected $primaryKey='id';
    //关闭时间戳
    public $timestamps=false;
}
