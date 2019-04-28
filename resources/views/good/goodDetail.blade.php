@extends('layouts.shop')
@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>产品详情</h1>
      </div>
     </header>
     <input type="hidden" name="goods_id" value="{{$goodsInfo['goods_id']}}">
     <div id="sliderA" class="slider">
      <img src="http://uploads.laravel.com/{{$goodsInfo['goods_img']}}" />
     </div><!--sliderA/-->
     <table class="jia-len">
      <tr>
       <th><strong class="orange">￥{{$goodsInfo['goods_price']}}</strong></th>
       <td>
          <!-- <button class="less">-</button> -->
            <input type="text" style="width:50px;" value="{{$goodsInfo['goods_num']}}" id="buy_num"  />
          <!-- <button class="add">+</button> -->
       </td>
       @csrf
      </tr>
      <tr>
       <td>
        <strong>{{$goodsInfo['goods_name']}}</strong>
       </td>
       <td align="right">
        <a href="javascript:;" class="shoucang"><span class="glyphicon glyphicon-star-empty"></span></a>
       </td>
      </tr>
     </table>
     <table class="jrgwc">
      <tr>
       <th>
        <a href="index.html"><span class="glyphicon glyphicon-home"></span></a>
       </th>
       <td>
        <a href="edit?goods_id={{$goodsInfo['goods_id']}}">修改</a>
        <a id="del" href="javascript:;">删除</a>
      </td>
      </tr>
     </table>
     <script type="text/javascript">
       $(function(){
        layui.use(['layer'],function(){
          var layer=layui.layer;
          var _token=$('input[name="_token"]').val();
          var goods_id=$('input[name="goods_id"]').val();
          $('#del').click(function(){
            var _this=$(this);
            layer.confirm('你确定要删除该商品吗?',function(index){
              $.ajax({
                url:'del',
                method:'post',
                data:{goods_id:goods_id,_token:_token},
                async:false,
                success:function(msg){
                  layer.msg(msg.font,{icon:msg.skin,time:1000});
                  location.href='/good/goodList';
                },
                dataType:'json'
              });
            });
          });
        });
       });
     </script>
@endsection('content')