<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    //
    protected $table='shop_category';
    protected $primaryKey='cate_id';
    public $timestamps=false;
}
