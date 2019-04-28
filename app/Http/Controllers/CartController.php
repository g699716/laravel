<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\CartModel;
use App\Model\GoodsModel;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class CartController extends CommonController
{
    //
    public function cartList()
    {
    	if($this->checkLogin()){
    		$cartInfo=$this->getCartInfoDb();
    	}else{
    		$cartInfo=$this->getCartInfoCookie();
    	}
    	$count=count($cartInfo);
    	return view('cart/cart_list',compact('cartInfo','count'));
    }

    public function getCartInfoDb()
    {
    	$u_id=$this->getUserId();
    	$where=[
    		'u_id'=>$u_id,
    		'goods_up'=>1,
    		'is_del'=>1,
    	];
    	$cart_model=new CartModel;
    	$cartInfo=$cart_model->join('shop_Goods','shop_cart.goods_id','=','shop_goods.goods_id')->get()->toArray();
    	if(!empty($cartInfo)){
    		return $cartInfo;
    	}else{
    		return false;
    	}
    }

    public function getCartInfoCookie()
    {
    	$str=request()->cookie('cartInfo');
    	$goods_model=new GoodsModel;
    	if(!empty($str)){
    		$cartInfo=$this->getBase64Info($str);
    		$goods_id=[];
    		$cartInfo=array_reverse($cartInfo);
    		foreach ($cartInfo as $k => $v) {
    			$goods_id[]=$v['goods_id'];
    		}
    		$info=$goods_model->whereIn('goods_id',$goods_id)->orderByRaw("FIELD(goods_id,".implode(',',$goods_id).")")->get()->toArray();
    		foreach ($info as $k => $v) {
    			foreach ($cartInfo as $key => $value) {
    				if($v['goods_id']==$value['goods_id']){
    					$info[$k]['buy_num']=$value['buy_num'];
    				}
    			}
    		}
    		if(!empty($info)){
    			return $info;
    		}else{
    			return false;
    		}
    	}else{
    		return false;
    	}
    }

    public function countTotal(Request $request)
    {
    	$goods_id=$request->input('goods_id');
    	if($this->checkLogin()){
    		$this->getCountTotalDb($goods_id);
    	}else{
    		$this->getCountTotalCookie($goods_id);
    	}
    }

    public function getCountTotalDb($goods_id)
    {
    	$goods_id=explode(',',$goods_id);
    	// dd($goods_id);
    	$where=[
    		['shop_cart.u_id','=',$this->getUserId()],
    		['goods_up','=',1],
    		['is_del','=',1],
    	];
    	$cart_model=new CartModel;
    	// $info=$cart_model->join('shop_goods','shop_cart.goods_id','=','shop_cart.goods_id')->whereIn('shop_cart.goods_id',$goods_id)->get()->toArray();
    	$info=$cart_model
    		->join('shop_goods','shop_cart.goods_id','=','shop_goods.goods_id')
    		->where($where)
    		->whereIn('shop_cart.goods_id',$goods_id)
    		->get()
    		->toArray();
    	$count=0;
    	foreach ($info as $k => $v) {
    		$count+=$v['goods_price']*$v['buy_num'];
    	}
    	echo $count;
    	return;
    }

    public function getCountTotalCookie($goods_id)
    {
    	$goods_id=explode(',', $goods_id);
    	$str=request()->cookie('cartInfo');
    	if(!empty($str)){
    		$goods_model=new GoodsModel;
    		$cartInfo=$this->getBase64Info($str);
    		$info=[];
    		foreach ($cartInfo as $k => $v) {
    			if(in_array($v['goods_id'],$goods_id)){
    				$info[]=$v;
    			}
    		}
    		$count=0;
    		foreach ($info as $k => $v) {
    			$where=[
    				['goods_up','=',1]
    			];
    			$g_id=explode(',',$v['goods_id']);
    			$goods_price=$goods_model->whereIn('goods_id',$g_id)->where($where)->value('goods_price');
    			$count+=$goods_price*$v['buy_num'];
    		}
    		echo $count;
    		return;
    	}else{
    		echo 0;
    		return;
    	}
    }

    public function changeBuyNum(Request $request)
    {
    	$goods_id=$request->input('goods_id');
    	$buy_num=$request->input('buy_num');
    	if(empty($goods_id) || !isset($goods_id)){
    		$this->no('请选择一件商品');
    	}
    	if(empty($buy_num) || !isset($buy_num)){
    		$this->no('请输入购买数量');
    	}

    	if($this->checkLogin()){
    		$this->changeBuyNumDb($goods_id,$buy_num);
    	}else{
    		$this->changeBuyNumCookie($goods_id,$buy_num);
    	}
    }

    public function changeBuyNumDb($goods_id,$buy_num)
    {
    	$res=$this->checkGoodsNum($goods_id,$buy_num);
    	if($res){
    		$cart_model=new CartModel;
    		$where=[
    			['goods_id','=',$goods_id],
    			['u_id','=',$this->getUserId()]
    		];
    		$updateInfo=[
    			'buy_num'=>$buy_num,
    			'update_time'=>time()
    		];
    		$result=$cart_model->where($where)->update($updateInfo);
    		if($result){
    			$this->ok('修改成功');
    		}else{
    			$this->no('修改失败');
    		}
    	}else{
    		$this->no('购买数量超过库存限制');
    	}
    }

    public function changeBuyNumCookie($goods_id,$buy_num)
    {
    	$str=request()->cookie('cartInfo');
    	if(!empty($str)){
    		$res=$this->checkGoodsNum($goods_id,$buy_num);
    		if($res){
    			$cartInfo=$this->getBase64Info($str);
    			foreach ($cartInfo as $k => $v) {
    				if($goods_id==$v['goods_id']){
    					$cartInfo[$k]['buy_num']=$buy_num;
    				}
    			}
    			$cartStr=$this->createBase64Info($cartInfo);
    			Cookie::queue('cartInfo',$cartStr,60*24);
    			$this->ok('修改成功');
    		}else{
    			$this->no('购买数量超过库存限制');
    		}
    	}
    }

    public function getSubTotal(Request $request)
    {
    	$goods_id=$request->input('goods_id');
    	if(empty($goods_id) || !isset($goods_id)){
    		$this->no('请选择一件商品');
    	}
    	if($this->checkLogin()){
    		$this->getSubTotalDb($goods_id);
    	}else{
    		$this->getSubTotalCookie($goods_id);
    	}
    }

    public function getSubTotalDb($goods_id)
    {
    	$cart_model=new CartModel;
    	$goods_model=new GoodsModel;
    	$cart_where=[
    		['goods_id','=',$goods_id],
    		['u_id','=',$this->getUserId()],
    		['is_del','=',1]
    	];
    	$buy_num=$cart_model->where($cart_where)->value('buy_num');
    	$goods_where=[
    		['goods_id','=',$goods_id],
    		['goods_up','=',1]
    	];
    	$goods_price=$goods_model->where($goods_where)->value('goods_price');
    	echo $goods_price*$buy_num;
    }

    public function getSubTotalCookie($goods_id)
    {
    	$goods_id=explode(',', $goods_id);
    	$str=request()->cookie('cartInfo');
    	if(!empty($str)){
    		$goods_model=new GoodsModel;
    		$cartInfo=$this->getBase64Info($str);
    		$info=[];
    		foreach ($cartInfo as $k => $v) {
    			if(in_array($v['goods_id'],$goods_id)){
    				$info[]=$v;
    			}
    		}
    		$count=0;
    		foreach ($info as $k => $v) {
    			$where=[
    				['goods_up','=',1]
    			];
    			$g_id=explode(',',$v['goods_id']);
    			$goods_price=$goods_model->whereIn('goods_id',$g_id)->where($where)->value('goods_price');
    			$count=$goods_price*$v['buy_num'];
    		}
    		echo $count;
    		return;
    	}
    }

    public function add(Request $request)
    {
    	$goods_id=$request->input('goods_id');
    	$buy_num=$request->input('buy_num');
    	if(empty($goods_id) || !isset($goods_id)){
    		$this->abort('请选择一件商品','/goods/goods_list');
    	}
    	if(empty($buy_num) || !isset($buy_num)){
    		$this->abort('请输入购买数量','/goods/goodsDetail?goods_id='.$goods_id);
    	}

    	if($this->checkLogin()){
    		$this->saveCartInfoDb($goods_id,$buy_num);
    	}else{
    		$this->saveCartInfoCookie($goods_id,$buy_num);
    	}
    }

    public function saveCartInfoDb($goods_id,$buy_num)
    {
    	$u_id=$this->getUserId();
    	$where=[
    		['u_id','=',$u_id],
    		['goods_id','=',$goods_id]
    	];
    	$cart_model=new CartModel;
    	$info=$cart_model->where($where)->first();
    	if(!empty($info)){
    		$info=$info->toArray();
    		if($info['is_del']==2){
    			$res=$this->checkGoodsNum($goods_id,$buy_num,$info['buy_num']);
    			if($res){
    				$data=[
    					'u_id'=>$u_id,
    					'goods_id'=>$goods_id,
    					'buy_num'=>$buy_num,
    					'create_time'=>time(),
    				];
    				$result=$cart_model->insert($data);
    			}else{
    				$this->abort('购买数量超过库存限制','/goods/goodsDetail?goods_id='.$goods_id);
    			}
    		}else{
    			$res=$this->checkGoodsNum($goods_id,$buy_num,$info['buy_num']);
    			if($res){
    				$updateInfo=[
    					'buy_num'=>$buy_num+$info['buy_num'],
    					'update_time'=>time(),
    				];
    				$result=$cart_model->where($where)->update($updateInfo);
    			}else{
    				$this->abort('购买数量超过库存限制','/goods/goodsDetail?goods_id='.$goods_id);
    			}
    		}
    	}else{
    		$res=$this->checkGoodsNum($goods_id,$buy_num);
    		if($res){
    			$data=[
    				'u_id'=>$u_id,
    				'goods_id'=>$goods_id,
    				'buy_num'=>$buy_num,
    				'create_time'=>time(),
    			];
    			$result=$cart_model->insert($data);
    		}else{
    			$this->abort('购买数量超过库存限制','/goods/goodsDetail?goods_id='.$goods_id);
    		}
    	}

    	if($result){
    		$this->abort('加入购物车成功','/cart/cart_list');
    	}else{
    		$this->abort('加入购物车成功','/cart/cart_list');
    	}
    }

    public function saveCartInfoCookie($goods_id,$buy_num)
    {
    	$str=request()->cookie('cartInfo');
    	if(!empty($str)){
    		$cartInfo=$this->getBase64Info($str);
    		$flag=0;
    		foreach ($cartInfo as $k => $v) {
    			if($v['goods_id']==$goods_id){
    				$res=$this->checkGoodsNum($goods_id,$buy_num,$v['buy_num']);
    				if($res){
    					$cartInfo[$k]['buy_num']=$buy_num+$v['buy_num'];
    					$cartStr=$this->createBase64Info($cartInfo);
    					Cookie::queue('cartInfo',$cartStr,60*24);
    					$this->abort('加入购物车成功','/cart/cart_list');return;
    				}else{
    					$this->abort('购买数量超过库存限制','/goods/goodsDetail?goods_id='.$goods_id);return;
    				}
    			}else{
    				$flag=1;
    			}
    		}

    		if($flag==1){
    			$res=$this->checkGoodsNum($goods_id,$buy_num);
    			if($res){
    				$cartInfo[]=[
    					'goods_id'=>$goods_id,
    					'buy_num'=>$buy_num,
    					'create_time'=>time(),
    				];
    				$cartStr=$this->createBase64Info($cartInfo);
    				Cookie::queue('cartInfo',$cartStr,60*24);
    				$this->abort('加入购物车成功','/cart/cart_list');return;
    			}else{
    				$this->abort('购买数量超过库存限制','/goods/goodsDetail?goods_id='.$goods_id);return;
    			}
    		}
    	}else{
    		$res=$this->checkGoodsNum($goods_id,$buy_num);
    		if($res){
    			$cartInfo[]=[
    				'goods_id'=>$goods_id,
    				'buy_num'=>$buy_num,
    				'create_time'=>time(),
    			];
    			$cartStr=$this->createBase64Info($cartInfo);
    			Cookie::queue('cartInfo',$cartStr,60*24);
    			$this->abort('加入购物车成功','/cart/cart_list');return;
    		}else{
    			$this->abort('购买数量超过库存限制','/goods/goodsDetail?goods_id='.$goods_id);return;
    		}
    	}
    }

}
