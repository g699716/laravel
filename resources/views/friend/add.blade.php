<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<script type="text/javascript" src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/layui/layui.js')}}"></script>
</head>
<body>
	<form action="addHandle" method="post" enctype="multipart/form-data">
		@csrf
		<table border="0" align="center">
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
				<td>网站名称：</td>
				<td>
					<input type="text" name="f_name">
				</td>
			</tr>
			<tr>
				<td>网站网址：</td>
				<td>
					<input type="text" name="f_url">
				</td>
			</tr>
			<tr>
				<td>链接类型：</td>
				<td>
					<input type="radio" name="f_type" value="1">LOGO链接
					<input type="radio" name="f_type" value="2">文字链接
				</td>
			</tr>
			<tr>
				<td>图片LOGO：</td>
				<td>
					<input type="file" name="f_logo">
				</td>
			</tr>
			<tr>
				<td>网站联系人：</td>
				<td>
					<input type="text" name="f_man">
				</td>
			</tr>
			<tr>
				<td>网站介绍：</td>
				<td>
					<textarea name="f_desc"></textarea>
				</td>
			</tr>
			<tr>
				<td>是否显示：</td>
				<td>
					<input type="radio" name="is_show" value="1">是
					<input type="radio" name="is_show" value="2">否
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<button id="btn">添加</button>
					<button type="reset">重置</button>
				</td>
			</tr>
		</table>
	</form>
	<script type="text/javascript">
		$(function(){
			layui.use(['layer'],function(){
				var layer=layui.layer;
				$('#btn').click(function(){
					var f_name=$('input[name="f_name"]').val();
					var f_url=$('input[name="f_url"]').val();
					var _token=$('input[name="_token"]').val();
					var flag=false;
					var reg1=/^[a-z0-9_\u4e00-\u9fa5]{1,}$/i;
					if(f_name==''){
						layer.msg('网站名称必填',{icon:5,time:1000});
						return false;
					}else if(!reg1.test(f_name)){
						layer.msg('网站名称由中文字母数字下划线组成',{icon:5,time:1000});
						return false;
					}else{
						$.ajax({
							url:'checkName',
							method:'post',
							async:false,
							data:{f_name:f_name,_token:_token},
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

					var reg2=/(http):\/\/([\w.]+\/?)\S*/;
					if(f_url==''){
						layer.msg('网站网址必填',{icon:5,time:1000});
						return false;
					}else if(!reg2.test(f_url)){
						layer.msg('网址格式不正确',{icon:5,time:1000});
						return false;
					}

					$('form').submit();
				});
			});
		});
	</script>
</body>
</html>