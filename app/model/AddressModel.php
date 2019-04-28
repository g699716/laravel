<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class AddressModel extends Model
{
    //
    protected $table='shop_address';
    protected $primaryKey='address_id';
    public $timestamps=false;
}
