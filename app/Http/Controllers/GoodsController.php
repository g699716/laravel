<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CommonController;
use App\model\GoodsModel;
use App\model\CategoryModel;

class GoodsController extends CommonController
{
    //
    public function goodsList()
    {
    	$goods_model=new GoodsModel;
    	$tiao=config('app.pageSize');
    	$query=request()->all();
    	$goodsInfo=$goods_model->where('goods_up',1)->orderBy('goods_hot','desc')->paginate($tiao);
    	// $goodsInfo=$goods_model->where('goods_up',1)->orderBy('goods_hot','desc')->get()->toArray();
    	return view('goods/goods_list',compact('goodsInfo','query'));
    }

    public function getGoodsInfo(Request $request)
    {
    	$goods_model=new GoodsModel;
    	$tiao=config('app.pageSize');
    	$query=$request->all();
    	$p=$request->input('p');
    	$order_field=$request->input('order_field');
    	$order_type=$request->input('order_type');
    	$where=[
    		['goods_up','=',1]
    	];
    	$goodsInfo=$goods_model->where($where)->orderBy($order_field,$order_type)->paginate($tiao);
    	echo view('goods/goods_info',compact('goodsInfo','query'));
    }

    public function goodsDetail(Request $request)
    {
    	$goods_model=new GoodsModel;
    	$goods_id=$request->input('goods_id');
    	$where=[
    		['goods_up','=',1],
    		['goods_id','=',$goods_id],
    	];
    	$goodsInfo=$goods_model->where($where)->first()->toArray();
    	return view('goods/goods_detail',['goodsInfo'=>$goodsInfo]);
    }
}
