<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<!-- <style type="text/css">
		ul li{
			float:left;
			list-style:none;
			margin-left:10px;
		}
	</style> -->
	<link rel="stylesheet" type="text/css" href="{{asset('css/page.css')}}">
	<script type="text/javascript" src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/layui/layui.js')}}"></script>
</head>
<body>
	<form action="">
		<input style="margin-left:550px;" type="text" name="username" value="{{$username}}">
		<select name="age">
			<option value="">请选择年龄</option>
			<option value="12" @if($age==12) selected @endif >12</option>
			<option value="13" @if($age==13) selected @endif >13</option>
			<option value="14" @if($age==14) selected @endif >14</option>
			<option value="15" @if($age==15) selected @endif >15</option>
		</select>
		<button>搜索</button>
	</form>
	<table border="1" width="600" align="center">
		<tr>
			<th>编号</th>
			<th>用户名</th>
			<th>手机号</th>
			<th>性别</th>
			<th>年龄</th>
			<th>头像</th>
			<th>操作</th>
		</tr>
		@foreach($userData as $v)
		@php
		$sex=$v->sex==1?'男':'女';
		@endphp
		<tr u_id="{{$v->id}}">
			<td>{{$v->id}}</td>
			<td>{{$v->username}}</td>
			<td>{{$v->tel}}</td>
			<td>
				{{$sex}}
				<!-- @if($v->sex==1)男 @else 女 @endif -->
			</td>
			<td>{{$v->age}}</td>
			<td>
				<img src="http://uploads.laravel.com/{{$v->u_img}}" width="50">
			</td>
			<td>
				[<a href="edit?id={{$v->id}}">修改</a>]|
				[<a href="javascript:void(0);" class="del">删除</a>]
			</td>
		</tr>
		@endforeach
		<tr>
			<td colspan="7">
				{{$userData->appends($query)->links()}}
			</td>
		</tr>
	</table>
	<script type="text/javascript">
		$(function(){
			layui.use(['layer'],function(){
				var layer=layui.layer;
				$('.del').click(function(){
					var _this=$(this);
					var u_id=_this.parents('tr').attr('u_id');
					layer.confirm('你确定要删除该信息吗?',function(index){
						$.ajax({
							url:"destroy",
							data:{id:u_id},
							async:false,
							success:function(msg){
								layer.msg(msg.font,{icon:msg.skin,time:1000},function(){
									if(msg.code==1){
										_this.parents('tr').remove();
									}
								});
							},
							'dataType':'json'
						});
					});
				});
			});
		});
	</script>
</body>
</html>