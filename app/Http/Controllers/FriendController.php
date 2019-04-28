<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Friend as FriendRequest;
use App\model\Friend;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CommonController;

class FriendController extends CommonController
{
    //
    public function add()
    {
    	return view('friend/add');
    }

    public function addHandle(FriendRequest $request)
    {
    	$post=$request->except('_token');
    	$friend_model=new Friend;
    	// $reg='/^[\u4e00-\u9fa5]{1,}$/';
    	$reg='/^[a-z0-9_]{1,}$/i';
    	if(!preg_match($reg,$post['f_name'])){
    		$this->abort('网站名称由中文字母数字下划线组成','add');
    	}
    	$unique=$friend_model->where('f_name',$post['f_name'])->count();
    	if($unique>0){
    		$this->abort('网站名称已被占用','add');
    	}
    	if(isset($post['f_logo']) && !empty($post['f_logo'])){
    		$post['f_logo']=$this->upload($post['f_logo']);
    	}
    	
    	$res=$friend_model->insert($post);
    	if($res){
    		$this->abort('添加成功','index');
    	}else{
    		$this->abort('添加失败','add');
    	}
    }

    public function index(Request $request)
    {
    	$query=$request->all();
    	$where=[];
    	$f_name=$query['f_name']??'';
    	$page=$query['page']??1;
    	if($f_name){
    		$where[]=['f_name','like',"%{$f_name}%"];
    	}
    	$friend_model=new Friend;
    	$tiao=config('app.pageSize');
    	$friendInfo=$friend_model->where($where)->orderBy('f_id','desc')->paginate($tiao);
    	return view('friend/index',compact('friendInfo','f_name','query'));
    }

    public function edit(Request $request)
    {
    	$f_id=$request->input('f_id')??'';
    	if($f_id){
    		$friend_model=new Friend;
    		$friendInfo=$friend_model->where('f_id',$f_id)->first()->toArray();
    		if($friendInfo){
    			return view('friend/edit',['friendInfo'=>$friendInfo]);
    		}else{
    			$this->abort('用户信息异常','index');
    		}
    	}else{
    		$this->abort('请选择一个网站','index');
    	}
    }

    public function update(FriendRequest $request)
    {
    	$post=$request->except('_token');
    	$f_name=$post['f_name'];
    	$f_id=$post['f_id'];
    	$friend_model=new Friend;
    	// $reg='/^[\u4e00-\u9fa5]{1,}$/';
    	$reg='/^[a-z0-9_]{1,}$/i';
    	if(!preg_match($reg,$post['f_name'])){
    		$this->abort('网站名称由中文字母数字下划线组成',"edit?f_id={$post['f_id']}");
    	}
    	$res=Db::select('select * from friend where f_name=? and f_id!=?',[$f_name,$f_id]);
    	if($res){
    		$this->abort('网站名称已被占用',"edit?f_id={$post['f_id']}");
    	}
    	if(isset($post['f_logo']) && !empty($post['f_logo'])){
    		$post['f_logo']=$this->upload($post['f_logo']);
    	}
    	$result=$friend_model->where('f_id',$post['f_id'])->update($post);
    	if($result){
    		$this->abort('修改成功','index');
    	}else{
    		$this->abort('修改失败',"edit?f_id={$post['f_id']}");
    	}
    }

    public function destroy(Request $request)
    {
    	$f_id=$request->input('f_id')??'';
    	if($f_id){
    		$friend_model=new Friend;
    		$res=$friend_model->where('f_id',$f_id)->delete();
    		if($res){
    			$this->ok('删除成功');
    		}else{
    			$this->no('删除失败');
    		}
    	}else{
    		$this->no('请选择一个网站');
    	}
    }

    public function checkName(Request $request)
    {
    	$f_id=$request->input('f_id')??'';
    	$f_name=$request->input('f_name')??'';
    	$friend_model=new Friend;
    	if($f_id){
    		// $where=[
    		// 	'f_id'=>['neq',$f_id],
    		// 	'f_name'=>$f_name,
    		// ];
    		$res=Db::select('select * from friend where f_name=? and f_id!=?',[$f_name,$f_id]);
    	}else{
    		$where=[
    			'f_name'=>$f_name,
    		];
    		$res=$friend_model->where($where)->count();
    	}
    	// $res=$friend_model->where($where)->count();
    	if($res){
    		$this->no('网站名称已被占用');
    	}else{
    		$this->ok();
    	}
    }

    public function upload($f_logo)
    {
    	if($f_logo->isValid()){
    		$extension=$f_logo->extension();
    		$store_result=$f_logo->store('friend/'.date('Ymd'));
    		return $store_result;
    	}else{
    		exit('未获取到上传文件或上传过程出错');
    	}
    }

}
