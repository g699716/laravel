<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\model\UserModel;
use Mail;

class LoginController extends CommonController
{
    //
    public function checkUnique(Request $request)
    {
    	$u_email=$request->input('u_email');
        $u_tel=$request->input('u_tel');
    	$u_id=$request->input('u_id');
    	$user_model=new UserModel;
    	if($u_email){
            if($u_id){

            }else{
                $where=['u_email'=>$u_email];
            }
        }else if($u_tel){
            if($u_id){

            }else{
                $where=['u_tel'=>$u_tel];
            }
        }else{
            $this->no('账号必填');
            return false;
        }
    	$res=$user_model->where($where)->count();
    	if($res){
    		$this->no('用户名已被占用');
    	}else{
    		$this->ok();
    	}
    }

    public function sendEmail(Request $request)
    {
    	$u_email=$request->input('u_email');
    	$res=$this->sendMail($u_email);
        if($res){
            session(['emailCode'=>$res]);
            $this->ok('发送成功');
        }else{
            $this->no('发送失败');
        }
    }

    public function sendNote(Request $request)
    {
        $u_tel=$request->input('u_tel');
        $res=$this->sendNot($u_tel);
        if($res){
           session(['tel_code'=>$res]);
            $this->ok('发送成功');
        }else{
            $this->no('发送失败');
        }
    }

    // public function doregister(UserRequest $request)
    public function doregister(Request $request)
    {
        $post=$request->input('obj');
        $email_code=session('emailCode');
        $user_model=new UserModel;
        if(isset($post['u_tel']) && !empty($post['u_tel'])){
            $count=$user_model->where(['u_tel'=>$post['u_tel']])->count();
            $code=session('tel_code');
        }else if(isset($post['u_email']) && !empty($post['u_email'])){
            $count=$user_model->where(['u_email'=>$post['u_email']])->count();
            $code=session('emailCode');
        }else{
            $this->no('用户名必填');
        }
        if($count){
            $this->no('用户名已被占用');
        }
        if($post['email_code']!=$code){
            $this->no('验证码错误');
        }
        unset($post['repwd']);
        unset($post['email_code']);
        $post['u_pwd']=encrypt($post['u_pwd']);//decrypt
        $res=$user_model->insert($post);
        if($res){
            $this->ok('注册成功');
        }else{
            $this->no('注册失败');
        }
    }

    public function doLogin(Request $request)
    {
        $post=$request->input('obj');
        $user_model=new UserModel;
        if(isset($post['u_email']) && !empty($post['u_email'])){
            $user=$user_model->where('u_email',$post['u_email'])->first()->toArray();
        }else if(isset($post['u_tel']) && !empty($post['u_tel'])){
            $user=$user_model->where('u_tel',$post['u_tel'])->first()->toArray();
        }else{
            $this->no('用户名必填');
        }
        if(!$user){
            $this->no('该用户不存在');
        }
        $now=time();
        $error_num=$user['error_num'];
        $last_error_time=$user['last_error_time'];
        $u_pwd=decrypt($user['u_pwd']);
        if($post['u_pwd']==$u_pwd){
            if($error_num>=3 && $now-$last_error_time<3600){
                $errorTime=60-ceil(($now-$last_error_time)/60);
                $this->no('账号已锁定,请于'.$errorTime.'分钟后登录');
            }
            $errorInfo=[
                'error_num'=>0,
                'last_error_time'=>null
            ];
            $user_model->where('u_id',$user['u_id'])->update($errorInfo);
            $sessionInfo=[
                'u_id'=>$user['u_id'],
            ];
            session(['userInfo'=>$sessionInfo]);
            $this->asyncCart();
            $this->ok('登录成功');
        }else{
            if($now-$last_error_time>3600){
                $errorInfo=[
                    'error_num'=>1,
                    'last_error_time'=>$now
                ];
                $user_model->where('u_id',$user['u_id'])->update($errorInfo);
                $this->no('用户名或密码错误,还有2次机会');
            }else{
                if($error_num>=3){
                    $this->no('账号已冻结,请1小时后登录');
                }else{
                    $errorInfo=[
                        'error_num'=>$error_num+1,
                        'last_error_time'=>$now
                    ];
                    $user_model->where('u_id',$user['u_id'])->update($errorInfo);
                    $count=3-($error_num+1);
                    $this->no('用户名或密码错误,还有'.$count.'次机会');
                }
            }
        }
    }

}
