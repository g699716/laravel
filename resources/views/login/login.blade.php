@extends('layouts.shop')
@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>会员注册</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="images/head.jpg" />
     </div><!--head-top/-->
     <form action="" method="get" class="reg-login">
      <h3>还没有三级分销账号？点此<a class="orange" href="register">注册</a></h3>
      @csrf
      <div class="lrBox">
       <div class="lrList"><input type="text" name="account" placeholder="输入手机号码或者邮箱号" /></div>
       <div class="lrList"><input type="password" name="u_pwd" placeholder="输入密码" /></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" id="btn" value="立即登录" />
      </div>
     </form><!--reg-login/-->
     <script type="text/javascript">
       $(function(){
        layui.use(['layer'],function(){
          var layer=layui.layer;
          $('#btn').click(function(){
            var obj={};
            var _token=$('input[name="_token"]').val();
            var account=$('input[name="account"]').val();
            obj.u_pwd=$('input[name="u_pwd"]').val();
            if(account.length>11){
              obj.u_email=account;
            }else{
              obj.u_tel=account;
            }
            $.post(
              'dologin',
              {obj,_token},
              function(msg){
                layer.msg(msg.font,{icon:msg.skin,time:1000},function(){
                  location.href='/';
                })
              },
              'json'
            )
            return false;
          });
        });
       });
     </script>
@endsection
