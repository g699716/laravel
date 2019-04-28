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
     <form action=""  method="post" class="reg-login">
      
      <h3>已经有账号了？点此<a class="orange" href="login">登陆</a></h3>
      @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>             
                @endforeach         
              </ul>     
            </div> 
      @endif
      @csrf
      <div class="lrBox">
       <div class="lrList"><input type="text" name="email" placeholder="输入手机号码或者邮箱号" /></div>
       <div class="lrList2"><input type="text" name="email_code" placeholder="输入短信验证码" /> 
        <button id="getCode" type="button">获取验证码</button></div>
       <div class="lrList"><input type="password" name="u_pwd" placeholder="设置新密码（6-18位数字或字母）" /></div>
       <div class="lrList"><input type="password" name="repwd" placeholder="再次输入密码" /></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" id="btn" value="立即注册" />
      </div>
     </form><!--reg-login/-->
     @extends('public.footer')
     <script type="text/javascript">
       $(function(){
        layui.use(['layer'],function(){
          var layer=layui.layer;
          var _token=$('input[name="_token"]').val();
          $(document).on('click','#getCode',function(){
            var _this=$(this);
            var account=$('input[name="email"]').val(); 
            var flag=false;
            if(account==''){
              layer.msg('用户名必填',{icon:5,time:1000});
              return false;
            }
            if(account.length>11){
              var reg1=/^\w+@\w+.com$/i;
              if(account==''){
                layer.msg('邮箱必填',{icon:5,time:1000});
                return false;
              }else if(!reg1.test(account)){
                layer.msg('邮箱格式错误',{icon:5,time:1000});
                return false;
              }else{
                $.ajax({
                  url:'checkUnique',
                  method:'post',
                  async:false,
                  data:{u_email:account,_token:_token},
                  success:function(msg){
                    if(msg.code==2){
                      layer.msg(msg.font,{icon:msg.skin,time:1000});
                      flag=false;
                    }else{
                      flag=true;
                    }
                  },
                  dataType:'json'
                });
                if(flag==false){
                  return false;
                }
                $.post(
                  "sendEmail",
                  {u_email:account,_token:_token},
                  function(msg){
                    layer.msg(msg.font,{icon:msg.skin,time:1000});
                  },
                  'json'
                )
              }
            }else{
              var reg1=/^[1]+[3,5,8]\d{9}$/;
              if(account==''){
                layer.msg('手机号必填',{icon:5,time:1000});
                return false;
              }else if(!reg1.test(account)){
                layer.msg('手机号格式错误',{icon:5,time:1000});
                return false;
              }else{
                $.ajax({
                  url:'checkUnique',
                  method:'post',
                  async:false,
                  data:{u_tel:account,_token:_token},
                  success:function(msg){
                    if(msg.code==2){
                      layer.msg(msg.font,{icon:msg.skin,time:1000});
                      flag=false;
                    }else{
                      flag=true;
                    }
                  },
                  dataType:'json'
                });
                if(flag==false){
                  return false;
                }
                $.post(
                  'sendNote',
                  {u_tel:account,_token:_token},
                  function(msg){
                    layer.msg(msg.font,{icon:msg.skin,time:1000});
                  },
                  'json'
                )
              }
            }

            
            var getCode=$('#getCode');
            getCode.text(60+'s');
            var setI=setInterval(timeLess,1000);

            

            function timeLess(){
              var _time=parseInt($('#getCode').text());
              if(_time<=0){
                getCode.text('获取验证码');
                clearInterval(setI);
                $('#getCode').css('pointerEvents','auto');
              }else{
                _time=_time-1;
                getCode.text(_time+'s');
                $('#getCode').css('pointerEvents','none');
              }
            }
          });

          $('#btn').click(function(){
            var obj={};
            var account=$('input[name="email"]').val();
            obj.email_code=$('input[name="email_code"]').val();
            obj.u_pwd=$('input[name="u_pwd"]').val();
            obj.repwd=$('input[name="repwd"]').val();
            var flag=false;
            if(account==''){
              layer.msg('用户名必填',{icon:5,time:1000});
              return false;
            }
            if(account.length>11){
              obj.u_email=account;
              var reg1=/^\w+@\w+.com$/i;
              if(obj.u_email==''){
                layer.msg('邮箱必填',{icon:5,time:1000});
                return false;
              }else if(!reg1.test(obj.u_email)){
                layer.msg('邮箱格式错误',{icon:5,time:1000});
                return false;
              }else{
                $.ajax({
                  url:'checkUnique',
                  method:'post',
                  async:false,
                  data:{u_email:obj.u_email,_token:_token},
                  success:function(msg){
                    if(msg.code==2){
                      layer.msg(msg.font,{icon:msg.skin,time:1000});
                      flag=false;
                    }else{
                      flag=true;
                    }
                  },
                  dataType:'json'
                });
                if(flag==false){
                  return false;
                }
              }
            }else{
              obj.u_tel=account;
              var reg1=/^[1]+[3,5,8]\d{9}$/;
              if(obj.u_tel==''){
                layer.msg('手机号必填',{icon:5,time:1000});
                return false;
              }else if(!reg1.test(obj.u_tel)){
                layer.msg('手机号格式错误',{icon:5,time:1000});
                return false;
              }else{
                $.ajax({
                  url:'checkUnique',
                  method:'post',
                  async:false,
                  data:{u_tel:obj.u_tel,_token:_token},
                  success:function(msg){
                    if(msg.code==2){
                      layer.msg(msg.font,{icon:msg.skin,time:1000});
                      flag=false;
                    }else{
                      flag=true;
                    }
                  },
                  dataType:'json'
                });
                if(flag==false){
                  return false;
                }
              }
            }
            

            var reg2=/^\d{6}$/;
            if(obj.email_code==''){
              layer.msg('验证码必填',{icon:5,time:1000});
              return false;
            }else if(!reg2.test(obj.email_code)){
              layer.msg('验证码格式错误',{icon:5,time:1000});
              return false;
            }

            var reg3=/^.{2,12}$/;
            if(obj.u_pwd==''){
              layer.msg('密码必填',{icon:5,time:1000});
              return false;
            }else if(!reg3.test(obj.pwd)){
              layer.msg('密码的长度为2~12位',{icon:5,time:1000});
              return false;
            }

            if(obj.repwd==''){
              layer.msg('确认密码必填',{icon:5,time:1000});
              return false;
            }else if(obj.repwd!=obj.u_pwd){
              layer.msg('两次密码不一致',{icon:5,time:1000});
              return false;
            }

            $.post(
              'doregister',
              {obj,_token},
              function(msg){
                layer.msg(msg.font,{icon:msg.skin,time:1000},function(){
                  if(msg.code==1){
                    location.href="login";
                  }
                });
              },
              'json'
            )
            return false;
          });
        });
       });
     </script>
@endsection
