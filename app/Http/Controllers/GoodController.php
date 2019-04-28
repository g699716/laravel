<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CommonController;
use App\model\GoodsModel;
use App\model\CartModel;
use Illuminate\Support\Facades\Cache;

class GoodController extends CommonController
{
	//商品列表页
    public function goodList()
    {
    	$query=request()->all();
    	$where=[
    		['goods_up','=',1]
    	];
    	$goods_name=$query['goods_name']??'';
    	$goods_desc=$query['goods_desc']??'';
    	$page=$query['page']??1;
    	if(!empty($goods_name) && isset($goods_desc)){
    		$where[]=['goods_name','like',"%{$goods_name}%"];
    	}
    	if(!empty($goods_desc) && isset($goods_desc)){
    		$where[]=['goods_desc','like',"%{$goods_desc}%"];
    	}
    	$goods_model=new GoodsModel;
    	$tiao=config('app.pageSize');
    	$goodsInfo=$goods_model->where($where)->orderBy('goods_time','desc')->paginate($tiao);
    	return view('good.goodlist',compact('goodsInfo','goods_name','goods_desc','query'));
    }

    public function goodDetail()
    {
    	$goods_id=request()->input('goods_id');
    	if(empty($goods_id) || !isset($goods_id)){
    		$this->abort('请选择一件商品',"goodList");
    		return;
    	}
    	$goodsInfo=cache('goodsInfo'.$goods_id);
    	if(empty($goodsInfo)){
    		$goods_model=new GoodsModel;
    		$where=[
    			'goods_id'=>$goods_id,
    			'goods_up'=>1,
    		];
    		$info=$goods_model->where($where)->first();
    		if(empty($info)){
    			$this->abort('商品数据异常','goodList');
    			return;
    		}
    		$info=$info->toArray();
    		$goodsInfo=cache(['goodsInfo'.$goods_id=>$info],60*24);
    	}
    	return view('good.goodDetail',compact('goodsInfo'));
    }

    public function edit()
    {
    	$goods_id=request()->input('goods_id');
    	if(!isset($goods_id) || empty($goods_id)){
    		$this->abort('请选择一件商品','goodList');
    		return;
    	}
    	$goodsInfo=cache('goodsInfo'.$goods_id);
    	if(empty($goodsInfo)){
    		$goods_model=new GoodsModel;
    		$where=[
    			'goods_id'=>$goods_id,
    			'goods_up'=>1,
    		];
    		$info=$goods_model->where($where)->first();
    		if(empty($info)){
    			$this->abort('商品数据异常','goodList');
    			return;
    		}
    		$info=$info->toArray();
    		$goodsInfo=cache(['goodsInfo'.$goods_id=>$info],60*24);
    	}
    	return view('good.edit',compact('goodsInfo'));
    }

    public function update()
    {
    	$post=request()->except('_token');
    	$goods_model=new GoodsModel;
    	if(isset($post['goods_img']) && !empty($post['goods_img'])){
    		$post['goods_img']=$this->upload($post['goods_img']);
    	}
    	$res=$goods_model->where('goods_id',$post['goods_id'])->update($post);
    	$info=$goods_model->where('goods_id',$post['goods_id'])->first()->toArray();
    	$goodsInfo=cache(['goodsInfo'.$post['goods_id']=>$info],60*24);
    	$this->abort('修改成功','goodList');
    }

    public function upload($img)
    {
    	if($img->isValid()){
    		$extension=$img->extension();
    		$store_result=$img->store('goods/'.date('Ymd'));
    		return $store_result;
    	}else{
    		exit('未获取到上传文件或上传过程出错');
    	}
    }

    public function del()
    {
    	$goods_id=request()->input('goods_id');
    	if(!isset($goods_id) || empty($goods_id)){
    		$this->no('请选择一件商品');
    	}
    	$goods_model=new GoodsModel;
    	$res=$goods_model->where('goods_id',$goods_id)->delete();
    	if($res){
    		Cache::forget('goodsInfo'.$goods_id);
    		$this->ok('删除成功');
    	}else{
    		$this->no('删除失败');
    	}
    }
}
