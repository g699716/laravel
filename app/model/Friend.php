<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    //指定表名
    protected $table='friend';
    //指定主键
    protected $primaryKey='f_id';
    //关闭时间戳自动写入
    public $timestamps=false;
}
