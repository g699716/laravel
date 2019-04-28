<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreUsersRequest;
use Validator;
use Illuminate\Validation\Rule;
use App\model\Users;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *添加页面
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        return view('users/add');
    }

    // public function addHandle(StoreUsersRequest $request)
    public function addHandle()
    {
        // $data=request()->post();
        // $data=$request->except('_token');
        $data=request()->except('_token');
        if(!empty($data['u_img'])){
            $data['u_img']=$this->upload($data['u_img']);
        }
        $rule=[
            'username'=>'required|unique:users|min:3|max:30',
            'tel'=>'required|digits:11',
        ];
        $msg=[
            'username.required'=>'用户名必填',
            'username.unique'=>'用户名已被占用',
            'username.min'=>'用户名长度最小为3位',
            'username.max'=>'用户名长度最大为30',
            'tel.required'=>'手机号必填',
            'tel.digits'=>'手机号长度为11位纯数字'
        ];
        // request()->validate($rule,$msg);
        // $validator=Validator::make($data,$rule,$msg);
        $validator=\Validator::make($data,$rule,$msg);
        if($validator->fails()){
            $errors=$validator->errors();
            if(!empty($errors)){
                foreach ($errors->all() as $message) {
                    $this->abort($message,'add');die;
                }
            }
        }
        // $errors=$validator->errors();
        // if(!empty($errors)){
            // foreach ($errors->all() as $message) {
                // $this->abort($message,'add');die;
            // }    
        // }
        // dd($data);
        // $data=request()->only('_token');
        // $res=Db::insert('insert into users(username,tel,sex) values(?,?,?)',[$data['username'],$data['tel'],$data['sex']]);
        // $res=Db::table('users')->insert($data);
        $users_model=new Users;
        $res=$users_model->insert($data);
        // $res=$users_model->save($data);
        if($res){
            $this->abort('添加成功','show');
        }else{
            $this->abort('添加失败','add');
        }
    }

    public function upload($img='')
    {
        if ($img->isValid()){
            //获取文件后缀名
            $extension = $img->extension();
            //移动临时文件并获取文件完整路径
            $store_result = $img->store('users/'.date('Ymd'));
            // $output = [
            //     'extension' => $extension,'store_result' => $store_result
            // ];
            return $store_result;
        }else{
            exit('未获取到上传文件或上传过程出错');
        }
    }

    public function ajax()
    {
        $username=request()->input('username')??'';
        $id=request()->input('id')??'';
        if($id){
            $where=[
                'id'=>['neq',$id],
                'username'=>$username
            ];
        }else{
            $where=['username'=>$username];
        }
        $users_model=new Users;
        // $res=Db::table('users')->where($where)->count();
        $res=$users_model->where($where)->count();
        if($res>0){
            $this->no('用户名已被占用');
        }else{
            $this->ok();
        }
    }

    public function ok($font='操作成功',$code=1,$skin=6)
    {
        echo json_encode(['font'=>$font,'code'=>$code,'skin'=>$skin]);
    }

    public function no($font='操作失败',$code=2,$skin=5)
        {
        echo json_encode(['font'=>$font,'code'=>$code,'skin'=>$skin]);
    }

    public function abort($msg,$url='')
    {
        echo "<script>alert('{$msg}');location.href='{$url}';</script>";
    }

    /**
     * Store a newly created resource in storage.
     *入库
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *展示页面
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id=null)
    {
        // $userData=Db::select('select * from users order by id asc');
        // $userData=Db::table('users')->get()->toArray();
        //添加指定session
        // session(['userInfo'=>'111']);
        //删除指定session
        // request()->session()->forget('userInfo');
        //获取所有session
        // $sessionInfo=request()->session()->all();
        //获取指定session
        // $sessionInfo=session('userInfo');
        // dd($session);
        // dd(session('userInfo'));
        // dd($sessionInfo);
        $query=request()->all();
        $where=[];
        $username=$query['username']??'';
        $age=$query['age']??'';
        $page=$query['page']??1;
        if($username){
            $where[]=['username','like',"%$username%"];
        }
        if(!empty($age)){
            $where['age']=$age;
        }
        $tiao=config('app.pageSize');
        $users_model=new Users;
        // $users_model=model('Users');
        // $userData=Db::table('users')->where($where)->paginate($tiao);
        $userData=$users_model->where($where)->orderBy('id','desc')->paginate($tiao);
        return view('users/show',compact('userData','username','age','query'));
    }

    /**
     * Show the form for editing the specified resource.
     *修改页面
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id=null)
    {
        $id=request()->input('id');
        $users_model=new Users;
        // $userData=Db::select('select * from users where id=?',[$id]);
        // $userData=Db::table('users')->where('id',$id)->first();
        $userData=$users_model->where('id',$id)->first();
        return view('users/edit',['userData'=>$userData]);
    }

    /**
     * Update the specified resource in storage.
     *修改数据
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(StoreUsersRequest $request, $id=null)
    public function update(Request $request, $id=null)
    {
        $data=request()->except('_token');
        $users_model=new Users;
        $oldData=$users_model->where('id',$data['id'])->first()->toArray();
        // $u_img='/storage/app/uploads/'.$oldData['u_img'];
        // $u_img='http://uploads.laravel.com/'.$oldData['u_img'];
        // dd($u_img);
        // @unlink('/storage/app/uploads/'.$oldData['u_img']);die;
        // @unlink('http://uploads.laravel.com/'.$oldData['u_img']);die;
        if(isset($data['u_img']) && !empty($data['u_img'])){
            $data['u_img']=$this->upload($data['u_img']);
        }
        // $res=Db::update('update users set username=?,tel=?,sex=? where id=?',[$data['username'],$data['tel'],$data['sex'],$data['id']]);
        // $res=Db::table('users')->where('id',$data['id'])->update($data);
        $res=$users_model->where('id',$data['id'])->update($data);
        // $res=$users_model->where('id',$data['id'])->save($data);
        if($res){
            $this->abort('修改成功','show');
        }else{
            $this->abort('修改失败',"edit?id={$data['id']}");
        }
    }

    /**
     * Remove the specified resource from storage.
     *删除数据
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id=null)
    {
        $id=request()->input('id')??'';
        $users_model=new Users;
        // $res=Db::delete('delete from users where id=?',[$id]);
        // $res=Db::table('users')->where('id',$id)->delete();
        $res=$users_model->where('id',$id)->delete();
        if($res){
            $this->ok('删除成功');
        }else{
            $this->no('删除失败');
        }
    }

    public function doLogin()
    {
        $post=request()->all();
        if($post['username']=='admin' && $post['pwd']==123){
            session(['userInfo'=>1]);
            $this->abort('登录成功','/');
        }else{
            $this->abort('用户名或密码错误','login');
        }
        // dd(request()->session()->get('_token'));
        // DIRECTORY_SEPARATOR;
        // dd(DIRECTORY_SEPARATOR);
    }

}
