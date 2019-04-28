<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CommonController;
use App\model\CartModel;

class OrderController extends CommonController
{
    public function isLogin()
    {
    	if($this->checkLogin()){
    		$this->ok();
    	}else{
    		$this->no('登录后才能结算商品');
    	}
    }

    public function orderList(Request $request)
    {
    	$goods_id=$request->input('goods_id');
        if(!isset($goods_id) || empty($goods_id)){
            $this->abort('请选择一件商品','/cart/cart_list');
        }
        $goods_id=explode(',', $goods_id);
        $cart_model=new CartModel;
        $where=[
            ['goods_up','=',1],
            ['shop_cart.u_id','=',$this->getUserId()],
            ['is_del','=',1]
        ];
        $cartInfo=$cart_model->join('shop_goods','shop_cart.goods_id','=','shop_goods.goods_id')->where($where)->whereIn('shop_cart.goods_id',$goods_id)->get()->toArray();
        $countTotal=0;
        foreach ($cartInfo as $k => $v) {
            $cartInfo[$k]['subTotal']=$v['goods_price']*$v['buy_num'];
            $countTotal+=$v['goods_price']*$v['buy_num'];
        }
        return view('order.orderList',compact('cartInfo','countTotal'));
    }
}
