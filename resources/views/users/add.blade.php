<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<script type="text/javascript" src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/layui/layui.js')}}"></script>
</head>
<body>
	<form method="post" action="addHandle" enctype="multipart/form-data">
		<table border="0" align="center">
			@csrf
			<tr>
				<td></td>
				<td>
					@if ($errors->any())
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>             
								@endforeach         
							</ul>     
						</div> 
					@endif
				</td>
			</tr>
			<tr>
				<td>用户名:</td>
				<td>
					<input type="text" name="username">
				</td>
			</tr>
			<tr>
				<td>手机号:</td>
				<td>
					<input type="text" name="tel">
				</td>
			</tr>
			<tr>
				<td>性别:</td>
				<td>
					<input type="radio" name="sex" value="1">男
					<input type="radio" name="sex" value="2">女
				</td>
			</tr>
			<tr>
				<td>头像:</td>
				<td>
					<input type="file" name="u_img">
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<button id="btn">提交</button>
					<button type="reset">重置</button>
				</td>
			</tr>
		</table>
	</form>
	<script type="text/javascript">
		$(function(){
			layui.use(['layer'],function(){
				var layer=layui.layer;
				$(document).on('click','#btn',function(){
					var obj={};
					var flag=false;
					obj._token=$('input[name="_token"]').val();
					obj.username=$('input[name="username"]').val();
					obj.tel=$('input[name="tel"]').val();
					obj.sex=$('input:checked').val();
					obj.u_img=$('input[name="u_img"]').val();
					var reg1=/^.{3,30}$/;
					if(obj.username==''){
						layer.msg('用户名必填',{icon:5,time:1000});
						return false;
					}else if(!reg1.test(obj.username)){
						layer.msg('用户名的长度为3~30位',{icon:5,time:1000});
						return false;
					}else{
						$.ajax({
							url:'ajax',
							method:'post',
							async:false,
							data:{username:obj.username,_token:obj._token},
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
					$('form').submit();
				});
			});
		});
	</script>
</body>
</html>