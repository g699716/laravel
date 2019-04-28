<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Model\GoodsModel;
use App\Model\CartModel;
use App\Model\AddressModel;
use Illuminate\Support\Facades\Cookie;

class CommonController extends Controller
{
    //
    public function abort($msg,$url)
    {
    	echo "<script>alert('{$msg}');location.href='{$url}';</script>";
    	return;
    }

    public function ok($font='操作成功',$code=1,$skin=6)
    {
    	echo json_encode(['font'=>$font,'code'=>$code,'skin'=>$skin]);
    	return;
    }

    public function no($font='操作失败',$code=2,$skin=5)
    {
    	echo json_encode(['font'=>$font,'code'=>$code,'skin'=>$skin]);
    	return;
    }

    public function checkLogin()
    {
        if(session('userInfo')){
            return true;
        }else{
            return false;
        }
    }

    public function createBase64Info($info)
    {
        return base64_encode(serialize($info));
    }

    public function getBase64Info($info)
    {
        return unserialize(base64_decode($info));
    }

    public function getUserId()
    {
        return session('userInfo.u_id');
    }

    public function getCateId($cateInfo,$pid)
    {
        static $id=[];
        foreach ($cateInfo as $v) {
            if($v['pid']==$pid){
                $id[]=$v['cate_id'];
                $this->getCateId($cateInfo,$v['cate_id']);
            }
        }
        return $id;
    }

    public function checkGoodsNum($goods_id,$buy_num,$goods_num=0)
    {
        $goods_model=new GoodsModel;
        $num=$goods_model->where('goods_id',$goods_id)->value('goods_num');
        if(($buy_num+$goods_num)>$num){
            return false;
        }else{
            return true;
        }
    }

    public function asyncCart()
    {
        $str=request()->cookie('cartInfo');
        if(!empty($str)){
            $cart_model=new CartModel;
            $goods_model=new GoodsModel;
            $u_id=$this->getUserId();
            $cartInfo=$this->getBase64Info($str);
            foreach ($cartInfo as $k => $v) {
                $where=[
                    ['goods_id','=',$v['goods_id']],
                    ['u_id','=',$u_id],
                    ['is_del','=',1]
                ];
                $info=$cart_model->where($where)->first();
                if(!empty($info)){
                    $info=$info->toArray();
                    $res=$this->checkGoodsNum($v['goods_id'],$v['buy_num'],$info['buy_num']);
                    if($res){
                        $updateInfo=[
                            'buy_num'=>$v['buy_num']+$info['buy_num'],
                            'update_time'=>time()
                        ];
                        $result=$cart_model->where($where)->update($updateInfo);
                        if($result){
                            Cookie::forget('cartInfo');
                            return;
                        }
                    }
                }else{
                    $res=$this->checkGoodsNum($v['goods_id'],$v['buy_num']);
                    if($res){
                        $data=[
                            'goods_id'=>$v['goods_id'],
                            'buy_num'=>$v['buy_num'],
                            'create_time'=>time(),
                            'u_id'=>$u_id
                        ];
                        $result=$cart_model->insert($data);
                        if($result){
                            Cookie::forget('cartInfo');
                            return;
                        }
                    }
                }
            }
        }
    }

    public function getAddressInfo()
    {
        $address_model=new AddressModel;
        $where=[
            ['u_id','=',$this->getUserId()],
            ['is_del','=',1]
        ];
        $addressInfo=$address_model->where($where)->get()->toArray();
        
    }

    //发送邮件
    public function sendMail($email)
    {
        $code=rand(111111,999999);
        Mail::send('login/sendMail',['code'=>$code,'name'=>$email],function($message)use($email){
            $message->subject('欢迎注册');
            $message->to($email);
        });
        return $code;
    }
    //发送短信
    public function sendNot($u_tel)
    {
        $code=rand(111111,999999);
        $host = env("MOBILE_POST");
        $path = env("MOBILE_PATH");
        $method = "POST";
        $appcode = env("MOBILE_CODE");
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "mobile=".$u_tel."&param=code:".$code."&tpl_id=TP1711063";
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        if(curl_exec($curl)){
            return $code;
        }
    }
}
