<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/page.css')}}">
	<script type="text/javascript" src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/layui/layui.js')}}"></script>
</head>
<body>
	<form>
		<input type="text" style="margin-left:600px;" name="f_name" value="{{$f_name}}">
		<button>搜索</button>
	</form>
	<table border="1" align="center" width="600">
		<tr>
			<th>编号</th>
			<th>网站名称</th>
			<th>图片LOGO</th>
			<th>链接类型</th>
			<th>状态</th>
			<th>管理操作</th>
		</tr>
		@foreach($friendInfo as $v)
		<tr f_id="{{$v['f_id']}}" >
			<td>{{$v['f_id']}}</td>
			<td>{{$v['f_name']}}</td>
			<td>
				<img src="http://uploads.laravel.com/{{$v['f_logo']}}" width="50">
			</td>
			<td>@if($v['f_type']==1) LOGO链接 @elseif($v['f_type']==2) 文字链接 @endif </td>
			<td>@if($v['is_show']==1) 显示 @elseif($v['is_show']==2) 不显示 @endif </td>
			<td>
				<a href="edit?f_id={{$v['f_id']}}">修改</a>|
				<a href="javascript:void(0);" class="del">删除</a>
			</td>
		</tr>
		@endforeach
		<tr>
			<td colspan="6">
				{{$friendInfo->appends($query)->links()}}
			</td>
		</tr>
	</table>
	<script type="text/javascript">
		$(function(){
			layui.use(['layer'],function(){
				var layer=layui.layer;
				$('.del').click(function(){
					var _this=$(this);
					layer.confirm('您确定要删除此网站吗?',function(index){
						var f_id=_this.parents('tr').attr('f_id');
						$.ajax({
							url:'destroy',
							method:'get',
							async:false,
							data:{f_id:f_id},
							success:function(msg){
								layer.msg(msg.font,{icon:msg.skin,time:1000},function(){
									if(msg.code==1){
										_this.parents('tr').remove();
									}
								});
							},
							dataType:'json'
						});
					});
				});
			});
		});
	</script>
</body>
</html>