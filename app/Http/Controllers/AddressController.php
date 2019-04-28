<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CommonController;
use App\model\AreaModel;
use App\model\AddressModel;

class AddressController extends CommonController
{
    public function add()
    {
    	if(!$this->checkLogin()){
    		$this->abort('请先登录','/login');
    		return;
    	}
    	$addressInfo=$this->getAddressInfo();
    	$areaInfo=$this->getAreaInfo(0);
    	return view('address.add',compact('addressInfo','areaInfo'));
    }

    public function getAreaInfo($pid)
    {
    	$area_model=new AreaModel;
    	$where=[
    		'pid'=>$pid
    	];
    	$areaInfo=$area_model->where($where)->get();
    	if(!empty($areaInfo)){
    		$areaInfo=$areaInfo->toArray();
    		return $areaInfo;
    	}else{
    		return false;
    	}
    }

    public function getArea(Request $request)
    {
    	$id=$request->input('id');
    	if(!isset($id) || empty($id)){
    		$this->no('请至少选择一项');
    		return;
    	}
    	$areaInfo=$this->getAreaInfo($id);
    	if(!empty($areaInfo)){
    		echo json_encode($areaInfo);
    	}
    }

    public function addressDo(Request $request)
    {
    	$post=$request->input('_obj');
    	$address_model=new AddressModel;
    }
}
