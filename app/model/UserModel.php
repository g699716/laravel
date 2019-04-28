<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    //
    protected $table='user';
    protected $primaryKey='u_id';
    public $timestamps=false;
}
